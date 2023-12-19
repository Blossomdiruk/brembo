@section('title', 'Action')
@php
$officeName = 'office_name_en';
$forwardType = 'type_name';
$complaintcatName = 'category_name_en';
$updatestatus = 'status_en';
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
$forwardType = 'type_name_tam';
$complaintcatName = 'category_name_ta';
$updatestatus = 'status_ta';
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
$forwardType = 'type_name_sin';
$complaintcatName = 'category_name_si';
$updatestatus = 'status_si';
@endphp
@endif




<x-app-layout>
    <x-slot name="header">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
        <!-- <link rel="stylesheet" href="{{ asset('public/back/css/datepicker/bootstrap-datepicker3.min.css') }}" /> -->

        <style>
            .select2-selection__rendered {
                padding-left: 5px !important;
            }
        </style>

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
                        {{ __('action.form_title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('action.complaint_ref_no') }} : {{ $data->ref_no }} </h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('complaintstatus.internal_complaint_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('complaintstatus.external_complaint_no') }}</strong> : {{ $data->external_ref_no }}</h5>
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
            <div class="jarviswidget" id="user_register" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
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
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ __('action.sub_heading') }}</h2>


                </header>


                <!-- widget div-->
                <div>


                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->
                    <!---------------------------------------------Tab widget--------------------------------------------------------------->
                    <div class="widget-body padding-10">
                        <!--<hr class="simple">-->
                        <ul id="myTab1" class="nav nav-tabs bordered">

                            <li id="s4D" class=@if($complainhistory->status != "Approved_temp_close" && $complainhistory->status != "Approved_close"){{ "active" }}@endif>
                                <a href="#s4" data-toggle="tab" style=@if($complainhistory->status == "Approved_temp_close"){{ "display:none;" }}@elseif($complainhistory->status == "Approved_close"){{ "display:none;" }}@endif>{{ __('action.update_status') }}</a>
                            </li>

                            <li id="s6F">
                                <a href="#s6" data-toggle="tab" style=@if($complainhistory->status == "Request_recovery" || $complainhistory->status == "Approved_temp_close" || $complainhistory->status == "Approved_close" || $data->complaint_status == "Request_appeal"){{ "display:none;" }}@else{{ "" }}@endif>{{ __('action.assign_lo') }}</a>
                            </li>

                            <li id="s1A">
                                <a href="#s1" data-toggle="tab" style=@if($complainhistory->status == "Approved_temp_close"){{ "display:none;" }}@elseif($complainhistory->status == "Approved_close"){{ "display:none;" }}@elseif($data->complaint_status == "Request_appeal"){{ "display:none;" }}@endif>{{ __('action.forward') }}</a>

                            <!-- <li class="active" id="s1A">
                                <a href="#s1" data-toggle="tab">{{ __('action.forward') }}</a> -->

                            </li>

                            <li id="s7G">
                                <a href="#s7" data-toggle="tab" style=@if($complainhistory->status == "Approved_temp_close"){{ "display:none;" }}@elseif($complainhistory->status == "Approved_close"){{ "display:none;" }}@elseif($data->complaint_status == "Request_appeal"){{ "display:none;" }}@endif>{{ __('action.tab_request') }}</a>
                            </li>
                            @if ($complaintcat->count() > 1)
                            <li id="s5E">

                                <a href="#s5" data-toggle="tab" style=@if($complainhistory->status == "Approved_temp_close" || $complainhistory->status == "Approved_close" || $complainhistory->status == "Request_recovery" || $data->complaint_status == "Request_appeal"){{ "display:none;" }}@else{{ "" }}@endif>{{ __('action.copy') }}</a>
                            </li>
                            @endif

                            <li id="s2B">
                                <a href="#s2" data-toggle="tab">{{ __('action.temporary_close') }}</a>
                            </li>
                            <li id="s3C" >
                                <a href="#s3" data-toggle="tab">{{ __('action.close_menu') }}</a>
                            </li>
                        </ul>
                        <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">
                            <!------------------------------- Forward ---------------------------------------------------->
                            <div id="s1" class="tab-pane fade in">

                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('forward-actionpending') }}" enctype="multipart/form-data" method="post" id="forward-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_to') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select name="labour_office_id" id="labour_office_id" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($labourOffice as $lOffice)
                                                            <option value="{{ $lOffice->id }}">{{ $lOffice->$officeName }}--{{ $lOffice->office_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                    <span style="display:none;" id="error_forward_to" class="text-danger">This value is required.</span>
                                                </section>
                                                {{-- <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                        <select name="forward_type_id" id="forward_type_id" class="select2" required>
                                                            <option value=""></option>
                                                            @foreach ($forwardTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->$forwardType }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    <span style="display:none;" id="error_forward_type" class="text-danger">This value is required.</span>
                                                </section> --}}

                                                <input type="hidden" name="forward_type_id" id="forward_type_id" value="2" >
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option" id="remark_option" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                                <button type="submit" id="forwardbtn" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>

                            <!------------------------------- Request ---------------------------------------------------->
                            <div id="s7" class="tab-pane fade in">

                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('forward-request') }}" enctype="multipart/form-data" method="post" id="request-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                {{-- <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_to') }}<span style="color: #FF0000;"> *</span> </label>
                                                        <select name="labour_office_id" id="labour_office_id" class="select2" required>
                                                            <option value=""></option>
                                                            @foreach ($labourOffice as $lOffice)
                                                            <option value="{{ $lOffice->id }}">{{ $lOffice->$officeName }}--{{ $lOffice->office_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    <span style="display:none;" id="error_forward_to" class="text-danger">This value is required.</span>
                                                </section> --}}

                                                <input type="hidden" id="labour_office_id" class="labour_office_id" name="labour_office_id" value="{{ $data->current_office_id }}" >

                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_request_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                        <select name="forward_type_id" id="forward_type_id" class="select2 forward_type_id">
                                                            <option value=""></option>
                                                            @foreach ($forwardTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->$forwardType }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    <span style="display:none;" id="error_forward_type" class="text-danger">This value is required.</span>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option1" id="remark_option1" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec1" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                                <button type="submit" id="requestbtn" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>

                            <!------------------------------- Copy ---------------------------------------------------->
                            <div class="tab-pane fade in" id="s5">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('create-complaintcopy') }}" enctype="multipart/form-data" method="post" id="copy-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_to') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select name="labour_office_id" id="labour_office_id" class="select2" required>
                                                            <option value=""></option>
                                                            @foreach ($labourOffice as $lOffice)
                                                            <option value="{{ $lOffice->id }}">{{ $lOffice->$officeName }}--{{ $lOffice->office_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_complaint_category') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select multiple="multiple" row="5" name="category_id[]" id="category_id" class="select2" required style="height: 100%;">
                                                            <!-- <option value=""></option> -->
                                                            @foreach ($complaintcat as $item)
                                                            <!-- <option value="{{ $item->id }}">{{ $item->$complaintcatName }}</option> -->
                                                            <option value="{{ $item->id }}">{{ $item->category_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option2" id="remark_option2" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec2" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext2" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <button type="button" style=" float: right; " class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>

                            <!------------------------------- Assign LO ---------------------------------------------------->
                            <div class="tab-pane fade in" id="s6">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('assignlo-action') }}" enctype="multipart/form-data" method="post" id="assignlo-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.labour_officer_to_assign') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select class="officerlo select2" name="labour_officer_id" id="labour_officer_id" required disabled>
                                                            <option value=""></option>
                                                            @foreach ($loOfficers as $loOfficer)
                                                            <option value="{{ $loOfficer->id }}" {{$data->lo_officer_id == $loOfficer->id ? 'selected' : ''}}>{{ $loOfficer->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>

                                                @if($data->lo_officer_id != "")
                                                    <section class="col col-4" style="margin-top: 2%;">
                                                        <label class="label">{{ __('action.change_officer') }}
                                                        <button id="changelo" type="button" style="margin-left: 2%; width: 90px; background-color: #963c2c; color: #e7e7e7;" class="btn btn-default"> {{ __('action.yes') }} </button></label>
                                                    </section>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option3" id="remark_option3" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec3" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext3" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>

                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" id="assignlobtn" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            <!---------------------------------Tab temp close---------------------------------------------------->

                            <div id="s2" class="tab-pane fade in" >
                                <div class="widget-body no-padding">
                                <fieldset>
                                        <form action="{{ route('tempclose-action') }}" enctype="multipart/form-data" method="post" id="tempclose-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_status') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        {{-- <select name="complaint_status_id" id="complaint_status_id" class="select2" required>
                                                            <option value=""></option>
                                                            @if($data->action_type == 'Pending_recovery' || $data->action_type == 'Pending'){
                                                                @foreach ($tempclosestatus as $complaintstate)
                                                                <option value="{{ $complaintstate->id }}">{{ $complaintstate->$updatestatus }}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="Request_approval_for_temp_close">Request Approval for Temp Close</option>
                                                            @endif
                                                        </select>
                                                        <i></i> --}}

                                                        <select name="forward_type_id" id="forward_type_id" class="select2" required>
                                                            <option value="{{ $tempcloseFtype->id }}" selected>{{ $tempcloseFtype->$forwardType }}</option>
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                            </div>

                                            <input type="hidden" id="labour_office_id" class="labour_office_id" name="labour_office_id" value="{{ $data->current_office_id }}" >

                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option4" id="remark_option4" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec4" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext4" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">

                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" id="tempclosebtn" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>

                            <!------------------------------- Close ---------------------------------------------------->
                            <div class="tab-pane fade in" id="s3" >
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('close-action') }}" enctype="multipart/form-data" method="post" id="close-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_status') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        {{-- <select name="complaint_status_id" id="complaint_status_id" class="select2" required>
                                                            <option value=""></option>
                                                            @if($data->action_type == 'Pending_recovery' || $data->action_type == 'Pending'){
                                                                @foreach ($closedstatus as $complaintstate)
                                                                <option value="{{ $complaintstate->id }}">{{ $complaintstate->$updatestatus }}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="Request_approval_for_close">Request Approval for Close</option>
                                                            @endif
                                                        </select> --}}
                                                        <select name="forward_type_id" id="forward_type_id" class="select2 forward_type_id">
                                                            <option value="{{ $closeFtype->id }}" selected>{{ $closeFtype->$forwardType }}</option>
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                            </div>

                                            <input type="hidden" id="labour_office_id" class="labour_office_id" name="labour_office_id" value="{{ $data->current_office_id }}" >

                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option5" id="remark_option5" class="select2" required>
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec5" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext5" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">

                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" id="closebtn" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                                <button type="button" style=" float: right; " class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>

                            <!--------------------------------------------------- update Status -------------------------------->
                            <div class="tab-pane fade in <?php if($complainhistory->status != "Approved_temp_close" && $complainhistory->status != "Approved_close"){ echo 'active';} ?>" id="s4">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('status-update-action') }}" enctype="multipart/form-data" method="post" id="update-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_status') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select name="complaint_status_id" id="complaint_status_id" class="select2" required>
                                                            <option value=""></option>
                                                            @foreach ($complaintstatus as $complaintstate)
                                                            <option value="{{ $complaintstate->id }}">{{ $complaintstate->$updatestatus }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6">
                                                        <label class="label">{{ __('action.updated_date') }} <span style="color: #FF0000;"> *</span></label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="status_updated_date" name="status_updated_date" value="" class="datepicker" data-date-format='yyyy-mm-dd' required>
                                                        </label>
                                                    </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option6" id="remark_option6" class="select2">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                                <section class="col col-6 remarksec6" style="display:none;">
                                                    <label class="label">{{ __('action.remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea class="remarktext6" rows="3" id="remark" name="remark" ></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">

                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---------------------------------------------------------------- End Tab ------------------------------------------->

                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>

    <script>
        $(".select2").select2();
    </script>

    <script>
        $(document).ready(function () {
            var currentDate = new Date();

            var startingdate = new Date();
            startingdate.setDate( startingdate.getDate() - 14 );

            $('#status_updated_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                startDate: startingdate,
                maxDate: currentDate,
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#status_updated_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });
    </script>

    <x-slot name="script">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
        <!-- <script type="text/javascript" src="{{ asset('public/back/js/datepicker/bootstrap-datepicker.min.js') }}"></script> -->
        <script>
            $(function() {
                $('#copy-form').parsley();
            });
            $(function() {
                $('#tempclose-form').parsley();
            });
            $(function() {
                $('#close-form').parsley();
            });
            $(function() {
                $('#assignlo-form').parsley();
            });

            $(document).ready(function () {

                // $("#forward-form").submit(function (e) {

                //     $("#forwardbtn").attr("disabled", true);

                //     return true;

                // });

                $("#tempclose-form").submit(function (e) {

                    $("#tempclosebtn").attr("disabled", true);

                    return true;

                });

                $("#close-form").submit(function (e) {

                    $("#closebtn").attr("disabled", true);

                    return true;

                });

                $("#assignlo-form").submit(function (e) {

                    $("#assignlobtn").attr("disabled", true);

                    return true;

                });
            });
        </script>
        <script>
            $('#remark_option').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec').css("display", "block");
                    $('.remarktext').attr('required', true);
                } else{
                    $('.remarksec').css("display", "none");
                    $('.remarktext').attr('required', false);
                }

            });

            $('#remark_option1').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec1').css("display", "block");
                    $('.remarktext1').attr('required', true);
                } else{
                    $('.remarksec1').css("display", "none");
                    $('.remarktext1').attr('required', false);
                }

            });

            $('#remark_option2').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec2').css("display", "block");
                    $('.remarktext2').attr('required', true);
                } else{
                    $('.remarksec2').css("display", "none");
                    $('.remarktext2').attr('required', false);
                }

            });

            $('#remark_option3').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec3').css("display", "block");
                    $('.remarktext3').attr('required', true);
                } else{
                    $('.remarksec3').css("display", "none");
                    $('.remarktext3').attr('required', false);
                }

            });

            $('#remark_option4').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec4').css("display", "block");
                    $('.remarktext4').attr('required', true);
                } else{
                    $('.remarksec4').css("display", "none");
                    $('.remarktext4').attr('required', false);
                }

            });

            $('#remark_option5').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec5').css("display", "block");
                    $('.remarktext5').attr('required', true);
                } else{
                    $('.remarksec5').css("display", "none");
                    $('.remarktext5').attr('required', false);
                }

            });

            $('#remark_option6').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec6').css("display", "block");
                    $('.remarktext6').attr('required', true);
                } else{
                    $('.remarksec6').css("display", "none");
                    $('.remarktext6').attr('required', false);
                }

            });
        </script>
            <script>

            $(document).ready(function () {
                var assignlo = $('#labour_officer_id').val();

                console.log(assignlo);

                if(assignlo == "") {
                    $('.officerlo').attr('disabled',false);
                } else {
                    $('.officerlo').attr('disabled',true);
                }

                $('#changelo').click(function(){ // click to
                    $('.officerlo').attr('disabled',false); // removing disabled in this class
                });
            });
            </script>
        <script>
            $(document).ready(function () {
                var currentDate = new Date();
                $('#status_updated_date').datepicker({
                    format: 'yyyy-mm-dd',
                    prevText : '<i class="fa fa-chevron-left"></i>',
                    nextText : '<i class="fa fa-chevron-right"></i>',
                    autoclose:true,
                    endDate: "currentDate",
                    maxDate: currentDate
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
                $('#status_updated_date').keyup(function () {
                    if (this.value.match(/[^0-9]/g)) {
                        this.value = this.value.replace(/[^0-9^-]/g, '');
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
            $('#forward-form .row section .select select#labour_office_id').change(function() {
                if($('#labour_office_id').val() != ''){
                    $('#error_forward_to').css('display', 'none');
                } else {
                    $('#error_forward_to').css('display', 'block');
                }
            });

            // $('#forward-form .row section .select select#forward_type_id').change(function() {
            //     if($('#forward_type_id').val() != ''){
            //         $('#error_forward_type').css('display', 'none');
            //     } else {
            //         $('#error_forward_type').css('display', 'block');
            //     }
            // });

            $('#request-form .row section .select select.forward_type_id').change(function() {
                console.log('test123');
                if($('.forward_type_id').val() != ''){
                    $('#error_forward_type').css('display', 'none');
                } else {
                    $('#error_forward_type').css('display', 'block');
                }
            });

            $('#tempclose-form .row section .select select#forward_type_id').change(function() {
                if($('#forward_type_id').val() != ''){
                    $('#error_forward_type').css('display', 'none');
                } else {
                    $('#error_forward_type').css('display', 'block');
                }
            });

            $('#close-form .row section .select select#forward_type_id').change(function() {
                if($('#forward_type_id').val() != ''){
                    $('#error_forward_type').css('display', 'none');
                } else {
                    $('#error_forward_type').css('display', 'block');
                }
            });
        });
        </script>
        <script>
            $(document).ready(function(){
                $('#forward-form').on('submit', function(e){

                    e.preventDefault();
                    var labour_office_id = $('#labour_office_id').val();
                    var forward_type_id = $('#forward_type_id').val();
                    var remark_option = $('#remark_option').val();

                    form_data = new FormData(document.getElementById("forward-form"));

                    if(labour_office_id == '' || forward_type_id == ''){
                        if(labour_office_id == ''){
                            $('#error_forward_to').css('display', 'block');
                        } else {
                            $('#error_forward_to').css('display', 'none');
                        }

                        if(forward_type_id == ''){
                            $('#error_forward_type').css('display', 'block');
                        } else {
                            $('#error_forward_type').css('display', 'none');
                        }
                    } else {
                        swal({
                            title: 'Are you sure?',
                            text: 'This record will be forward!',
                            icon: 'warning',
                            buttons: ["Cancel", "Yes"],
                        }).then(function(value) {
                            if (value == true) {
                                document.getElementById("forward-form").submit();
                                $("#forwardbtn").attr("disabled", true);
                                //return true;
                            }
                        });
                    }
                });

                $('#request-form').on('submit', function(e){

                    e.preventDefault();
                    var labour_office_id = $('.labour_office_id').val();
                    var forward_type_id = $('.forward_type_id').val();
                    var remark_option = $('#remark_option').val();

                    form_data = new FormData(document.getElementById("request-form"));

                    if(labour_office_id == '' || forward_type_id == ''){
                        if(labour_office_id == ''){
                            $('#error_forward_to').css('display', 'block');
                        } else {
                            $('#error_forward_to').css('display', 'none');
                        }

                        if(forward_type_id == ''){
                            $('#error_forward_type').css('display', 'block');
                        } else {
                            $('#error_forward_type').css('display', 'none');
                        }
                    } else {
                        swal({
                            title: 'Are you sure?',
                            text: 'This record will be submitted to the request list!',
                            icon: 'warning',
                            buttons: ["Cancel", "Yes"],
                        }).then(function(value) {
                            if (value == true) {
                                document.getElementById("request-form").submit();
                                $("#requestbtn").attr("disabled", true);
                                //return true;
                            }
                        });
                    }
                });

                $('#tempclose-form').on('submit', function(e){

                    e.preventDefault();
                    var labour_office_id = $('.labour_office_id').val();
                    var forward_type_id = $('#forward_type_id').val();
                    var remark_option = $('#remark_option').val();

                    form_data = new FormData(document.getElementById("tempclose-form"));

                    if(labour_office_id == '' || forward_type_id == ''){
                        if(labour_office_id == ''){
                            $('#error_forward_to').css('display', 'block');
                        } else {
                            $('#error_forward_to').css('display', 'none');
                        }

                        if(forward_type_id == ''){
                            $('#error_forward_type').css('display', 'block');
                        } else {
                            $('#error_forward_type').css('display', 'none');
                        }
                    } else {
                        swal({
                            title: 'Are you sure?',
                            text: 'This record will be submitted for approval!',
                            icon: 'warning',
                            buttons: ["Cancel", "Yes"],
                        }).then(function(value) {
                            if (value == true) {
                                document.getElementById("tempclose-form").submit();
                                $("#tempclosebtn").attr("disabled", true);
                                //return true;
                            }
                        });
                    }
                });

                $('#close-form').on('submit', function(e){
                    e.preventDefault();
                    var labour_office_id = $('.labour_office_id').val();
                    var forward_type_id = $('#forward_type_id').val();
                    var remark_option = $('#remark_option').val();

                    form_data = new FormData(document.getElementById("close-form"));

                    if(labour_office_id == '' || forward_type_id == ''){
                        if(labour_office_id == ''){
                            $('#error_forward_to').css('display', 'block');
                        } else {
                            $('#error_forward_to').css('display', 'none');
                        }

                        if(forward_type_id == ''){
                            $('#error_forward_type').css('display', 'block');
                        } else {
                            $('#error_forward_type').css('display', 'none');
                        }
                    } else {
                        swal({
                            title: 'Are you sure?',
                            text: 'This record will be submitted for approval!',
                            icon: 'warning',
                            buttons: ["Cancel", "Yes"],
                        }).then(function(value) {
                            if (value == true) {
                                document.getElementById("close-form").submit();
                                $("#closebtn").attr("disabled", true);
                                //return true;
                            }
                        });
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>

