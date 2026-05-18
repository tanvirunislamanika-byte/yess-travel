<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Email Verification</title>
</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#faf1ec" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'">
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
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                
            </td>
        </tr>
        </tbody>
    </table>
            
    <!-- hero_welcome -->
    <table align="center" width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
        <tbody>
        <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <table role="presentation" class="column" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr>
                                <td align="center" bgcolor="#015b9c" style="padding: 20px 40px;">
                                    <h1 style="color: #ffffff; font-size: 30px; margin: 0; font-weight: bold;">
                                        Welcome {{ optional($user ?? null)->name ?? optional($notifiable ?? null)->name ?? 'Guest' }}
                                    </h1>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table></td>
        </tr>
        </tbody>
    </table>
            
    
    <!-- content -->
    <table align="center" width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
        <tbody>
            
            
        <tr>
            <td style="padding: 30px 60px; color: #333; line-height: 26px; text-align: left;">
                
        
            <h2 style="font-size: 30px; color: #131224; font-weight:bold; margin-bottom: 0;">Please validate your account</h2>
            <p style="color: #777; margin-bottom: 30px;">
                
                Welcome to TravelIn, your gateway to unforgettable journeys! 🌟 We're excited to have you on board.
                <br><br>
                Your account is almost ready! To unlock exclusive deals, personalized recommendations, and seamless bookings, please verify your email by clicking the button below:
            </p>
            <a href="{{ $actionUrl }}" style="display: inline-block; background: #f8b93a; color: #000; font-size: 14px; font-weight: bold; text-align: center; text-decoration: none; padding: 10px 25px;">
                Verify Now
            </a>


            <p style="color: #777; margin-top: 30px;">
            If you didn't sign up, please ignore this email.
            <br><br>
            Ready to explore? Let's make your dream trip a reality! 🌎✈️
            </p>

            <p style="color: #555; margin-top: 40px;">
            <strong> Warm Regards</strong><br>
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
