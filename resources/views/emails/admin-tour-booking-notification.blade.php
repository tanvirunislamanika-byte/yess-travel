<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Tour Booking Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
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
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 28px;
        }
        .alert {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ffeaa7;
            border-left: 4px solid #f39c12;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #495057;
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
            color: #495057;
            min-width: 180px;
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
        .tour-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        .action-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .action-button:hover {
            background-color: #0056b3;
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
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/'.widget(1)->extra_image_1) }}" alt="Logo">
            <h1>New Tour Booking Alert!</h1>
        </div>

        <div class="alert">
            <strong>Attention Admin!</strong> A new tour booking has been made and requires your review.
        </div>

        <div class="section">
            <div class="section-title">Booking Summary</div>
            <div class="info-row">
                <span class="info-label">Tour:</span>
                <span class="info-value"><strong>{{ $tour->title }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Booking Reference:</span>
                <span class="info-value"><strong>{{ $booking->booking_reference }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Customer:</span>
                <span class="info-value">{{ $user->name }} ({{ $user->email }})</span>
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
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ ucfirst($booking->payment_method) }}</span>
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
            <div class="section-title">Tour Information</div>
            <div class="tour-info">
                <div class="info-row">
                    <span class="info-label">Departure Country:</span>
                    <span class="info-value">{{ $tour->departureCountry->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure State:</span>
                    <span class="info-value">{{ $tour->departureState->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure City:</span>
                    <span class="info-value">{{ $tour->departureCity->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Duration:</span>
                    <span class="info-value">{{ $tour->duration }} days</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Adult Price:</span>
                    <span class="info-value">{{ $currencySymbol }} {{ number_format($tour->adult_price) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Children Price:</span>
                    <span class="info-value">{{ $currencySymbol }} {{ number_format($tour->children_price) }}</span>
                </div>
            </div>
        </div>

        <div class="section" style="text-align: center;">
            <a href="{{ route('admin.tour-bookings.show', $booking->id) }}" class="action-button">
                View Full Booking Details
            </a>
        </div>

        
    </div>
</body>
</html>
