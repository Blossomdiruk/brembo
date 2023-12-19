@section('title', 'Complaint')
<x-app-layout>
    <x-slot name="header">

        {{-- <script type="text/javascript">
            $(document).ready(function(){
            $('$print').printPage();
            });
            </script> --}}

    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">

                <div class="col-lg-12">

                         <!--<button type="button" style="float: right; width: 97px; height: 43px; font-size: 14px" class="btn btn-primary" onclick="addexcel()">  Excel </button> -->
                        <!--<button type="button" id="print" style="float: right; width: 97px; height: 43px; font-size: 14px; margin-right: 10px" class="btn btn-primary" onclick="addprint()">  Print </button>-->

                    <a href="{{ url('/complain-print/'.$data->id.'') }}" type="button" id="print" style="float: right; width: 97px; height: 43px; font-size: 14px; margin-right: 10px; margin-top: 10px" class="btn btn-primary">{{ __('viewcomplaint.print') }}</a>
                    @if($complaintstatusdetails[0]->action_type == "Pending_approve" && $userrole == "ACL") <a href="{{ url('/pending-approval-action/'.$encryptID.'') }}" type="button" style="float: right; width: 202px; height: 43px; font-size: 14px; margin-right: 10px; margin-top: 10px" class="btn btn-primary">{{ __('viewcomplaint.approve_reject') }}</a> @endif
                </div>

            </div>

            <br>
            {{-- <h1 class="alert alert-info"> {{ __('uploaddocument.complaint_ref_no') }} :  <br/>
                {{ __('registercomplaint.current_status') }} : </h1> --}}

                {{-- <article class="col-sm-12 col-md-12 col-lg-12"> --}}
                    <div>
                        <div class="jarviswidget-editbox">
                        </div>
                        <div class="alert alert-info fade in " style="text-align: center; min-height: 45px;">
                            <h5 style="font-weight: 600">
                                {{ __('uploaddocument.complaint_ref_no') }} :&nbsp;{{ $data->ref_no }}<br>
                                {{ __('uploaddocument.external_ref_no') }}&nbsp;&nbsp;:&nbsp;{{ $data->external_ref_no }}<br>
                                {{ __('viewcomplaint.current_status') }} &nbsp;&nbsp;:&nbsp;{{ $complaintstatusdetails[0]->status_des }}
                            </h5>
                        </div>
                    </div>

                {{-- </article> --}}
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
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="row ">
                <article class="col-lg-6">
                    <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">

                        <header>
                            <h2><b>{{ __('viewcomplaint.complainant_details') }}</b></h2>
                        </header>

                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="width:100% ;  border-style: hidden">
                                        <tbody style=" border-style: hidden">
                                            <tr style=" border-style: hidden">
                                                <td style="width: 50%;">{{ __('viewcomplaint.name') }} </td>
                                                <td style=" border-style: hidden">:&nbsp;
                                                <?php
                                                if($data->title == '5') {
                                                    echo "Dr.";}
                                                else if($data->title == '4') {
                                                    echo "Rev. ";
                                                } else if ($data->title == '3') {
                                                    echo "Mrs. ";
                                                } else if ($data->title == '2') {
                                                    echo "Miss ";
                                                } else if($data->title == '1') {
                                                    echo "Mr. ";
                                                } ?>{{ $data->complainant_full_name }}</td>
                                            </tr>

                                            <tr style=" border-style: hidden">
                                                <td style=" border-style: hidden">{{ __('viewcomplaint.dob') }} </td>
                                                <td style=" border-style: hidden">:&nbsp; {{ $data->complainant_dob }} </td>
                                                <?php
                                                    // $dob = $data->complainant_dob;
                                                    // $currentDate = date("Y-m-d");

                                                    // $age = date_diff(date_create($dob), date_create($currentDate));

                                                    // echo $age->format("%y");

                                                ?>
                                            </tr>

                                            <tr style=" border-style: hidden">
                                                <td style=" border-style: hidden">{{ __('viewcomplaint.gender') }} </td>
                                                <td style=" border-style: hidden">:&nbsp;
                                                <?php
                                                    if($data->complainant_gender == 'M') {
                                                        echo "Male";
                                                    } else if($data->complainant_gender == 'F') {
                                                        echo "Female";
                                                    } else {
                                                        echo "";
                                                    }
                                                ?>
                                                </td>
                                            </tr>

                                            <tr style=" border-style: hidden">
                                                <td>{{ __('viewcomplaint.nic_no') }} </td>
                                                <td style=" border-style: hidden">:&nbsp; {{ $data->complainant_identify_no }} </td>
                                            </tr>

                                            <tr style=" border-style: hidden">
                                                <td>{{ __('viewcomplaint.complainant_address') }} </td>
                                                <td style=" border-style: hidden">:&nbsp; {{ $data->complainant_address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="col-lg-6">
                    <div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false">
                        <header>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="width:100% ; border-style: hidden">
                                        <tbody style=" border-style: hidden">
                                            <tr style=" border-style: hidden">
                                                <td style="width: 40%; border-style:  hidden">{{ __('viewcomplaint.complainant_tel') }}</td>
                                                <td style=" border-style:  hidden">:&emsp; {{ $data->complainant_tel }}</td>
                                                <input type="hidden" name="refid" id="refid" value="{{ $data->id }}">
                                            </tr>
                                            <tr style=" border-style: hidden">
                                                <td>{{ __('viewcomplaint.complainant_mobile') }}</td>
                                                <td style=" border-style: hidden">:&emsp; {{ $data->complainant_mobile }}</td>
                                            </tr>
                                            <tr style=" border-style: hidden">
                                                <td>{{ __('viewcomplaint.complainant_email') }}</td>
                                                <td style=" border-style: hidden">:&emsp; {{ $data->complainant_email }}</td>
                                            </tr>
                                            <tr style=" border-style: hidden">
                                                <td>{{ __('viewcomplaint.nationality') }}</td>
                                                <td style=" border-style: hidden">:&emsp; {{ $data->nationality }}</td>
                                            </tr>
                                            <tr style=" border-style: hidden">
                                                <td></td>
                                                <td style=" border-style: hidden">&emsp; </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="row">
                <article class="col-lg-12">
                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

                        <header>
                            <h2><b>{{ __('viewcomplaint.emp_details_title') }} </b></h2>
                        </header>

                        <div>
                            <div class="jarviswidget-editbox"> </div>
                            <div class="widget-body">
                                <div class="table-responsive">

                                    <table class="table table-bordered" style="width:100%; border-style:  hidden ">

                                        <tbody style="border-style: hidden">
                                            <tr style="border-style: hidden">
                                                <td colspan="2" style="font-weight:bold;">{{ __('viewcomplaint.current_employer_details') }}</td>
                                            </tr>
                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.current_working_name') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->current_employer_name }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.current_working_add') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->current_employer_address }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.current_working_tel') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->current_employer_tel }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.province') }}</td>
                                                <td style="border-style: hidden">:&emsp; @if(!empty($data->provinces->province_name_en)){{ $data->provinces->province_name_en }}@endif</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.district') }}</td>
                                                <td style="border-style: hidden">:&emsp; @if(!empty($data->districts->district_name_en)){{ $data->districts->district_name_en }}@endif</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.city') }}</td>
                                                <td style="border-style: hidden">:&emsp; @if(!empty($data->cities->city_name_en)){{ $data->cities->city_name_en }}@endif</td>
                                            </tr>
                                            <tr style="border-style: hidden">
                                                <td colspan="2" style="font-weight:bold;">{{ __('viewcomplaint.employer_details') }}</td>
                                            </tr>
                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.emp_name') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->employer_name }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.emp_address') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->employer_address }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.emp_tel') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->employer_tel }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.business_nature') }}</td>
                                                <td style="border-style: hidden">:&emsp; @if(!empty($data->businessnatures->business_nature_en)){{ $data->businessnatures->business_nature_en }}@endif</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.establishment') }}</td>
                                                <td style="border-style: hidden">:&emsp; @if(!empty($data->establishments->establishment_name_en)){{ $data->establishments->establishment_name_en }}@endif</td>
                                            </tr>

                                            <!-- <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.establishment_reg_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->establishment_reg_no }}</td>
                                            </tr> -->

                                            {{-- <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.employer_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->employer_no }}</td>
                                            </tr> --}}

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.employee_member_no') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->employee_mem_no }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.epf_no') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->epf_no }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.joined_date') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->join_date }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.terminate_date') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->terminate_date }}</td>
                                            </tr>

                                            <!-- <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.ppe_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->ppe_no }}</td>
                                            </tr> -->

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.designation') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->designation }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.last_sal_date') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->last_sal_date }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.basic_sal') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ 'Rs.'.' '. number_format($data->basic_sal, 2) }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.allowance') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ 'Rs.'.' '. number_format($data->allowance, 2)}}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td colspan="2" style="font-weight:bold;">{{ __('viewcomplaint.complaint_details') }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.nature_of_complaint') }}</td>
                                                <td style="border-style: hidden">:&emsp;
                                                    @if(!empty($complaintcategorydetails))
                                                    @foreach($complaintcategorydetails as $complaintcategorydetail)
                                                    {{ $complaintcategorydetail->complaintcategories->category_name_en }}<br/> &emsp;
                                                    @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="row">
                <article class="col-lg-12">
                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

                        <header>
                            <h2><b>{{ __('viewcomplaint.already_complaint') }}</b></h2>
                        </header>

                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding" style="min-height: auto;">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30%">{{ __('viewcomplaint.submitted_office') }}</th>
                                            <th width="20%">{{ __('viewcomplaint.submitted_date') }}</th>
                                            <th width="20%">{{ __('viewcomplaint.ref_no') }}</th>
                                            <th width="30%">{{ __('viewcomplaint.received_relief') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $data->submitted_office }}</td>
                                            <td>{{ $data->submitted_date }}</td>
                                            <td>{{ $data->ref_no }}</td>
                                            <td>{{ $data->received_relief }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="row">
                <article class="col-lg-12">
                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

                        <header>
                            <h2><b>{{ __('viewcomplaint.supportive_evidence') }} </b></h2>
                        </header>

                        <div>
                            <div class="jarviswidget-editbox"></div>
                            <div class="widget-body">
                                <div class="table-responsive">
                                    <form class="smart-form">
                                        <fieldset>
                                            <section>
                                                <label class="label">{{ __('viewcomplaint.expective_relief') }}</label>
                                                <label class="textarea">
                                                        <textarea rows="20" disabled="" class="custom-scroll">{{ $data->complain_purpose }}</textarea>
                                                    </label>
                                            </section>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>

                            <article class="col-sm-12 col-md-12 col-lg-12">
                                <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                                    <header>
                                        <h2><b>{{ __('viewcomplaint.doc_evidence') }}</b></h2>
                                    </header>
                                    <div>
                                        <div class="jarviswidget-editbox">
                                        </div>
                                        <div class="widget-body no-padding">
                                            <table class="table table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="50%">{{ __('viewcomplaint.file_name') }}</th>
                                                        {{--  <th width="50%">{{ __('viewcomplaint.document') }}</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($complaintdocuments as $complaintdocument)
                                                    <tr>
                                                        <td> <a href="{{ '../storage/app/'.$complaintdocument->file_name }}" target="_blank" title="View">{{ $complaintdocument->description }}</a>   </td>
                                                        <!-- <td></td> -->
                                                        {{-- <td style="text-align:center"><button class="delete-confirm" value="{{ $complaintdocument->id }}"><i id="545" class="fa fa-trash-o trash"></i></button></td> --}}
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </article>
            </div>
            <!-- <div class="row">
                <article class="col-lg-12">
                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

                        <header>
                            <h2><b>{{ __('viewcomplaint.status_history') }}</b></h2>
                        </header>

                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding" style="min-height: auto;">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">{{ __('viewcomplaint.created_at') }}</th>
                                            <th width="30%">{{ __('viewcomplaint.status_des') }}</th>
                                            <th width="50%">{{ __('viewcomplaint.remark') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($complaintstatusdetails as $data)
                                        <tr>
                                            <td>{{ $data->created_at }}</td>
                                            <td>{{ $data->status_des }}</td>
                                            <td>{{ $data->remark }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
            </div> -->

            <footer>
                <!--<button type="button" style="width: 100px; font-size: 15px" id="btnclose" name="btnclose" class="btn btn-primary pull-right" value="CANCEL" onClick="self.close()">{{ __('viewcomplaint.close') }}</button>-->
                                <a href="{{ url()->previous() }}" style="width: 100px; font-size: 15px" id="btnclose" name="btnclose" class="btn btn-primary pull-right" value="CANCEL">{{ __('viewcomplaint.close') }}</a>
                <br>
                <br>
            </footer>
            <!-- end widget -->
        </div>
    </div>
    <x-slot name="script">

        <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#register-complaint-form').parsley();
            });
        </script>

        <script>
            function addprint() {
                var refid = $(this).val('#refid');

                window.open('', '_blank');
            }

            function addexcel() {
                window.open('');
            }
        </script>


        <script type="text/javascript">
            $(document).ready(function() {
                $("#addrow").click(function(){
                    var lsthmtl = $(".clone").html();
                    $(".increment").after(lsthmtl);
                });
                $("body").on("click","#remrow",function(){
                    $(this).parents(".hdtuto").remove();
                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                var i = 0;
                $("#add").click(function(){
                    ++i;
                    var lsthmtl2 = $(".clone2").html();
                    $(".increment2").after(lsthmtl2);
                });
                $("body").on("click","#remove",function(){
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

        {{-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}
        <script type="text/javascript">
            function unionfields() {
                if (document.getElementById("U").checked) {
                    // document.getElementById('union').style.visibility = 'visible';
                    document.getElementById('officer').style.visibility = 'visible';
                }
                else
                    // document.getElementById('union').style.visibility = 'hidden';
                    document.getElementById('officer','union').style.visibility = 'hidden';
            }
        </script>

        <script type="text/javascript">
            function complainavailable() {
                if (document.getElementById("yes").checked) {
                    // document.getElementById('union').style.visibility = 'visible';
                    document.getElementById('existcomplain').style.visibility = 'visible';
                }
                else
                    // document.getElementById('union').style.visibility = 'hidden';
                    document.getElementById('existcomplain').style.visibility = 'hidden';
                }
        </script>

    </x-slot>
</x-app-layout>
