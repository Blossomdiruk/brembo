@section('title', 'Register Complaint')
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
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="" style="position: relative; top: 10px;">
                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto;  transform: translate(0%, -12%);">
                            <a href="">   <h5> Action Pending <span class="txt-color-blue" onclick="" style=" text-align: center">796</span></h5></a>

                        </li>
                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto; transform: translate(0%, -12%);">
                            <a href="">   <h5> Investigation Ongoing <span class="txt-color-blue" onclick="" style=" text-align: center">488</span></h5></a>

                        </li>

                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                            <a href="">   <h5> Temporary Closed<span class="txt-color-blue" style=" text-align: center"><i class="" ></i>308</span></h5></a>

                        </li>

                        <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 30px 10px !important; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                            <a href="">   <h5> Closed <span class="txt-color-blue" style=" text-align: center"><i class="" ></i>308</span></h5></a>

                        </li>

                    </ul>
                </div> -->
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
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
            @elseif($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ __('registercomplaint.main_title') }}</h2>
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
                        <form action="{{ route('new-register-complaint') }}" enctype="multipart/form-data" method="post" id="register-complaint-form" class="smart-form" autocomplete="off">
                            @csrf
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

                                <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                                <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">

                                    <!-------------------------------Tab 1---------------------------------------------------->

                                    <div class="tab-pane fade in active" id="s1">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('registercomplaint.comp_type') }}</label>
                                                        <div class="inline-group ">
                                                            <label class="radio">
                                                                <input type="radio" id="N" value="N" name="comp_type" onclick="unionfields();anonoumousfieldhidden();" checked>
                                                                <i></i>{{ __('registercomplaint.normal') }}</label>
                                                            <label class="radio">
                                                                <input type="radio" id="A" value="A" name="comp_type" onclick="unionfields();anonoumousfieldhidden();">
                                                                <i></i>{{ __('registercomplaint.anonymous') }}</label>
                                                            <label class="radio">
                                                                <input type="radio" id="U" value="U" name="comp_type" onclick="unionfields();anonoumousfieldhidden();">
                                                                <i></i>{{ __('registercomplaint.union') }}</label>
                                                        </div>
                                                    </section>

                                                    <section class="col col-2">
                                                        <label class="label">{{ __('registercomplaint.pref_lang') }}</label>
                                                        <label class="select">
                                                            <select id="pref_lang" name="pref_lang">
                                                                <option value="EN">{{ __('registercomplaint.english') }}</option>
                                                                <option value="SI">{{ __('registercomplaint.sinhala') }}</option>
                                                                <option value="TA">{{ __('registercomplaint.tamil') }}</option>
                                                            </select><i></i>
                                                        </label>
                                                    </section>

                                                    <section class="col col-6" id="groupcomp">
                                                        <br/>
                                                        @if($lang == "TA")
                                                        <p>முறைப்பாடானது இரண்டு அல்லது இரண்டுக்கு மேற்பட்டவர்களால் செய்யப்படவேண்டும் எனின், அவர்களுள் தலைவரின் விபரங்களை இங்கே குறிப்பிட்டு ஏனையவர்களின் விபரங்களை துணை ஆவணங்களின்கீழ் இணைப்பாகச் சேர்க்கவும்.</p>
                                                        @elseif($lang == "SI")
                                                        <p>පැමිණිලිකරුවන් දෙදෙනෙකු හෝ ඊට වැඩි පිරිසක් විසින් පැමිණිල්ල සිදු කරන්නේනම් අදාල ප්‍රධානියාගේ තොරතුරු මෙහි සඳහන් කර අනෙකුත් පුද්ගලයින්ගේ තොරතුරු ඇමුණුමක් සේ ආධාරක ලේඛන යටතේ යොමු කරන්න.</p>
                                                        @else
                                                        <p>If the complaint is made by two or more complainants, mention the relevant head's information here and refer to the other persons' information as an attachment under the supporting documents.</p>
                                                        @endif
                                                    </section>

                                                    <!-- <section class="col col-3" style="visibility:hidden">
                                                        <label class="label">{{ __('registercomplaint.ref_no') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="ref_no" name="ref_no" value="CW01/COM/A/202101/01" required readonly>
                                                        </label>
                                                    </section>

                                                    <section class="col col-3" style="visibility:hidden">
                                                        <label class="label">{{ __('registercomplaint.external_ref_no') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="external_ref_no" name="external_ref_no" value="{{-- $new_complaint_no --}}" required readonly>
                                                        </label>
                                                    </section> -->
                                                </div>
                                                <div id="officer" style="display:none;">
                                                    <div class="row">
                                                        <section class="col col-6">
                                                            <label class="label">{{ __('registercomplaint.union_name') }}<span style="color: #FF0000;">*</span></label>
                                                            <label class="input">
                                                                <input type="text" id="union_name" name="union_name" value="">
                                                            </label>
                                                        </section>

                                                        <section class="col col-6">
                                                            <label class="label">{{ __('registercomplaint.union_address') }}<span style="color: #FF0000;">*</span></label>
                                                            <label class="textarea">
                                                                <textarea  id="union_address" name="union_address" style="height: 32px;"></textarea>
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

                                                    <div class="row">
                                                        <section class="col col-12" style="margin-bottom: 20px;">
                                                            <p>
                                                                {{-- Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. --}}
                                                            </p>
                                                        </section>
                                                        <br>
                                                        <div class="cleafix"></div>
                                                        <section class="col col-12">
                                                            <label class="label">{{ __('registercomplaint.attachment') }}</label>
                                                            <div class="input-group hdtutoattachment control-group lst increment">
                                                                <input type="file" name="files[]" class="myfrm form-control">
                                                                <div class="input-group-btn">
                                                                    <button class="btn btn-info btn-sm" id="addrowattachment" type="button" style="background-color: #5D98CC;height: 32px; width: 100px;  padding :7px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;{{ __('registercomplaint.add') }}</button>
                                                                </div>
                                                            </div>
                                                        </section>

                                                        <div class="cloneattachment hide">
                                                            <div class="hdtutoattachment control-group lst input-group" style="margin-top:10px">
                                                                <input type="file" name="files[]" class="myfrm form-control">
                                                                <div class="input-group-btn">
                                                                    <button class="btn btn-danger" id="remrowattachment" type="button" style="margin-top: 0px; height: 40px; width: 102px; border-color:  #383838"><i class="glyphicon glyphicon-remove"></i>&nbsp;{{ __('registercomplaint.remove') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <br>
                                                <div class="cleafix"></div>
                                                <div class="row">
                                                    <section class="col col-1" style="padding-right: 0px;">
                                                        <label class="label">{{ __('registercomplaint.title') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="select">
                                                            <select id="title" name="title" required>
                                                                <option value=""> </option>
                                                                <option value="0" hidden> </option>
                                                                <option value="1">{{ __('registercomplaint.mr') }}</option>
                                                                <option value="2">{{ __('registercomplaint.miss') }}</option>
                                                                <option value="3">{{ __('registercomplaint.mrs') }}</option>
                                                                <option value="4">{{ __('registercomplaint.rev') }}</option>
                                                                <option value="5">{{ __('registercomplaint.dr') }}</option>
                                                            </select>
                                                            <i></i>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.title_description') }}</span></label> --}}
                                                    </section>

                                                    <section class="col col-5">
                                                        <label class="label" id="comp_fname">{{ __('registercomplaint.complainant_f_name') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="label" id="contact_fname">{{ __('registercomplaint.contact_fname') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_f_name" name="complainant_f_name" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.comfname_description') }}</span></label> --}}
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label" id="comp_lname">{{ __('registercomplaint.complainant_l_name') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="label" id="contact_lname">{{ __('registercomplaint.contact_lname') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            {{-- <input type="text" id="complainant_l_name" name="complainant_l_name" class="required" value="" @if($lang == "EN")pattern="^[a-zA-Z ]+(\s[a-zA-Z ]+)?$"@endif required> --}}
                                                            <input type="text" id="complainant_l_name" name="complainant_l_name" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.comlname_description') }}</span></label> --}}
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6" id="fullname">
                                                        <label class="label">{{ __('registercomplaint.complainant_full_name') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_full_name" name="complainant_full_name" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.comfullname_description') }}</span></label> --}}
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_identify_no') }} <span id="checkstatus"><a type="button" style="padding: 0px 8px;position: relative;top: -3px;" class="btn btn-success checknic">{{ __('registercomplaint.check_record') }}</a></span></label>
                                                        <label class="input">
                                                            <input type="text" id="complainant_identify_no" name="complainant_identify_no" value="" pattern=".{8,12}">
                                                        </label>
                                                        <!-- <span style="color:red;display:none" id="ermsg">The complaint has already been registered at this NIC number.</span> -->
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.comnic_description') }}</span></label> --}}
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_dob') }} </label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="complainant_dob" name="complainant_dob" value="" class="datepicker" data-date-format='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_gender') }}</label>
                                                        <label class="select">
                                                            <select id="complainant_gender" name="complainant_gender">
                                                                <option value=""> </option>
                                                                <option value="M">{{ __('registercomplaint.male') }}</option>
                                                                <option value="F">{{ __('registercomplaint.female') }}</option>
                                                            </select> <i></i>
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.nationality') }}</label>
                                                        <label class="input">

                                                            <input type="text" id="nationality" name="nationality" value="@if($lang == "SI"){{ "ශ්‍රී ලාංකික" }}@elseif($lang == "TA"){{ "இலங்கை" }}@else{{ "Srilankan" }}@endif" @if($lang == "EN")pattern="^[A-Za-z -]+$"@endif>

                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_email') }} </label>
                                                        <label class="input">
                                                            <input type="email" id="complainant_email" name="complainant_email" value="" pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_mobile') }} </label>
                                                        <label class="input">
                                                            <input type="tel" id="complainant_mobile" name="complainant_mobile" class="required" value="" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.complainant_tel') }}</label>
                                                        <label class="input">
                                                            <input type="tel" id="complainant_tel" name="complainant_tel" value="" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label" id="comp_address">{{ __('registercomplaint.complainant_address') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="label" id="contact_address">{{ __('registercomplaint.contact_address') }}<span style="color: #FF0000;">*</span></label>
                                                        <label class="textarea">
                                                            <textarea rows="3" id="complainant_address" name="complainant_address" required></textarea>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.comaddress_description') }}</span></label> --}}
                                                    </section>
                                                </div>

                                                {{-- <div class="row" style="visibility:hidden" id="union">
                                                    <section class="col col-12">
                                                        <label class="label">Name and address of union Chairmen, Secretary and Treasurer</label>
                                                    </section>
                                                </div> --}}

                                                <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                    <a href="#s2" id="testing" class="test" onclick="show_submit('T2');changeactive('s2B', 's1A');" data-toggle="tab">
                                                        <button type="button" class="btn btn-primary next test"> {{ __('registercomplaint.next') }} </button>
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
                                                        <label class="label">{{ __('registercomplaint.current_working_name') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="current_employer_name" name="current_employer_name" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.empname_description') }}</span></label> --}}
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.current_working_add') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="current_employer_address" name="current_employer_address" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.empaddress_description') }}</span></label> --}}
                                                    </section>
                                                </div>
                                                <div  class="row">
                                                <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.current_working_tel') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="current_employer_tel" name="current_employer_tel" value="" pattern="[0-9]{10,15}">
                                                        </label>
                                                </section>
                                                <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.province_id') }} <span style="color: #FF0000;">*</span></label>
                                                        {{-- <label class="select"> --}}
                                                            <?php
                                                            $logeduserProvince = "";
                                                            if(!empty($provincelist)){
                                                                $logeduserProvince = $provincelist->id;
                                                            }

                                                            ?>
                                                            <select id="province_id" name="province_id" class="required select2" required>
                                                                <option value="">@if($lang == "SI"){{ "පළාත තෝරන්න" }}@elseif($lang == "TA"){{ "மாகாணத்தைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select Province" }}@endif </option>
                                                                @foreach ($provinces as $province)
                                                                    <option value="{{ $province->id }}" {{ $logeduserProvince == $province->id ? 'selected' : ''}}>@if($lang == "TA"){{ $province->province_name_tamil }}@elseif($lang == "SI"){{ $province->province_name_sin }}@else{{ $province->province_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                        {{-- <label class="input">
                                                            <input type="text" id="province_name" name="province_name" class="required" value="@if($lang == "TA"){{ $provincelist->province_name_tamil }}@elseif($lang == "SI"){{ $provincelist->province_name_sin }}@else{{ $provincelist->province_name_en }}@endif" required readonly>
                                                            <input type="hidden" id="province_id" name="province_id" value="{{ $provincelist->id }}">
                                                        </label> --}}
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.province_description') }}</span></label> --}}
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.district_id') }} <span style="color: #FF0000;">*</span></label>
                                                        {{-- <label class="select"> --}}
                                                            <select id="district_id" name="district_id" class="select2" required>
                                                                <option value="">@if($lang == "SI"){{ "දිස්ත්‍රික්කය තෝරන්න" }}@elseif($lang == "TA"){{ "மாவட்டத்தைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select District" }}@endif</option>
                                                                {{-- @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}">@if($lang == "TA"){{ $district->district_name_tamil }}@elseif($lang == "SI"){{ $district->district_name_sin }}@else{{ $district->district_name_en }}@endif</option>
                                                                @endforeach --}}
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.district_description') }}</span></label> --}}
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.city_id') }} <span style="color: #FF0000;">*</span></label>
                                                        {{-- <label class="select"> --}}
                                                            <select id="city_id" name="city_id" class="required select2" required>
                                                                <option value="">@if($lang == "SI"){{ "නගරය තෝරන්න" }}@elseif($lang == "TA"){{ "நகரத்தைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select City" }}@endif</option>
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.city_description') }}</span></label> --}}
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.no_of_employees') }}</label>
                                                        <select id="worked_employees" name="worked_employees" class="select2">
                                                            <option value=""> </option>
                                                            @if($lang == "SI")
                                                                <option value="1">සේවකයින් 1-14</option>
                                                                <option value="2">සේවකයින් 15-50</option>
                                                                <option value="3">සේවකයින් 51-200</option>
                                                                <option value="4">සේවකයින් 201-500</option>
                                                                <option value="5">සේවකයින් 501-1000</option>
                                                                <option value="6">සේවකයින් 1001-5000</option>
                                                                <option value="7">සේවකයින් 5001-10,000</option>
                                                                <option value="8">සේවකයින් 10,001+</option>
                                                            @elseif($lang == "TA")
                                                                <option value="1">1-14 பணியாளர்கள்</option>
                                                                <option value="2">15-50 பணியாளர்கள்</option>
                                                                <option value="3">51-200 பணியாளர்கள்</option>
                                                                <option value="4">201-500 பணியாளர்கள்</option>
                                                                <option value="5">501-1000 பணியாளர்கள்</option>
                                                                <option value="6">1001-5000 பணியாளர்கள்</option>
                                                                <option value="7">5001-10,000 பணியாளர்கள்</option>
                                                                <option value="8">10,001+ பணியாளர்கள்</option>
                                                            @else
                                                                <option value="1">1-14 employees</option>
                                                                <option value="2">15-50 employees</option>
                                                                <option value="3">51-200 employees</option>
                                                                <option value="4">201-500 employees</option>
                                                                <option value="5">501-1000 employees</option>
                                                                <option value="6">1001-5000 employees</option>
                                                                <option value="7">5001-10,000 employees</option>
                                                                <option value="8">10,001+ employees</option>
                                                            @endif
                                                        </select> <i></i>
                                                        {{-- <label class="input">
                                                            <input type="number" id="worked_employees" name="worked_employees" value="" >
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
                                                            <input type="text" id="employer_name" name="employer_name" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.empname_description') }}</span></label> --}}
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_address') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="employer_address" name="employer_address" class="required" value="" required>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.empaddress_description') }}</span></label> --}}
                                                    </section>
                                                </div>

                                                <!-- <div class="row"> -->

                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.zone_id') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="select">
                                                            <select id="zone_id" name="zone_id"  required>
                                                                <option value="">Select Zone </option>
                                                            </select> <i></i>
                                                        </label>
                                                        {{-- <label class="label"><span style="color: #FF0000;">{{ __('registercomplaint.zone_description') }}</span></label> --}}
                                                    </section> -->

                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.district_id') }} <span style="color: #FF0000;">*</span></label>
                                                        <label class="select">
                                                            <select id="district_id" name="district_id" required>
                                                                <option value="">Select District </option>
                                                                @foreach ($districts as $district)
                                                                    <option value="{{ $district->id }}">{{ $district->district_name_en }}</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        </label>
                                                    </section> -->
                                                <!-- </div> -->


                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_tel') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employer_tel" name="employer_tel" value="" pattern="[0-9]{10,15}">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.business_nature') }}</label>
                                                        {{-- <label class="select"> --}}
                                                            <select class="select2" id="business_nature" name="business_nature">
                                                                <option value="">@if($lang == "SI"){{ "ව්‍යාපාරයේ ස්වභාවය තෝරන්න" }}@elseif($lang == "TA"){{ "வணிகத்தின் தன்மையைத் தேர்ந்தெடுக்கவும்" }}@else{{ "Select Nature of the Business" }}@endif </option>
                                                                @foreach ($businessnatures as $businessnature)
                                                                    <option value="{{ $businessnature->id }}">@if($lang == "TA"){{ $businessnature->business_nature_ta }}@elseif($lang == "SI"){{ $businessnature->business_nature_si }}@else{{ $businessnature->business_nature_en }}@endif</option>
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
                                                                <option value="{{ $establishmenttype->id }}">@if($lang == "TA"){{ $establishmenttype->establishment_name_tam }}@elseif($lang == "SI"){{ $establishmenttype->establishment_name_sin }}@else{{ $establishmenttype->establishment_name_en }}@endif</option>
                                                                @endforeach
                                                            </select> <i></i>
                                                        {{-- </label> --}}
                                                    </section>

                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.establishment_reg_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="establishment_reg_no" name="establishment_reg_no" value="">
                                                        </label>
                                                    </section> -->
                                                    {{-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employer_no') }} </label>
                                                        <label class="input">
                                                            <input type="text" id="employer_no" name="employer_no" value="">
                                                        </label>
                                                    </section> --}}
                                                </div>

                                                <div class="row">

                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.ppe_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="ppe_no" name="ppe_no" value="">
                                                        </label>
                                                    </section> -->
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employee_mem_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employee_mem_no" name="employee_mem_no" value="">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.epf_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="epf_no" name="epf_no" value="">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <!-- <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.employee_mem_no') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="employee_mem_no" name="employee_mem_no" value="">
                                                        </label>
                                                    </section> -->

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.designation') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="designation" name="designation" value="">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.join_date') }}</label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="join_date" name="join_date" value="" class="datepicker" data-date-format='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.terminate_date') }}</label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="terminate_date" name="terminate_date" value="" class="datepicker" data-date-format='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                                        </label>
                                                    </section>

                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.last_sal_date') }}</label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="last_sal_date" name="last_sal_date" value="" class="datepicker" data-date-format='yyyy-mm' placeholder="YYYY-MM" >
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.basic_sal') }}</label>
                                                        <label class="input">
                                                            <input type="text" step=".01" id="basic_sal" name="basic_sal" value="" onkeydown="numberWithCommas(this)">
                                                        </label>
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.allowance') }}</label>
                                                        <label class="input">
                                                            <input type="text" step=".01" id="allowance" name="allowance" value="" onkeydown="numberWithCommas(this)">
                                                        </label>
                                                    </section>
                                                </div>

                                                <div class="row">
                                                    <section class="col col-6">
                                                        <label class="label"> {{ __('registercomplaint.is_available') }}</label>
                                                        <div class="inline-group ">
                                                            <label class="radio">
                                                                <input type="radio" value="1" name="is_available" id="yes" onclick="complainavailable();">
                                                                <i></i>{{ __('registercomplaint.yes') }}
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" value="0" name="is_available" id="no" checked=" checked" onclick="complainavailable();">
                                                                <i></i>{{ __('registercomplaint.no') }}
                                                            </label>
                                                        </div>
                                                    </section>
                                                </div>

                                                <div id="existcomplain" style="display: none;">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                            <label class="label">{{ __('registercomplaint.submitted_office') }}</label>
                                                            <label class="input">
                                                                {{-- <select id="submitted_office" name="submitted_office">
                                                                    <option value="">Select Submitted Office </option>
                                                                    @foreach ($labouroffices as $labouroffice)
                                                                    <option value="{{ $labouroffice->id }}">{{ $labouroffice->office_name_en }}</option>
                                                                    @endforeach
                                                                </select> <i></i> --}}
                                                                <input type="text" id="submitted_office" name="submitted_office" value="">
                                                            </label>
                                                        </section>

                                                        <section class="col col-4">
                                                            <label class="label">{{ __('registercomplaint.submitted_date') }}</label>
                                                            <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                                <input type="text" id="submitted_date" name="submitted_date" value="" class="datepicker" data-date-format='yyyy-mm-dd'>
                                                            </label>
                                                        </section>

                                                        <section class="col col-4">
                                                            <label class="label">{{ __('registercomplaint.case_no') }}</label>
                                                            <label class="input">
                                                                <input type="text" id="case_no" name="case_no" value="">
                                                            </label>
                                                        </section>
                                                    </div>

                                                    <div class="row">
                                                        <section class="col col-lg-12">
                                                            <label class="label">{{ __('registercomplaint.received_relief') }}</label>
                                                            <label class="textarea">
                                                                <textarea rows="5" class="custom scrollspy-example" name="received_relief"></textarea>
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
                                                                <input type="checkbox" name="complain_category_id[]" required  value="{{ $complaincategory->id }}"> <i></i>@if($lang == "SI"){{ $complaincategory->category_name_si }}@elseif($lang == "TA"){{ $complaincategory->category_name_ta }}@else{{ $complaincategory->category_name_en }}@endif
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </section>
                                                    {{-- <label class="label"><span style="color: #FF0000; margin-left: 2%">{{ __('registercomplaint.category_description') }}</span></label> --}}
                                                </div>

                                                <div class="row">
                                                    <section class="col col-lg-12">
                                                        <label class="label">{{ __('registercomplaint.complain_purpose') }}</label>
                                                        <label class="textarea">
                                                            <textarea rows="5" class="custom scrollspy-example" name="complain_purpose"></textarea>
                                                        </label>
                                                    </section>
                                                </div>

                                                <footer style="background-color: #fff; border-top: transparent; padding:0px;">

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
                                                    <section class="col col-12" style="margin-bottom: 20px;">
                                                        <p>
                                                            {{-- Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. --}}
                                                        </p>
                                                    </section>
                                                    <br>
                                                    <div class="cleafix"></div>
                                                    <section class="col col-6">
                                                        <label class="label">{{ __('registercomplaint.support_files') }}</label>
                                                        <div class="input-group hdtuto control-group lst increment">
                                                            <input type="file" name="files[]" class="myfrm form-control">
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-info btn-sm" id="addrow" type="button" style="background-color: #5D98CC;height: 32px; width: 100px;  padding :7px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;{{ __('registercomplaint.add') }}</button>
                                                            </div>
                                                        </div>
                                                    </section>

                                                    <div class="clone hide">
                                                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                                            <input type="file" name="files[]" class="myfrm form-control">
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-danger" id="remrow" type="button" style="margin-top: 0px; height: 40px; width: 102px; border-color:  #383838"><i class="glyphicon glyphicon-remove"></i>&nbsp;{{ __('registercomplaint.remove') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <br>
                                                <div class="row">
                                                    <section class="col col-12">
                                                    <p id="error-msg" style="color: red; display:none;">{{ __('registercomplaint.mark_fields') }} </p>
                                                    </section>
                                                </div>

                                                <footer style="padding:0px; border-top:0px; background:transparent;">

                                                    <button id="button1id" name="button1id" type="submit" class="btn btn-primary" >
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="page-title txt-color-blueDark" style="margin:0px;">Complaint List</h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="widget-body no-padding table-responsive">
                <table class="table table-bordered data-table" width="100%">
                    <thead>
                        <tr>
                            <!-- <th width='2%' style="text-align:center;">{{ __('actionpendinglist.no') }}</th> -->
                            <th width='20%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.complaint_name') }}</th>
                            <th width='20%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.ref_num') }}</th>
                            <th width='20%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.external_ref_no') }}</th>
                            <th width='10%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.complainant_mobile') }}</th>
                            <th width='10%' style="text-align: center">{{ __('actionpendinglist.date') }}</th>
                            <th width='5%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.status') }} </th>
                            <th width='5%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.view') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<!-- <script type="text/javascript" src="{{ asset('public/back/js/datepicker/bootstrap-datepicker.min.js') }}"></script> -->
