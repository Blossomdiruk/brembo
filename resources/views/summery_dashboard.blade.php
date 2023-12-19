@section('title', 'Dashboard')

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
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
                            <a href="{{ 'action-pending-list' }}" >
                                <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.new') }} <span class="txt-color-blue" style="color: #57889c!important;"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;{{ $newcomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'action-pending-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.action_pending') }} <span class="txt-color-purple"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;{{ $pendingcomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'investigation-ongoing-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.ongoing') }}  <span class="txt-color-greenDark"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;{{ $ongoingcomplaintcount }}</span></h5>
                        </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'recovery-pending-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.recovery') }}  <span class="txt-color-greenDark"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;{{ $recoverycomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'appeal-pending-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.appeal') }}  <span class="txt-color-greenDark"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;{{ $appealcomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'legal-certificate-pending-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.legal') }}  <span class="txt-color-greenDark"><i class="fa fa-certificate" aria-hidden="true"></i>&nbsp;{{ $legalcomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'plaint-chargesheet-pending-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.plaint') }}  <span class="txt-color-greenDark"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;{{ $chargecomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'temporary-closed-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.temporary_closed') }} <span class="txt-color-greenDark"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;{{ $tempclosedcomplaintcount }}</span></h5>
                            </a>
                        </li>
                        {{-- <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> Transfer In <span class="txt-color-blue">$47,171</span></h5>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> Transfer Out <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i>&nbsp;25,000</span></h5>
                        </li> --}}
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                        <a href="{{ 'closed-list' }}" >
                            <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.closed') }} <span class="txt-color-greenDark"><i class="fa fa-times-circle-o" aria-hidden="true"></i>&nbsp;{{ $closedcomplaintcount }}</span></h5>
                            </a>
                        </li>
                        <li class="sparks-info" style="background-color: transparent; text-align: left; padding: 5px 10px !important; width: auto;">
                            @if($userrole == "ACL")
                            <a href="{{ 'pending-approval-list' }}" >
                                <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.approve') }} <span class="txt-color-greenDark"><i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;{{ $approvecount }}</span></h5>
                            </a>
                            @else
                            <a href="{{ 'sent-approval-list' }}" >
                                <h5 style="color: #555 !important; float: none; font-weight: 400;"> {{ __('dashboard.approve') }} <span class="txt-color-greenDark"><i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;{{ $approvecount }}</span></h5>
                            </a>
                            @endif
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
                    <article class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="jarviswidget" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                             <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ $lastyear }} - {{ __('dashboard.last_year_monthly_complain_summery') }}</h2>
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
                                    <div id="bar-graph-monthly" class="chart no-padding"></div>
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
                    <article class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <!-- new widget -->
                        <div class="jarviswidget" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                             <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ __('dashboard.past_complaint_summery') }}</h2>
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
                                    <div id="past-complaint-summery" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
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
                </div>
                <!-- end row -->
                <!-- 3 rd row -->
                <div class="row">
                    <article class="col-sm-12">
                        <!-- new widget -->
                        <div class="jarviswidget" id="wid-id-6" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                             <header style="color: #333 !important; border: 1px solid #C2C2C2; background: #fafafa; border-color: #C2C2C2!important;">
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>{{ $lastMonth }} - {{ __('dashboard.last_month_category_wise_complain') }}</h2>
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
                                    <div id="bar-graph" class="chart no-padding"></div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
                    </article>
                </div>
                <!-- 4 th row -->
                <div class="row">
                    <article class="col-sm-12">
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
                </div>
                <!-- end row -->
            </section>
            <!-- end widget grid -->
        </div>
    </div>
    <x-slot name="script">

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
    </x-slot>
</x-app-layout>
