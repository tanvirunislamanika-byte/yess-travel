<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Report</title>
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
                text-indent: 0;
            }
            .s1 {
                color: black;
                font-family: Arial, sans-serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 15pt;
            }
            .s2 {
                color: black;
                font-family: Arial, sans-serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 16pt;
            }
            .s3 {
                color: black;
                font-family: Calibri, sans-serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 11pt;
            }
            .s4 {
                color: black;
                font-family: Calibri, sans-serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 11pt;
            }
            h1 {
                color: black;
                font-family: Calibri, sans-serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 11pt;
            }
            .p,
            p {
                color: black;
                font-family: Calibri, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 11pt;
                margin: 0pt;
            }
            .s5 {
                color: #00f;
                font-family: Calibri, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 11pt;
            }
            .s6 {
                color: #00f;
                font-family: Calibri, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: underline;
                font-size: 11pt;
            }
            table,
            tbody {
                vertical-align: top;
                overflow: visible;
            }
            
            .table td{
                height:40px;
            }
        </style>
        <?php $url = url('images/back-work-sheet.jpg');?>
    </head>
    <body>
       
                 <table style="border-collapse: collapse; margin-left: 5.594pt;padding-left:50px;padding-right:50px;padding-top:50px;width: 100%;" cellspacing="0">
            <tr style="height: 39pt;">
                <td
                    style="
                        width: 120pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                    rowspan="2"
                >
                    <p style="text-indent: 0pt; text-align: left;"><br /></p>
                    <p style="padding-left: 28pt; text-indent: 0pt; text-align: left;">
                        <span>
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td></td>
                                </tr>
                            </table>
                        </span>
                    </p>
                </td>
                <td
                    style="
                        width: 389pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s1" style="padding-top: 9pt; padding-left: 87pt; padding-right: 93pt; text-indent: 0pt; text-align: center;">Accident Report.</p>
                </td>
            </tr>
            <tr style="height: 47pt;">
                <td
                    style="
                        width: 389pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s2" style="padding-top: 8pt; padding-left: 87pt; padding-right: 87pt; text-indent: 0pt; text-align: center;">Report</p>
                </td>
            </tr>
        </table>
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
        <table style="border-collapse: collapse; margin-left: 5.594pt;padding-left:50px;padding-right:50px;padding-top:10px" class="table" cellspacing="0">
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 128pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Client</p>
                </td>
                <td
                    style="
                        width: 120pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->first_name }} {{ $report->last_name }}</p>
                </td>
                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Street Address</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->street_address }}</p>
                </td>
            </tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 128pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">City</p>
                </td>
                <td
                    style="
                        width: 120pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->city }}</p>
                </td>
                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">State</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->state }}</p>
                </td>
            </tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 128pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Date of Birth</p>
                </td>
                <td
                    style="
                        width: 120pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->dob }}</p>
                </td>

                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Sex</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->sex) }}</p>
                </td>
            </tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 128pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Weight</p>
                </td>
                <td
                    style="
                        width: 120pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->weight }}</p>
                </td>
                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Height</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->height }}</p>
                </td>
                </tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Email Address</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->email_address }}</p>
                </td>

                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Phone Number</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->phone_number }}</p>
                </td> 
</tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Work / Lessons at Bousquet</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->work_lessons_at_bousquet) }}</p>
                </td>

                <td
                    style="
                        width: 119pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Contacts / Eyeglasses</p>
                </td>
                <td
                    style="
                        width: 142pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                   <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->contacts_eyeglasses) }}</p>
                </td>
            </tr>
        </table>
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
        <table style="border-collapse: collapse; margin-left: 5.594pt;padding-left:50px;padding-right:50px;padding-top:10px;width:100% !important ;" class="table" cellspacing="0">
            
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Date</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->date }}</p>
                </td>
                
            </tr><tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Time of Incident</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->time }}</p>
                </td>
                
            </tr><tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Injury Location</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ $report->injury_location }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Selected Trail</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->trail) }}</p>
                </td>
                
            </tr><tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Experience on Trail</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->experience_on_trail) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Ski Lift</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->ski_lift) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Experience on Lift</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->experience_on_lift) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Tubing</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->tubing) }}</p>
                </td>
                
            </tr>
            @if($report->tubing == '21')
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Equipment</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->equipment) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Removed by Patrol</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->removed_by_patrol) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Rental Skis / Snowboard</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->rental_skis_snowboard) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Experience on Skis/Snowboard</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->experience_on_skis_snowboard) }}</p>
                </td>
                
            </tr>
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Wearing Helmet</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->wearing_helmet) }}</p>
                </td>
                
            </tr>
            @if($report->wearing_helmet == '38')
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Removed at Accident</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->removed_at_accident) }}</p>
                </td>
                
            </tr>
            @endif
            @endif
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Transport in Method</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->transport_in_method) }}</p>
                </td>
                
            </tr>
            @if($report->transport_in_method == '102')
            <tr style="height: 36pt;">
                <td
                    style="
                        width: 100pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Remove to Vehicle Details</p>
                </td>
                <td
                    style="
                        width: 265pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{{ title($report->remove_to_vehicle_details) }}</p>
                </td>
                
            </tr>
            @endif
        </table>
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
        <table style="border-collapse: collapse; margin-left: 5.594pt;padding-left:50px;padding-right:50px;padding-top:10px" class="table" cellspacing="0">
            <tr style="height: 110pt;">
                <td
                    style="
                        width: 248pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s4" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Remarks</p>
                </td>
                <td
                    style="
                        width: 261pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">{!!strip_tags($report->note)!!}</p>
                </td>
            </tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 248pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s4" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Inspector Signature</p>
                </td>
                <td
                    style="
                        width: 261pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="text-indent: 0pt; text-align: left;"><br /></p>
                </td>
            </tr>
            <tr style="height: 14pt;">
                <td
                    style="
                        width: 248pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p class="s4" style="padding-left: 5pt; text-indent: 0pt; line-height: 23pt; text-align: left;">Client Signature</p>
                </td>
                <td
                    style="
                        width: 261pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                        border-left-style: solid;
                        border-left-width: 1pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                        border-right-style: solid;
                        border-right-width: 1pt;
                    "
                >
                    <p style="text-indent: 0pt; text-align: left;"><br /></p>
                </td>
            </tr>
        </table>
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
       
        <p style="text-indent: 0pt; text-align: left;">
            <span>
                
            </span>
        </p>
      

        

    
</div>
    </body>
</html>
