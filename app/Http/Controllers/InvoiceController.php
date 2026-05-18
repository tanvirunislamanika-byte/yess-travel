<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotels;
use App\Models\ModulesData;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function generateInvoice($booking_id)
    {
        $booking = Hotels::findOrFail($booking_id);
        $hotel = ModulesData::where('id',$booking->hotel_id)->first();

        $data = [
            'booking' => $booking,
            'hotel' => $hotel,
        ];

        $pdf = Pdf::loadView('invoice', $data);
        return $pdf->download('invoice.pdf');
    }
}

