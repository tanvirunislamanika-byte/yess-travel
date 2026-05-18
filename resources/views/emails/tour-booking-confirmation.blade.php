<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 150px;
        }
        .info-value {
            color: #333;
            text-align: right;
        }
        .passenger-list {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .passenger-item {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .passenger-item:last-child {
            border-bottom: none;
        }
        .contact-info {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/'.widget(1)->extra_image_1) }}" alt="Logo">
            <h1>Tour Booking Confirmed!</h1>
        </div>

        <div class="greeting">
            Hello <strong>{{ $user->name }}</strong>!
        </div>

        <div class="success-message">
            <strong>Congratulations!</strong> Your tour booking has been confirmed successfully. We're excited to have you join us on this amazing adventure!
        </div>

        <div class="section">
            <div class="section-title">Booking Details</div>
            <div class="info-row">
                <span class="info-label">Tour:</span>
                <span class="info-value">{{ $tour->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booking Reference:</span>
                <span class="info-value"><strong>{{ $booking->booking_reference }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Departure Date:</span>
                <span class="info-value">{{ date('d M Y', strtotime($booking->departure_date)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adults:</span>
                <span class="info-value">{{ $booking->adults }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Children:</span>
                <span class="info-value">{{ $booking->children }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Amount:</span>
                <span class="info-value amount">{{ $currencySymbol }} {{ number_format($booking->total_amount) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Status:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $booking->payment_status }}">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Passenger Details</div>
            <div class="passenger-list">
                @foreach($booking->passengers as $index => $passenger)
                    <div class="passenger-item">
                        <strong>{{ $index + 1 }}. {{ $passenger['title'] }} {{ $passenger['first_name'] }} {{ $passenger['last_name'] }}</strong>
                        @if(isset($passenger['country_name']))
                            <br><small>Country: {{ $passenger['country_name'] }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section">
            <div class="section-title">Contact Information</div>
            <div class="contact-info">
                @if(isset($booking->contact['name']))
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $booking->contact['name'] }}</span>
                    </div>
                @endif
                @if(isset($booking->contact['email']))
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $booking->contact['email'] }}</span>
                    </div>
                @endif
                @if(isset($booking->contact['phone']))
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $booking->contact['phone'] }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Important Information</div>
            <ul style="color: #555; line-height: 1.8;">
                <li>Please arrive at the departure point 30 minutes before the scheduled departure time.</li>
                <li>Don't forget to bring your identification documents and any required visas.</li>
                <li>Check the weather forecast and pack accordingly.</li>
                <li>If you have any special dietary requirements, please inform us in advance.</li>
                <li>For any questions or changes, contact our support team immediately.</li>
            </ul>
        </div>

        <div class="footer">
        <p style="color: #000; margin-top: 40px; font-weight: bold;">
                                    If you have any questions, feel free to contact us.
                                    <br>
                                    We look forward to hosting you!
                                    </p>
        <p style="color: #555; margin-top: 40px;">
            Warm Regards,<br>
            The {{ config('app.name') }} Team<br>
            <a href="{{url('/')}}" target="_blank" style="color: #015b9c;">{{url('/')}}</a>
        </p>
        </div>
    </div>
</body>
</html>
