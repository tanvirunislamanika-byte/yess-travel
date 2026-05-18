<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>file_1701024712641</title>
        <meta name="author" content="usman khan" />
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
        <table style="border-collapse: collapse; margin-left: 5.594pt;padding-left:50px;padding-right:50px;padding-top:50px" cellspacing="0">
            <tr style="height: 39pt;">
                
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
                    <p class="s2" style="padding-top: 8pt; padding-left: 87pt; padding-right: 87pt; text-indent: 0pt; text-align: center;">Details</p>
                </td>
            </tr>
        </table>
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
        <ul class="row">
                    <li class="col-md-12">{!!$module_data->title!!}</li>
                <?php 
                for ($i = 1; $i <= 25; $i++){
                    $fieldName = "extra_field_type_$i";
                    $fieldType = $module->$fieldName;
                    $fieldTitle = "extra_field_$i";
                    if ($fieldType === 'file') {
                        
                        $fileFields[] = $fieldTitle;
                    }elseif ($fieldType === 'select') {
                        echo '<li class="col-md-6">'.title($module_data->$fieldTitle).'</li>';
                    }else{
                        echo '<li class="col-md-6">'.$module_data->$fieldTitle.'</li>';
                    }


                }
                ?>
                
                    <?php foreach ($fileFields as $key => $fileField) {

                    $filePath = asset("images/" . $module_data->$fileField);
                    //dd($filePath);
                        if (!empty($module_data->$fileField)) { ?>

                            <li class="col-md-6">{!!generateFileViews($filePath)!!}</li>

                       <?php }
                    }?>
                    <li class="col-md-12">{!!$module_data->description!!}</li>
                </ul>
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
        
        <p style="text-indent: 0pt; text-align: left;"><br /></p>
        
       
        
        <h1 style="padding-top: 2pt; padding-left: 19pt; text-indent: 0pt; line-height: 115%; text-align: left;padding-left:50px;padding-right:50px;padding-top:10px">
            Address<span class="p">: IBN Sulaiman Commercial Center, South Tower, 6th Floor Office # 607, P.O.Box 35100, Riyadh 1138, Kingdom of Saudi Arabia.</span>
        </h1>
        <p class="s6" style="padding-left: 19pt; text-indent: 0pt; line-height: 23pt; text-align: left;padding-left:50px;padding-right:50px;padding-top:10px">
            <a href="mailto:info@adeptuscertifications.co.uk" style="color: black; font-family: Calibri, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 11pt;" target="_blank">EMAIL: </a>
            info@pacific.com<span class="s5"> </span><span class="p">PHONE: </span>+966 50 060 1788<span class="p">, </span>+966 (0) 11 286 3394
        </p>
    </body>
</html>












