@section('title', 'Action Recovery')
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
                        {{ __('actionrecovery.form_title') }}
                    </h1>
                </div>
            </div>
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('actionrecovery.internal_ref_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('actionrecovery.external_ref_no') }}</strong> : {{ $data->external_ref_no }}</h5>
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
                    <h2>{{ __('actionrecovery.sub_heading') }}</h2>


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
                            <li class="active" id="s1A">
                                <a href="#s1" data-toggle="tab">{{ __('action.forward') }}</a>
                            </li>
                            <li id="s4D">
                                <a href="#s4" data-toggle="tab">{{ __('action.update_status') }}</a>
                            </li>
                        </ul>
                        <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">
                            <div class="tab-pane fade in active" id="s1">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('forward-action-recovery') }}" enctype="multipart/form-data" method="post" id="forward-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_to') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="select">
                                                        <select name="labour_office_id" id="labour_office_id" required>
                                                            <option value=""></option>
                                                            @foreach ($labourOffice as $lOffice)
                                                            <option value="{{ $lOffice->id }}">{{ $lOffice->$officeName }}--{{ $lOffice->office_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    </label>
                                                    <span style="display:none;" id="error_forward_to" class="text-danger">This value is required.</span>
                                                </section>
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_forward_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="select">
                                                        <select name="forward_type_id" id="forward_type_id" required>
                                                            <option value=""></option>
                                                            @foreach ($forwardTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->$forwardType }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    </label>
                                                    <span style="display:none;" id="error_forward_type" class="text-danger">This value is required.</span>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_remark') }}</label>
                                                    <!-- <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label> -->
                                                    <label class="select">
                                                        <select name="remark_option" id="remark_option">
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    </label>
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
                                                <button type="submit" id="forwardbtn" class="btn btn-primary">{{ __('action.submit') }}</button>
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            <!--------------------------------------------------- update Status -------------------------------->
                            <div class="tab-pane fade" id="s4">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('status-update-action') }}" enctype="multipart/form-data" method="post" id="update-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('action.select_status') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="select">
                                                        <select name="complaint_status_id" id="complaint_status_id" required>
                                                            <option value=""></option>
                                                            @foreach ($complaintstatus as $complaintstate)
                                                            <option value="{{ $complaintstate->id }}">{{ $complaintstate->$updatestatus }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    </label>
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
                                                    <label class="select">
                                                        <select name="remark_option6" id="remark_option6" required>
                                                            <option value=""></option>
                                                            @foreach ($remarks as $remark)
                                                            <option value="{{ $remark->remark_en }}">{{ $remark->remark_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i></i>
                                                    </label>
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
                            {{-- <div class="widget-body no-padding">
                                <fieldset>
                                    <form action="{{ route('forward-recovery') }}" enctype="multipart/form-data" method="post" id="certificate-action-form" class="smart-form">
                                        @csrf
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">{{ __('actionrecovery.action') }}<span style="color: #FF0000;"> *</span> </label>
                                                <label class="select">
                                                    <select name="action_type" id="action_type" required>
                                                        <option value=""></option>
                                                        <option {{ ($data->complaint_status) == 'Recovered' ? 'selected' : '' }} value="Recovered">{{ __('actionrecovery.recovered') }}</option>
                                                        <option {{ ($data->complaint_status) == 'Rejected' ? 'selected' : '' }} value="Rejected">{{ __('actionrecovery.reject') }}</option>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </section>
                                        </div>
                                        <div class="row">
                                            <section class="col col-12" style="width: 100%;">
                                                <label class="label">{{ __('actionrecovery.remark') }}</label>
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
                                            {{ __('actionrecovery.submit') }}
                                            </button>
                                        </footer>
                                    </form>
                                </fieldset>
                            </div> --}}
                        </div>
                    </div>

                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->
        </div>
    </div>
    <x-slot name="script">
    <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#recovery-action-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
