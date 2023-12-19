
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

        <title>DoL | Dashboard</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/font-awesome.min.css') }}">

        <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/smartadmin-production-plugins.min.css') }}">
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/smartadmin-production.min.css') }}">
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/smartadmin-skins.min.css') }}">

        <!-- SmartAdmin RTL Support  -->
        <link rel="stylesheet" type="text/css" media="screen" href="http://labour/public/back/css/smartadmin-rtl.min.css{{ asset('public/back/css/smartadmin-skins.min.css') }}">

                <!--media query css-->
            <link href="http://labour/public/back/css/mediaquery.css{{ asset('public/back/css/smartadmin-skins.min.css') }}" rel="stylesheet" type="text/css" media="screen">
            <!--media query css-->

        <!-- We recommend you use "your_style.css" to override SmartAdmin
                 specific styles this will also ensure you retrain your customization with each SmartAdmin update.
            <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css') }}" -->

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/demo.min.css') }}">

        <!-- FAVICONS -->
        <link rel="shortcut icon" href="{{ asset('public/back/img/favicon/favicon1.png') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('public/back/img/favicon/favicon1.png') }}" type="image/x-icon">

        <!-- GOOGLE FONT -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- Specifying a Webpage Icon for Web Clip
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="{{ asset('public/back/img/splash/sptouch-icon-iphone.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('public/back/img/splash/touch-icon-ipad.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('public/back/img/splash/touch-icon-iphone-retina.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('public/back/img/splash/touch-icon-ipad-retina.png') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">

        <!--Date Picker-->
        <!--<link rel="stylesheet" type="text/css" href="http://labour/public/css/userpanel/datepicker/bootstrap-datepicker3.min.css"/>-->
        <!--<link rel="stylesheet" type="text/css" href="http://labour/public/css/userpanel/datepicker/bootstrap-datepicker3.standalone.min.css"/>-->
        <!--Date Picker end-->

        <!-- Scripts -->
        <script src="{{ asset('public/js/app.js') }}" defer></script>
        <script src="{{ asset('public/back/js/jquery.min.js') }}"></script>
        <!-- BOOTSTRAP JS -->
        <script src="{{ asset('public/back/js/bootstrap/bootstrap.min.js') }}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <style>
            .parsley-errors-list li{
                list-style: none;
                color: red;
            }

                .demo>span {
                    display: none !important;
                }

                /* .widget-body.no-padding {
                    margin: 12px 12px;
                } */

                .widget-body.no-padding {
                    margin: 0px 0px;
                }

                #content{
                    padding-top: 0px;
                }

                #sparks {
                    margin-top: 5px;
                    margin-bottom: 0px;
                }

                #sparks li {
                    border-radius: 5px;
                    display: inline-block;
                    max-height: 47px;
                    overflow: hidden;
                    box-sizing: content-box;
                    -moz-box-sizing: content-box;
                    -webkit-box-sizing: content-box;
                    width: 95px;
                    text-align: center;
                    background-color: #963c2c;
                    color: #ffffff !important;
                    padding: 22px 15px !important;
                }

                #sparks .sparks-info {
                    border-radius: 5px;
                    display: inline-block;
                    max-height: 47px;
                    text-align: center;
                    overflow: hidden;
                    box-sizing: content-box;
                    -moz-box-sizing: content-box;
                    -webkit-box-sizing: content-box;
                    width: 95px;
                    background-color: #963c2c;
                    color: #ffffff !important;
                    padding: 22px 15px !important;
                }

                .top_btn_height {
                    padding: 5px;
                    height: 93px;
                    width: 127px;
                    white-space: normal;
                }

                .cms_top_btn {
                    display: inline-flex;
                    justify-content: center;
                    align-items: center;
                    background-color: #963c2c;
                    --padding-x: 1.2em;
                    border-color: transparent;
                    border-radius: 0.25em;
                    box-shadow: 0 1px 4px rgba(9, 66, 179, 0.25);
                    color: #ffffff !important;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    transition: 0.2s;
                    word-wrap: break-word;
                    font-weight: 600;
                    font-size: 11px;
                    text-transform: uppercase;
                    margin-bottom:5px;
                }
                .cms_top_btn:hover {
                    background-color: #3b2c46;
                }
                .cms_top_btn:focus {
                    outline: none;
                    box-shadow: 0px 0px 0px 2px rgba(42, 109, 244, 0.2);
                }
                .cms_top_btn:active {
                    transform: translateY(2px);
                }

                .cms_top_btn_row{
                    text-align:right;
                    margin-top:5px;

                }

                .cms_top_btn_active{
                    background-color: #3b2c46 !important;

                }

                #sparks li h5{
                    color: #ffffff !important;
                    float: none;
                    font-weight: 900;
                }

                #sparks .sparks-info_active{
                    background-color: #3b2c46 !important;
                }

                #ribbon {
                    min-height: 40px;
                    background: #2e2236;
                    padding: 0 13px;
                    position: relative;
                }

                aside {
                    background: #3b2c46 !important;
                    color: #fff;
                }

                nav ul ul {
                    margin: 0;
                    display: none;
                    background: rgb(46 34 54);
                    padding: 7px 0;
                }

                .btn-primary{
                    background-color: #963c2c;
                    color: #fff;
                    font-weight: 900;
                    padding: 12px 30px;
                    -webkit-transition: all 0.5s ease-in-out;
                    -moz-transition: all 0.5s ease-in-out;
                    -ms-transition: all 0.5s ease-in-out;
                    -o-transition: all 0.5s ease-in-out;
                    transition: all 0.5s ease-in-out;
                }

                .btn-primary:hover{
                    color: #fff;
                    background-color: #3b2c46;
                }

                select.input-sm {
                    height: 30px;
                    line-height: 15px !important;
                }

                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                    padding: 8px;
                    line-height: 1.42857143;
                    vertical-align: middle !important;
                }

                #logo {
                    display: inline-block;
                    width: 200px;
                    margin-top: 5px;
                    margin-left: 4px;
                }

                #logo img {
                    width: 190px;
                    height: auto;
                    padding-left: 3px;
                }

                .no-padding .dataTables_wrapper table, .no-padding>table {
                    border: 1px solid #dddddd !important;
                    margin-bottom: 20!important;
                    border-bottom-width: 0.2 !important;
                }

                .txt-color-blue {
                    color: #fee73d!important;
                }

                .input-group-btn>.btn {
                    position: relative;
                    padding: 10px;
                    height: auto !important;
                }

                .page-title {
                    margin: 8px 0 13px;
                    font-size: 24px;
                    font-weight: 900;
                    text-transform: uppercase;
                    /* background-color: #2e75b5; */
                    display: inline-block;
                    /* padding: 10px 25px; */
                    color: #963c2c !important;
                    border-radius: 5px;
                    margin-top: 12px !important;
                    /* border-left: 5px solid #ffa500; */
                }

                #header>:first-child {
                    width: auto;
                }

                .jarviswidget>header {
                    border-color: #383838!important;
                    background: #404040;
                    color: #fff;
                }

                img.online {
                    border-left-color: transparent !important;
                }

                .tooltip.fade.in {
                    opacity: 1 !important;
                }

                .modal-header .close {
                    margin-top: -2px;
                    position: absolute;
                    right: 15px;
                    top: 24px;
                }

                 @media  only screen and (max-width : 1024px) {
                   .page-title span {
                        font-size: 12px;
                    }

                    .page-title {
                        margin: 8px 0 13px;
                        font-size: 17px;
                    }

                    #sparks li h5 {
                        /* font-size: 9px; */
                    }

                    #sparks .sparks-info {
                        padding: 22px 0px !important;
                    }
                }

                /* Extra Small Devices, Phones */
                @media  only screen and (max-width : 880px) {
                    #hide-menu>:first-child>a, .btn-header a{
                        border: 1px solid #bfbfbf !important;
                        background-color: #f8f8f8 !important;
                        font-size: 16px; width: 40px!important;
                        height: 39px!important;
                        width: 40px!important;
                        text-align: center;
                    }

                    .btn-header.transparent a {
                        width: 40px!important;
                    }

                    .login_sign{
                        position: relative;
                        top: -5px;
                    }
                }

                @media  only screen and (max-width : 768px) {
                    #logo-group{
                      width: 153px!important;
                    }

                    #logo img {
                        width: 124px;
                        height: auto;
                        padding-left: 3px;
                    }
                }

                 /* Large Devices, Wide Screens */
                 @media  only screen and (max-width : 1024px) {
                    .jarviswidget header h2 {
                    width: auto;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    overflow: hidden;
                    font-size: 11px;
                    }


                }

                @media  only screen and (max-width : 767px) {
                    .widget-body.no-padding {
                        margin: -10px -10px;
                    }

                    .cms_top_btn_row{
                        text-align:center;
                    }
                }
                .widget-toolbar {
                    display: none !important;
                    }

                .jarviswidget>header>.widget-icon {
                    padding-top: 10px !important;
                }
        </style>

    <script language="javascript">
        setTimeout(function(){
        window.location.reload(1);
        }, 30000);
    </script>

    </head>

    <div id="" role="">
        <!-- RIBBON -->
        <div id="">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i>  {{ __('dashboard.title') }} <span>>&nbsp;All Offices Summery Dashboard  </span></h1>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <ul id="sparks" class="">
                        {{-- <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 0 10px; width: auto;">
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> Received <span class="txt-color-blue" style="color: #57889c!important;"><i class="fa fa-arrow-circle-up"></i>&nbsp;</span></h5>
                        </li> --}}
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            {{-- <a href="{{ 'action-pending-list' }}" > --}}
                                <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.new') }} <span class="txt-color-blue" style="color: #57889c!important;"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;{{ $newcomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'action-pending-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.action_pending') }} <span class="txt-color-purple"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;{{ $pendingcomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'investigation-ongoing-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.ongoing') }}  <span class="txt-color-greenDark"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;{{ $ongoingcomplaintcount }}</span></h5>
                        {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'recovery-pending-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.recovery') }}  <span class="txt-color-greenDark"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;{{ $recoverycomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'appeal-pending-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.appeal') }}  <span class="txt-color-greenDark"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;{{ $appealcomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'legal-certificate-pending-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.legal') }}  <span class="txt-color-greenDark"><i class="fa fa-certificate" aria-hidden="true"></i>&nbsp;{{ $legalcomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'plaint-chargesheet-pending-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.plaint') }}  <span class="txt-color-greenDark"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;{{ $chargecomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        {{-- <a href="{{ 'temporary-closed-list' }}" > --}}
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.temporary_closed') }} <span class="txt-color-greenDark"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;{{ $tempclosedcomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        {{-- <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> Transfer In <span class="txt-color-blue">$47,171</span></h5>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> Transfer Out <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i>&nbsp;25,000</span></h5>
                        </li> --}}
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            {{-- <a href="{{ 'closed-list' }}" > --}}
                                <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.closed') }} <span class="txt-color-greenDark"><i class="fa fa-times-circle-o" aria-hidden="true"></i>&nbsp;{{ $closedcomplaintcount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            {{-- <a href="{{ 'pending-approval-list' }}" > --}}
                                <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.approve') }} <span class="txt-color-greenDark"><i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;{{ $approvecount }}</span></h5>
                            {{-- </a> --}}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- 1 st row -->
                <div class="row">
                <article class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                            <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ $thisMonth }} - {{ __('dashboard.this_month_summery') }}</h2>
                            </header>

                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                    <div id="donut-graph-thismonth" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                    </article>
                    <article class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <!-- new widget -->
                        <div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                        <!-- widget options:
                        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                        data-widget-colorbutton="false"
                        data-widget-editbutton="false"
                        data-widget-togglebutton="false"
                        data-widget-deletebutton="false"
                        data-widget-fullscreenbutton="false"
                        data-widget-custombutton="false"
                        data-widget-collapsed="true"
                        data-widget-sortable="false"

                        -->
                         <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                        <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                        <h2>{{ __('dashboard.received_vs_closed') }}</h2>

                        </header>

                        <!-- widget div-->
                        <div>

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                        <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                        <!-- this is what the user will see -->
                        <canvas id="lineChart" height="120"></canvas>

                        </div>
                        <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                        </div>
                        <!-- end widget -->
                    </article>
                    <article class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                            <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ $lastMonth }} - {{ __('dashboard.last_month_summery') }}</h2>
                            </header>

                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                    <div id="donut-graph" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                    </article>
                </div>
                <!-- end row -->
                <!-- 2 nd row -->
                <div class="row">
                <article class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <!-- new widget -->
                        <div class="jarviswidget" id="wid-id-7" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                            <!-- widget options:
                            usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                            data-widget-colorbutton="false"
                            data-widget-editbutton="false"
                            data-widget-togglebutton="false"
                            data-widget-deletebutton="false"
                            data-widget-fullscreenbutton="false"
                            data-widget-custombutton="false"
                            data-widget-collapsed="true"
                            data-widget-sortable="false"  -->
                             <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ $lastyear }} - {{ __('dashboard.last_year_category_wise_complain') }}</h2>
                            </header>
                             <!-- This area used as dropdown edit box -->


                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                   <div id="bar-graph-year" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->



                        </div>
                        <!-- end widget -->
                    </article>
                <article class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                             <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ $lastyear }} - {{ __('dashboard.last_year_summery') }}</h2>
                            </header>
                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                    <div id="donut-graph-year" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                    </article>
                    <article class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <!-- new widget -->
                        <div class="jarviswidget" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                             <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ __('dashboard.past_complaint_summery') }}</h2>
                            </header>

                            <!-- widget div-->
                            <div style="padding-top: 57px;">
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                    <div id="past-complaint-summery" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
                    </article>


                </div>
                <!-- end row -->
            </section>
            <!-- end widget grid -->
        </div>
    </div>
    {{-- <x-slot name="script"> --}}

        <!-- IMPORTANT: APP CONFIG -->
	<script src="{{ asset('public/back/js/app.config.js') }}"></script>

	<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
	<script src="{{ asset('public/back/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js') }}"></script>



	<!-- CUSTOM NOTIFICATION -->
	<script src="{{ asset('public/back/js/notification/SmartNotification.min.js') }}"></script>

	<!-- JARVIS WIDGETS -->
	<script src="{{ asset('public/back/js/smartwidgets/jarvis.widget.min.js') }}"></script>

	<!-- EASY PIE CHARTS -->
	<script src="{{ asset('public/back/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js') }}"></script>

	<!-- SPARKLINES -->
	<script src="{{ asset('public/back/js/plugin/sparkline/jquery.sparkline.min.js') }}"></script>

	<!-- JQUERY VALIDATE -->
	<script src="{{ asset('public/back/js/plugin/jquery-validate/jquery.validate.min.js') }}"></script>

	<!-- JQUERY MASKED INPUT -->
	<script src="{{ asset('public/back/js/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>

	<!-- JQUERY SELECT2 INPUT -->
	<script src="{{ asset('public/back/js/plugin/select2/select2.min.js') }}"></script>

	<!-- JQUERY UI + Bootstrap Slider -->
	<script src="{{ asset('public/back/js/plugin/bootstrap-slider/bootstrap-slider.min.js') }}"></script>

	<!-- browser msie issue fix -->
	<script src="{{ asset('public/back/js/plugin/msie-fix/jquery.mb.browser.min.js') }}"></script>

	<!-- FastClick: For mobile devices -->
	<script src="{{ asset('public/back/js/plugin/fastclick/fastclick.min.js') }}"></script>

	<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

	<!-- Demo purpose only -->
	<script src="{{ asset('public/back/js/demo.min.js') }}"></script>

	<!-- MAIN APP JS FILE -->
	<script src="{{ asset('public/back/js/app.min.js') }}"></script>

	<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
	<!-- Voice command : plugin -->
	<script src="{{ asset('public/back/js/speech/voicecommand.min.js') }}"></script>

	<!-- SmartChat UI : plugin -->
	<script src="{{ asset('public/back/js/smart-chat-ui/smart.chat.ui.min.js') }}"></script>
	<script src="{{ asset('public/back/js/smart-chat-ui/smart.chat.manager.min.js') }}"></script>

	<!-- PAGE RELATED PLUGIN(S) -->

	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
	<script src="{{ asset('public/back/js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
	<script src="{{ asset('public/back/js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
	<script src="{{ asset('public/back/js/plugin/flot/jquery.flot.time.min.js') }}"></script>
	<script src="{{ asset('public/back/js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>

	<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
	<script src="{{ asset('public/back/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
	<script src="{{ asset('public/back/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

	<!-- Full Calendar -->
	<script src="{{ asset('public/back/js/plugin/moment/moment.min.js') }}"></script>
	<script src="{{ asset('public/back/js/plugin/fullcalendar/fullcalendar.min.js') }}"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Morris Chart Dependencies -->
		{{-- <script src="{{ asset('public/js/plugin/morris/raphael.min.js') }}"></script>
		<script src="{{ asset('public/js/plugin/morris/morris.min.js') }}"></script>
        <script src="{{ asset('public/js/plugin/chartjs/chart.min.js') }}"></script> --}}
        <script src="https://lcms.tekgeeks.net/public/js/plugin/morris/raphael.min.js"></script>
        <script src="https://lcms.tekgeeks.net/public/js/plugin/morris/morris.min.js"></script>
        <script src="https://lcms.tekgeeks.net/public/js/plugin/chartjs/chart.min.js"></script>

		<script>
			$(document).ready(function() {

				// DO NOT REMOVE : GLOBAL FUNCTIONS!
				pageSetUp();

				/*
				 * PAGE RELATED SCRIPTS
				 */
				 // donut
				if ($('#donut-graph-year').length) {
					Morris.Donut({
						element : 'donut-graph-year',
						colors: [
						'#f2e9cc',
						'#e3a7c0',
						'#bad5f0',
						'#c2d5a8'
					  ],
					  data: [
							{label:"On going", value: {{ $lastyrongoingcomplaintcount }}},
							{label:"Action pending", value:{{ $lastyrpendingcomplaintcount }}},
							{label:"Temporary Closed", value:{{ $lastyrtempclosedcomplaintcount }}},
							{label:"Closed", value:{{ $lastyrclosedcomplaintcount }}}
						  ],
						formatter : function(x) {
							return x + "%"
						}
					});
				}

				// donut
				if ($('#donut-graph').length) {
					Morris.Donut({
						element : 'donut-graph',
						colors: [
						'#f2e9cc',
						'#e3a7c0',
						'#bad5f0',
						'#c2d5a8'
					  ],
					  data: [
							{label:"On going", value:{{ $lastmonongoingcomplaintcount }}},
							{label:"Action pending", value:{{ $lastmonpendingcomplaintcount }}},
							{label:"Temporary Closed", value:{{ $lastmontempclosedcomplaintcount }}},
							{label:"Closed", value:{{ $lastmonclosedcomplaintcount }}}
						  ],
						formatter : function(x) {
							return x + "%"
						}
					});
				}

                if ($('#donut-graph-thismonth').length) {
					Morris.Donut({
						element : 'donut-graph-thismonth',
						colors: [
						'#f2e9cc',
						'#e3a7c0',
						'#bad5f0',
						'#c2d5a8'
					  ],
					  data: [
							{label:"On going", value:{{ $thismonongoingcomplaintcount }}},
							{label:"Action pending", value:{{ $thismonpendingcomplaintcount }}},
							{label:"Temporary Closed", value:{{ $thismontempclosedcomplaintcount }}},
							{label:"Closed", value:{{ $thismontempclosedcomplaintcount }}}
						  ],
						formatter : function(x) {
							return x + "%"
						}
					});
				}

				if ($('#bar-graph').length) {

                    var datali = <?php echo json_encode($datalist) ?>;
                    // console.log(datali);

                    createChart(datali);
                    function createChart(data) {
                        // console.log(data);
                        let labels = data.map(obj => obj.label);

                        // Generte n keys
                        let ykeys = data[0].values.map((obj, i) => 'val' + i);
                        let values = data.map(function(obj) {
                            let map = {
                            label: obj.label
                            }
                            obj.values.forEach(function(obj, i) {
                            map[ykeys[i]] = obj;
                            });
                            return map;
                        });

                        let countLable = ['Count'];

                        Morris.Bar({
                            element : 'bar-graph',
                            data : values,
                            xkey : 'label',
                            ykeys : ykeys,
                            labels : countLable,
                            barColors : function(row, series, type) {
                                if (type === 'bar') {
                                    var red = Math.ceil(350 * row.y / this.ymax);
                                    //return 'rgba(' + red + ',0,0,0.2)';
                                    return '#57889c';
                                } else {
                                    return '#000';
                                }
                            }
                        });
                    }
                }

				if ($('#bar-graph-year').length) {

                    var datali = <?php echo json_encode($datalist2) ?>;
                    // console.log(datali);

                    createChart2(datali);
                        function createChart2(data) {
                            // console.log(data);
                            let labels2 = data.map(obj => obj.label);

                            // Generte n keys
                            let ykeys = data[0].values.map((obj, i) => 'val' + i);
                            let values2 = data.map(function(obj) {
                                let map = {
                                label: obj.label
                                }
                                obj.values.forEach(function(obj, i) {
                                map[ykeys[i]] = obj;
                                });
                                return map;
                        });

                        let countLable = ['Count'];

                        Morris.Bar({
                            element : 'bar-graph-year',
                            data : values2,
                            xkey : 'label',
                            ykeys : ykeys,
                            labels : countLable,
                            barColors : function(row, series, type) {
                                if (type === 'bar') {
                                    var red = Math.ceil(350 * row.y / this.ymax);
                                    //return 'rgba(' + red + ',0,0,0.2)';
                                    return '#57889c';
                                } else {
                                    return '#000';
                                }
                            }
                        });
                    }

				}
				if ($('#bar-graph-monthly').length) {

					Morris.Bar({
						element : 'bar-graph-monthly',
						data : [{
							x : 'January',
							y : {{ $jancomplaincount }}
						}, {
							x : 'February',
							y : {{ $febcomplaincount }}
						}, {
							x : 'March',
							y : {{ $marcomplaincount }}
						}, {
							x : 'April',
							y : {{ $aprcomplaincount }}
						}, {
							x : 'May',
							y : {{ $maycomplaincount }}
						}, {
							x : 'June',
							y : {{ $juncomplaincount }}
						}, {
							x : 'July',
							y : {{ $julcomplaincount }}
						}, {
							x : 'August',
							y : {{ $augcomplaincount }}
						}, {
							x : 'September',
							y : {{ $sepcomplaincount }}
						}, {
							x : 'October',
							y : {{ $octcomplaincount }}
						}, {
							x : 'November',
							y : {{ $novcomplaincount }}
						}, {
							x : 'December',
							y : {{ $deccomplaincount }}
						}],
						xkey : 'x',
						ykeys : ['y'],
						labels : ['Y'],
						barColors : function(row, series, type) {
							if (type === 'bar') {
								var red = Math.ceil(350 * row.y / this.ymax);
								//return 'rgb(' + red + ',0,0)';
								return '#b19a6b';
							} else {
								return '#000';
							}
						}
					});

				}
				if ($('#bar-graph-last-5-year').length) {

					Morris.Bar({
						element : 'bar-graph-last-5-year',
						data : [{
							x : {{ $lastyear }},
							y : {{ $lastyearcount }}
						}, {
							x : {{ $year5 }},
							y : {{ $yearcount5 }}
						}, {
							x : {{ $year4 }},
							y : {{ $yearcount4 }}
						}, {
							x : {{ $year3 }},
							y : {{ $yearcount3 }}
						}, {
							x : {{ $year2 }},
							y : {{ $yearcount2 }}
						}, {
							x : {{ $year1 }},
							y : {{ $yearcount1 }}
						}],
						xkey : 'x',
						ykeys : ['y'],
						labels : ['Y'],
						barColors : function(row, series, type) {
							if (type === 'bar') {
								var red = Math.ceil(350 * row.y / this.ymax);
								//return 'rgb(' + red + ',0,0)';
								return '#b586cd';
							} else {
								return '#000';
							}
						}
					});

				}

                if ($('#past-complaint-summery').length) {

                    Morris.Bar({
                        element : 'past-complaint-summery',
                        data : [{
                            x : '< 1 Month',
                            y : {{ $pastMonthComplaints }}
                        }, {
                            x : '1 - 3 Months',
                            y : {{ $lastThreeMonthComplaints }}
                        }, {
                            x : '3 - 6 Months',
                            y : {{ $lastThreeToSixMonthComplaints }}
                        }, {
                            x : '6 - 12 Months',
                            y : {{ $lastSixToTwelMonthComplaints }}
                        }, {
                            x : '> 1 Year',
                            y : {{ $pastYearComplaints }}
                        }],
                        xkey : 'x',
                        ykeys : ['y'],
                        labels : ['Y'],
                        barColors : function(row, series, type) {
                            if (type === 'bar') {
                                var red = Math.ceil(350 * row.y / this.ymax);
                                //return 'rgb(' + red + ',0,0)';
                                return '#b586cd';
                            } else {
                                return '#000';
                            }
                        }
                    });

                }

			});

		</script>

        <script>
            var yeartags = <?php echo json_encode($labels) ?>;
            var newcomcount = <?php echo json_encode($newcomplaints) ?>;
            var closedcomcount = <?php echo json_encode($closedcomplaints) ?>;
            // var yeartags = ["2020 Dec", "2021 Jan", "2021 Feb", "2021 Mar", "2021 Apr", "2021 May", "2021 Jun", "2021 Jul", "2021 Aug", "2021 Sep", "2021 Oct", "2021 Nov"];
            // var newcomcount = [600, 550, 300, 600, 400, 650, 525, 650, 400, 500, 550, 500];

            var randomScalingFactor = function() {
                return Math.round(Math.random() * 100);
                //return 0;
            };
            var randomColorFactor = function() {
                return Math.round(Math.random() * 255);
            };
            var randomColor = function(opacity) {
                return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
            };

            var LineConfig = {
                    type: 'line',
                    data: {
                            labels: yeartags,
                            datasets: [{
                                label: "New Complains",
                                data: newcomcount,

                            }, {
                                label: "Closed Complains",
                                data: closedcomcount,
                            }]
                        },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'label'
                        },
                        hover: {
                            mode: 'dataset'
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    show: true,
                                    labelString: 'Month'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    show: true,
                                    labelString: 'Value'
                                },
                                ticks: {
                                    suggestedMin: 0,
                                    suggestedMax: 100,
                                }
                            }]
                        }
                    }
                };

                $.each(LineConfig.data.datasets, function(i, dataset) {
                    dataset.borderColor = 'rgba(0,0,0,0.15)';
                    dataset.backgroundColor = randomColor(0.5);
                    dataset.pointBorderColor = 'rgba(0,0,0,0.15)';
                    dataset.pointBackgroundColor = randomColor(0.5);
                    dataset.pointBorderWidth = 1;
                });

                window.onload = function() {
                window.myLine = new Chart(document.getElementById("lineChart"), LineConfig);

            };
        </script>
