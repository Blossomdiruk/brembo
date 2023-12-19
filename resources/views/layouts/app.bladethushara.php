<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

	<title>DoL | @yield('title')</title>
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
	<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/smartadmin-rtl.min.css') }}">

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

	<!-- Scripts -->
	<script src="{{ asset('public/js/app.js') }}" defer></script>
	<script src="{{ asset('public/back/js/jquery.min.js') }}"></script>
	<!-- BOOTSTRAP JS -->
	<script src="{{ asset('public/back/js/bootstrap/bootstrap.min.js') }}"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	@if(Session()->get('applocale')=='ta')
	<script src="{{ asset('public/back/js/i18n/ta.js') }}"></script>
	@endif
	@if(Session()->get('applocale')=='si')
	<script src="{{ asset('public/back/js/i18n/si.js') }}"></script>
	@endif
	<style>
		.parsley-errors-list li{
			list-style: none;
			color: red;
		}

		    .demo>span {
                display: none !important;
            }

            .widget-body.no-padding {
                margin: 12px 12px;
            }

            #sparks {
                /*margin-top: 0px;*/
                margin-bottom: 10px;
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

            #sparks li h5{
                color: #ffffff !important;
                float: none;
                font-weight: 900;
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
	</style>
	{{ $header }}
</head>

