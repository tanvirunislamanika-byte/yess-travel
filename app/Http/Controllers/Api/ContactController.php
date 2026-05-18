<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact_us;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Store contact form submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'phone' => 'sometimes|string|max:20',
            'type' => 'sometimes|in:general,support,booking,complaint'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'phone' => $request->phone,
                'type' => $request->type ?? 'general',
                'status' => 'new'
            ];

            $contact = Contact_us::create($contactData);

            // Send email notification (if mail is configured)
            try {
                Mail::to(config('mail.admin_email', 'admin@example.com'))
                    ->send(new ContactMail($contact));
            } catch (\Exception $e) {
                Log::warning('Failed to send contact email:', [
                    'contact_id' => $contact->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Contact form submitted:', [
                'contact_id' => $contact->id,
                'name' => $request->name,
                'email' => $request->email,
                'type' => $request->type ?? 'general'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. We will get back to you soon.',
                'data' => [
                    'contact_id' => $contact->id,
                    'reference' => 'CONT' . $contact->id
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Contact form error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contact information
     */
    public function info()
    {
        try {
            $contactInfo = [
                'address' => [
                    'street' => '123 Travel Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'USA'
                ],
                'phone' => [
                    'main' => '+1 (555) 123-4567',
                    'support' => '+1 (555) 123-4568',
                    'emergency' => '+1 (555) 123-4569'
                ],
                'email' => [
                    'info' => 'info@travelapp.com',
                    'support' => 'support@travelapp.com',
                    'bookings' => 'bookings@travelapp.com'
                ],
                'social_media' => [
                    'facebook' => 'https://facebook.com/travelapp',
                    'twitter' => 'https://twitter.com/travelapp',
                    'instagram' => 'https://instagram.com/travelapp',
                    'linkedin' => 'https://linkedin.com/company/travelapp'
                ],
                'business_hours' => [
                    'monday' => '9:00 AM - 6:00 PM',
                    'tuesday' => '9:00 AM - 6:00 PM',
                    'wednesday' => '9:00 AM - 6:00 PM',
                    'thursday' => '9:00 AM - 6:00 PM',
                    'friday' => '9:00 AM - 6:00 PM',
                    'saturday' => '10:00 AM - 4:00 PM',
                    'sunday' => 'Closed'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $contactInfo
            ]);

        } catch (\Exception $e) {
            Log::error('Get contact info error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch contact information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get FAQ categories and questions
     */
    public function faq()
    {
        try {
            $faqs = [
                'booking' => [
                    'title' => 'Booking & Reservations',
                    'questions' => [
                        [
                            'question' => 'How do I book a flight?',
                            'answer' => 'You can book flights through our website or mobile app. Simply search for your desired route, select your preferred flight, and complete the booking process.'
                        ],
                        [
                            'question' => 'Can I modify my booking?',
                            'answer' => 'Yes, you can modify your booking up to 24 hours before departure. Please contact our customer service for assistance.'
                        ],
                        [
                            'question' => 'What is your cancellation policy?',
                            'answer' => 'Cancellation policies vary by airline and fare type. Please check the specific terms when booking or contact our support team.'
                        ]
                    ]
                ],
                'payment' => [
                    'title' => 'Payment & Billing',
                    'questions' => [
                        [
                            'question' => 'What payment methods do you accept?',
                            'answer' => 'We accept all major credit cards, debit cards, and digital wallets including PayPal, Apple Pay, and Google Pay.'
                        ],
                        [
                            'question' => 'Is my payment information secure?',
                            'answer' => 'Yes, we use industry-standard SSL encryption to protect your payment information. We never store your complete credit card details.'
                        ]
                    ]
                ],
                'support' => [
                    'title' => 'Customer Support',
                    'questions' => [
                        [
                            'question' => 'How can I contact customer support?',
                            'answer' => 'You can reach our customer support team via phone, email, or live chat. We\'re available 24/7 to assist you.'
                        ],
                        [
                            'question' => 'What is your response time?',
                            'answer' => 'We typically respond to inquiries within 2-4 hours during business hours and within 24 hours for after-hours inquiries.'
                        ]
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $faqs
            ]);

        } catch (\Exception $e) {
            Log::error('Get FAQ error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit feedback
     */
    public function feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|min:10',
            'category' => 'sometimes|in:website,app,service,booking,other'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedbackData = [
                'name' => $request->name,
                'email' => $request->email,
                'rating' => $request->rating,
                'feedback' => $request->feedback,
                'category' => $request->category ?? 'other',
                'status' => 'new'
            ];

            // Store feedback (you might want to create a separate feedback table)
            // For now, we'll log it
            Log::info('Feedback submitted:', $feedbackData);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback! We appreciate your input.'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Feedback submission error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 