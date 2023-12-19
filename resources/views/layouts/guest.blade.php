<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Brembo | @yield('title')</title>


    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}" defer></script>


    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/css/userpanel/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/css/userpanel/font-awesome.min.css') }}">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/css/userpanel/smartadmin-production.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/css/userpanel/smartadmin-skins.min.css') }}">

    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/css/userpanel/smartadmin-rtl.min.css') }}">

    <!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="{{ asset('public/back/img/favicon.jpg') }}https://cms.tekgeeks.net/public/back/img/favicon.jpg" type="image/x-icon">
    <link rel="icon" href="{{ asset('public/back/img/favicon.jpg') }}" type="image/x-icon">



    <!-- #APP SCREEN / ICONS -->
    <!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="{{ asset('public/img/splash/sptouch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('public/img/splash/touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('public/img/splash/touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('public/img/splash/touch-icon-ipad-retina.png') }}">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="{{ asset('public/img/splash/ipad-landscape.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="{{ asset('public/img/splash/ipad-portrait.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="{{ asset('public/img/splash/iphone.png') }}" media="screen and (max-device-width: 320px)">

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            direction: ltr;
        }

        #extr-page #main {
            padding-top: 0px;
        }

        #main {
            /* margin-left: 220px; */
            padding: 0;
            padding-bottom: 0px;
            min-height: 500px;
            position: relative;
            background-image: url('https://cms.tekgeeks.net/public/back/img/inner_bg.jpg') !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-position: bottom center !important;
        }
      
      .shadow {
          box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      }

        #content {
            padding-top: 0px !important;
            padding: 10px 14px;
            position: relative;
            height: 100vh;
        }

        .login_form_div {
            position: relative;
            height: 100vh;
            background-color: rgba(228,48,56, 0.9);
        }

        .well {
            margin-top: 45px !important;
            background-color: #fbfbfb;
            border: transparent;
            box-shadow: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            position: relative;
        }

        .client-form header {
            padding: 15px 13px;
            margin: 0;
            border-bottom-style: solid;
            border-bottom-color: transparent;
            background: #3b2c46;
        }

        .smart-form fieldset {
            display: block;
            padding: 25px 14px 5px;
            border: none;
            background: #3b2c46;
            position: relative;
        }

        .smart-form footer {
            display: block;
            padding: 7px 14px 15px;
            border-top: transparent;
            background: #3b2c46 !important;
        }

        .smart-form .input input,
        .smart-form .select select,
        .smart-form .textarea textarea {
            display: block;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            height: 45px;
            line-height: 32px;
            padding: 5px 10px;
            outline: 0;
            border-width: 1px;
            border-style: solid;
            border-color: #999999;
            border-radius: 5px;
            background: #fff;
            font: 13px/16px 'Open Sans', Helvetica, Arial, sans-serif;
            color: #000000;
            appearance: normal;
            -moz-appearance: none;
            -webkit-appearance: none;
        }

        .smart-form .icon-append,
        .smart-form .icon-prepend {
            position: absolute;
            top: 12px;
            width: 22px;
            height: 22px;
            font-size: 14px;
            line-height: 22px;
            text-align: center;
        }

        .smart-form .label {
            display: block;
            margin-bottom: 6px;
            line-height: 19px;
            font-weight: 900;
            font-size: 13px;
            color: #ffffff;
            text-align: left;
            white-space: normal;
        }

        .btn-primary {
            color: #fff;
            background-color: #000000;
            border-color: #00000;
            font-weight: 900 !important;
            padding: 10px !important;
            height: auto !important;
            border-radius: 5px;
            webkit-transition: all 500ms ease;
            -moz-transition: all 500ms ease;
            -ms-transition: all 500ms ease;
            -o-transition: all 500ms ease;
            transition: all 500ms ease;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #10722f;
            border-color: #ffffff;
        }

        .logo_col {
            float: left;
            margin-bottom: 25px;
            position: relative;
            z-index: 999;
            padding-left: 7px;
        }

        .logo {
            width: 65px;
            float: left;
            padding-right: 15px;
            position: relative;
            top: 5px;
        }

        .l_logo {
            width: 70px;
            float: left;
            padding-left: 5px;
            position: relative;
            top: 5px;
        }

        .logo_text_e {
            color: #ffffff;
            letter-spacing: 2px;
            font-weight: 900;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 0px;
            width: 255px;
        }

        .logo_text_s {
            color: #ffffff;
            font-family: 'Iskoola Pota' !important;
            font-weight: 900;
            font-size: 18px;
            margin-top: 0px;
            margin-bottom: 5px;
            width: 255px;
        }


        .logo_text_t {
            color: #ffffff;
            font-weight: 900;
            font-size: 13.5px;
            margin-top: 5px;
            margin-bottom: 5px;
            width: 255px;
        }

        #extr-page .hero {
            background-image: url(../img/gradient/login.png);
            background-repeat: no-repeat;
            background-position: 0 137px;
            height: auto;
            width: 100%;
            float: left;
        }

        .save_btn {
            background-color: #000000 !important;
            color: #ffffff !important;
            font-weight: 900 !important;
            padding: 12px 30px !important;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -ms-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
        }

        .save_btn:hover {
            color: #E43038 !important;
            background-color: #000000 !important;
        }


        .lan_text_e {
            font-weight: 900;
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 5px;
            padding-left: 15px;
            color: #999999;
        }

        .lan_text_s {
            font-family: 'Iskoola Pota' !important;
            font-weight: 900;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 5px;
            padding-left: 15px;
            color: #999999;
            border-left: 1px solid #000000;
            margin-left: 10px;
        }

        .lan_text_t {
            font-weight: 900;
            font-size: 12.5px;
            margin-top: 5px;
            margin-bottom: 5px;
            padding-left: 15px;
            color: #999999;
            border-left: 1px solid #000000;
            margin-left: 10px;
        }
    </style>
</head>

<body class="animated fadeInDown" style="background-image: url('{{ asset('public/back/img/banner_bg.jpg') }}');background-repeat: no-repeat;
  background-size: cover;">
    <!-- <div class="font-sans text-gray-900 antialiased"> -->
    <div>
        {{ $slot }}
    </div>
</body>

</html>