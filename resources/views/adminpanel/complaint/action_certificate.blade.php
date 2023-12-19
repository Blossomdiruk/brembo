@section('title', 'Action Certificate')
@php
$officeName = 'office_name_en';
$updatestatus = 'status_en';
$forwardType = 'type_name';
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
$updatestatus = 'status_ta';
$forwardType = 'type_name_tam';
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
$updatestatus = 'status_si';
$forwardType = 'type_name_sin';
@endphp
@endif




<x-app-layout>
    <x-slot name="header">
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
                        {{ __('actioncertificate.form_title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('actioncertificate.complaint_ref_no') }} : {{ $data->ref_no }} </h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('actioncertificate.internal_ref_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('actioncertificate.external_ref_no') }}</strong> : {{ $data->external_ref_no }}</h5>
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
                    <h2>{{ __('actioncertificate.sub_heading') }}</h2>


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
                            <li id="s3A" class="active" >
                                <a href="#s3" data-toggle="tab">{{ __('action.forward') }}</a>
                            <li>
                            <li id="s1A">
                                <a href="#s1" data-toggle="tab" >{{ __('actioncertificate.action') }}</a>
                            </li>
                            <li id="s5E">
                                <a href="#s2" data-toggle="tab" >{{ __('actioncertificate.update_status') }}</a>
                            </li>
                        </ul>
                        <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">
                        <!------------------------------- Forward ---------------------------------------------------->
                        <div id="s3" class="tab-pane fade in active">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('forward-actionpending') }}" enctype="multipart/form-data" method="post" id="forward-form" class="smart-form">
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
                                                    <span style="display:none;" id="error_forward_to" class="text-danger">This value is required.</span>
                                                </section>
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select name="forward_type_id" id="forward_type_id" class="select2" required>
                                                            <option value=""></option>
                                                            @foreach ($forwardTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->$forwardType }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
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
                            <!------------------------------- Action ---------------------------------------------------->
                            <div id="s1" class="tab-pane fade in">

                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('forward-pendingcertificate') }}" enctype="multipart/form-data" method="post" id="certificate-action-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('actioncertificate.action') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select name="action_type" id="action_type" class="select2" required>
                                                            <option value=""></option>
                                                            <option {{ ($data->complaint_status) == 'Create_legal_certificate' ? 'selected' : '' }} value="Create_legal_certificate">{{ __('actioncertificate.legal_certificate_created') }}</option>
                                                            <option {{ ($data->complaint_status) == 'Reject_legal_certificate' ? 'selected' : '' }} value="Reject_legal_certificate">{{ __('actioncertificate.request_reject') }}</option>
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-12" style="width: 100%;">
                                                    <label class="label">{{ __('actioncertificate.remark') }}</label>
                                                    <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                                <input type="hidden" name="labour_office_id" value="{{ $history[0]->sent_from_office }}" >
                                                <button type="submit" class="btn btn-primary">
                                                {{ __('actioncertificate.submit') }}
                                                </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>

                            </div>
                            <!--------------------------------------------------- update Status -------------------------------->
                            <div class="tab-pane fade" id="s2">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('status-update-action-certificate-list') }}" enctype="multipart/form-data" method="post" id="update-form" class="smart-form">
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
                                                    <label class="label">{{ __('action.select_remark') }}<span style="color: #FF0000;"> *</span></label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    {{-- <label class="select"> --}}
                                                        <select name="remark_option6" id="remark_option6" class="select2" required>
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

    <x-slot name="script">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
        <!-- <script type="text/javascript" src="{{ asset('public/back/js/datepicker/bootstrap-datepicker.min.js') }}"></script> -->
    <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#certificate-action-form').parsley();
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
            $('#remark_option').on('change', function() {
                if(this.value == 'Other'){
                    $('.remarksec').css("display", "block");
                    $('.remarktext').attr('required', true);
                } else{
                    $('.remarksec').css("display", "none");
                    $('.remarktext').attr('required', false);
                }

            });
        </script>
    </x-slot>
</x-app-layout>
