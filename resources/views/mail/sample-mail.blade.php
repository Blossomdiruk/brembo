
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>Labour Department</title>
      <!--favicon-->
      <link rel="icon" href="{{ asset('public/back/img/favicon/favicon1.png') }}" type="image/x-icon" />
      <!--favicon-->
      <!-- Bootstrap -->
      <link href="{{ asset('public/css/userpanel/bootstrap.min.css') }}" rel="stylesheet">
      <!-- Bootstrap -->
      <!-- Custom CSS -->
      <link href="{{ asset('public/css/userpanel/full-slider.css') }}" rel="stylesheet">
      <!-- Custom CSS -->
      <!--main css-->
      <link href="{{ asset('public/css/userpanel/laybour_dep.css') }}" rel="stylesheet" type="text/css" media="screen">
      <!--main css-->
      <!--media query css-->
      <link href="{{ asset('public/css/userpanel/mediaquery.css') }}" rel="stylesheet" type="text/css" media="screen">
      <!--media query css-->
      <!--Date Picker-->
      <link rel="stylesheet" type="text/css" href="{{ asset('public/css/userpanel/datepicker/bootstrap-datepicker3.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('public/css/userpanel/datepicker/bootstrap-datepicker3.standalone.min.css') }}" />
      <!--Date Picker end-->
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
      <![endif]-->
      <!--scroll bar style-->
        <style type="text/css">
            ::-webkit-scrollbar {
            width: 1px;
            }
            ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 0px #000000;
            border-radius: 0px;
            }
            ::-webkit-scrollbar-thumb {
            border-radius: 0px;
            background-color: #000000;
            -webkit-box-shadow: inset 0 0 1px #000000;
            }

            .logo{
                width: 65px;
                padding-right: 15px;
            }

            .l_logo{
                width: 70px;
                padding-left: 5px;
            }

            .logo_text_e{
                letter-spacing: 2px;
                font-weight: 900;
                font-size: 16px;
                margin-top: 5px;
                margin-bottom: 5px;
                width: 350px;
            }

            .logo_text_s{
                font-family: 'Iskoola Pota' !important;
                font-weight: 900;
                font-size: 18px;
                margin-top: 5px;
                margin-bottom: 5px;
                width: 350px;
            }

            .logo_text_t{
                font-weight: 900;
                font-size: 13.5px;
                margin-top: 5px;
                margin-bottom: 5px;
                width: 350px;
            }
/* 
            .no_padding{
                padding-right: 0px;
                padding-left: 0px;
            } */

            
            
        </style>
    </head>
    <body >
    <div class="container">
    <div class="row"> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 logo_col" align="center">
            <div >
                <img src="https://cms.tekgeeks.net/public/back/img/gov_logo.png" alt="" class="logo">
                <p class="logo_text_e">Department Labour</p>
                <p class="logo_text_s">කම්කරු දෙපාර්තමේන්තුව</p>
                <p class="logo_text_t">தொழில் திணைக்களம்</p>
            </div> 
        </div>
    </div>
    <div class="clearfix"></div>
        </br></br>  
        <hr/>
                        <div class="row">
                            <label>
                              <p> {!! $body !!} </p>
                            </label>    
                        </div>

                        <div class="clearfix"></div>
                        <hr/>
            <div class="row" id="powerwidgets">
                <section class="col col-4" style="margin-top:25px;">
                <div style="text-align: center;line-height:12px">

                <p>කම්කරු ලේකම් කාර්යාලය, අංක 41, කිරුළ පාර, කොළඹ 05, ශ්‍රී ලංකාව.</p>
                <p>தொழிலாளர் செயலகம், இல 41, கிருளா சாலை, கொழும்பு 05, இலங்கை.</p>
                <p>Labour Secretariat, no 41, kirula Road, colombo 05, sri Lanka.</p>
                <p>ක්ෂණික අැමතැමර්‍ි / தொலைபேசி / Hot Line -  011 2581142-3-6, 011 2581148-9</p>
                    </div>

                        <!-- <img src="http://205.134.251.68/~pcms/assets/img/printoutfooter.jpg" alt="NPC" style="width: 592px; margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto;"> -->
                </section>
            </div>
</div>
</body>
</html>
