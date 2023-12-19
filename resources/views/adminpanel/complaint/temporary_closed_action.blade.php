@section('title', 'Action')
@php
$officeName = 'office_name_en';
$updatestatus = 'status_en'
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
$updatestatus = 'status_ta';
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
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
                        {{ __('action.form_title') }}
                    </h1>
                </div>
            </div>
            <h1 class="alert alert-info"> {{ __('action.complaint_ref_no') }} : {{ $data->ref_no }} </h1>
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
                            <li class="active" id="s1A">
                                <a href="#s1" data-toggle="tab">Reopen</a>
                            </li>
                            <li id="s2B">
                                <a href="#s2" data-toggle="tab">Close</a>
                            </li>
                            <!-- <li id="s3C">
                                <a href="#s3" data-toggle="tab">Update Status</a>
                            </li> -->
                        </ul>
                        <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">
                            <!------------------------------- Forward ---------------------------------------------------->
                            <div class="tab-pane fade in active" id="s1">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('reopen') }}" enctype="multipart/form-data" method="post" id="reopen-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-12" style="width: 100%;">
                                                    <label class="label">Remark <span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark" required></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" class="btn btn-primary">
                                                    Submit
                                                </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            {{-- {{ route('assignlo-action') }} --}}

                            <!------------------------------- Close ---------------------------------------------------->
                            <div class="tab-pane fade" id="s2">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('close-action-temp-list') }}" enctype="multipart/form-data" method="post" id="close-form" class="smart-form">
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
                                            </div>
                                            <div class="row">
                                                <section class="col col-12" style="width: 100%;">
                                                    <label class="label">Remark<span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark" required></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">

                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" class="btn btn-primary">
                                                    Submit
                                                </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>

                            <!--------------------------------------------------- update Status -------------------------------->
                            <div class="tab-pane fade" id="s3">
                                <div class="widget-body no-padding">
                                <fieldset>
                                <form action="{{ route('status-update-action-temp-list') }}" enctype="multipart/form-data" method="post" id="update-form" class="smart-form">
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
                                            </div>
                                            <div class="row">
                                                <section class="col col-12" style="width: 100%;">
                                                    <label class="label">Remark <span style="color: #FF0000;"> *</span></label>
                                                    <label class="textarea">
                                                        <textarea rows="3" id="remark" name="remark" required></textarea>
                                                    </label>
                                                </section>
                                            </div>
                                            <footer style="background-color: #fff; border-top: transparent;">

                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" class="btn btn-primary">
                                                    Submit
                                                </button>
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
    <x-slot name="script">
        <script>
            $(function() {
                $('#reopen-form').parsley();
            });
            $(function() {
                $('#tempclose-form').parsley();
            });
            $(function() {
                $('#close-form').parsley();
            });
        </script>



    </x-slot>
</x-app-layout>
