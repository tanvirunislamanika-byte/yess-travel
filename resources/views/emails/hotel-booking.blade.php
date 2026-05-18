<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Hotel Booking Confirmation</title>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#faf1ec">
        <tbody>
            <tr>
                <td height="50">&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <table width="650" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#ffffff" style="border-top: 5px solid #015b9c;">
                        <tbody>
                            <tr>
                                <td align="center" height="100">
                                    <img src="{{ asset('images/'.widget(1)->extra_image_1) }}" alt="Logo">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table align="center" width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                            <tr>
                                <td align="center" bgcolor="#015b9c" style="padding: 20px 40px;">
                                    <h1 style="color: #ffffff; font-size: 30px; margin: 0; font-weight: bold;">
                                        @if(isset($isAdmin) && $isAdmin)
                                            Hello Admin
                                        @else
                                            Hello {{ $user->name }}
                                        @endif
                                    </h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table align="center" width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                            <tr>
                                <td style="padding: 30px 60px; color: #333; line-height: 26px; text-align: left;">
                                    <h2 style="font-size: 24px; color: #131224; font-weight:bold; margin-bottom: 15px;">
                                        @if(isset($isAdmin) && $isAdmin)
                                            New Hotel Booking has been Made
                                        @else
                                            Your Hotel Booking Confirmation
                                        @endif
                                    </h2>

                                    <p><strong>Hotel Details:</strong></p>
                                    <div class="section">
                                        <p><strong class="label">Hotel:</strong> {{ $hotel->title ?? 'Hotel Name Not Available' }}</p>
                                        <p><strong class="label">Address:</strong> {{ $hotel->extra_field_18 ?? 'Address Not Available' }}</p>
                                        <p><strong class="label">Type:</strong> {{ $hotel->extra_field_23 ?? 'N/A' }} {{ $hotel->extra_field_2 ?? 'N/A' }}</p>
                                    </div>

                                    <hr>
                                   
                                    <div class="section">
                                        <h3>Booking Information</h3>
                                        <p><strong class="label">Booking ID:</strong> {{ $booking->id }}</p>
                                        <p><strong class="label">Travelling From:</strong> {{ $booking->travelling_from }}</p>
                                        <p><strong class="label">Check-in:</strong> {{ $booking->check_in }}</p>
                                        <p><strong class="label">Check-out:</strong> {{ $booking->check_out }}</p>
                                        <p><strong class="label">Adults:</strong> {{ $booking->adults }}</p>
                                        <p><strong class="label">Childrens:</strong> {{ $booking->childrens }}</p>
                                        <p><strong class="label">Rooms:</strong> {{ $booking->rooms }}</p>
                                        <p><strong class="label">Total Amount:</strong> ${{ $booking->price }}</p>
                                        <p><strong class="label">Payment Method:</strong> {{ $booking->payment_via }}</p>
                                        <p><strong class="label">Payment Status:</strong> {{ $booking->status ?? 'Pending' }}</p>
                                        @php
                                            // Handle guest_details whether it's JSON string or array
                                            $guestDetails = $booking->guest_details;
                                            if (is_string($guestDetails)) {
                                                $guestDetails = json_decode($guestDetails, true);
                                            }
                                            // Ensure it's an array
                                            if (!is_array($guestDetails)) {
                                                $guestDetails = [];
                                            }
                                        @endphp

                                        <div class="section">
                                            <h3>Guest Details</h3>
                                            @if(is_array($guestDetails) && !empty($guestDetails))
                                                @if(isset($guestDetails[0]))
                                                    <p><strong class="label">Name:</strong> {{ $guestDetails[0] ?? 'N/A' }}</p>
                                                    <p><strong class="label">Email:</strong> {{ $guestDetails[1] ?? 'N/A' }}</p>
                                                    <p><strong class="label">Phone:</strong> {{ $guestDetails[2] ?? 'N/A' }}</p>
                                                @else
                                                    @foreach($guestDetails as $index => $guest)
                                                        <p><strong class="label">Guest {{ $index + 1 }}:</strong> {{ is_array($guest) ? json_encode($guest) : $guest }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p>Guest details not available.</p>
                                            @endif
                                        </div>

                                    </div>

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
                                </td>       
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="50">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
