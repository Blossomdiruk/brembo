@section('title', 'Edit Complaint')
<x-app-layout>
    <x-slot name="header">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />

        <style>
            .select2-selection__rendered {
                padding-left: 5px !important;
            }
        </style>
    </x-slot>

    @if(Session()->get('applocale')=='ta')
    @php
    $lang = "TA";
    @endphp
    @elseif(Session()->get('applocale')=='si')
    @php
    $lang = "SI";
    @endphp
    @else
    @php
    $lang = "EN";
    @endphp
    @endif

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-lg-4">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        {{ __('registercomplaint.form_title') }}
                    </h1>
                </div>
                <div class="col-lg-8">
                    <ul id="sparks" class="">
                        {{-- <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto;  transform: translate(0%, -12%);">
                                <a href="{{ 'action-pending-list' }}">
                        <h5> {{ __('registercomplaint.action_pending') }} <span class="txt-color-blue" onclick="" style=" text-align: center">{{ $pendingCount }}</span></h5>
                        </a>

                        </li>
                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto; transform: translate(0%, -12%);">
                            <a href="{{ route('investigation-ongoing-list') }}">
                                <h5> {{ __('registercomplaint.investigation_ongoing') }} <span class="txt-color-blue" onclick="" style=" text-align: center">{{ $ongoingCount }}</span></h5>
                            </a>

                        </li>
                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                            <a href="{{ route('legal-certificate-pending-list') }}">
                                <h5> {{ __('registercomplaint.leagle') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $certificateCount }}</span></h5>
                            </a>

                        </li>

                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                            <a href="{{ route('plaint-chargesheet-pending-list') }}">
                                <h5> {{ __('registercomplaint.plaint') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $chargesheetCount }}</span></h5>
                            </a>

                        </li>

                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                            <a href="{{ route('temporary-closed-list') }}">
                                <h5> {{ __('registercomplaint.temp_close') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $tempClosedCount }}</span></h5>
                            </a>

                        </li>


                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 27px 10px !important; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                            <a href="{{ route('closed-list') }}">
                                <h5> {{ __('registercomplaint.close') }} <span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $closedCount }}</span></h5>
                            </a>

                        </li> --}}

                    </ul>
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
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ __('registercomplaint.main_edit_title') }}</h2>
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
                        <form action="{{ route('save-register-complaint') }}" enctype="multipart/form-data" method="post" id="edit-register-complaint-form" class="smart-form" autocomplete="off">
                            @csrf
                            <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                            <div class="widget-body padding-10">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                    <li class="active" id="s1A">
                                        <a href="#s1" onclick="show_submit('T1')" data-toggle="tab">{{ __('registercomplaint.tab_title_one') }} </a>
                                    </li>
                                    <li id="s2B">
                                        <a href="#s2" class="next" onclick="show_submit('T2')" data-toggle="tab">{{ __('registercomplaint.tab_title_two') }} </a>
                                    </li>
                                    <li id="s3C">
                                        <a href="#s3" class="nextII" onclick="show_submit('T3')" data-toggle="tab">{{ __('registercomplaint.tab_title_three') }} </a>
                                    </li>
                                </ul>

                                <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">

                                    <!-------------------------------Tab 1---------------------------------------------------->

                                    <input type="hidden" name="complaint_id" value="{{ $data->id }}">

                                    <div class="tab-pane fade in active" id="s1">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('registercomplaint.comp_type') }}</label>
                                                        <div class="inline-group ">
                                                            <label class="radio">
                                                                <input type="radio" id="N" value="N" name="comp_type" onclick="unionfields();anonoumousfieldhidden();" {{ $data->comp_type == 'N' ? 'checked':'' }}>
                                                                <i></i>{{ __('registercomplaint.normal') }}
                                                            </label>
                                                            <label class="radio" id="anou" style="">
                                                                <input type="radio" id="A" value="A" name="comp_type" onclick="unionfields();anonoumousfieldhidden();" {{ $data->comp_type == 'A' ? 'checked':'' }}>
                                                                <i></i>{{ __('registercomplaint.anonymous') }}
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" id="U" value="U" name="comp_type" onclick="unionfields();anonoumousfieldhidden();" {{ $data->comp_type == 'U' ? 'checked':'' }}>
                                                                <i></i>{{ __('registercomplaint.union') }}
                                                            </label>
                                                        </div>
                                                    </section>

                                                    <section class="col col-2">
                                                        <label class="label">{{ __('registercomplaint.pref_lang') }}</label>
                                                        <label class="select">
                                                            <select id="pref_lang" name="pref_lang">
                                                                <option value="EN" {{ $data->pref_lang == 'EN' ? "selected" : "" }}>{{ __('registercomplaint.english') }}</option>
                                                                <option value="SI" {{ $data->pref_lang == 'SI' ? "selected" : "" }}>{{ __('registercomplaint.sinhala') }}</option>
                                                                <option value="TA" {{ $data->pref_lang == 'TA' ? "selected" : "" }}>{{ __('registercomplaint.tamil') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section>
                                                    <?php $arrSerial = explode('/', $data->ref_no);
                                                        if ($arrSerial) {
                                                            $array1 = $arrSerial[0].'/'.$arrSerial[1].'/';
                                                            $array3 = '/'.$arrSerial[3];
                                                            $array2 = $arrSerial[2];
                                                            $array4 = '/'.$arrSerial[4].'/'.$arrSerial[5];
                                                        } ?>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.ref_no') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                        <section class="col col-4">
                                                            <input style="margin-left:-13px;" type="text" id="ref_no" name="ref_no" value="{{ $array1 }}" readonly>
                                                        </section>
                                                        <section class="col col-2">
                                                            <input style="margin-left:-13px;" type="text" id="ref_no1" name="ref_no1" maxlength="2" value="{{ $array2 }}">
                                                        </section>
                                                        <section class="col col-2">
                                                            <input style="margin-left:-13px;" type="text" id="ref_no2" name="ref_no2" maxlength="2" value="{{ $array3 }}" required>
                                                        </section>
                                                        <section class="col col-4">
                                                            <input style="margin-left:-13px;" type="text" id="ref_no3" name="ref_no3" value="{{ $array4 }}" readonly>
                                                            </section>
                                                        </label>
                                                    </section>
                                                </div>
                                                <div id="officer" style="display:none;">
                                                    <div class="row">
                                                        <section class="col col-6">
                                                            <label class="label">{{ __('registercomplaint.union_name') }}<span style="color: #FF0000;">*</span></label>
                                                            <label class="input">
                                                                <input type="tel" id="union_name" name="union_name" value="{{ $data->union_name }}">
                                                            </label>
                                                        </section>

                                                        <section class="col col-6">
                                                            <label class="label">{{ __('registercomplaint.union_address') }}<span style="color: #FF0000;">*</span></label>
                                                            <label class="textarea">
                                                                <textarea id="union_address" name="union_address" style="height: 32px;">{{ $data->union_address }}</textarea>
                                                            </label>
                                                        </section>
                                                    </div>
                                                    <section class="">
                                                        <label class="label">{{ __('registercomplaint.details_union_chairmen') }}</label>
                                                    </section>

                                                    <section class="col col-12" style="margin-bottom: 20px; padding-left: 0px; padding-right: 0px;">
                                                        <p>{{ __('registercomplaint.union_description') }}</p>
                                                    </section>
                                                    <br>
                                                    <div class="cleafix"></div>

                                                    <div class="row">
                                                        <div class="input-group hdtuto2 control-group lst increment2" style="width: 100%;">
                                                            <section class="col col-5">
                                                                <label class="label">{{ __('registercomplaint.union_officer_name') }}</label>
                                                                <label class="input">
                                                                    <input type="text" id="union_officer_name" name="union_officer_name[]" value="">
                                                                </label>
                                                            </section>

                                                            <section class="col col-6">
                                                                <label class="label">{{ __('registercomplaint.union_officer_address') }}</label>
                                                                <label class="textarea">
                                                                    <textarea style="height: 32px;" id="union_officer_address" name="union_officer_address[]"></textarea>
                                                                </label>
                                                            </section>
                                                            <section class="col col-1">
                                                                <div class="input-group-btn">
                                                                    <button class="btn btn-info btn-sm" id="add" type="button" style="background-color: #5D98CC;height: 32px; width: 100%; margin-top:23px; padding :7px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;{{ __('registercomplaint.add') }}</button>
                                                                </div>
                                                            </section>

                                                        </div>
                                                    </div>
                                                    <div class="clone2 hide">
                                                        <div class="hdtuto2 control-group lst input-group" style="margin-top:10px; width: 100%;">
                                                            <section class="col col-5">
                                                                <label class="label">{{ __('registercomplaint.union_officer_name') }}</label>
                                                                <label class="input">
                                                                    <input type="text" id="union_officer_name" name="union_officer_name[]" value="">
                                                                </label>
                                                            </section>

                                                            <section class="col col-6">
                                                                <label class="label">{{ __('registercomplaint.union_officer_address') }}</label>
                                                                <label class="textarea">
                                                                    <textarea style="height: 32px;" id="union_officer_address" name="union_officer_address[]"></textarea>
                                                                </label>
                                                            </section>
                                                            <section class="col col-1">
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-danger" id="remove" style="height: 32px; width: 100%; margin-top:23px; padding :7px; border-color:  #383838"><i class="glyphicon glyphicon-remove"></i>&nbsp;{{ __('registercomplaint.remove') }}</button>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>

                                                    @if($unionofficers->isNotEmpty())
                                                    <div class="row">
                                                        <section class="col col-lg-11">
                                                            <label class="label">{{ __('registercomplaint.added_union_officers') }}</label>
                                                            <div class="table-bordered">

                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="40%">{{ __('registercomplaint.officer_name') }}</th>
                                                                            <th width="50%">{{ __('registercomplaint.office_address') }}</th>
                                                                            <th width="10%" style="text-align:center">{{ __('registercomplaint.delete') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    @foreach ($unionofficers as $unionofficer)
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>{{ $unionofficer->officer_name }} </td>
                                                                            <td>{{ $unionofficer->officer_address }}</td>
                                                                            <td style="text-align:center"><button class="delete-officer-confirm" value="{{ $unionofficer->id }}"><i id="545" class="fa fa-trash-o trash"></i></button></td>
                                                                        </tr>
                                                                    </tbody>
                                                                    @endforeach
                                                                </table>
                                                            </div>
                                                        </section>
                                                    </div>
                                                    @endif

                                                </div>
                                                <br>
                                                <div class="cleafix"></div>
                                                <div class="row">
                                                    <section class="col col-1" style="padding-right: 0px;">
                                                        <label class="label">{{ __('registercomplaint.title') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="select">
                                                            <select id="title" name="title" {{ $data->comp_type != 'A' ? 'required':'' }}>
                                                                <option value=""> </option>
                                                                <option value="0" hidden> </option>
                                                                <option value="1" {{ $data->title == '1' ? "selected" : "" }}>{{ __('registercomplaint.mr') }}</option>
                                                                <option value="2" {{ $data->title == '2' ? "selected" : "" }}>{{ __('registercomplaint.miss') }}</option>
                                                                <option value="3" {{ $data->title == '3' ? "selected" : "" }}>{{ __('registercomplaint.mrs') }}</option>
                                                                <option value="4" {{ $data->title == '4' ? "selected" : "" }}>{{ __('registercomplaint.rev') }}</option>
                                                                <option value="5" {{ $data->title == '5' ? "selected" : "" }}>{{ __('registercomplaint.dr') }}</option>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-5">
                                                        <label class="label" id="comp_fname">{{ __('registercomplaint.complainant_f_name') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="label" id="contact_fname">{{ __('registercomplaint.contact_fname') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_f_name" name="complainant_f_name" class="required" value="@if($data->pref_lang == "SI"){{ $data->complainant_f_name_si }}@elseif($data->pref_lang == "TA"){{ $data->complainant_f_name_ta }}@else{{ $data->complainant_f_name }}@endif" required>
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label" id="comp_lname">{{ __('registercomplaint.complainant_l_name') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="label" id="contact_lname">{{ __('registercomplaint.contact_lname') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_l_name" name="complainant_l_name" class="required" value="@if($data->pref_lang == "SI"){{ $data->complainant_l_name_si }}@elseif($data->pref_lang == "TA"){{ $data->complainant_l_name_ta }}@else{{ $data->complainant_l_name }}@endif" required>
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6" id="fullname">
                                                        <label class="label">{{ __('registercomplaint.complainant_full_name') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_full_name" name="complainant_full_name" class="required" value="{{ $data->complainant_full_name }}" required>
                                                        </label>
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_identify_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_identify_no" name="complainant_identify_no" <?php if($data->comp_type != 'A'){?>pattern=".{8,12}"<?php } ?>  value="{{ $data->complainant_identify_no }}" >
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_dob') }} </label>
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="complainant_dob" name="complainant_dob" value="{{ $data->complainant_dob }}" class="datepicker" dateformat='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_gender') }}</label>
                                                        <label class="select">
                                                            <select id="complainant_gender" name="complainant_gender">
                                                                <option value=""> </option>
                                                                <option value="M" {{ $data->complainant_gender == 'M' ? "selected" : "" }}>{{ __('registercomplaint.male') }}</option>
                                                                <option value="F" {{ $data->complainant_gender == 'F' ? "selected" : "" }}>{{ __('registercomplaint.female') }}</option>
                                                            </select> <i></i>
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.nationality') }}</label>
                                                        <label class="input">

                                                            <input type="text" id="nationality" name="nationality" value="{{ $data->nationality }}" @if($lang == "EN")pattern="^[A-Za-z -]+$"@endif>


                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_email') }} </label>
                                                        <label class="input">
                                                            <input type="email" id="complainant_email" name="complainant_email" value="{{ $data->complainant_email }}">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_mobile') }}</label>
                                                        <label class="input">
                                                            <input type="tel" id="complainant_mobile" name="complainant_mobile" class="required" value="{{ $data->complainant_mobile }}" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_tel') }}</label>
                                                        <label class="input">
                                                            <input type="tel" id="complainant_tel" name="complainant_tel" value="{{ $data->complainant_tel }}" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label" id="comp_address">{{ __('registercomplaint.complainant_address') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="label" id="contact_address">{{ __('registercomplaint.contact_address') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="complainant_address" name="complainant_address" required>{{ $data->complainant_address }}</textarea>
                                                        </label>
                                                    </section>
                                                </div>

                                                <footer style="background-color: #fff; border-top: transparent;">
                                                    <a href="#s2" id="testing" class="test" onclick="show_submit('T2');changeactive('s2B', 's1A');" data-toggle="tab">
                                                        <button type="button" id="testing" class="btn btn-primary next test"> {{ __('registercomplaint.next') }} </button>
                                                    </a>
                                                </footer>


                                            </fieldset>
                                        </div>
                                    </div>

                                    <!---------------------------------Tab 2---------------------------------------------------->

                                    <div class="tab-pane fade" id="s2">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label" style="font-weight: bold;">{{ __('registercomplaint.current_employer_details') }}</label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_name') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="current_employer_name" name="current_employer_name" class="required" value="{{ $data->current_employer_name }}" required>
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_address') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="current_employer_address" name="current_employer_address" class="required" value="{{ $data->current_employer_address }}" required>
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_tel') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="current_employer_tel" name="current_employer_tel" value="{{ $data->current_employer_tel }}" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.province_id') }} <span style="color: #FF0000;">*</span></label>
                                                        {{-- <label class="select"> --}}
                                                            <select id="province_id" name="province_id" class="required select2" required>
                                                                <option value="">@if($lang == "SI"){{ "පළාත තෝරන්න" }}@elseif($lang == "TA"){{ "மாகாணத்தைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select Province" }}@endif </option>
                                                                @foreach ($provinces as $province)
                                                                <option value="{{ $province->id }}" {{$data->province_id == $province->id ? 'selected' : ''}}>@if($lang == "TA"){{ $province->province_name_tamil }}@elseif ($lang == "SI"){{ $province->province_name_sin }}@else{{ $province->province_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                    </section>
                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.zone_id') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="select">
                                                            <select id="zone_id" name="zone_id" class="required">
                                                                <option value="">Select Zone </option>
                                                                @foreach ($zone as $zn)
                                                                <option value="{{ $zn->id }}">@if($lang == "TA"){{ $zn->office_name_tam }}@elseif ($lang == "SI"){{ $zn->office_name_sin }}@else{{ $zn->office_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        </label>
                                                    </section> -->
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.district_id') }} <span style="color: #FF0000;">*</span></label>
                                                        {{-- <label class="select"> --}}
                                                            <select id="district_id" name="district_id" class="select2" required>
                                                                <option value="">@if($lang == "SI"){{ "දිස්ත්‍රික්කය තෝරන්න" }}@elseif($lang == "TA"){{ "மாவட்டத்தைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select District" }}@endif </option>
                                                                @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}" {{$data->district_id == $district->id ? 'selected' : ''}}>@if($lang == "TA"){{ $district->district_name_tamil }}@elseif ($lang == "SI"){{ $district->district_name_sin }}@else{{ $district->district_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.city_id') }} <span style="color: #FF0000;">*</span></label>
                                                        {{-- <label class="select"> --}}
                                                            <select id="city_id" name="city_id" class="select2" required>
                                                                <option value="">@if($lang == "SI"){{ "නගරය තෝරන්න" }}@elseif($lang == "TA"){{ "நகரத்தைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select City" }}@endif </option>
                                                                @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}" {{$data->city_id == $city->id ? 'selected' : ''}}>@if($lang == "TA"){{ $city->city_name_tam }}@elseif ($lang == "SI"){{ $city->city_name_sin }}@else{{ $city->city_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.no_of_employees') }}</label>
                                                        <select id="worked_employees" name="worked_employees" class="select2">
                                                            <option value=""> </option>
                                                            @if($lang == "SI")
                                                                <option value="1" {{$data->worked_employees == '1' ? 'selected' : ''}}>සේවකයින් 1-14</option>
                                                                <option value="2" {{$data->worked_employees == '2' ? 'selected' : ''}}>සේවකයින් 15-50</option>
                                                                <option value="3" {{$data->worked_employees == '3' ? 'selected' : ''}}>සේවකයින් 51-200</option>
                                                                <option value="4" {{$data->worked_employees == '4' ? 'selected' : ''}}>සේවකයින් 201-500</option>
                                                                <option value="5" {{$data->worked_employees == '5' ? 'selected' : ''}}>සේවකයින් 501-1000</option>
                                                                <option value="6" {{$data->worked_employees == '6' ? 'selected' : ''}}>සේවකයින් 1001-5000</option>
                                                                <option value="7" {{$data->worked_employees == '7' ? 'selected' : ''}}>සේවකයින් 5001-10,000</option>
                                                                <option value="8" {{$data->worked_employees == '8' ? 'selected' : ''}}>සේවකයින් 10,001+</option>
                                                            @elseif($lang == "TA")
                                                                <option value="1" {{$data->worked_employees == '1' ? 'selected' : ''}}>1-14 பணியாளர்கள்</option>
                                                                <option value="2" {{$data->worked_employees == '2' ? 'selected' : ''}}>15-50 பணியாளர்கள்</option>
                                                                <option value="3" {{$data->worked_employees == '3' ? 'selected' : ''}}>51-200 பணியாளர்கள்</option>
                                                                <option value="4" {{$data->worked_employees == '4' ? 'selected' : ''}}>201-500 பணியாளர்கள்</option>
                                                                <option value="5" {{$data->worked_employees == '5' ? 'selected' : ''}}>501-1000 பணியாளர்கள்</option>
                                                                <option value="6" {{$data->worked_employees == '6' ? 'selected' : ''}}>1001-5000 பணியாளர்கள்</option>
                                                                <option value="7" {{$data->worked_employees == '7' ? 'selected' : ''}}>5001-10,000 பணியாளர்கள்</option>
                                                                <option value="8" {{$data->worked_employees == '8' ? 'selected' : ''}}>10,001+ பணியாளர்கள்</option>
                                                            @else
                                                                <option value="1" {{$data->worked_employees == '1' ? 'selected' : ''}}>1-14 employees</option>
                                                                <option value="2" {{$data->worked_employees == '2' ? 'selected' : ''}}>15-50 employees</option>
                                                                <option value="3" {{$data->worked_employees == '3' ? 'selected' : ''}}>51-200 employees</option>
                                                                <option value="4" {{$data->worked_employees == '4' ? 'selected' : ''}}>201-500 employees</option>
                                                                <option value="5" {{$data->worked_employees == '5' ? 'selected' : ''}}>501-1000 employees</option>
                                                                <option value="6" {{$data->worked_employees == '6' ? 'selected' : ''}}>1001-5000 employees</option>
                                                                <option value="7" {{$data->worked_employees == '7' ? 'selected' : ''}}>5001-10,000 employees</option>
                                                                <option value="8" {{$data->worked_employees == '8' ? 'selected' : ''}}>10,001+ employees</option>
                                                            @endif
                                                        </select> <i></i>
                                                        {{-- <label class="input">
                                                            <input type="number" id="worked_employees" name="worked_employees" value="{{ $data->worked_employees }}" >
                                                        </label> --}}
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.empaddress_description') }}</span></label> --}}
                                                    </section>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label" style="font-weight: bold;">{{ __('registercomplaint.employer_details') }}</label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="checkbox">
                                                            <input type="checkbox" id="duplicate_data" name="duplicate_data" value="1"> <i></i>{{ __('registercomplaint.same_as_above') }}
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_name') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="employer_name" name="employer_name" class="required" value="{{ $data->employer_name }}" required>
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_address') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="employer_address" name="employer_address" class="required" value="{{ $data->employer_address }}" required>
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_tel') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employer_tel" name="employer_tel" value="{{ $data->employer_tel }}" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.business_nature') }}</label>
                                                        {{-- <label class="select"> --}}
                                                            <select class="select2" id="business_nature" name="business_nature">
                                                                <option value="">@if($lang == "SI"){{ "ව්‍යාපාරයේ ස්වභාවය තෝරන්න" }}@elseif($lang == "TA"){{ "வணிகத்தின் தன்மையைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select Nature of the Business" }}@endif </option>
                                                                @foreach ($businessnatures as $businessnature)
                                                                <option value="{{ $businessnature->id }}" {{$data->business_nature_id == $businessnature->id ? 'selected' : ''}}>@if($lang == "TA"){{ $businessnature->business_nature_ta }}@elseif ($lang == "SI"){{ $businessnature->business_nature_si }}@else{{ $businessnature->business_nature_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.establishment_type_id') }}</label>
                                                        {{-- <label class="select"> --}}
                                                            <select class="select2" id="establishment_type_id" name="establishment_type_id">
                                                            <option value="">@if($lang == "SI"){{ "ආයතන වර්ගය තෝරන්න" }}@elseif($lang == "TA"){{ "ஸ்தாபன வகையைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select Establishment Type" }}@endif </option>
                                                                @foreach ($establishmenttypes as $establishmenttype)
                                                                <option value="{{ $establishmenttype->id }}" {{$data->establishment_type_id == $establishmenttype->id ? 'selected' : ''}}>@if($lang == "TA"){{ $establishmenttype->establishment_name_tam }}@elseif ($lang == "SI"){{ $establishmenttype->establishment_name_sin }}@else{{ $establishmenttype->establishment_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                    </section>
                                                    {{-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_no') }} </label>
                                                    <label class="input">
                                                        <input type="text" id="employer_no" name="employer_no" value="{{ $data->employer_no }}">
                                                    </label>
                                                    </section> --}}

                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.establishment_reg_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="establishment_reg_no" name="establishment_reg_no"  value="{{ $data->establishment_reg_no }}">
                                                        </label>
                                                    </section> -->
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.epf_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="epf_no" name="epf_no" value="{{ $data->epf_no }}">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employee_mem_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employee_mem_no" name="employee_mem_no" value="{{ $data->employee_mem_no }}">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.designation') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="designation" name="designation" value="{{ $data->designation }}">
                                                        </label>
                                                    </section>

                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.ppe_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="ppe_no" name="ppe_no"  value="">
                                                        </label>
                                                    </section> -->
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.join_date') }}</label>
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="join_date" name="join_date" value="{{ $data->join_date }}" class="datepicker" data-dateformat='yy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                                        </label>
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.terminate_date') }}</label>
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="terminate_date" name="terminate_date" value="{{ $data->terminate_date }}" class="datepicker" data-dateformat='yy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.last_sal_date') }}</label>
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="last_sal_date" name="last_sal_date" value="{{ $data->last_sal_date }}" class="datepicker" data-dateformat='yy-mm' placeholder="YYYY-MM">
                                                        </label>
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.basic_sal') }}</label>
                                                        <label class="input">
                                                            <input type="text" step=".01" id="basic_sal" name="basic_sal" value="{{ number_format($data->basic_sal,2); }}" onkeydown="numberWithCommas(this)">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.allowance') }}</label>
                                                        <label class="input">
                                                            <input type="text" step=".01" id="allowance" name="allowance" value="{{ number_format($data->allowance,2); }}" onkeydown="numberWithCommas(this)">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label"> {{ __('registercomplaint.is_available') }}</label>
                                                        <div class="inline-group ">
                                                            <label class="radio">
                                                                <input type="radio" value="1" name="is_available" id="yes" onclick="complainavailable();" {{ $data->is_available == '1' ? 'checked':'' }}>
                                                                <i></i>{{ __('registercomplaint.yes') }}
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" value="0" name="is_available" id="no" onclick="complainavailable();" {{ $data->is_available == '0' ? 'checked':'' }}>
                                                                <i></i>{{ __('registercomplaint.no') }}
                                                            </label>
                                                        </div>
                                                    </section>
                                                </div>

                                                <div id="existcomplain">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                            <label class="label">{{ __('registercomplaint.submitted_office') }}</label>
                                                            <label class="input">
                                                                {{-- <select id="submitted_office" name="submitted_office">
                                                                    <option value="">Select Submitted Office </option>
                                                                    @foreach ($officedivisions as $key => $officedivision)
                                                                        <option value="{{ $key }}" {{ $data->submitted_office == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                                @endforeach
                                                                </select> <i></i> --}}
                                                                <input type="text" id="submitted_office" name="submitted_office" value="{{ $data->submitted_office }}">
                                                            </label>
                                                        </section>

                                                        <section class="col col-4">
                                                            <label class="label">{{ __('registercomplaint.submitted_date') }}</label>
                                                            <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                                <input type="text" id="submitted_date" name="submitted_date" value="{{ $data->submitted_date }}" class="datepicker" data-dateformat='yy-mm-dd'>
                                                            </label>
                                                        </section>

                                                        <section class="col col-4">
                                                            <label class="label">{{ __('registercomplaint.case_no') }}</label>
                                                            <label class="input">
                                                                <input type="text" id="case_no" name="case_no" value="{{ $data->case_no }}">
                                                            </label>
                                                        </section>
                                                    </div>

                                                    <div class="row">
                                                        <section class="col col-lg-12">
                                                            <label class="label">{{ __('registercomplaint.received_relief') }}</label>
                                                            <label class="textarea">
                                                                <textarea rows="5" class="custom scrollspy-example" name="received_relief">{{ $data->received_relief }}</textarea>
                                                            </label>
                                                        </section>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="label">{{ __('registercomplaint.complain_category_id') }} <span style="color: #FF0000;">*</span></label>
                                                        @foreach ($complaincategories as $complaincategory)
                                                        <div class="col col-4">
                                                            <label class="checkbox">
                                                                <input type="checkbox" name="complain_category_id[]" required value="{{ $complaincategory->id }}" @foreach ($categorydetails as $categorydetail) @if($complaincategory->id == $categorydetail->category_id )
                                                                {{ "checked" }}
                                                                @endif
                                                                @endforeach> <i></i>@if($lang == "SI"){{ $complaincategory->category_name_si }}@elseif($lang == "TA"){{ $complaincategory->category_name_ta }}@else{{ $complaincategory->category_name_en }}@endif
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </section>
                                                </div>
                                                @foreach ($categorydetails as $categorydetail)
                                                <input type="hidden" name="cat_id" value="{{ $categorydetail->id }}">
                                                @endforeach


                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="label">{{ __('registercomplaint.complain_purpose') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="5" class="custom scrollspy-example" name="complain_purpose">{{ $data->complain_purpose }}</textarea>
                                                        </label>
                                                    </section>
                                                </div>

                                                <footer style="background-color: #fff; border-top: transparent;">

                                                    <a href="#s3" id="test" onclick="show_submit('T3');changeactive('s3C', 's2B');" data-toggle="tab">
                                                        <button type="button" id="test" class="btn btn-primary nextII"> {{ __('registercomplaint.next') }} </button>
                                                    </a>

                                                    <a href="#s1" onclick="show_submit('T1');changeactive('s1A', 's2B');" data-toggle="tab">
                                                        <button type="button" class="btn btn-default"> {{ __('registercomplaint.back_cms') }} </button>
                                                    </a>
                                                </footer>

                                            </fieldset>

                                        </div>
                                    </div>

                                    <!-------------------------------Tab 3---------------------------------------------------->
                                    <div class="tab-pane fade" id="s3">

                                        <div class="widget-body no-padding">

                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.support_files') }}</label>
                                                        {{-- @foreach ($complaintdocuments as $complaintdocument) --}}
                                                        <div class="input-group hdtuto control-group lst increment">
                                                            <input type="file" name="files[]" class="myfrm form-control" value="">
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-info btn-sm" id="addrow" type="button" style="background-color: #5D98CC; height: 40px; width: 102px;"><i class="glyphicon glyphicon-plus"></i>{{ __('registercomplaint.add') }}</button>
                                                            </div>
                                                        </div>
                                                        {{-- @endforeach --}}
                                                    </section>

                                                    <div class="clone hide">
                                                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                                            <input type="file" name="files[]" class="myfrm form-control">
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-danger" id="remrow" type="button" style="margin-top: 0px; height: 40px; width: 102px; border-color:  #383838"><i class="glyphicon glyphicon-remove"></i> {{ __('registercomplaint.remove') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <section class="col col-lg-11">
                                                    <label class="label">{{ __('registercomplaint.recently_upload_report') }}</label>
                                                    <div class="table-bordered">

                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th width="40%">{{ __('registercomplaint.file_name') }}</th>
                                                                    <th width="50%">{{ __('registercomplaint.description') }}</th>
                                                                    <th width="10%" style="text-align:center">{{ __('registercomplaint.delete') }}</th>
                                                                </tr>
                                                            </thead>
                                                            @foreach ($complaintdocuments as $complaintdocument)
                                                            <tbody>
                                                                <tr>
                                                                    <td> <a href="{{ '../storage/app/'.$complaintdocument->file_name }}" target="_blank" title="View">{{ $complaintdocument->description }}</a> </td>
                                                                    <td></td>
                                                                    <td style="text-align:center"><button class="delete-confirm" value="{{ $complaintdocument->id }}"><i id="545" class="fa fa-trash-o trash"></i></button></td>
                                                                </tr>

                                                            </tbody>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </section>

                                                <br>
                                                <div class="row">
                                                    <section class="col col-12">
                                                        <p id="error-msg" style="color: red; display:none;">{{ __('registercomplaint.mark_fields') }} </p>
                                                    </section>
                                                </div>
                                                <footer>
                                                    <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                                        {{ __('registercomplaint.submit') }}
                                                    </button>

                                                    <a href="#s2" onclick="show_submit('T1');changeactive('s2B', 's3C');" data-toggle="tab">
                                                        <button type="submit" class="btn btn-default"> {{ __('registercomplaint.back_cms') }} </button>
                                                    </a>
                                                </footer>

                                            </fieldset>

                                        </div>
                                    </div>

                                    <!--------------------------------------------------- end Tab 3-------------------------------->
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            var comType = '<?php echo $data->comp_type; ?>';
            if (comType == "U") {
                $("#officer").show();
                $("#fullname").hide();
                $("#complainant_full_name").val('');
                $("#complainant_full_name").attr("required", false);
                $("#union_name").attr("required", true);
                $("#union_address").attr("required", true);
                $("#contact_fname").show();
                $("#contact_lname").show();
                $("#contact_address").show();
                $("#comp_fname").hide();
                $("#comp_lname").hide();
                $("#comp_address").hide();
            } else {
                $("#officer").hide();
                $("#fullname").show();
                $("#complainant_full_name").attr("required", true);
                $("#union_name").attr("required", false);
                $("#union_address").attr("required", false);
                $("#contact_fname").hide();
                $("#contact_lname").hide();
                $("#contact_address").hide();
                $("#comp_fname").show();
                $("#comp_lname").show();
                $("#comp_address").show();
            }

            if (comType == "A") {
                $("#title").val(0);
            }


        // $(document).ready(function(){

        //     $("#contact_fname").hide();
        //     $("#contact_lname").hide();
        //     $("#contact_address").hide();

        // });

        $(document).ready(function () {

            $('#union_address').blur(function () {
                    var unionAddress = $(this).val();
                    var unionName = $('#union_name').val();

                    $('#complainant_f_name').val(unionName);
                    $('#complainant_address').val(unionAddress);
            });

        });

            var currentDate = new Date();
            $('#complainant_dob').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                autoclose: true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $('#complainant_dob').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function() {
            var currentDate = new Date();
            $('#join_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                autoclose: true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $('#join_date').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function() {
            var currentDate = new Date();
            $('#terminate_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                autoclose: true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $('#terminate_date').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function() {
            var currentDate = new Date();
            $('#last_sal_date').datepicker({
                format: 'yyyy-mm',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                autoclose: true,
                endDate: "currentDate",
                maxDate: currentDate,
                startView: "months",
                minViewMode: "months",
            }).on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $('#last_sal_date').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function() {
            var currentDate = new Date();
            $('#submitted_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                autoclose: true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });
            $('#submitted_date').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });
    </script>


    <script>

        // function numberWithCommas(x) {
        //     setTimeout(function(){
        //         if(x.value.lastIndexOf(".")!=x.value.length-1){
        //         var a = x.value.replace(",","");
        //         var nf = new Intl.NumberFormat();
        //         // console.log(nf.format(a));
        //         x.value = nf.format(a);
        //         }
        //     },1);
        // }

        var myinput = document.getElementById('allowance');

            myinput.addEventListener('keyup', function() {
            var val = this.value;
            val = val.replace(/[^0-9\.]/g,'');

            if(val != "") {
                valArr = val.split('.');
                valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
                val = valArr.join('.');
            }

            this.value = val;
            });

        var myinput = document.getElementById('basic_sal');

            myinput.addEventListener('keyup', function() {
            var val = this.value;
            val = val.replace(/[^0-9\.]/g,'');

            if(val != "") {
                valArr = val.split('.');
                valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
                val = valArr.join('.');
            }

            this.value = val;
        });

    </script>

    <script>
        // $(".select2").select2();
    </script>


    <x-slot name="script">
        <script>
            $(function() {
                //window.ParsleyValidator.setLocale('ta');
                // $('#register-complaint-form').parsley();
                $("#error-msg").hide();
                $('#edit-register-complaint-form').parsley().on('form:error', function(formInstance) {
                    $("#error-msg").show();
                });
            });


            $('#province_id').change(function() {

                var provinceID = $(this).val();
                var lang = $('#lang').val();

                if (provinceID) {

                    /*
                    $.ajax({
                        type: "GET",
                        url: "{{ url('getZone') }}?province_id=" + provinceID,
                        success: function(res) {

                            if (res) {
                                // console.log(res);
                                $("#zone_id").empty();
                                $("#zone_id").append('<option value="">Select Zone</option>');
                                $.each(res, function(key, value) {

                                    if (lang == "SI") {

                                        $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_sin'] +
                                            '</option>');

                                    } else if (lang == "TA") {

                                        $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_tam'] +
                                            '</option>');

                                    } else {

                                        $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_en'] +
                                            '</option>');
                                    }

                                });

                            } else {

                                $("#zone_id").empty();
                            }
                        }
                    });
                    */

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getDistrict') }}?province_id=" + provinceID,
                        success: function(res) {

                            if (res) {
                                // console.log(res);
                                $("#district_id").empty();
                                $("#district_id").append('<option value="">Select District</option>');
                                $.each(res, function(key, value) {

                                    if (lang == "SI") {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_sin'] +
                                            '</option>');

                                    } else if (lang == "TA") {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_tam'] +
                                            '</option>');

                                    } else {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_en'] +
                                            '</option>');
                                    }

                                });

                            } else {

                                $("#district_id").empty();
                            }
                        }
                    });
                } else {

                    $("#district_id").empty();
                    $("#city_id").empty();
                }
            });

            $('#district_id').on('change', function() {

                var districtID = $(this).val();
                var lang = $('#lang').val();


                if (districtID) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getCityforOffice') }}?district_id=" + districtID,
                        success: function(res) {

                            if (res) {
                                $("#city_id").empty();
                                $("#city_id").append('<option value="">Select City</option>');
                                $.each(res, function(key, value) {

                                    if (lang == "SI") {

                                        $("#city_id").append('<option value="' + value['id'] + '">' + value['city_name_sin'] +
                                            '</option>');

                                    } else if (lang == "TA") {

                                        $("#city_id").append('<option value="' + value['id'] + '">' + value['city_name_tam'] +
                                            '</option>');

                                    } else {

                                        $("#city_id").append('<option value="' + value['id'] + '">' + value['city_name_en'] +
                                            '</option>');
                                    }

                                });

                            } else {

                                $("#city_id").empty();
                            }
                        }
                    });
                } else {

                    $("#city_id").empty();
                }
            });
        </script>

        <script type="text/javascript">
            function anonoumousfieldhidden() {
                if (document.getElementById("A").checked) {
                    $('#title').attr('readonly', 'readonly').val('0');
                    $('#complainant_f_name').attr('readonly', 'readonly').val('Anonoumous');
                    $('#complainant_l_name').attr('readonly', 'readonly').val('Complaint');
                    $('#complainant_full_name').attr('readonly', 'readonly').val('Anonoumous');
                    $('#complainant_address').attr('readonly', 'readonly').val('N/A');
                    $('#complainant_identify_no').attr('readonly', 'readonly').val('N/A         ');
                    $('#complainant_dob').attr('readonly', 'readonly');
                    $('#complainant_mobile').attr('readonly', 'readonly');
                    $('#complainant_tel').attr('readonly', 'readonly');
                    $('#complainant_gender').attr('readonly', 'readonly');
                    $('#nationality').attr('readonly', 'readonly');
                    $('#complainant_email').attr('readonly', 'readonly');
                    $('#union_name').val('');
                    $('#union_address').val('');

                } else {

                    $('#title').removeAttr('readonly');
                    $('#complainant_f_name').removeAttr('readonly').val('');
                    $('#complainant_l_name').removeAttr('readonly').val('');
                    $('#complainant_full_name').removeAttr('readonly').val('');
                    $('#complainant_address').removeAttr('readonly').val('');
                    $('#complainant_identify_no').removeAttr('readonly').val('');
                    $('#complainant_dob').removeAttr('readonly');
                    $('#complainant_mobile').removeAttr('readonly');
                    $('#complainant_tel').removeAttr('readonly');
                    $('#complainant_gender').removeAttr('readonly');
                    $('#nationality').removeAttr('readonly');
                    $('#complainant_email').removeAttr('readonly');
                    $('#union_name').val('');
                    $('#union_address').val('');
                }

            }
        </script>

        <script>
            $("#duplicate_data").change(function() {
                if(this.checked) {
                    var employerName = $('#current_employer_name').val();
                    var employerAddress = $('#current_employer_address').val();
                    var employerContactNo = $('#current_employer_tel').val();

                    $('#employer_name').val(employerName);
                    $('#employer_address').val(employerAddress);
                    $('#employer_tel').val(employerContactNo);
                } else {
                    $('#employer_name').val('');
                    $('#employer_address').val('');
                    $('#employer_tel').val('');
                }
            });
        </script>

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('.delete-confirm').on('click', function(event) {
                event.preventDefault();
                const url = '<?php echo  url('/')  ?>';
                var id = $(this).val();
                swal({
                    title: 'Are you sure?',
                    text: 'This record and it`s details will be permanantly deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                }).then(function(value) {
                    if (value == true) {
                        window.location.replace("/edit-register-complaint/delete/" + id);
                    }
                });
            });
        </script>

        <script>
            $('.delete-officer-confirm').on('click', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');
                var id = $(this).val();
                swal({
                    title: 'Are you sure?',
                    text: 'This record and it`s details will be permanantly deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                }).then(function(value) {
                    if (value == true) {
                        window.location.replace("/edit-register-complaint/delete-officer/" + id);
                    }
                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("#addrow").click(function() {
                    var lsthmtl = $(".clone").html();
                    $(".increment").after(lsthmtl);
                });
                $("body").on("click", "#remrow", function() {
                    $(this).parents(".hdtuto").remove();
                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                var i = 0;
                $("#add").click(function() {
                    ++i;
                    var lsthmtl2 = $(".clone2").html();
                    $(".increment2").after(lsthmtl2);
                });
                $("body").on("click", "#remove", function() {
                    $(this).parents(".hdtuto2").remove();
                });
            });
        </script>

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
        </script>

        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="js/libs/jquery-ui.min.js"><\/script>');
            }
        </script> -->

        <script>
            // START AND FINISH DATE

            $('#join_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                onSelect: function(selectedDate) {
                    $('#terminate_date').datepicker('option', 'minDate', selectedDate);
                }
            });

            $('#terminate_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                onSelect: function(selectedDate) {
                    $('#join_date').datepicker('option', 'maxDate', selectedDate);
                }
            });
        </script>

        {{-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}
        <script type="text/javascript">
            function unionfields() {
                if (document.getElementById("U").checked) {
                    $("#officer").show();
                    $("#fullname").hide();
                    $("#complainant_full_name").val('');
                    $("#complainant_full_name").attr("required", false);
                    $("#union_name").attr("required", true);
                    $("#union_address").attr("required", true);
                    $("#contact_fname").show();
                    $("#contact_lname").show();
                    $("#contact_address").show();
                    $("#comp_fname").hide();
                    $("#comp_lname").hide();
                    $("#comp_address").hide();
                } else {
                    $("#officer").hide();
                    $("#fullname").show();
                    $("#complainant_full_name").attr("required", true);
                    $("#union_name").attr("required", false);
                    $("#union_address").attr("required", false);
                    $("#contact_fname").hide();
                    $("#contact_lname").hide();
                    $("#contact_address").hide();
                    $("#comp_fname").show();
                    $("#comp_lname").show();
                    $("#comp_address").show();
                }
            }

            $(document).ready(function () {

                $("#edit-register-complaint-form").submit(function (e) {

                    $("#button1id").attr("disabled", true);

                    return true;

                });
            });
        </script>

        {{-- <script type="text/javascript">
            function complainavailable() {
                if (document.getElementById("yes").checked) {
                    // document.getElementById('union').style.visibility = 'visible';
                    document.getElementById('existcomplain').style.visibility = 'visible';
                }
                else
                    // document.getElementById('union').style.visibility = 'hidden';
                    document.getElementById('existcomplain').style.visibility = 'hidden';

            }

        </script> --}}

    </x-slot>
</x-app-layout>
