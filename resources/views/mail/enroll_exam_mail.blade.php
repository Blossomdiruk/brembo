<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Brembo</title>
  </head>

  <style>
    .main_btn{
      padding: 10px 22px;
      background-color: #e43038;
      font-size: 12px;
      text-transform: uppercase;
      color: #fff;
      font-weight: 700;
      border-radius: 5px;
      transition: 0.4s ease;
      border: none;
      cursor: pointer;
    }

    .main_btn:hover{
      background-color: #333333;
      color: #fff;
    }
  </style>
  
  <body style="background-color: #f6f6f6; width: 100%; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; ">
   
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
      <tr>
        <td>&nbsp;</td>
        <td style="display: block; margin: 0 auto !important; /* makes it centered */ max-width: 580px; padding: 10px; width: 580px;">
          <div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">
              <img src="{{ asset('public/images/top_banner.jpg') }}" alt="" style="width: 650px; border: none; -ms-interpolation-mode: bicubic; max-width: 100%; ">

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" style="background: #ffffff; border-radius: 3px; width: 100%;">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td style="background: #ffffff; border-radius: 3px; width: 100%; padding: 20px;">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
   
                      <td>

                        <h2 style="text-align: center; text-transform: uppercase; color: #000000; margin-bottom: 15px;"><b>Registered to an Exam</b></h2>

                        
                        <div style="width: 520px; float: left; text-align: left;">
                                  
                            <p style="font-size: 16px; margin-bottom: 0px;"><b style="display: inline-block; width: 120px;">Hi {{ $name }},</b></p>

                            <p>
                              You recently requested to reset your password for your Brembo account. Use the button below to reset it. This password reset is only valid for the next 24 hours.
                            </p>

                            <div style="text-align: center; padding-bottom: 20px;">
                              <a href="{{ url('workshop/reset-password/'.encrypt($workshop_id)) }}" class="main_btn">Start the Exam</a>
                            </div> 
                            
                            <p>
                              If you did not request a password reset, please ignore this email or <a style="color: #e43038;" href="">contact support</a> if you have questions.
                            </p>
                            
                            <p style="margin-bottom: 0px;">Thanks,</p>
                            <p style="margin-top: 0px;">The Brembo Team</p>
                            
                          </div>

                          <div class="clearfix"></div>

                        <div class="clearfix"></div>

                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->

            <!-- START FOOTER -->
            <div class="footer" style="color: #999999; font-size: 12px; text-align: center;">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                <tr>
                  <td style="padding-bottom: 0px; font-size: 12px; text-align: center; padding-bottom: 5px;">
                    <br>
                    <span class="apple-link">No : 123/A, ABC Road, Sample City, Australia.</span>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 12px; text-align: center;">
                      Tel : 0000000000  |  0000000000
                  </td>
                </tr>

                 <tr>
                  <td style="font-size: 12px; text-align: center;">
                      Email : info@brembo.com
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->

          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>