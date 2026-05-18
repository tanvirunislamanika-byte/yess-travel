<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>New Contact Form Submission</title>
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
                                    <h1 style="color: #ffffff; font-size: 30px; margin: 0; font-weight: bold;">Hello Admin</h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table align="center" width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                            <tr>
                                <td style="padding: 30px 60px; color: #333; line-height: 26px; text-align: left;">
                                    <h2 style="font-size: 24px; color: #131224; font-weight:bold; margin-bottom: 15px;">
                                        New Contact Form Submission
                                    </h2>


                                    <p><strong>Details:</strong></p>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 14px; margin-bottom: 10px;">
                                        <tbody>
                                            <tr>
                                                <td width="150" style="padding: 5px 0;">Name</td>
                                                <td style="padding: 5px 0;"><strong>{{ $data['first_name'] }} {{ $data['last_name'] }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">Email</td>
                                                <td style="padding: 5px 0;"><strong>{{ $data['email_address'] }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">Phone</td>
                                                <td style="padding: 5px 0;"><strong>{{ $data['phone_number'] }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="padding: 5px 0;">Message</td>
                                                <td style="padding: 5px 0;"><strong>{{ $data['message'] }}</strong></td>
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
