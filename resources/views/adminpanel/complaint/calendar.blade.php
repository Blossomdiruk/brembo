@section('title', 'Calendar')
@php
$officeName = 'office_name_en';
$lang = "EN";
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
$lang = "TA";
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
$lang = "SI";
@endphp
@endif

<?php
foreach ($events_cal as $key => $val) {
    // echo $val->event_title;

}
?>
<x-app-layout>
    <x-slot name="header">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        {{ __('calendar.form_title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('calendar.complaint_ref_no') }} : {{ $data->external_ref_no }} </h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('calendar.internal_complaint_ref_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('calendar.external_complaint_ref_no') }}</strong> : {{ $data->external_ref_no }}</h5>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            @if($data->lo_officer_id == "")
            <h5><strong>{{ __('calendar.lo_message') }}</strong>
                @else
                <div id="content">
                    <!-- row -->

                    <div class="row">

                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <!-- new widget -->
                            <div class="jarviswidget jarviswidget-color-blueDark">
                                <header>
                                    <h2> {{ __('calendar.add_event') }} </h2>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <div class="widget-body">
                                        <!-- content goes here -->

                                        <form action="{{ route('new-event') }}" enctype="multipart/form-data" method="post" id="event-form">
                                            @csrf
                                            <fieldset>

                                                <!-- <div class="form-group">
                                                <label>{{ __('calendar.event_icon') }}</label>
                                                <div class="btn-group btn-group-sm btn-group-justified" data-toggle="buttons">
                                                    <label class="btn btn-default active">
                                                        <input type="radio" name="event_icon" id="icon-1" value="fa-info" checked>
                                                        <i class="fa fa-info text-muted"></i> </label>
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="event_icon" id="icon-2" value="fa-warning">
                                                        <i class="fa fa-warning text-muted"></i> </label>
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="event_icon" id="icon-3" value="fa-check">
                                                        <i class="fa fa-check text-muted"></i> </label>
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="event_icon" id="icon-4" value="fa-user">
                                                        <i class="fa fa-user text-muted"></i> </label>
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="event_icon" id="icon-5" value="fa-lock">
                                                        <i class="fa fa-lock text-muted"></i> </label>
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="event_icon" id="icon-6" value="fa-clock-o">
                                                        <i class="fa fa-clock-o text-muted"></i> </label>
                                                </div>
                                            </div> -->

                                                <div class="form-group">
                                                    <label> {{ __('calendar.lo_name') }} - {{ $officerlo->name }}<span style="color: #FF0000;"> *</span></label>
                                                    <input type="hidden" name="lo_id" id="lo_id" value="{{ $data->lo_officer_id }}">
                                                </div>

                                                <div class="form-group">
                                                    <label>{{ __('calendar.event_title') }}<span style="color: #FF0000;"> *</span></label>
                                                     <select class="form-control" id="event_title" name="event_title" required>
                                                        <option value="">Select Inquiry</option>
                                                        @foreach($eventtitles as $eventtitle)
                                                            <option value="@if($lang == "TA"){{ $eventtitle->title_name_ta }}@elseif($lang == "SI"){{ $eventtitle->title_name_si }}@else{{ $eventtitle->title_name_en }}@endif">@if($lang == "TA"){{ $eventtitle->title_name_ta }}@elseif($lang == "SI"){{ $eventtitle->title_name_si }}@else{{ $eventtitle->title_name_en }}@endif</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                    {{-- <input class="form-control" id="event_title" name="event_title" maxlength="40" type="text" autocomplete="off" placeholder="Event Title" required> --}}
                                                </div>

                                                <div class="form-group">
                                                    <label>{{ __('calendar.select_status') }}<span style="color: #FF0000;"> *</span></label>
                                                    {{-- <label class="select"> --}}
                                                    <select class="form-control" id="status_id" name="status_id" required>
                                                        <option value="">Select Status </option>
                                                        @foreach ($complaintstatus as $complaintstate)
                                                        <option {{ $last_complaint_sataus == $complaintstate->id ? "selected" : "" }} value="{{ $complaintstate->id }}">@if($lang == "TA"){{ $complaintstate->status_ta }}@elseif($lang == "SI"){{ $complaintstate->status_si }}@else{{ $complaintstate->status_en }}@endif</option>
                                                        @endforeach
                                                    </select> <i></i>
                                                    {{-- </label> --}}
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>{{ __('calendar.event_date') }}<span style="color: #FF0000;"> *</span></label>

                                                            <input type="text" id="event_date" name="event_date" value="" autocomplete="off" class="datepicker form-control" dateformat='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date" required>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>{{ __('calendar.start_time') }}<span style="color: #FF0000;"> *</span></label>
                                                            <input type="time" class="form-control" name="start_time" id="start_time" autocomplete="off" placeholder="Time" required><br>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>{{ __('calendar.end_time') }}</label>
                                                            <input type="time" class="form-control" name="end_time" id="end_time" autocomplete="off" placeholder="Time"><br>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group">
                                                <label>{{ __('calendar.event_people') }}</label>
                                                <input type="text" class="form-control"  id="event_people" name="event_people" value="{{ $data->complainant_email }}" placeholder="Event People">
                                            </div> -->
                                                <!-- <div class="form-group">
                                                <label>{{ __('calendar.event_desc') }}</label>
                                                <textarea class="form-control" placeholder="Please be brief" rows="3" maxlength="40" id="description"></textarea>
                                                <p class="note">Maxlength is set to 40 characters</p>
                                            </div> -->

                                                <div class="form-group">
                                                    <section class="col col-12">
                                                        <p id="duplicatecheck-msg" style="color: red; display:none;">Another event is created for selected time range. </p>
                                                    </section>
                                                </div>

                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}">
                                                <input type="hidden" name="officer_id" value="{{ $officer_id }}">

                                            </fieldset>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-default" type="submit" id="add-event">
                                                            {{ __('calendar.add_event_btn') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- end content -->
                                    </div>

                                </div>
                                <!-- end widget div -->
                            </div>
                            <!-- end widget -->
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-8">

                            <!-- new widget -->
                            <div class="jarviswidget jarviswidget-color-blueDark">

                                <header>
                                    <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                                    <h2> {{ __('calendar.my_event') }} </h2>
                                    <div class="widget-toolbar">
                                        <!-- add: non-hidden - to disable auto hide -->
                                        <div class="btn-group">
                                            <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                                                Showing <i class="fa fa-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu js-status-update pull-right">
                                                <li>
                                                    <a href="javascript:void(0);" id="mt">Month</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" id="ag">Agenda</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" id="td">Today</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <div class="widget-body no-padding">
                                        <!-- content goes here -->
                                        <div class="widget-body-toolbar">

                                            <div id="calendar-buttons">

                                                <div class="btn-group">
                                                    <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="calendar"></div>

                                        <!-- end content -->
                                    </div>

                                </div>
                                <!-- end widget div -->
                            </div>
                            <!-- end widget -->

                        </div>

                        <footer>
                            <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                            <br>
                            <br>
                        </footer>

                    </div>

                    <!-- end row -->

                </div>
                @endif
                <!-- end widget -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentDate = new Date();
            $('#event_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                autoclose: true,
                // endDate: "currentDate",
                // maxDate: currentDate
            }).on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $('#event_date').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });
    </script>

    <script type="text/javascript">
        $('#event_date').change(function() {

            var eventDate = $('#event_date').val();
            var startTime = $('#start_time').val();
            var endTime = $('#end_time').val();
            var loID = $('#lo_id').val();

            // console.log(eventDate);
            // console.log(startTime);
            // console.log(endTime);

            $("#duplicatecheck-msg").hide();

            if (eventDate) {

                $.ajax({
                    type: "POST",
                    url: "{{ route('checkEventsDuplicate') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        eventDate: eventDate,
                        loID: loID,
                        startTime: startTime,
                        endTime: endTime
                    },
                    success: function(data) {
                        if (data != "") {

                            console.log(data);

                            $("#duplicatecheck-msg").show();

                        } else {

                            $("#duplicatecheck-msg").hide();


                        }
                    }
                });
            } else {

                $("#district_id").empty();
            }
        });
    </script>

    <x-slot name="script">
        <script>
            $(function() {
                //window.ParsleyValidator.setLocale('ta');
                $('#event-form').parsley();
            });

            // DO NOT REMOVE : GLOBAL FUNCTIONS!

            $(document).ready(function() {

                pageSetUp();
                var districtID = '0';


                "use strict";

                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();

                var hdr = {
                    left: 'title',
                    center: 'month,agendaWeek,agendaDay',
                    right: 'prev,today,next'
                };

                var initDrag = function(e) {
                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end

                    var eventObject = {
                        title: $.trim(e.children().text()), // use the element's text as the event title
                        description: $.trim(e.children('span').attr('data-description')),
                        icon: $.trim(e.children('span').attr('data-icon')),
                        className: $.trim(e.children('span').attr('class')) // use the element's children as the event class
                    };
                    // store the Event Object in the DOM element so we can get to it later
                    e.data('eventObject', eventObject);

                    // make the event draggable using jQuery UI
                    e.draggable({
                        zIndex: 999,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0 //  original position after the drag
                    });
                };

                var addEvent = function(title, priority, description, icon) {
                    title = title.length === 0 ? "Untitled Event" : title;
                    description = description.length === 0 ? "No Description" : description;
                    icon = icon.length === 0 ? " " : icon;
                    priority = priority.length === 0 ? "label label-default" : priority;

                    var html = $('<li><span class="' + priority + '" data-description="' + description + '" data-icon="' +
                        icon + '">' + title + '</span></li>').prependTo('ul#external-events').hide().fadeIn();

                    $("#event-container").effect("highlight", 800);

                    initDrag(html);
                };

                /* initialize the external events
                -----------------------------------------------------------------*/

                $('#external-events > li').each(function() {
                    initDrag($(this));
                });

                $('#add-event').click(function() {
                    var title = $('#title').val(),
                        priority = $('input:radio[name=priority]:checked').val(),
                        description = $('#description').val(),
                        icon = $('input:radio[name=event_icon]:checked').val();

                    addEvent(title, priority, description, icon);
                });

                /* initialize the calendar
                -----------------------------------------------------------------*/

                $('#calendar').fullCalendar({

                    header: hdr,
                    editable: true,
                    droppable: true, // this allows things to be dropped onto the calendar !!!

                    drop: function(date, allDay) { // this function is called when something is dropped

                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');

                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                        // is the "remove after drop" checkbox checked?
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }

                    },

                    select: function(start, end, allDay) {
                        var title = prompt('Event Title:');
                        if (title) {
                            calendar.fullCalendar('renderEvent', {
                                    title: event_title,
                                    start: event_date,
                                    event_icon: event_icon,
                                    allDay: allDay
                                }, true // make the event "stick"
                            );
                        }
                        calendar.fullCalendar('unselect');
                    },

                    events: [
                        <?php
                        //for($i=1;$i<5;$i++){
                        foreach ($events_cal as $key => $val) {
                            $start_month = null;
                            $st_date = explode('-', $val->event_date);
                            $start_year = (int)$st_date[0];
                            $start_month = (int)$st_date[1] - 1;
                            $start_date = (int)$st_date[2];

                            $st_time = explode(':', $val->start_time);
                            $start_hour = (int)$st_time[0];
                            $start_min = (int)$st_time[1];

                            $ref_no = explode('/', $val->ref_no);
                            $in_year = $ref_no[4];
                            $in_no = $ref_no[5];

                            $short_ref_no = $in_year . '/' . $in_no;

                            //$start_date = $start_year . ',' . $start_month . ',' . $start_date;
                            //$start_month = 03;
                        ?> {
                                title: '<?php echo $val->event_title; ?>',
                                start: new Date(<?php echo $start_year ?>, <?php echo $start_month ?>, <?php echo $start_date ?>, <?php echo $start_hour ?>, <?php echo $start_min ?>),
                                description: '<?php echo $short_ref_no; ?>',
                                className: ["event", "<?php echo $val->event_color; ?>"],
                                icon: '<?php echo $val->event_icon; ?>'
                            },
                        <?php
                        }
                        ?>
                    ],

                    eventRender: function(event, element, icon) {
                        if (!event.description == "") {
                            element.find('.fc-title').append("<br/><span class='ultra-light'>" + event.description +
                                "</span>");
                        }
                        if (!event.icon == "") {
                            element.find('.fc-title').append("<i class='air air-top-right fa " + event.icon +
                                " '></i>");
                        }
                    },

                    windowResize: function(event, ui) {
                        $('#calendar').fullCalendar('render');
                    }
                });

                /* hide default buttons */
                $('.fc-right, .fc-center').hide();


                $('#calendar-buttons #btn-prev').click(function() {
                    $('.fc-prev-button').click();
                    return false;
                });

                $('#calendar-buttons #btn-next').click(function() {
                    $('.fc-next-button').click();
                    return false;
                });

                $('#calendar-buttons #btn-today').click(function() {
                    $('.fc-today-button').click();
                    return false;
                });

                $('#mt').click(function() {
                    $('#calendar').fullCalendar('changeView', 'month');
                });

                $('#ag').click(function() {
                    $('#calendar').fullCalendar('changeView', 'agendaWeek');
                });

                $('#td').click(function() {
                    $('#calendar').fullCalendar('changeView', 'agendaDay');
                });

            })
        </script>

        <script>
            $(document).ready(function() {

                $("#event-form").submit(function(e) {

                    $("#add-event").attr("disabled", true);

                    return true;

                });
            });
        </script>
    </x-slot>
</x-app-layout>
