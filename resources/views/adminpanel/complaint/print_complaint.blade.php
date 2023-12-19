<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>DoL | Complaint Print</title>
      <!--favicon-->
      
      <script type="text/javascript">

            window.onload = function() { window.print();  }
            window.onafterprint = function(event) {
                document.location.href = '{{ url('/view/'.encrypt($data->id).'') }}';
            };
        </script>
   </head>
   <body id="home">
    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">

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
                                {{ __('viewcomplaint.current_status') }} &nbsp;&nbsp;:&nbsp;{{ $complainthistorydetails->status_des }} 
                            </h5>
                        </div>
                    </div>

                {{-- </article> --}}
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
                                                <td style="width: 40%;">{{ __('viewcomplaint.name') }} </td>
                                                <td style=" border-style: hidden">:&emsp;
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
                                                <td style=" border-style: hidden">:&emsp; {{ $data->complainant_dob }} </td>
                                                <?php
                                                    // $dob = $data->complainant_dob;
                                                    // $currentDate = date("Y-m-d");

                                                    // $age = date_diff(date_create($dob), date_create($currentDate));

                                                    // echo $age->format("%y");

                                                ?>
                                            </tr>

                                            <tr style=" border-style: hidden">
                                                <td style=" border-style: hidden">{{ __('viewcomplaint.gender') }} </td>
                                                <td style=" border-style: hidden">:&emsp;
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
                                                <td style=" border-style: hidden">:&emsp;{{ $data->complainant_identify_no }} </td>
                                            </tr>

                                            <tr style=" border-style: hidden">
                                                <td>{{ __('viewcomplaint.complainant_address') }} </td>
                                                <td style=" border-style: hidden">:&emsp;{{ $data->complainant_address }}</td>
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
                                                <td style="width: 40%;">{{ __('viewcomplaint.current_working_name') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->current_employer_name }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.current_working_add') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->current_employer_address }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.current_working_tel') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->current_employer_tel }}</td>
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
                                                <td style="width: 40%;">{{ __('viewcomplaint.emp_name') }}</td>
                                                <td style="border-style: hidden">:&emsp; {{ $data->employer_name }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.emp_address') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->employer_address }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.emp_tel') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->employer_tel }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.business_nature') }}</td>
                                                <td style="border-style: hidden">:&emsp;@if(!empty($data->businessnatures->business_nature_en)){{ $data->businessnatures->business_nature_en }}@endif</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="width: 40%;">{{ __('viewcomplaint.establishment') }}</td>
                                                <td style="border-style: hidden">:&emsp; @if(!empty($data->establishments->establishment_name_en)){{ $data->establishments->establishment_name_en }}@endif</td>
                                            </tr>

                                            <!-- <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.establishment_reg_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->establishment_reg_no }}</td>
                                            </tr> -->

                                            <!-- <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.employer_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->employer_no }}</td>
                                            </tr> -->

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.employee_member_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->employee_mem_no }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.epf_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->epf_no }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.joined_date') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->join_date }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.terminate_date') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->terminate_date }}</td>
                                            </tr>

                                            <!-- <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.ppe_no') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->ppe_no }}</td>
                                            </tr> -->

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.designation') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->designation }}</td>
                                            </tr>
                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.last_sal_date') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ $data->last_sal_date }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td>{{ __('viewcomplaint.basic_sal') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ 'Rs.'.' '. number_format($data->basic_sal, 2) }}</td>
                                            </tr>

                                            <tr style="border-style: hidden">
                                                <td style="border-style: hidden">{{ __('viewcomplaint.allowance') }}</td>
                                                <td style="border-style: hidden">:&emsp;{{ 'Rs.'.' '. number_format($data->allowance, 2)}}</td>
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
            @if(!empty($data->submitted_office) || !empty($data->submitted_date) || !empty($data->ref_no) || !empty($data->received_relief))
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
            @endif
            @if (count($complaintdocuments) > 1)
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
                                        <fieldset style="border: none;border-style: none;">
                                        <section>
                                            <label class="label">{{ __('viewcomplaint.expective_relief') }}</label>
                                        </section>
                                        <section>
                                            <label class="textarea">
                                                <textarea rows="20" disabled="" class="custom-scroll" style="width: 100%">{{ $data->complain_purpose }}</textarea>
                                            </label>
                                        </section>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>

                            <article style="page-break-before: always;" class="col-sm-12 col-md-12 col-lg-12">
                                <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                                    <header>
                                        <h2><b>{{ __('viewcomplaint.doc_evidence') }}</b></h2>
                                    </header>
                                    <div>
                                        <div class="jarviswidget-editbox">
                                        </div>
                                        <div class="widget-body no-padding table-responsive">
                                            <table class="table table-bordered" width="100%">
                                                <tbody>
                                                <tr  >
                                                    @if (count($complaintdocuments) > 1)
                                                        <td rowspan="{{ count($complaintdocuments) }}"  width="40%">{{ __('viewcomplaint.file_name') }}</th>
                                                    @else
                                                    <td  width="40%">{{ __('viewcomplaint.file_name') }}</th>
                                                    @endif 
                                                    @foreach ($complaintdocuments as $key => $complaintdocument)
                                                    
                                                        @if ($key > 0)
                                                        <tr>
                                                        <td> <a href="{{ '../storage/app/'.$complaintdocument->file_name }}" target="_blank" title="View">{{ $complaintdocument->description }}</a>   </td>
                                                        @else
                                                        <td> <a href="{{ '../storage/app/'.$complaintdocument->file_name }}" target="_blank" title="View">{{ $complaintdocument->description }}</a>   </td>
                                                        <!-- <td></td> -->
                                                        @endif
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
            @endif
            
        </div>
    </div>
    <footer>
        <p>{{ __('viewcomplaint.generateby') }}</p>
    </footer>
    </body>
</html>

