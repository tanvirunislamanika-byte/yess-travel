<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourBooking;
use App\Models\Tours;
use App\Models\User;
use App\Helpers\CurrencyHelper;
use App\Services\TourBookingNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTourBookingController extends Controller
{
    /**
     * Display a listing of tour bookings with search and filter options
     */
    public function index(Request $request)
    {
        $query = TourBooking::with([
            'tour.departureCountry', 
            'tour.departureState', 
            'tour.departureCity',
            'user.countryData',
            'user.stateData', 
            'user.cityData'
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'LIKE', "%{$search}%")
                  ->orWhere('payment_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('tour', function ($tourQuery) use ($search) {
                      $tourQuery->where('title', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('departure_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('departure_date', '<=', $request->date_to);
        }

        // Filter by booking date range
        if ($request->filled('booking_date_from')) {
            $query->where('created_at', '>=', $request->booking_date_from);
        }
        if ($request->filled('booking_date_to')) {
            $query->where('created_at', '<=', $request->booking_date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('total_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('total_amount', '<=', $request->amount_max);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $bookings = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_bookings' => TourBooking::count(),
            'pending_payments' => TourBooking::where('payment_status', 'pending')->count(),
            'confirmed_bookings' => TourBooking::where('status', 'confirmed')->count(),
            'total_revenue' => TourBooking::where('payment_status', 'completed')->sum('total_amount'),
            'today_bookings' => TourBooking::whereDate('created_at', today())->count(),
            'this_month_bookings' => TourBooking::whereMonth('created_at', now()->month)->count(),
        ];

        // Get filter options for dropdowns
        $filterOptions = [
            'statuses' => TourBooking::distinct()->pluck('status')->filter()->values(),
            'payment_statuses' => TourBooking::distinct()->pluck('payment_status')->filter()->values(),
            'payment_methods' => TourBooking::distinct()->pluck('payment_method')->filter()->values(),
        ];

        // Get currency symbol for views
        $currencySymbol = CurrencyHelper::getSymbol();
        
        return view('admin.tour-bookings.index', compact('bookings', 'stats', 'filterOptions', 'currencySymbol'));
    }

    /**
     * Display the specified tour booking
     */
    public function show($id)
    {
        $booking = TourBooking::with([
            'tour.departureCountry', 
            'tour.departureState', 
            'tour.departureCity',
            'user.countryData',
            'user.stateData', 
            'user.cityData'
        ])->findOrFail($id);
        
        // Debug logging
        \Log::info('Tour Booking Debug', [
            'booking_id' => $id,
            'user_country' => $booking->user->country,
            'user_state' => $booking->user->state,
            'user_city' => $booking->user->city,
            'tour_country' => $booking->tour->extra_field_5,
            'tour_state' => $booking->tour->extra_field_6,
            'tour_city' => $booking->tour->extra_field_7,
            'user_country_data' => $booking->user->countryData ? $booking->user->countryData->name : 'null',
            'tour_country_data' => $booking->tour->departureCountry ? $booking->tour->departureCountry->name : 'null',
        ]);
        
        // Get related bookings for the same tour
        $relatedBookings = TourBooking::where('tour_id', $booking->tour_id)
            ->where('id', '!=', $id)
            ->with(['user'])
            ->latest()
            ->take(5)
            ->get();

        // Get currency symbol for views
        $currencySymbol = CurrencyHelper::getSymbol();
        
        return view('admin.tour-bookings.show', compact('booking', 'relatedBookings', 'currencySymbol'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $booking = TourBooking::findOrFail($id);
        $oldStatus = $booking->status;
        $oldPaymentStatus = $booking->payment_status;

        $booking->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        // Send status update notification to user
        try {
            $notificationService = new TourBookingNotificationService();
            $notificationService->sendStatusUpdateNotification($booking, $oldStatus, $request->status);
        } catch (\Exception $e) {
            \Log::error('Failed to send status update notification: ' . $e->getMessage());
            // Don't fail the status update if notifications fail
        }

        // Log the status change
        \Log::info("Tour booking {$booking->booking_reference} status updated", [
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'old_payment_status' => $oldPaymentStatus,
            'new_payment_status' => $request->payment_status,
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Export bookings to CSV
     */
    public function export(Request $request)
    {
        try {
            \Log::info('CSV Export started', [
                'request_params' => $request->all(),
                'user_id' => auth()->id()
            ]);
            
            $query = TourBooking::with(['tour', 'user']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('booking_reference', 'LIKE', "%{$search}%")
                      ->orWhere('payment_id', 'LIKE', "%{$search}%")
                      ->orWhereHas('tour', function ($tourQuery) use ($search) {
                          $tourQuery->where('title', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('date_from')) {
                $query->where('departure_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('departure_date', '<=', $request->date_to);
            }

            if ($request->filled('booking_date_from')) {
                $query->where('created_at', '>=', $request->booking_date_from);
            }
            if ($request->filled('booking_date_to')) {
                $query->where('created_at', '<=', $request->booking_date_to);
            }

            if ($request->filled('amount_min')) {
                $query->where('total_amount', '>=', $request->amount_min);
            }
            if ($request->filled('amount_max')) {
                $query->where('total_amount', '<=', $request->amount_max);
            }

            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            $bookings = $query->get();

            $filename = 'tour_bookings_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            // Create CSV content as string first
            $csvContent = '';
            
            // Add BOM for UTF-8
            $csvContent .= chr(0xEF).chr(0xBB).chr(0xBF);
            
            // Get currency symbol for formatting
            $currencySymbol = CurrencyHelper::getSymbol();
            
            // CSV headers
            $csvContent .= implode(',', [
                'Booking Reference',
                'Tour Title',
                'Customer Name',
                'Customer Email',
                'Customer Phone',
                'Adults',
                'Children',
                'Departure Date',
                'Total Amount (' . $currencySymbol . ')',
                'Adult Price (' . $currencySymbol . ')',
                'Children Price (' . $currencySymbol . ')',
                'Status',
                'Payment Status',
                'Payment Method',
                'Payment ID',
                'Booking Date',
                'Created At'
            ]) . "\n";

            foreach ($bookings as $booking) {
                try {
                                    $row = [
                    $booking->booking_reference ?? '',
                    $booking->tour->title ?? 'N/A',
                    $booking->user->name ?? 'N/A',
                    $booking->user->email ?? 'N/A',
                    $booking->user->phone ?? 'N/A',
                    $booking->adults ?? 0,
                    $booking->children ?? 0,
                    $booking->departure_date ? date('Y-m-d', strtotime($booking->departure_date)) : 'N/A',
                    $currencySymbol . ' ' . number_format($booking->total_amount ?? 0),
                    $currencySymbol . ' ' . number_format($booking->adult_price ?? 0),
                    $currencySymbol . ' ' . number_format($booking->children_price ?? 0),
                    $booking->status ?? 'N/A',
                    $booking->payment_status ?? 'N/A',
                    $booking->payment_method ?? 'N/A',
                    $booking->payment_id ?? 'N/A',
                    $booking->created_at ? $booking->created_at->format('Y-m-d') : 'N/A',
                    $booking->created_at ? $booking->created_at->format('Y-m-d H:i:s') : 'N/A'
                ];
                    
                    // Escape CSV values properly
                    $escapedRow = array_map(function($value) {
                        if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                            return '"' . str_replace('"', '""', $value) . '"';
                        }
                        return $value;
                    }, $row);
                    
                    $csvContent .= implode(',', $escapedRow) . "\n";
                    
                } catch (\Exception $e) {
                    \Log::error('Error exporting booking: ' . $e->getMessage(), [
                        'booking_id' => $booking->id ?? 'unknown'
                    ]);
                    // Continue with next booking
                    continue;
                }
            }

            \Log::info('CSV Export completed successfully', [
                'total_bookings' => $bookings->count(),
                'csv_length' => strlen($csvContent)
            ]);
            
            return response($csvContent, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('CSV Export Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $stats = [
            'total_bookings' => TourBooking::count(),
            'pending_payments' => TourBooking::where('payment_status', 'pending')->count(),
            'confirmed_bookings' => TourBooking::where('status', 'confirmed')->count(),
            'total_revenue' => TourBooking::where('payment_status', 'completed')->sum('total_amount'),
            'today_bookings' => TourBooking::whereDate('created_at', today())->count(),
            'this_month_bookings' => TourBooking::whereMonth('created_at', now()->month)->count(),
            'monthly_revenue' => TourBooking::where('payment_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        return response()->json($stats);
    }
}
