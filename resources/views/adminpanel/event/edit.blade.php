@section('title', 'Event')
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
                        {{ __('event.title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('calendar.complaint_ref_no') }} : {{ $complaint->external_ref_no }} </h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('calendar.internal_complaint_ref_no') }}</strong> : {{ $complaint->ref_no }}</h5>
                    <h5><strong>{{ __('calendar.external_complaint_ref_no') }}</strong> : {{ $complaint->external_ref_no }}</h5>
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
                <div id="content">
                    <!-- row -->

                    <div class="row">

                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <!-- new widget -->
                            <div class="jarviswidget jarviswidget-color-blueDark">
                                <header>
                                    <h2> {{ __('event.edit_event') }} </h2>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <div class="widget-body">
                                        <!-- content goes here -->

                                        <form action="{{ route('save-event') }}" enctype="multipart/form-data" method="post" id="event-form">
                                            @csrf
                                            @method('PUT')
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
                                                            <option {{$data->event_title == $eventtitle->title_name_ta || $data->event_title == $eventtitle->title_name_en || $data->event_title == $eventtitle->title_name_si ? 'selected' : ''}} value="@if($lang == "TA"){{ $eventtitle->title_name_ta }}@elseif($lang == "SI"){{ $eventtitle->title_name_si }}@else{{ $eventtitle->title_name_en }}@endif">@if($lang == "TA"){{ $eventtitle->title_name_ta }}@elseif($lang == "SI"){{ $eventtitle->title_name_si }}@else{{ $eventtitle->title_name_en }}@endif</option>
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
                                                        <option {{$data->status_id == $complaintstate->id  ? 'selected' : ''}} value="{{ $complaintstate->id }}">@if($lang == "TA"){{ $complaintstate->status_ta }}@elseif($lang == "SI"){{ $complaintstate->status_si }}@else{{ $complaintstate->status_en }}@endif</option>
                                                        @endforeach
                                                    </select> <i></i>
                                                    {{-- </label> --}}
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>{{ __('calendar.event_date') }}<span style="color: #FF0000;"> *</span></label>

                                                            <input type="text" id="event_date" name="event_date" value="{{ $data->event_date }}" autocomplete="off" class="datepicker form-control" dateformat='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date" required>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>{{ __('calendar.start_time') }}<span style="color: #FF0000;"> *</span></label>
                                                            <input type="time" class="form-control" name="start_time" id="start_time" value="{{ $data->start_time }}" autocomplete="off" placeholder="Time" required><br>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>{{ __('calendar.end_time') }}</label>
                                                            <input type="time" class="form-control" name="end_time" id="end_time" value="{{ $data->end_time }}" autocomplete="off" placeholder="Time"><br>

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

                                            </fieldset>
                                            <div class="form-actions">
                                                <div class="row">
                                                <input type="hidden" name="id" value="{{ $data->id }}>">
                                                <input type="hidden" name="complaint_id" value="{{ $data->complaint_id }}">
                                                <input type="hidden" name="officer_id" value="{{ $officer_id }}">
                                                <input type="hidden" name="lo_id" id="lo_id" value="{{ $complaint->lo_officer_id }}">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-default" type="submit" id="add-event">
                                                            {{ __('event.edit_event') }}
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

                    </div>

                    <!-- end row -->

                </div>
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