<script src="{{ asset('public/back/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var currentDate = new Date();
            $('#complainant_dob').datepicker({
                format: 'yyyy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#complainant_dob').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function () {
            var currentDate = new Date();
            $('#join_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#join_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function () {
            var currentDate = new Date();
            $('#terminate_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#terminate_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function () {
            var currentDate = new Date();
            $('#last_sal_date').datepicker({
                format: 'yyyy-mm',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
                startView: "months",
                minViewMode: "months",
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#last_sal_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function () {
            var currentDate = new Date();
            $('#submitted_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#submitted_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

    </script>

    {{-- <script type="text/javascript">
        $(document).ready(function() {
            $("#allowance").attr("maxlength", "7");
            $("#allowance").keypress(function(e) {
            var kk = e.which;
            if(kk < 48 || kk > 57)
                e.preventDefault();
            });

            $("#basic_sal").attr("maxlength", "7");
            $("#basic_sal").keypress(function(e) {
            var kk = e.which;
            if(kk < 48 || kk > 57)
                e.preventDefault();
            });
        });
    </script> --}}

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
        $(".select2").select2();
    </script>

    <x-slot name="script">

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
                    $('#checkstatus').hide();

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
                    $('#checkstatus').show();
                }

                if (document.getElementById("N").checked) {
                    $("#groupcomp").show();
                } else {
                    $("#groupcomp").hide();
                }

            }
        </script>

        <script>
            $(document).ready(function(){

                    $("#contact_fname").hide();
                    $("#contact_lname").hide();
                    $("#contact_address").hide();

            });
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

        <script>
            $(function() {
                //window.ParsleyValidator.setLocale('ta');
               // $('#register-complaint-form').parsley();
                $("#error-msg").hide();
                $('#register-complaint-form').parsley().on('form:error', function(formInstance) {
                $("#error-msg").show();
                });
            });

            $(document).ready(function () {

                $("#register-complaint-form").submit(function (e) {

                    $("#button1id").attr("disabled", true);

                    return true;

                });
            });


            $(document).ready(function () {

                    var provinceID = $('#province_id').val();
                    var lang = $('#lang').val();

                    //console.log(provinceID);
                    $('#province_id').trigger('change');

            });

            $('#province_id').on('change', function() {

                var provinceID = $('#province_id').val();
                var lang = $('#lang').val();

                //console.log(provinceID);

                if (provinceID) {

                    // $.ajax({
                    //     type: "GET",
                    //     url: "{{ url('getZone') }}?province_id=" + provinceID,
                    //     success: function(res) {
                    //         if (res) {
                    //             // console.log(res);
                    //             $("#zone_id").empty();
                    //             $("#zone_id").append('<option value="">Select Zone</option>');
                    //             $.each(res, function(key, value) {

                    //                 if(lang == "SI") {

                    //                     $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_sin'] +
                    //                     '</option>');

                    //                 } else if(lang == "TA") {

                    //                     $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_tam'] +
                    //                     '</option>');

                    //                 } else {

                    //                     $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_en'] +
                    //                     '</option>');
                    //                 }

                    //             });

                    //         } else {

                    //             $("#zone_id").empty();
                    //         }
                    //     }
                    // });

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getDistrict') }}?province_id=" + provinceID,
                        success: function(res) {

                            if (res) {
                                // console.log(res);
                                $("#district_id").empty();
                                $("#district_id").append('<option value="">Select District</option>');
                                $.each(res, function(key, value) {

                                    if(lang == "SI") {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_sin'] +
                                        '</option>');

                                    } else if(lang == "TA") {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_tamil'] +
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

                                    if(lang == "SI") {

                                        $("#city_id").append('<option value="' + value['id'] + '">' + value['city_name_sin'] +
                                        '</option>');

                                    } else if(lang == "TA") {

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
                $("#addrowattachment").click(function() {
                    var lsthmtl = $(".cloneattachment").html();
                    $(".increment").after(lsthmtl);
                });
                $("body").on("click", "#remrowattachment", function() {
                    $(this).parents(".hdtutoattachment").remove();
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


            $(document).ready(function() {

                $('#complainant_identify_no').on('input', function() {
                    var nic = $('#complainant_identify_no').val();
                    var niclength = $('#complainant_identify_no').val().length;

                    var lastChar = nic.slice(-1);

                    console.log(niclength);

                    console.log(test);

                    if(niclength == "10" && lastChar == "V") {
                        $.ajax({
                            type:'GET',
                            url:"{{ url('checkNic') }}?nic=" + nic,
                            success:function(result) {

                                if(result != ''){
                                    $("#exampleModal").modal("show");
                                    $('tbody').html('');
                                    $.each(result, function(index, value) {
                                        var encryptid = '';
                                        $.ajax({
                                            type:'GET',
                                            url:"{{ url('/encrypt/') }}?value=" + value.id,
                                            success:function(data) {
                                            var  encryptid = data;

                                                var newDate = moment(value.created_at).format('YYYY-MM-DD');

                                                var url = "{{ url('/complaint-status-history/') }}"+ '/' + encryptid;
                                                var view_url = "{{ url('/view/') }}"+ '/' + encryptid;
                                                var my_row = $('<tr>');
                                                // var my_html = '<td>'+(index+1)+'</td>';
                                                var my_html = '<td>'+value.complainant_f_name+'</td>';
                                                my_html += '<td>'+value.ref_no+'</td>';
                                                my_html += '<td>'+value.external_ref_no+'</td>';
                                                my_html += '<td>'+value.complainant_mobile+'</td>';
                                                my_html += '<td>'+newDate+'</td>';
                                                my_html += '<td><a  href="'+url+'" target="_blank" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a></td>';
                                                my_html += '<td><a href="' +view_url+ '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a></td>';

                                                my_row.html(my_html);
                                                $('tbody').append(my_row);
                                            }
                                            });

                                    });
                                } else {

                                    $("#exampleModal").modal("show");
                                    $('tbody').html('');
                                    var my_row = $('<tr>');
                                    var my_html = '<td colspan="9">No data available in table</td>';
                                    my_row.html(my_html);
                                    $('tbody').append(my_row);
                                }

                            }
                        });
                    } else if(niclength == "12")
                    {
                        $.ajax({
                            type:'GET',
                            url:"{{ url('checkNic') }}?nic=" + nic,
                            success:function(result) {

                                if(result != ''){
                                    $("#exampleModal").modal("show");
                                    $('tbody').html('');
                                    $.each(result, function(index, value) {
                                        var encryptid = '';
                                        $.ajax({
                                            type:'GET',
                                            url:"{{ url('/encrypt/') }}?value=" + value.id,
                                            success:function(data) {
                                            var  encryptid = data;

                                                var newDate = moment(value.created_at).format('YYYY-MM-DD');

                                                var url = "{{ url('/complaint-status-history/') }}"+ '/' + encryptid;
                                                var view_url = "{{ url('/view/') }}"+ '/' + encryptid;
                                                var my_row = $('<tr>');
                                                // var my_html = '<td>'+(index+1)+'</td>';
                                                var my_html = '<td>'+value.complainant_f_name+'</td>';
                                                my_html += '<td>'+value.ref_no+'</td>';
                                                my_html += '<td>'+value.external_ref_no+'</td>';
                                                my_html += '<td>'+value.complainant_mobile+'</td>';
                                                my_html += '<td>'+newDate+'</td>';
                                                my_html += '<td><a  href="'+url+'" target="_blank" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a></td>';
                                                my_html += '<td><a href="' +view_url+ '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a></td>';

                                                my_row.html(my_html);
                                                $('tbody').append(my_row);
                                            }
                                            });

                                    });
                                } else {

                                    $("#exampleModal").modal("show");
                                    $('tbody').html('');
                                    var my_row = $('<tr>');
                                    var my_html = '<td colspan="9">No data available in table</td>';
                                    my_row.html(my_html);
                                    $('tbody').append(my_row);
                                }

                            }
                        });
                    }
                });


                $('.checknic').click(function() {
                // $("#testing").click(function() {
                    var nic = $('#complainant_identify_no').val();
                    $.ajax({
                        type:'GET',
                        url:"{{ url('checkNic') }}?nic=" + nic,
                        success:function(result) {

                            if(result != ''){
                                $("#exampleModal").modal("show");
                            $('tbody').html('');
                                $.each(result, function(index, value) {
                                    var encryptid = '';
                                    $.ajax({
                                        type:'GET',
                                        url:"{{ url('/encrypt/') }}?value=" + value.id,
                                        success:function(data) {
                                          var  encryptid = data;

                                            var newDate = moment(value.created_at).format('YYYY-MM-DD');

                                            var url = "{{ url('/complaint-status-history/') }}"+ '/' + encryptid;
                                            var view_url = "{{ url('/view/') }}"+ '/' + encryptid;
                                            var my_row = $('<tr>');
                                            // var my_html = '<td>'+(index+1)+'</td>';
                                            var my_html = '<td>'+value.complainant_f_name+'</td>';
                                            my_html += '<td>'+value.ref_no+'</td>';
                                            my_html += '<td>'+value.external_ref_no+'</td>';
                                            my_html += '<td>'+value.complainant_mobile+'</td>';
                                            my_html += '<td>'+newDate+'</td>';
                                            my_html += '<td><a  href="'+url+'" target="_blank" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a></td>';
                                            my_html += '<td><a href="' +view_url+ '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a></td>';

                                            my_row.html(my_html);
                                            $('tbody').append(my_row);
                                        }
                                        });

                                });
                            } else {

                                $("#exampleModal").modal("show");
                                $('tbody').html('');
                                var my_row = $('<tr>');
                                var my_html = '<td colspan="9">No data available in table</td>';
                                my_row.html(my_html);
                                $('tbody').append(my_row);
                            }

                    }
                    });
                });
            });

        </script>


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

                $('#union_address').blur(function () {
                        var unionAddress = $(this).val();
                        var unionName = $('#union_name').val();

                        console.log(unionName);

                        $('#complainant_f_name').val(unionName);
                        $('#complainant_address').val(unionAddress);
                });

            });

        </script>

        <script type="text/javascript">
            function complainavailable() {
                if (document.getElementById("yes").checked) {
                    // document.getElementById('union').style.visibility = 'visible';
                    //document.getElementById('existcomplain').style.visibility = 'visible';
                    $("#existcomplain").show();
                } else
                    // document.getElementById('union').style.visibility = 'hidden';
                    //document.getElementById('existcomplain').style.visibility = 'hidden';
                    $("#existcomplain").hide();
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
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                onSelect : function(selectedDate) {
                    $('#terminate_date').datepicker('option', 'minDate', selectedDate);
                }
            });

            $('#terminate_date').datepicker({
                format: 'yyyy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                onSelect : function(selectedDate) {
                    $('#join_date').datepicker('option', 'maxDate', selectedDate);
                }
            });
        </script>

    </x-slot>
</x-app-layout>