<body class="">
	@include('layouts.navigation')

	<!-- Page Content -->
	<main>
		{{ $slot }}
	</main>



	<!-- PAGE FOOTER -->
	<div class="page-footer">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<span class="txt-color-white">SmartAdmin 1.9.0 <span class="hidden-xs"> - Web Application Framework</span> © 2017-2019</span>
			</div>

			<div class="col-xs-6 col-sm-6 text-right hidden-xs">
				<div class="txt-color-white inline-block">
					<i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong>52 mins ago &nbsp;</strong> </i>
					<div class="btn-group dropup">
						<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
							<i class="fa fa-link"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu pull-right text-left">
							<li>
								<div class="padding-5">
									<p class="txt-color-darken font-sm no-margin">Download Progress</p>
									<div class="progress progress-micro no-margin">
										<div class="progress-bar progress-bar-success" style="width: 50%;"></div>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="padding-5">
									<p class="txt-color-darken font-sm no-margin">Server Load</p>
									<div class="progress progress-micro no-margin">
										<div class="progress-bar progress-bar-success" style="width: 20%;"></div>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="padding-5">
									<p class="txt-color-darken font-sm no-margin">Memory Load <span class="text-danger">*critical*</span></p>
									<div class="progress progress-micro no-margin">
										<div class="progress-bar progress-bar-danger" style="width: 70%;"></div>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="padding-5">
									<button class="btn btn-block btn-default">refresh</button>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE FOOTER -->


	<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
	<div id="shortcut">
		<ul>
			<li>
				<a href="inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
			</li>
			<li>
				<a href="calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
			</li>
			<li>
				<a href="gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
			</li>
			<li>
				<a href="invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
			</li>
			<li>
				<a href="gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
			</li>
			<li>
				<a href="profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
			</li>
		</ul>
	</div>
	<!-- END SHORTCUT AREA -->

	<!--================================================== -->

	<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
	<script data-pace-options='{ "restartOnRequestAfter": true }' src="{{ asset('public/back/js/plugin/pace/pace.min.js') }}"></script>

	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->

	<!-- <script>
		if (!window.jQuery) {
			document.write('<script src="{{ asset('public/back/js/libs/jquery-3.2.1.min.js') }}"></script>');
		}
	</script>

	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>
		if (!window.jQuery.ui) {
			document.write('<script src="{{ asset('public/back/js/libs/jquery-ui.min.js') }}"></script>');
		}
	</script> -->

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

	<script>
		$(document).ready(function() {

			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			pageSetUp();

			/*
			 * PAGE RELATED SCRIPTS
			 */

			$(".js-status-update a").click(function() {
				var selText = $(this).text();
				var $this = $(this);
				$this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
				$this.parents('.dropdown-menu').find('li').removeClass('active');
				$this.parent().addClass('active');
			});

			/*
			 * TODO: add a way to add more todo's to list
			 */

			// initialize sortable
			$(function() {
				$("#sortable1, #sortable2").sortable({
					handle: '.handle',
					connectWith: ".todo",
					update: countTasks
				}).disableSelection();
			});

			// check and uncheck
			$('.todo .checkbox > input[type="checkbox"]').click(function() {
				var $this = $(this).parent().parent().parent();

				if ($(this).prop('checked')) {
					$this.addClass("complete");

					// remove this if you want to undo a check list once checked
					//$(this).attr("disabled", true);
					$(this).parent().hide();

					// once clicked - add class, copy to memory then remove and add to sortable3
					$this.slideUp(500, function() {
						$this.clone().prependTo("#sortable3").effect("highlight", {}, 800);
						$this.remove();
						countTasks();
					});
				} else {
					// insert undo code here...
				}

			})
			// count tasks
			function countTasks() {

				$('.todo-group-title').each(function() {
					var $this = $(this);
					$this.find(".num-of-tasks").text($this.next().find("li").size());
				});

			}

			/*
			 * RUN PAGE GRAPHS
			 */

			/* TAB 1: UPDATING CHART */
			// For the demo we use generated data, but normally it would be coming from the server

			var data = [],
				totalPoints = 200,
				$UpdatingChartColors = $("#updating-chart").css('color');

			function getRandomData() {
				if (data.length > 0)
					data = data.slice(1);

				// do a random walk
				while (data.length < totalPoints) {
					var prev = data.length > 0 ? data[data.length - 1] : 50;
					var y = prev + Math.random() * 10 - 5;
					if (y < 0)
						y = 0;
					if (y > 100)
						y = 100;
					data.push(y);
				}

				// zip the generated y values with the x values
				var res = [];
				for (var i = 0; i < data.length; ++i)
					res.push([i, data[i]])
				return res;
			}

			// setup control widget
			var updateInterval = 1500;
			$("#updating-chart").val(updateInterval).change(function() {

				var v = $(this).val();
				if (v && !isNaN(+v)) {
					updateInterval = +v;
					$(this).val("" + updateInterval);
				}

			});

			// setup plot
			var options = {
				yaxis: {
					min: 0,
					max: 100
				},
				xaxis: {
					min: 0,
					max: 100
				},
				colors: [$UpdatingChartColors],
				series: {
					lines: {
						lineWidth: 1,
						fill: true,
						fillColor: {
							colors: [{
								opacity: 0.4
							}, {
								opacity: 0
							}]
						},
						steps: false

					}
				}
			};

			var plot = $.plot($("#updating-chart"), [getRandomData()], options);

			/* live switch */
			$('input[type="checkbox"]#start_interval').click(function() {
				if ($(this).prop('checked')) {
					$on = true;
					updateInterval = 1500;
					update();
				} else {
					clearInterval(updateInterval);
					$on = false;
				}
			});

			function update() {
				if ($on == true) {
					plot.setData([getRandomData()]);
					plot.draw();
					setTimeout(update, updateInterval);

				} else {
					clearInterval(updateInterval)
				}

			}

			var $on = false;

			/*end updating chart*/

			/* TAB 2: Social Network  */

			$(function() {
				// jQuery Flot Chart
				var twitter = [
						[1, 27],
						[2, 34],
						[3, 51],
						[4, 48],
						[5, 55],
						[6, 65],
						[7, 61],
						[8, 70],
						[9, 65],
						[10, 75],
						[11, 57],
						[12, 59],
						[13, 62]
					],
					facebook = [
						[1, 25],
						[2, 31],
						[3, 45],
						[4, 37],
						[5, 38],
						[6, 40],
						[7, 47],
						[8, 55],
						[9, 43],
						[10, 50],
						[11, 47],
						[12, 39],
						[13, 47]
					],
					data = [{
						label: "Twitter",
						data: twitter,
						lines: {
							show: true,
							lineWidth: 1,
							fill: true,
							fillColor: {
								colors: [{
									opacity: 0.1
								}, {
									opacity: 0.13
								}]
							}
						},
						points: {
							show: true
						}
					}, {
						label: "Facebook",
						data: facebook,
						lines: {
							show: true,
							lineWidth: 1,
							fill: true,
							fillColor: {
								colors: [{
									opacity: 0.1
								}, {
									opacity: 0.13
								}]
							}
						},
						points: {
							show: true
						}
					}];

				var options = {
					grid: {
						hoverable: true
					},
					colors: ["#568A89", "#3276B1"],
					tooltip: true,
					tooltipOpts: {
						//content : "Value <b>$x</b> Value <span>$y</span>",
						defaultTheme: false
					},
					xaxis: {
						ticks: [
							[1, "JAN"],
							[2, "FEB"],
							[3, "MAR"],
							[4, "APR"],
							[5, "MAY"],
							[6, "JUN"],
							[7, "JUL"],
							[8, "AUG"],
							[9, "SEP"],
							[10, "OCT"],
							[11, "NOV"],
							[12, "DEC"],
							[13, "JAN+1"]
						]
					},
					yaxes: {

					}
				};

				var plot3 = $.plot($("#statsChart"), data, options);
			});

			// END TAB 2

			// TAB THREE GRAPH //
			/* TAB 3: Revenew  */

			$(function() {

				var trgt = [
						[1354586000000, 153],
						[1364587000000, 658],
						[1374588000000, 198],
						[1384589000000, 663],
						[1394590000000, 801],
						[1404591000000, 1080],
						[1414592000000, 353],
						[1424593000000, 749],
						[1434594000000, 523],
						[1444595000000, 258],
						[1454596000000, 688],
						[1464597000000, 364]
					],
					prft = [
						[1354586000000, 53],
						[1364587000000, 65],
						[1374588000000, 98],
						[1384589000000, 83],
						[1394590000000, 980],
						[1404591000000, 808],
						[1414592000000, 720],
						[1424593000000, 674],
						[1434594000000, 23],
						[1444595000000, 79],
						[1454596000000, 88],
						[1464597000000, 36]
					],
					sgnups = [
						[1354586000000, 647],
						[1364587000000, 435],
						[1374588000000, 784],
						[1384589000000, 346],
						[1394590000000, 487],
						[1404591000000, 463],
						[1414592000000, 479],
						[1424593000000, 236],
						[1434594000000, 843],
						[1444595000000, 657],
						[1454596000000, 241],
						[1464597000000, 341]
					],
					toggles = $("#rev-toggles"),
					target = $("#flotcontainer");

				var data = [{
					label: "Target Profit",
					data: trgt,
					bars: {
						show: true,
						align: "center",
						barWidth: 30 * 30 * 60 * 1000 * 80
					}
				}, {
					label: "Actual Profit",
					data: prft,
					color: '#3276B1',
					lines: {
						show: true,
						lineWidth: 3
					},
					points: {
						show: true
					}
				}, {
					label: "Actual Signups",
					data: sgnups,
					color: '#71843F',
					lines: {
						show: true,
						lineWidth: 1
					},
					points: {
						show: true
					}
				}]

				var options = {
					grid: {
						hoverable: true
					},
					tooltip: true,
					tooltipOpts: {
						//content: '%x - %y',
						//dateFormat: '%b %y',
						defaultTheme: false
					},
					xaxis: {
						mode: "time"
					},
					yaxes: {
						tickFormatter: function(val, axis) {
							return "$" + val;
						},
						max: 1200
					}

				};

				plot2 = null;

				function plotNow() {
					var d = [];
					toggles.find(':checkbox').each(function() {
						if ($(this).is(':checked')) {
							d.push(data[$(this).attr("name").substr(4, 1)]);
						}
					});
					if (d.length > 0) {
						if (plot2) {
							plot2.setData(d);
							plot2.draw();
						} else {
							plot2 = $.plot(target, d, options);
						}
					}

				};

				toggles.find(':checkbox').on('change', function() {
					plotNow();
				});
				plotNow()

			});

			/*
			 * VECTOR MAP
			 */

			data_array = {
				"US": 4977,
				"AU": 4873,
				"IN": 3671,
				"BR": 2476,
				"TR": 1476,
				"CN": 146,
				"CA": 134,
				"BD": 100
			};

			$('#vector-map').vectorMap({
				map: 'world_mill_en',
				backgroundColor: '#fff',
				regionStyle: {
					initial: {
						fill: '#c4c4c4'
					},
					hover: {
						"fill-opacity": 1
					}
				},
				series: {
					regions: [{
						values: data_array,
						scale: ['#85a8b6', '#4d7686'],
						normalizeFunction: 'polynomial'
					}]
				},
				onRegionLabelShow: function(e, el, code) {
					if (typeof data_array[code] == 'undefined') {
						e.preventDefault();
					} else {
						var countrylbl = data_array[code];
						el.html(el.html() + ': ' + countrylbl + ' visits');
					}
				}
			});

			/*
			 * FULL CALENDAR JS
			 */

			if ($("#calendar").length) {
				var date = new Date();
				var d = date.getDate();
				var m = date.getMonth();
				var y = date.getFullYear();

				var calendar = $('#calendar').fullCalendar({

					editable: true,
					draggable: true,
					selectable: false,
					selectHelper: true,
					unselectAuto: false,
					disableResizing: false,
					height: "auto",

					header: {
						left: 'title', //,today
						center: 'prev, next, today',
						right: 'month, agendaWeek, agenDay' //month, agendaDay,
					},

					select: function(start, end, allDay) {
						var title = prompt('Event Title:');
						if (title) {
							calendar.fullCalendar('renderEvent', {
									title: title,
									start: start,
									end: end,
									allDay: allDay
								}, true // make the event "stick"
							);
						}
						calendar.fullCalendar('unselect');
					},

					events: [{
						title: 'All Day Event',
						start: new Date(y, m, 1),
						description: 'long description',
						className: ["event", "bg-color-greenLight"],
						icon: 'fa-check'
					}, {
						title: 'Long Event',
						start: new Date(y, m, d - 5),
						end: new Date(y, m, d - 2),
						className: ["event", "bg-color-red"],
						icon: 'fa-lock'
					}, {
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d - 3, 16, 0),
						allDay: false,
						className: ["event", "bg-color-blue"],
						icon: 'fa-clock-o'
					}, {
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d + 4, 16, 0),
						allDay: false,
						className: ["event", "bg-color-blue"],
						icon: 'fa-clock-o'
					}, {
						title: 'Meeting',
						start: new Date(y, m, d, 10, 30),
						allDay: false,
						className: ["event", "bg-color-darken"]
					}, {
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false,
						className: ["event", "bg-color-darken"]
					}, {
						title: 'Birthday Party',
						start: new Date(y, m, d + 1, 19, 0),
						end: new Date(y, m, d + 1, 22, 30),
						allDay: false,
						className: ["event", "bg-color-darken"]
					}, {
						title: 'Smartadmin Open Day',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						className: ["event", "bg-color-darken"]
					}],


					eventRender: function(event, element, icon) {
						if (!event.description == "") {
							element.find('.fc-title').append("<br/><span class='ultra-light'>" + event.description + "</span>");
						}
						if (!event.icon == "") {
							element.find('.fc-title').append("<i class='air air-top-right fa " + event.icon + " '></i>");
						}
					}
				});

			};

			/* hide default buttons */
			$('.fc-toolbar .fc-right, .fc-toolbar .fc-center').hide();

			// calendar prev
			$('#calendar-buttons #btn-prev').click(function() {
				$('.fc-prev-button').click();
				return false;
			});

			// calendar next
			$('#calendar-buttons #btn-next').click(function() {
				$('.fc-next-button').click();
				return false;
			});

			// calendar today
			$('#calendar-buttons #btn-today').click(function() {
				$('.fc-button-today').click();
				return false;
			});

			// calendar month
			$('#mt').click(function() {
				$('#calendar').fullCalendar('changeView', 'month');
			});

			// calendar agenda week
			$('#ag').click(function() {
				$('#calendar').fullCalendar('changeView', 'agendaWeek');
			});

			// calendar agenda day
			$('#td').click(function() {
				$('#calendar').fullCalendar('changeView', 'agendaDay');
			});

			/*
			 * CHAT
			 */

			$.filter_input = $('#filter-chat-list');
			$.chat_users_container = $('#chat-container > .chat-list-body')
			$.chat_users = $('#chat-users')
			$.chat_list_btn = $('#chat-container > .chat-list-open-close');
			$.chat_body = $('#chat-body');

			/*
			 * LIST FILTER (CHAT)
			 */

			// custom css expression for a case-insensitive contains()
			jQuery.expr[':'].Contains = function(a, i, m) {
				return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
			};

			function listFilter(list) { // header is any element, list is an unordered list
				// create and add the filter form to the header

				$.filter_input.change(function() {
					var filter = $(this).val();
					if (filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$.chat_users.find("a:not(:Contains(" + filter + "))").parent().slideUp();
						$.chat_users.find("a:Contains(" + filter + ")").parent().slideDown();
					} else {
						$.chat_users.find("li").slideDown();
					}
					return false;
				}).keyup(function() {
					// fire the above change event after every letter
					$(this).change();

				});

			}

			// on dom ready
			listFilter($.chat_users);

			// open chat list
			$.chat_list_btn.click(function() {
				$(this).parent('#chat-container').toggleClass('open');
			})

			$.chat_body.animate({
				scrollTop: $.chat_body[0].scrollHeight
			}, 500);

		});
	</script>

	<!-- Your GOOGLE ANALYTICS CODE Below -->
	<script>
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
	</script>

	{{ $script }}
</body>


</html>
