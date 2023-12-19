@section('title', 'Action')
@php
$officeName = 'office_name_en';
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
@endphp
@endif




<x-app-layout>
    <x-slot name="header">

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
                        {{ __('actionapproval.form_title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('actionapproval.complaint_ref_no') }} : {{ $data->ref_no }} </h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('actionapproval.internal_ref_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('actionapproval.external_ref_no') }}</strong> : {{ $data->external_ref_no }}</h5>
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
                    <h2>{{ __('actionapproval.sub_heading') }}</h2>


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


                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('forward-pendingapproval') }}" enctype="multipart/form-data" method="post" id="complaint-action-form" class="smart-form">
                                            @csrf

                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">@if($history->status == "Request_assign_lo"){{ $history->status_des.' - '. $requestAssignLO->name }}@else{{ $history->status_des }}@endif</label>
                                                    <input type="hidden" name="labour_officer_id" id="labour_officer_id" value="{{ $history->assigned_lo_id }}">
                                                </section>
                                            </div>

                                            @if($history->remark != "")
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label"><b>MSO Remark - </b>{{ $history->remark }}</label>
                                                </section>
                                            </div>
                                            @endif

                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('actionapproval.action') }}<span style="color: #FF0000;"> *</span> </label>
                                                    {{-- <label class="select"> --}}
                                                        <select name="action_type" id="action_type" class="select2" required>
                                                            <option value=""></option>
                                                            <option {{ ($data->complaint_status) == 'Approve' ? 'selected' : '' }} value="Approve">{{ __('actionapproval.approve') }}</option>
                                                            <option {{ ($data->complaint_status) == 'Reject' ? 'selected' : '' }} value="Reject">{{ __('actionapproval.reject') }}</option>
                                                        </select>
                                                        <i></i>
                                                    {{-- </label> --}}
                                                </section>
                                            </div>

                                            <div class="row" id="remarkreject">
                                                <section class="col col-12" style="width: 100%;">
                                                    <label class="label">{{ __('actionapproval.remark') }}</label>
                                                    <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark"></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                                <input type="hidden" name="labour_office_id" value="{{ $history->sent_from_office }}" >
                                                <input type="hidden" name="current_office" value="{{ $history->sent_to_office }}" >
                                                <button type="submit" class="btn btn-primary">
                                                {{ __('actionapproval.submit') }}
                                                </button>
                                            </footer>
                                        </form>
                                    </fieldset>


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
                $('#complaint-action-form').parsley();
            });
        </script>

    </x-slot>
</x-app-layout>
