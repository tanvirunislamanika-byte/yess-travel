<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Flight Booking Confirmation</title>
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
                                            New Flight Booking has been Made
                                        @else
                                            Your Flight Booking Confirmation
                                        @endif
                                    </h2>

                                    <p><strong>Flight Details:</strong></p>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 14px; margin-bottom: 10px;">
                                        <tbody>
                                            <tr>
                                                <td width="150" style="padding: 5px 0;">Airline</td>
                                                <td style="padding: 5px 0;"><strong>{{ $hotel->airline_name ?? 'N/A' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">From</td>
                                                <td style="padding: 5px 0;"><strong>{{ $hotel->origin_airport ?? $hotel->origin_code ?? 'N/A' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">To</td>
                                                <td style="padding: 5px 0;"><strong>{{ $hotel->destination_airport ?? $hotel->destination_code ?? 'N/A' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">Departure Date</td>
                                                <td style="padding: 5px 0;"><strong>{{ $hotel->departure_date ?? 'N/A' }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <hr>
                                    <p><strong>Booking Details:</strong></p>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 14px; margin-bottom: 10px;">
                                        <tbody>
                                            <tr>
                                                <td width="150" style="padding: 5px 0;">User Name</td>
                                                <td style="padding: 5px 0;"><strong>{{ $user->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">Email</td>
                                                <td style="padding: 5px 0;"><strong>{{ $user->email }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">Phone</td>
                                                <td style="padding: 5px 0;"><strong>{{ $user->mobile ?? 'N/A' }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <p style="color: #000; margin-top: 40px; font-weight: bold;">
                                        Login into admin to check further details.
                                    </p>

                                    <p style="color: #555; margin-top: 40px;">
                                        Warm Regards,<br>
                                        The Travelin Verification Team<br>
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
