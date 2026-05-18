<!DOCTYPE html>
<html>
<head>
    <title>Hotel Booking Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { text-align: left; }
        .title { font-size: 28px; font-weight: bold; text-align: right; }
        .section { margin-top: 30px; }
        .section h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .info-table, .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .info-table td { padding: 5px 10px; vertical-align: top; }
        .table, .table th, .table td { border: 1px solid black; padding: 10px; text-align: left; }
        .total { font-weight: bold; text-align: right; }
    </style>
</head>
<body>
<div class="container">
    <table width="100%" class="header" cellpadding="10">
        <tr>
            <td class="logo">
                <img src="{{ asset('images/'.widget(1)->extra_image_1) }}" alt="Logo" height="80">
            </td>
            <td class="title">Hotel Booking Invoice</td>
        </tr>
    </table>

    <div class="section">
        <h3>Guest Information</h3>
        <table class="info-table">
            <tr>
                <td><strong>Name:</strong> {{ auth()->user()->name }}</td>
                <td><strong>Email:</strong> {{ auth()->user()->email }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong> {{ auth()->user()->phone ?? 'Not provided' }}</td>
                <td><strong>Country:</strong> {{ auth()->user()->country->name ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td><strong>State:</strong> {{ auth()->user()->state ?? 'Not provided' }}</td>
                <td><strong>City:</strong> {{ auth()->user()->city ?? 'Not provided' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Booking Information</h3>
        <table class="info-table">
            <tr>
                <td><strong>Transaction ID:</strong> {{ $booking->transaction_id }}</td>
                <td><strong>Hotel:</strong> {{ $hotel->title }}</td>
            </tr>
            <tr>
                <td><strong>Check-In:</strong> {{ date('d M Y', strtotime($booking->check_in)) }}</td>
                <td><strong>Check-Out:</strong> {{ date('d M Y', strtotime($booking->check_out)) }}</td>
            </tr>
            <tr>
                <td><strong>Rooms:</strong> {{ $booking->rooms }}</td>
                <td><strong>Guests:</strong> {{ $booking->adults }} Adults, {{ $booking->childrens }} Children</td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong> {{ $booking->payment_via }}</td>
                <td><strong>Booking Status:</strong> Confirmed</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Payment Summary</h3>
        <table class="table">
            <tr>
                <th>Rooms</th>
                <th>Price per Night</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{{ $booking->rooms }}</td>
                <td>${{ number_format($hotel->extra_field_1, 2) }}</td>
                <td>${{ number_format($booking->price, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="total">Total Payment</td>
                <td class="total">${{ number_format($booking->price, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section" style="margin-top: 40px;">
    <p><strong>Thank you for choosing us!</strong> We're honored to have had the chance to host you and hope your stay was nothing short of exceptional.</p>
</div>

</div>
</body>
</html>
