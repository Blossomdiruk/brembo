@section('title', 'Letter')
@php
$officeName = 'office_name_en';
$forwardType = 'type_name';
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
$forwardType = 'type_name_tam';
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
$forwardType = 'type_name_sin';
@endphp
@endif


<x-app-layout>
    <x-slot name="header">
        <style>
            .note-editable {
                min-height: 550px !important;
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
                        {{ __('mailaction.title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('mailaction.ref_num') }} :  {{ $data->external_ref_no }}</h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('mailaction.ref_num') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('mailaction.external_ref_num') }}</strong> : {{ $data->external_ref_no }}</h5>
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
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ __('mailaction.sub_title') }}</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    @if (request()->has('printId'))
                        <input type="hidden" value="{{ request()->get('printId') }}" name="print_id" id="print_id" />
                        <a target="_blank" id="print_link" href="{{ route('send-letter', ['printId'=>request()->get('printId')]) }}" style="display: none;"></a>
                    @endif


                    <!---------------------------------------------Tab widget--------------------------------------------------------------->
                    <div class="widget-body padding-10">
                        <!--<hr class="simple">-->
                        <ul id="myTab1" class="nav nav-tabs bordered">
                            <li class="@if($tab == "s1"){{ "active" }}@else{{ "" }}@endif" id="s1A">
                                <a href="#s1" onclick="show_submit('T1')" data-toggle="tab">{{ __('mailaction.complain_detail') }}</a>
                            </li>
                            <li class="@if($tab == "s2"){{ "active" }}@else{{ "" }}@endif" id="s2B">
                                <a href="#s2" class="next" onclick="show_submit('T2')" data-toggle="tab">{{ __('mailaction.letter') }}</a>
                            </li>
                            <li class="@if($tab == "s3"){{ "active" }}@else{{ "" }}@endif" id="s3C">
                                <a href="#s3" class="nextII" onclick="show_submit('T3')" data-toggle="tab">{{ __('mailaction.email') }}</a>
                            </li>
                             <li class="@if($tab == "s4"){{ "active" }}@else{{ "" }}@endif" id="s4D">
                                <a href="#s4" class="nextIII" onclick="show_submit('T4')" data-toggle="tab">{{ __('mailaction.nd') }}</a>
                            </li>
                        </ul>
                        <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">
                            <!------------------------------- Forward ---------------------------------------------------->
                            <div class="tab-pane fade in @if($tab == "s1"){{ "active" }}@else{{ "" }}@endif" id="s1">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('update-complain-detail') }}" enctype="multipart/form-data" method="post" id="update-complain-detail-form" class="smart-form">
                                            @csrf
                                            {{-- @method('PUT') --}}
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-12">
                                                        <label class="label">{{ __('mailaction.complainant_pref_lang') }} : @if($data->pref_lang == "TA"){{ "Tamil" }}@elseif ($data->pref_lang == "SI"){{ "Sinhala" }}@else{{ "English" }}@endif</label>
                                                        {{-- <label class="input">
                                                            <input type="text" id="pref_lang" name="pref_lang" value="@if($data->pref_lang == "TA"){{ "Tamil" }}@elseif ($data->pref_lang == "SI"){{ "Sinhala" }}@else{{ "English" }}@endif" readonly>
                                                        </label> --}}
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.f_name_en') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_f_name" name="complainant_f_name" value="{{ $data->complainant_f_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.f_name_si') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_f_name_si" name="complainant_f_name_si" value="{{ $data->complainant_f_name_si }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.f_name_ta') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_f_name_ta" name="complainant_f_name_ta" value="{{ $data->complainant_f_name_ta }}">
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.l_name_en') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_l_name" name="complainant_l_name" value="{{ $data->complainant_l_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.l_name_si') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_l_name_si" name="complainant_l_name_si" value="{{ $data->complainant_l_name_si }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.l_name_ta') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_l_name_ta" name="complainant_l_name_ta" value="{{ $data->complainant_l_name_ta }}">
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.complainant_address_en') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="complainant_address" name="complainant_address">{{ $data->complainant_address }}</textarea>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.complainant_address_si') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="complainant_address_si" name="complainant_address_si">{{ $data->complainant_address_si }}</textarea>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.complainant_address_ta') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="complainant_address_ta" name="complainant_address_ta">{{ $data->complainant_address_ta }}</textarea>
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.employer_name_en') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employer_name" name="employer_name" value="{{ $data->employer_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.employer_name_si') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employer_name_si" name="employer_name_si" value="{{ $data->employer_name_si }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.employer_name_ta') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employer_name_ta" name="employer_name_ta" value="{{ $data->employer_name_ta }}">
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.employer_address_en') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="employer_address" name="employer_address">{{ $data->employer_address }}</textarea>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.employer_address_si') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="employer_address_si" name="employer_address_si">{{ $data->employer_address_si }}</textarea>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.employer_address_ta') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="employer_address_ta" name="employer_address_ta">{{ $data->employer_address_ta }}</textarea>
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>

                                            <footer style="background: none; border-top: 0px !important;">
                                                <input type="hidden" name="id" value="{{ $data->id }}">

                                                <button id="button1ids" name="button1id" type="submit" class="btn btn-primary">
                                                    {{ __('mailaction.update') }}
                                                </button>
                                                {{-- <footer style="background-color: #fff; border-top: transparent; padding:0px;"> --}}
                                                    <a href="#s2" id="testing" class="test" onclick="show_submit('T2');changeactive('s2B', 's1A');" data-toggle="tab">
                                                        <button type="button" id="testing" class="btn btn-primary next test"> {{ __('mailaction.next') }} </button>
                                                    </a>
                                                {{-- </footer> --}}
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="tab-pane fade in @if($tab == "s2"){{ active }}@else{{ '' }}@endif" id="s2">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="#" enctype="multipart/form-data" method="post" id="send-letter-form" class="smart-form" target="_blank">
                                            @csrf
                                            {{-- @method('PUT') --}}
                                            <fieldset>
                                                <div class="row">
                                                        <?php
                                                            if(Session()->get('applocale')=='ta') {
                                                                $lang = "TA";
                                                            } elseif(Session()->get('applocale')=='si') {
                                                                $lang = "SI";
                                                            } else {
                                                                $lang = "EN";
                                                            }
                                                        ?>

                                                    <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                                                    <input type="hidden" name="complainant_name" id="complainant_name" value="{{ $data->complainant_f_name }}">
                                                    <input type="hidden" name="complainant_id" id="complainant_id" value="{{ $data->id }}">
                                                    <input type="hidden" name="external_ref_no" id="external_ref_no" value="{{ $data->external_ref_no }}">

                                                    {{-- <section class="col col-6">
                                                        <label class="label">{{ __('mailaction.letter_for') }}</label>
                                                        <label class="select">
                                                            <select id="letter_for" name="letter_for">
                                                                <option value=""></option>
                                                                <option value="Employer">{{ __('mailaction.employer') }}</option>
                                                                <option value="Complainant">{{ __('mailaction.complainant') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section> --}}

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('mailaction.letter_for') }}</label>
                                                        <div class="inline-group ">
                                                            <label class="radio">
                                                                <input type="radio" id="letter_for" value="Employer" name="letter_for" class="letter_for">
                                                                <i></i>{{ __('mailaction.employer') }}</label>
                                                            <label class="radio">
                                                                <input type="radio" id="letter_for" value="Complainant" name="letter_for" class="letter_for">
                                                                <i></i>{{ __('mailaction.complainant') }}</label>
                                                        </div>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-4">
                                                      <input type="hidden" id="categ_ltr" name="categ_ltr" value="L" >
                                                        <label class="label">{{ __('mailaction.letter_pref_lang') }}</label>
                                                        <label class="select">
                                                            <select id="pref_lang_let" name="pref_lang_let">
                                                                <option value=""></option>
                                                                <option value="EN">{{ __('mailaction.english') }}</option>
                                                                <option value="SI">{{ __('mailaction.sinhala') }}</option>
                                                                <option value="TA">{{ __('mailaction.tamil') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.select_letter_template') }}<span style=" color: red;">*</span></label>
                                                        <label class="select">
                                                            <select name="letter_template_id" id="letter_template_id" required>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.name') }} </label>
                                                        <label class="input">
                                                            <input type="text" id="letter_complainant_name" name="letter_complainant_name" value="" readonly>
                                                        </label>
                                                    </section>
                                                </div>
                                                {{-- <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.address') }} </label>
                                                        <label class="input"> --}}
                                                            <input type="hidden" id="letter_complainant_address" name="letter_complainant_address" value="" readonly>
                                                        {{-- </label>
                                                    </section>
                                                </div> --}}

                                                <input type="hidden" name="sent_by" value="{{ $officer_id}}">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}">

                                                {{-- <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('mailaction.heading') }} </label>
                                                        <label class="input"> --}}
                                                            <input type="hidden" id="letter_heading" name="letter_heading" value="" style="border-width: 0px !important;" readonly>
                                                        {{-- </label>
                                                    </section>
                                                </div> --}}

                                                <div class="row">
                                                    <section class="col col-11"  style="width: 100%;">
                                                        <label class="label">{{ __('mailtemplate.body_content_en') }}<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <textarea class="form-control summernote" id="letter_body" name="letter_body" rows="3"> </textarea>
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>

                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="label">{{ __('mailaction.letter_history') }}</label>
                                                        <br>
                                                        <div class="table-responsive">

                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __('mailaction.template_name') }}</th>
                                                                        <th>{{ __('mailaction.date') }}</th>
                                                                        <th>{{ __('mailaction.mode') }}</th>
                                                                        <th>{{ __('mailaction.view') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($letterhistories as $letterhistory)
                                                                    <tr>
                                                                        <td id="tt">{{ $letterhistory->mailtemplatedetails->mail_template_title }}</td>
                                                                        <td>{{ $letterhistory->created_at }}</td>
                                                                        <td>{{ $letterhistory->status }}</td>
                                                                        <td><button id="loadbtn" name="loadbtn" type="button" value="{{ $letterhistory->id }}" class="btn btn-sm btn-primary btnload">
                                                                            {{ __('mailaction.load') }}
                                                                        </button></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </section>					<!-- end widget content -->
                                                </div>
                                            </fieldset>

                                            <footer style="background: none; border-top: 0px !important;">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" id="selected_lang" name="selected_lang" value="">

                                                {{-- <button id="sendletter" name="sendletter" type="button" class="btn btn-primary">
                                                    {{ __('mailaction.submit') }}
                                                </button> --}}
                                                <button id="printbtnleter" name="printbtnleter" type="button" class="btn btn-primary">
                                                    {{ __('mailaction.print') }}
                                                </button>
                                                {{-- <button type="button" class="btn btn-default" onclick="window.history.back();">
                                                    {{ __('mailaction.back') }}
                                                </button> --}}
                                                <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                    <a href="#s3" id="testing" class="test" onclick="show_submit('T3');changeactive('s3C', 's2B');" data-toggle="tab">
                                                        <button type="button" id="testing" class="btn btn-primary nextII test"> {{ __('mailaction.next') }} </button>
                                                    </a>
                                                    <a href="#s1" onclick="show_submit('T1');changeactive('s1A', 's2B');" data-toggle="tab">
                                                        <button type="button" class="btn btn-default"> {{ __('mailaction.back') }} </button>
                                                    </a>
                                                </footer>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="tab-pane fade in @if($tab == "s3"){{ active }}@else{{ "" }}@endif" id="s3">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="#" enctype="multipart/form-data" method="post" id="send-mail-form" class="smart-form">
                                            @csrf
                                            <fieldset>
                                                {{-- <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('mailaction.email_for') }}</label>
                                                        <label class="select">
                                                            <select id="email_for" name="email_for">
                                                                <option value=""></option>
                                                                <option value="Employer">{{ __('mailaction.employer') }}</option>
                                                                <option value="Complainant">{{ __('mailaction.complainant') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section>
                                                </div> --}}
                                                <div class="row">
                                                        <?php
                                                        if(Session()->get('applocale')=='ta') {
                                                            $lang = "TA";
                                                        } elseif(Session()->get('applocale')=='si') {
                                                            $lang = "SI";
                                                        } else {
                                                            $lang = "EN";
                                                        }
                                                    ?>

                                                    <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                                                    {{-- <input type="hidden" name="complainant_name" id="complainant_name" value="{{ $data->complainant_f_name }}"> --}}

                                                    <input type="hidden" id="categ_ml" name="categ_ml" value="E" >

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.mail_pref_lang') }}</label>
                                                        <label class="select">
                                                            <select id="pref_lang_mail" name="pref_lang_mail">
                                                                <option value=""></option>
                                                                <option value="EN">{{ __('mailaction.english') }}</option>
                                                                <option value="SI">{{ __('mailaction.sinhala') }}</option>
                                                                <option value="TA">{{ __('mailaction.tamil') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.select_mail_template') }}</label>
                                                        <label class="select">
                                                            <select name="template_id" id="template_id" >
                                                                {{-- <option value="">Select Mail Template</option>
                                                                @foreach ($mailtemplates as $mailtemplate)
                                                                    <option value="{{ $mailtemplate->id }}">@if($lang == "TA"){{ $mailtemplate->mail_template_name_tam }}@elseif($lang == "SI"){{ $mailtemplate->mail_template_name_sin }}@else{{ $mailtemplate->mail_template_name_en }}@endif</option>
                                                                @endforeach --}}
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </section>

                                                    <input type="hidden" name="sent_by" value="{{ $officer_id}}">
                                                    <input type="hidden" name="complaint_id" value="{{ $data->id }}">

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.sent_to') }}<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="sent_to" name="sent_to" required value="">
                                                        </label>
                                                        <label class="note"><strong>Note:</strong> Enter email address by comma separated.<br/><strong>Ex:</strong> example@example.com,example2@example.com</label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-8">
                                                        <label class="label">{{ __('mailaction.subject') }}<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="subject" name="subject" required value="">
                                                        </label>
                                                    </section>
                                                </div>

                                                {{-- <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('mailaction.heading') }} </label>
                                                        <label class="input"> --}}
                                                            <input type="hidden" id="mail_heading" name="mail_heading" value="" style="border-width: 0px !important;" readonly>
                                                        {{-- </label>
                                                    </section>
                                                </div> --}}

                                                <div class="row">
                                                    <section class="col col-11"  style="width: 100%;">
                                                        <label class="label">{{ __('mailtemplate.body_content_en') }}<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                                <textarea class="form-control summernote" id="mail_body" name="mail_body" rows="3"> </textarea>
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="label">{{ __('mailaction.mail_history') }}</label>
                                                        <br>
                                                        <div class="table-responsive">

                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __('mailaction.template_name') }}</th>
                                                                        <th>{{ __('mailaction.date') }}</th>
                                                                        <th>{{ __('mailaction.mode') }}</th>
                                                                        <th>{{ __('mailaction.view') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($mailhistories as $mailhistory)
                                                                    <tr>
                                                                        <td>{{ $mailhistory->mailtemplatedetails->mail_template_title }}</td>
                                                                        <td>{{ $mailhistory->created_at }}</td>
                                                                        <td>{{ $mailhistory->status }}</td>
                                                                        <td><button id="loadmailsbtn" name="loadmailsbtn" type="button" value="{{ $mailhistory->id }}" class="btn btn-sm btn-primary loadmailsbtn">
                                                                            {{ __('mailaction.load') }}
                                                                        </button></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </section>					<!-- end widget content -->
                                                </div>
                                            </fieldset>
                                            <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                <input type="hidden" name="id" value="{{ $data->id }}>">

                                                 <button id="button1id" name="button1id" type="button" class="btn btn-primary">
                                                    {{ __('mailaction.send_mail') }}
                                                </button>
                                                 <a href="#s4" id="testing" class="test" onclick="show_submit('T4');changeactive('s4D', 's3C');" data-toggle="tab">
                                                        <button type="button" id="testing" class="btn btn-primary nextIII test"> {{ __('mailaction.next') }} </button>
                                                    </a>
                                                <a href="#s2" onclick="show_submit('T2');changeactive('s2B', 's3C');" data-toggle="tab">
                                                    <button type="submit" class="btn btn-default"> {{ __('mailaction.back') }} </button>
                                                </a>


                                                {{-- <button id="printbtn" name="printbtn" type="button" class="btn btn-primary">
                                                    {{ __('mailaction.print') }}
                                                </button> --}}


                                                {{-- <button type="button" class="btn btn-default" onclick="window.history.back();">
                                                    {{ __('mailaction.back') }}
                                                </button> --}}
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                             <div class="tab-pane fade in @if($tab == "s4"){{ active }}@else{{ '' }}@endif" id="s4">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="#" enctype="multipart/form-data" method="post" id="send-nd-form" class="smart-form" target="_blank">
                                            @csrf
                                            {{-- @method('PUT') --}}
                                            <fieldset>
                                                <div class="row">
                                                        <?php
                                                            if(Session()->get('applocale')=='ta') {
                                                                $lang = "TA";
                                                            } elseif(Session()->get('applocale')=='si') {
                                                                $lang = "SI";
                                                            } else {
                                                                $lang = "EN";
                                                            }
                                                        ?>

                                                    <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                                                    <input type="hidden" name="complainant_name" id="complainant_name" value="{{ $data->complainant_f_name }}">
                                                    <input type="hidden" name="external_ref_no" id="external_ref_no" value="{{ $data->external_ref_no }}">


                                                    <section class="col col-6">
                                                        <label class="label">{{ __('mailaction.nd_for') }}</label>
                                                        <div class="inline-group ">
                                                            <label class="radio">
                                                                <input type="radio" id="nd_for" value="Employer" name="letter_for" class="nd_for">
                                                                <i></i>{{ __('mailaction.employer') }}</label>
                                                            <label class="radio">
                                                                <input type="radio" id="nd_for" value="Complainant" name="letter_for" class="nd_for">
                                                                <i></i>{{ __('mailaction.complainant') }}</label>
                                                        </div>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-4">
                                                      <input type="hidden" id="categ_nd" name="categ_nd" value="ND" >
                                                        <label class="label">{{ __('mailaction.nd_pref_lang') }}</label>
                                                        <label class="select">
                                                            <select id="pref_lang_nd" name="pref_lang_let">
                                                                <option value=""></option>
                                                                <option value="EN">{{ __('mailaction.english') }}</option>
                                                                <option value="SI">{{ __('mailaction.sinhala') }}</option>
                                                                <option value="TA">{{ __('mailaction.tamil') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.select_nd_template') }}<span style=" color: red;">*</span></label>
                                                        <label class="select">
                                                            <select name="letter_template_id" id="nd_template_id" required>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-4">
                                                        <label class="label">{{ __('mailaction.name') }} </label>
                                                        <label class="input">
                                                            <input type="text" id="nd_complainant_name" name="letter_complainant_name" value="" readonly>
                                                        </label>
                                                    </section>
                                                </div>

                                                <input type="hidden" id="nd_complainant_address" name="letter_complainant_address" value="" readonly>
                                                <input type="hidden" name="sent_by" value="{{ $officer_id}}">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}">
                                                 <input type="hidden" id="nd_heading" name="nd_heading" value="" style="border-width: 0px !important;" readonly>

                                               <div class="row">
                                                    <section class="col col-11"  style="width: 100%;">
                                                        <label class="label">{{ __('mailtemplate.body_content_en') }}<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <textarea class="form-control summernote" id="nd_body" name="letter_body" rows="3"> </textarea>
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>

                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="label">{{ __('mailaction.nd_history') }}</label>
                                                        <br>
                                                        <div class="table-responsive">

                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __('mailaction.template_name') }}</th>
                                                                        <th>{{ __('mailaction.date') }}</th>
                                                                        <th>{{ __('mailaction.mode') }}</th>
                                                                        <th>{{ __('mailaction.view') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($ndhistories as $ndhistory)
                                                                    <tr>
                                                                        <td id="tt">{{ $ndhistory->mailtemplatedetails->mail_template_title }}</td>
                                                                        <td>{{ $ndhistory->created_at }}</td>
                                                                        <td>{{ $ndhistory->status }}</td>
                                                                        <td><button id="loadbtn" name="loadbtn" type="button" value="{{ $ndhistory->id }}" class="btn btn-sm btn-primary btnloadnd">
                                                                            {{ __('mailaction.load') }}
                                                                        </button></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </section>					<!-- end widget content -->
                                                </div>
                                            </fieldset>

                                             <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                <input type="hidden" name="id" value="{{ $data->id }}>">

                                                <button id="sendNd" name="sendNd" type="button" class="btn btn-primary">
                                                    {{ __('mailaction.print') }}
                                                </button>
                                                <a href="#s3" onclick="show_submit('T3');changeactive('s3C', 's4D');" data-toggle="tab">
                                                    <button type="button" class="btn btn-default"> {{ __('mailaction.back') }} </button>
                                                </a>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---------------------------------------------------------------- End Tab ------------------------------------------->

                    {{-- $emailId = $request->paginate; --}}
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
                $('#update-complain-detail-form').parsley();
            });
            $(function() {
                $('#send-letter-form').parsley();
            });
            $(function() {
                $('#send-mail-form').parsley();
            });
             $(function() {
                $('#send-nd-form').parsley();
            });
        </script>

        <!-- include summernote css/js -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

        <script>

            function show_submit(cat) { //alert (cat);
                if (cat == 'T3') {
                    //alert (cat);
                    document.getElementById('button1id').style.display = "block";
                } else {
                    document.getElementById('button1id').style.display = "none";
                }
            }

            function changeactive(obj1, obj2) {
                $("#" + obj1).attr('class', 'active');
                $("#" + obj2).attr('class', '');
            }

            $(document).ready(function() {


                $('.summernote').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                    // ['para', ['ul', 'ol', 'paragraph']],
                    ['para', ['paragraph']],
                        ['height', ['height']],
                    // ['table', ['table']],
                        //['insert', ['link', 'picture', 'hr']],
                        //['view', ['fullscreen', 'codeview', 'help']]

                    ]
                });
            });
        </script>

        <script type="text/javascript">
            $('#printbtn').on('click', function(){
                $('#send-mail-form').attr('action', '{{ route("print-mail") }}');
                $('#send-mail-form').submit();
            });

            $('#button1id').on('click', function(){
                $('#send-mail-form').attr('action', '{{ route("send-mail") }}');
                $('#send-mail-form').submit();
            });

            $('#printbtnleter').on('click', function(){
                $('#send-letter-form').attr('action', '{{ route("print-letter") }}');
                $('#send-letter-form').submit();
            });

            $('#sendletter').on('click', function(){
                $('#send-letter-form').attr('action', '{{ route("send-letter") }}');
                $('#send-letter-form').submit();
            });

            $('#sendNd').on('click', function(){
                $('#send-nd-form').attr('action', '{{ route("print-letter") }}');
                $('#send-nd-form').submit();
            });

        </script>

        <script type="text/javascript">

            $('.letter_for').click(function() {
                $('#letter_heading').val('');
                $('#pref_lang_let').val('');
                $("#letter_template_id").val('');
                $("#letter_complainant_name").val('');
                $("#letter_complainant_address").val('');
                $(".note-editor .note-editable").empty();
                $("#letter_body").val('');

            });

            $('#pref_lang_let').change(function() {

                var prefLang = $('#pref_lang_let').val();
                var id = $('#complainant_id').val();
                var letterFor = $('.letter_for:checked').val();
                var category1 = $('#categ_ltr').val();

                if (prefLang) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getcomplainantDetails') }}/" + prefLang + '/'+id,
                        success: function(res) {

                            if (res) {

                                $(".note-editor .note-editable").empty();

                                $("#letter_body").val('');

                                $("#letter_heading").empty();

                                $.each(res, function(key, value) {

                                    if(letterFor == "Complainant") {
                                        if(prefLang == 'TA') {
                                            var title = 'அன்பார்ந்த';
                                            var name = res['complainant_f_name_ta'];
                                            var address = res['complainant_address_ta'];
                                        } else if(prefLang == 'SI') {
                                            var title = 'හිතවත්';
                                            var name = res['complainant_f_name_si'];
                                            var address = res['complainant_address_si'];
                                        } else {
                                            var title = 'Dear';
                                            var name = res['complainant_f_name'];
                                            var address = res['complainant_address'];
                                        }
                                    } else {
                                        if(prefLang == 'TA') {
                                            var title = 'அன்பார்ந்த';
                                            var name = res['employer_name_ta'];
                                            var address = res['employer_address_ta'];
                                        } else if(prefLang == 'SI') {
                                            var title = 'හිතවත්';
                                            var name = res['employer_name_si'];
                                            var address = res['employer_address_si'];
                                        } else {
                                            var title = 'Dear';
                                            var name = res['employer_name'];
                                            var address = res['employer_address'];
                                        }
                                    }

                                    $('#letter_complainant_name').val(name);
                                    $('#letter_complainant_address').val(address);
                                    $('#letter_heading').val(title+' '+name+',');
                                    $('#selected_lang').val(prefLang);
                                });

                            } else {

                                $("#letter_complainant_name").empty();
                                $("#letter_complainant_address").empty();
                            }
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getLetterTempTitle') }}/" + prefLang+ '/'+category1+ '/'+id,
                        success: function(res) {

                            if (res) {

                                $("#letter_template_id").empty();

                                $("#letter_template_id").append('<option>Select Letter Template</option>');
                                $.each(res, function(key, value) {

                                    if(prefLang == 'TA' ){
                                        var letterTemp = value['mail_template_name_tam'];
                                    } else if(prefLang == 'SI' ) {
                                        var letterTemp = value['mail_template_name_sin'];
                                    } else{
                                             var letterTemp = value['mail_template_name_en'];
                                    }

                                    $("#letter_template_id").append('<option value="' + value['id'] + '">' + letterTemp +
                                    '</option>');
                                });

                            } else {

                                $("#letter_complainant_name").empty();
                                $("#letter_complainant_address").empty();
                                $("#letter_template_id").empty();
                            }
                        }
                    });
                } else {

                    $("#letter_body").empty();
                }
            });

            $('#letter_template_id').change(function() {

                var prefLang = $('#pref_lang_let').val();
                var letterTemplateID = $('#letter_template_id').val();
                var externalRefno = $('#external_ref_no').val();
                var id = $('#complainant_id').val();
                var letterHeading = $('#letter_heading').val();
                var address = $('#letter_complainant_address').val();

                if (letterTemplateID) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getletterTemplates') }}/" + letterTemplateID + '/'+prefLang+'/'+id,
                        success: function(res) {
                            if (res) {
                                console.log(res);
                                // $("#mail_body").val();

                                $(".note-editor .note-editable").empty();

                                $("#letter_body").val('');

                                    // if($letterFor == "Employer") {

                                if(prefLang == 'TA') {
                                    $('#letter_body').val(res[0].body_content_tam);

                                    $('.note-editor .note-editable').append(res[0].body_content_tam);

                                } else if (prefLang == "SI") {
                                    $('#letter_body').val(res[0].body_content_sin);

                                    $('.note-editor .note-editable').append(res[0].body_content_sin);

                                } else {
                                    $('#letter_body').val(res[0].body_content_en);
                                    $('.note-editor .note-editable').append(res[0].body_content_en);
                                }
                                    // }

                            } else {

                                $("#letter_body").empty();
                            }
                        }
                    });
                } else {

                    $("#letter_body").empty();
                }
            });


            // ---------------- Mail -------------

            $('#pref_lang_mail').change(function() {

                var prefLang = $('#pref_lang_mail').val();
                var id = $('#complainant_id').val();
                var category1 = $('#categ_ml').val();

                if (prefLang) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getLetterTempTitle') }}/" + prefLang + '/'+category1+ '/'+id,
                        success: function(res) {
                            if (res) {
                                $("#template_id").empty();
                                $(".note-editor .note-editable").empty();
                                $("#mail_body").val('');
                                $("#template_id").append('<option>Select Letter Template</option>');
                                $("#subject").val('');
                                $.each(res, function(key, value) {
                                    if(prefLang == 'TA' ) {
                                        var letterTemp = value['mail_template_name_tam'];
                                    } else if(prefLang == 'SI' ) {
                                        var letterTemp = value['mail_template_name_sin'];
                                    } else {
                                        var letterTemp = value['mail_template_name_en'];
                                    }

                                    $("#template_id").append('<option value="' + value['id'] + '">' + letterTemp +
                                    '</option>');
                                });

                            } else {
                                $("#template_id").empty();
                            }
                        }
                    });

                } else {

                    $("#pref_lang_mail").empty();
                }
            });

            $('#pref_lang_mail').change(function() {

                var id = $('#complainant_id').val();
                var prefLang = $('#pref_lang_mail').val();

                if (id) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getRecipient') }}"+'/'+id,
                        success: function(res) {
                            if (res) {

                                $.each(res, function(key, value) {
                                    $('#sent_to').val(value['complainant_email']);

                                    if(prefLang == "SI") {
                                        $('#mail_heading').val('හිතවත්'+' '+value['complainant_f_name_si']+',');
                                    } else if(prefLang == "TA") {
                                        $('#mail_heading').val('அன்பார்ந்த'+' '+value['complainant_f_name_ta']+',');
                                    } else  {
                                        $('#mail_heading').val('Dear'+' '+value['complainant_f_name']+',');
                                    }

                                });

                            } else {
                                $("#template_id").empty();
                            }
                        }
                    });

                } else {

                    $("#pref_lang_mail").empty();
                }
            });

            $('#template_id').change(function() {

                var letterTemplateID = $(this).val();
                var prefLang = $('#pref_lang_mail').val();
                var email_for = $('#email_for').val();
                var externalRefno = $('#external_ref_no').val();
                var mailheading = $('#mail_heading').val();
                var id = $('#complainant_id').val();

                if (letterTemplateID) {

                    $.ajax({
                        type: "GET",
                        // url: "{{ url('getMailTemplates') }}/" + templateID + '/'+lang+'/'+complaintID,
                        url: "{{ url('getletterTemplates') }}/" + letterTemplateID + '/'+prefLang+'/'+id,
                        success: function(res) {
                            if (res) {
                                $(".note-editor .note-editable").empty();
                                $("#mail_body").val('');

                                $.each(res, function(key, value) {

                                    if(prefLang == 'TA') {
                                        var letterTemp = value['body_content_tam'];
                                        var subject = value['mail_template_name_tam'];
                                    } else if(prefLang == 'SI') {
                                        var letterTemp = value['body_content_sin'];
                                        var subject = value['mail_template_name_sin'];
                                    } else {
                                        var letterTemp = value['body_content_en'];
                                        var subject = value['mail_template_name_en'];
                                    }

                                    $('#mail_body').val(letterTemp);

                                    $('.note-editor .note-editable').append(letterTemp);

                                    $('#subject').val(subject);

                                });

                            } else {

                                $("#mail_body").empty();
                            }
                        }
                    });

                } else {

                    $("#mail_body").empty();
                }
            });

            $('.btnload').on('click', function () {
                var mailId = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ url('getSentMail') }}/" + mailId,
                    dataType: 'JSON',
                    success: function(res) {

                        $(".note-editor .note-editable").empty();
                        $("#letter_complainant_name").val('');
                        $("#letter_complainant_address").val('');
                        $(".letter_for").empty();
                        $('#pref_lang_let').empty();
                        $('#letter_template_id').empty();
                        $('#letter_heading').val('');
                        $('#letter_body').val('');

                        if(res.recipient == "Complainant") {
                            $('input[name^="letter_for"][value="Complainant"').prop('checked',true);
                            // $("input[name=type][value=" + value + "]").prop('checked', true);
                            // $('#letter_for').find(':radio[name=letter_for][value="' + res.recipient + '"]').prop('checked', true)
                            // $(".letter_for").append(':radio[name=letter_for][value="' + res.recipient + '"]');
                            // $(".letter_for").append('<input type="radio" id="letter_for" value="' + res.recipient + '" name="letter_for" class="letter_for">');
                        } else  {
                            $('input[name^="letter_for"][value="Employer"').prop('checked',true);
                            // $(".letter_for").append(':radio[name=letter_for][value="' + res.recipient + '"]');
                            // $('#letter_for').find(':radio[name=letter_for][value="' + res.recipient + '"]').prop('checked', true)
                            // $(".letter_for").append('<input type="radio" id="letter_for" value="' + res.recipient + '" name="letter_for" class="letter_for">');
                            // $(".letter_for").append('<input type="radio" id="letter_for" value="' + res.recipient + '" name="letter_for" class="letter_for" checked>');
                        }

                        if(res.pref_lang == "TA") {
                            $("#pref_lang_let").append('<option selected="selected" value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_let").append('<option value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_let").append('<option value="' + res.pref_lang + '">' + 'English' + '</option>');
                        } else if(res.pref_lang == "SI") {
                            $("#pref_lang_let").append('<option value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_let").append('<option selected="selected" value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_let").append('<option value="' + res.pref_lang + '">' + 'English' + '</option>');
                        } else {
                            $("#pref_lang_let").append('<option value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_let").append('<option value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_let").append('<option selected="selected" value="' + res.pref_lang + '">' + 'English' + '</option>');
                        }

                        if(res.pref_lang == "TA") {
                            $("#letter_template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#letter_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                            $("#letter_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                        } else if(res.pref_lang == "SI") {
                            $("#letter_template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                            $("#letter_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#letter_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                        } else {
                            $("#letter_template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                            $("#letter_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#letter_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                        }

                        $('#letter_complainant_name').val(res.sent_to);
                        $("#letter_complainant_address").val(res.address);
                        $('#letter_heading').val(res.heading);
                        $('.note-editor .note-editable').append(res.mail_body);
                    }
                });
            });


            $('.loadmailsbtn').on('click', function () {
                var mailId = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ url('getSentEMail') }}/" + mailId,
                    dataType: 'JSON',
                    success: function(res) {

                        $(".note-editor .note-editable").empty();
                        $('#pref_lang_mail').empty();
                        $('#sent_to').empty();
                        $('#mail_heading').val('');

                        if(res.pref_lang == "TA") {
                            $("#pref_lang_mail").append('<option selected="selected" value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_mail").append('<option value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_mail").append('<option value="' + res.pref_lang + '">' + 'English' + '</option>');
                        } else if(res.pref_lang == "SI") {
                            $("#pref_lang_mail").append('<option value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_mail").append('<option selected="selected" value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_mail").append('<option value="' + res.pref_lang + '">' + 'English' + '</option>');
                        } else {
                            $("#pref_lang_mail").append('<option value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_mail").append('<option value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_mail").append('<option selected="selected" value="' + res.pref_lang + '">' + 'English' + '</option>');
                        }

                        if(res.pref_lang == "TA") {
                            $("#template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                            $("#template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                        } else if(res.pref_lang == "SI") {
                            $("#template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                            $("#template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                        } else {
                            $("#template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                            $("#template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                        }

                        $('#sent_to').val(res.sent_to);
                        $('#subject').val(res.subject);
                        $('#mail_heading').val(res.heading);
                        $('.note-editor .note-editable').append(res.mail_body);
                    }
                });
            });

          //--------------- ND ---------------------

                $('.nd_for').click(function() {
                $('#nd_heading').val('');
                $('#pref_lang_nd').val('');
                $("#nd_template_id").val('');
                $("#nd_complainant_name").val('');
                $("#nd_complainant_address").val('');
                $(".note-editor .note-editable").empty();
                $("#nd_body").val('');

            });

            $('#pref_lang_nd').change(function() {

                var prefLang = $('#pref_lang_nd').val();
                var id = $('#complainant_id').val();
                var letterFor = $('.nd_for:checked').val();
                var category1 = $('#categ_nd').val();

                if (prefLang) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getcomplainantDetails') }}/" + prefLang + '/'+id,
                        success: function(res) {

                            if (res) {

                                $(".note-editor .note-editable").empty();

                                $("#nd_body").val('');

                                $("#nd_heading").empty();

                                $.each(res, function(key, value) {

                                    if(letterFor == "Complainant") {
                                        if(prefLang == 'TA') {
                                            var title = 'அன்பார்ந்த';
                                            var name = res['complainant_f_name_ta'];
                                            var address = res['complainant_address_ta'];
                                        } else if(prefLang == 'SI') {
                                            var title = 'හිතවත්';
                                            var name = res['complainant_f_name_si'];
                                            var address = res['complainant_address_si'];
                                        } else {
                                            var title = 'Dear';
                                            var name = res['complainant_f_name'];
                                            var address = res['complainant_address'];
                                        }
                                    } else {
                                        if(prefLang == 'TA') {
                                            var title = 'அன்பார்ந்த';
                                            var name = res['employer_name_ta'];
                                            var address = res['employer_address_ta'];
                                        } else if(prefLang == 'SI') {
                                            var title = 'හිතවත්';
                                            var name = res['employer_name_si'];
                                            var address = res['employer_address_si'];
                                        } else {
                                            var title = 'Dear';
                                            var name = res['employer_name'];
                                            var address = res['employer_address'];
                                        }
                                    }

                                    $('#nd_complainant_name').val(name);
                                    $('#nd_complainant_address').val(address);
                                    $('#nd_heading').val(title+' '+name+',');
                                    $('#selected_lang').val(prefLang);
                                });

                            } else {

                                $("#nd_complainant_name").empty();
                                $("#nd_complainant_address").empty();
                            }
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getLetterTempTitle') }}/" + prefLang+ '/'+category1+ '/'+id,
                        success: function(res) {
                            if (res) {

                                $("#nd_template_id").empty();

                                $("#nd_template_id").append('<option>Select Notices and Directives Template</option>');
                                $.each(res, function(key, value) {

                                    if(prefLang == 'TA' ){
                                        var letterTemp = value['mail_template_name_tam'];
                                    } else if(prefLang == 'SI' ) {
                                        var letterTemp = value['mail_template_name_sin'];
                                    } else{
                                             var letterTemp = value['mail_template_name_en'];
                                    }

                                    $("#nd_template_id").append('<option value="' + value['id'] + '">' + letterTemp +
                                    '</option>');
                                });

                            } else {

                                $("#nd_complainant_name").empty();
                                $("#nd_complainant_address").empty();
                                $("#nd_template_id").empty();
                            }
                        }
                    });
                } else {

                    $("#nd_body").empty();
                }
            });

            $('#nd_template_id').change(function() {

                var prefLang = $('#pref_lang_nd').val();
                var ndTemplateID = $('#nd_template_id').val();
                var externalRefno = $('#external_ref_no').val();
                var id = $('#complainant_id').val();
                var ndHeading = $('#nd_heading').val();
                var address = $('#nd_complainant_address').val();

                if (ndTemplateID) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getletterTemplates') }}/" + ndTemplateID + '/'+prefLang+'/'+id,
                        success: function(res) {
                            if (res) {
                             //   console.log(res);
                                // $("#mail_body").val();

                                $(".note-editor .note-editable").empty();

                                $("#nd_body").val('');

                                if(prefLang == 'TA') {
                                    $('#nd_body').val(res[0].body_content_tam);

                                    $('.note-editor .note-editable').append(res[0].body_content_tam);

                                } else if (prefLang == "SI") {
                                    $('#nd_body').val(res[0].body_content_sin);

                                    $('.note-editor .note-editable').append(res[0].body_content_sin);

                                } else {
                                    $('#nd_body').val(res[0].body_content_en);
                                    $('.note-editor .note-editable').append(res[0].body_content_en);
                                }
                                    // }

                            } else {

                                $("#nd_body").empty();
                            }
                        }
                    });
                } else {

                    $("#nd_body").empty();
                }
            });

              $('.btnloadnd').on('click', function () {
                var mailId = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ url('getSentMail') }}/" + mailId,
                    dataType: 'JSON',
                    success: function(res) {

                        $(".note-editor .note-editable").empty();
                        $("#nd_complainant_name").val('');
                        $("#nd_complainant_address").val('');
                        $(".nd_for").empty();
                        $('#pref_lang_nd').empty();
                        $('#nd_template_id').empty();
                        $('#nd_heading').val('');
                        $('#nd_body').val('');

                        if(res.recipient == "Complainant") {
                            $('input[name^="nd_for"][value="Complainant"').prop('checked',true);
                        } else  {
                            $('input[name^="nd_for"][value="Employer"').prop('checked',true);
                         }

                        if(res.pref_lang == "TA") {
                            $("#pref_lang_nd").append('<option selected="selected" value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_nd").append('<option value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_nd").append('<option value="' + res.pref_lang + '">' + 'English' + '</option>');
                        } else if(res.pref_lang == "SI") {
                            $("#pref_lang_nd").append('<option value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_nd").append('<option selected="selected" value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_nd").append('<option value="' + res.pref_lang + '">' + 'English' + '</option>');
                        } else {
                            $("#pref_lang_nd").append('<option value="' + res.pref_lang + '">' + 'Tamil' + '</option>');
                            $("#pref_lang_nd").append('<option value="' + res.pref_lang + '">' + 'Sinhala' + '</option>');
                            $("#pref_lang_nd").append('<option selected="selected" value="' + res.pref_lang + '">' + 'English' + '</option>');
                        }

                        if(res.pref_lang == "TA") {
                            $("#nd_template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#nd_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                            $("#nd_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                        } else if(res.pref_lang == "SI") {
                            $("#nd_template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                            $("#nd_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#nd_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                        } else {
                            $("#nd_template_id").append('<option selected="selected" value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_en + '</option>');
                            $("#nd_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_tam + '</option>');
                            $("#nd_template_id").append('<option value="' + res.template_id + '">' + res.mailtemplatedetails.mail_template_name_sin + '</option>');
                        }

                        $('#nd_complainant_name').val(res.sent_to);
                        $("#nd_complainant_address").val(res.address);
                        $('#nd_heading').val(res.heading);
                        $('.note-editor .note-editable').append(res.mail_body);
                    }
                });
            });


        </script>

    </x-slot>
</x-app-layout>
