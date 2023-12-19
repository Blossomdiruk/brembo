@section('title', 'Complaint Report')
<x-app-layout>
    <x-slot name="header">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/datatable-buttons/buttons.bootstrap4.min.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<!-- <link rel="stylesheet" href="{{ asset('public/back/css/datepicker/bootstrap-datepicker3.min.css') }}" /> -->

<style>
    .select2-selection__rendered {
        padding-left: 5px !important;
    }
</style>

 </head>
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
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        <font style=" font-size: 22px"> {{ __('reportsearch.title') }} </font> </span>
                    </h1>
                </div>
            </div>
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                    </div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <div id="search-complaint-form" class="smart-form">
                            {{-- @csrf --}}
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.external_complaint_no') }} </label>
                                        <label class="input">
                                            <input type="text" id="external_ref_no" name="external_ref_no" value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.internal_complaint_no') }} </label>
                                        <label class="input">
                                            <input type="text" id="ref_no" name="ref_no" value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.identification_no') }} </label>
                                        <label class="input">
                                            <input type="text" id="complainant_identify_no" name="complainant_identify_no" value="">
                                        </label>
                                    </section>
                                    <div class="clearfix"></div>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.mobile_no') }} </label>
                                        <label class="input">
                                            <input type="text" id="complainant_mobile" name="complainant_mobile" value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.complainant_name') }} </label>
                                        <label class="input">
                                            <input type="text" id="complainant_full_name" name="complainant_full_name" value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.complaint_category') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="complain_category" id="complain_category" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($complaintcategories as $complaintcategory)
                                                    <option value="{{ $complaintcategory->id }}">@if($lang == "SI"){{ $complaintcategory->category_name_si }}@elseif($lang == "TA"){{ $complaintcategory->category_name_ta }}@else{{ $complaintcategory->category_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <div class="clearfix"></div>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.province') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="province_id" id="province_id" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}">@if($lang == "SI"){{ $province->province_name_sin }}@elseif($lang == "TA"){{ $province->province_name_tamil }}@else{{ $province->province_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.district') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="district_id" id="district_id" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">@if($lang == "SI"){{ $district->district_name_sin }}@elseif($lang == "TA"){{ $district->district_name_tamil }}@else{{ $district->district_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.city') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="city_id" id="city_id" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}">@if($lang == "SI"){{ $city->city_name_sin }}@elseif($lang == "TA"){{ $city->city_name_tam }}@else{{ $city->city_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <div class="clearfix"></div>
                                    <section class="col col-4">
                                        <label class="label">{{ __('searchcomplaint.labour_office') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="labour_office" id="labour_office" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($officedivisions as $loOffice)
                                                <option value="{{ $loOffice->id }}">@if($lang == "SI"){{ $loOffice->office_name_sin }}@elseif($lang == "TA"){{ $loOffice->office_name_tam }}@else{{ $loOffice->office_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.labour_officer_to_assign') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="labour_officer_id" id="labour_officer_id" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($loOfficers as $loOfficer)
                                                <option value="{{ $loOfficer->id }}">{{ $loOfficer->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.employer_name') }} </label>
                                        <label class="input">
                                            <!-- <input type="text" list="browsers" id="employer_name" name="employer_name" value=""> -->
                                            <select id="employer_name" name="employer_name" class="select2">
                                                <option value=""></option>
                                                @foreach ($employers as $employer)
                                                    <option value="{{ $employer->employer_name }}">{{ $employer->employer_name }}</option>
                                                @endforeach
                                                </select>
                                                <i></i>
                                        </label>
                                    </section>
                                    <div class="clearfix"></div>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.epf_no') }} </label>
                                        <label class="input">
                                            <input type="text" id="epf_no" name="epf_no" value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.employee_mem_no') }} </label>
                                        <label class="input">
                                            <input type="text" id="employee_mem_no" name="employee_mem_no" value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.current_status') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="complaint_status" id="complaint_status" class="select2" required>
                                                <option value=""></option>
                                                <option value="New">New</option>
                                                <option value="Forward">Forward</option>
                                                <option value="Request_assign_lo">Request assign LO</option>
                                                <option value="LOAllocated">LO allocated</option>
                                                <option value="Approved_assign_lo">Approved assign LO</option>
                                                <option value="Request_legal_certificate">Request legal certificate</option>
                                                <option value="Create_legal_certificate">Create legal certificate</option>
                                                <option value="Reject_legal_certificate">Reject legal certificate</option>
                                                <option value="Request_plaint_charge_sheet">Request plaint charge sheet</option>
                                                <option value="Create_plaint_and_charge_sheet">Create plaint and charge sheet</option>
                                                <option value="Reject_plaint_and_charge_sheet">Reject plaint and charge sheet</option>
                                                <option value="Request_recovery">Sent Recovery</option>
                                                <option value="Update">Update</option>
                                                <option value="Request_approve_temp_close">Request approve temp close</option>   
                                                <option value="Approved_temp_close">Approved temp close</option>   
                                                <option value="Reject_temp_close">Reject temp close</option> 
                                                <option value="Request_approve_close">Request approve close</option>
                                                <option value="Approved_close">Approved close</option>
                                                <option value="Reject_close">Reject close</option>
                                                <option value="Tempclosed">Temp closed</option>
                                                <option value="Closed">Closed</option>
                                                <!-- @foreach ($status as $item)
                                                    <option value="{{ $item->complaint_status }}">{{ $item->complaint_status }}</option>
                                                @endforeach -->
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('searchcomplaint.status_type') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="status_type" id="status_type" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($statustypes as $item)
                                                    <option value="{{ $item->id }}">@if($lang == "SI"){{ $item->type_name_si }}@elseif($lang == "TA"){{ $item->type_name_ta }}@else{{ $item->type_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.updated_status') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="updated_status" id="updated_status" class="select2" required>
                                                <option value=""></option>
                                                {{-- @foreach ($complaintstatus as $item)
                                                    <option value="{{ $item->id }}">@if($lang == "SI"){{ $item->status_si }}@elseif($lang == "TA"){{ $item->status_ta }}@else{{ $item->status_en }}@endif</option>
                                                @endforeach --}}
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('searchcomplaint.remark') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="remark" id="remark" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($remarks as $item)
                                                    <option value="@if($lang == "SI"){{ $item->remark_si }}@elseif($lang == "TA"){{ $item->remark_ta }}@else{{ $item->remark_en }}@endif">@if($lang == "SI"){{ $item->remark_si }}@elseif($lang == "TA"){{ $item->remark_ta }}@else{{ $item->remark_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.mail_template') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="mail_template" id="mail_template" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($mailtemplate as $item)
                                                    <option value="{{ $item->id }}">{{ $item->mail_template_title }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.from_date') }} </label>
                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                            <input type="text" id="from_date" name="from_date" value="" class="datepicker" data-date-format='yyyy-mm-dd'>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportsearch.to_date') }} </label>
                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                            <input type="text" id="to_date" name="to_date" value="" class="datepicker" data-date-format='yyyy-mm-dd'>
                                        </label>
                                    </section>
                                </div>
                                <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('reportsearch.search') }}
                                </button>
                                <button name="reset" id="reset" type="button" class="btn btn-default"> {{ __('reportsearch.clear') }}</button>
                            </footer>
                        </div>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->

            {{-- @if(isset($details)) --}}
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">
                    <!-- NEW WIDGET START -->

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->

                        <div class="jarviswidget jarviswidget-color-darken" id="user_types" data-widget-editbutton="false">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>{{ __('reportsearch.category_list') }}</h2>
                            </header>
                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->
                                <!-- widget content -->
                                <div class="widget-body no-padding table-responsive">
                                    <table class="table table-bordered data-table" id="customer_data" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('searchcomplaint.external_reference_no') }}</th>
                                                <th>{{ __('searchcomplaint.internal_reference_no') }}</th>
                                                <th>{{ __('searchcomplaint.complainant_identify_no') }}</th>
                                                <th>{{ __('searchcomplaint.complainant_mobile') }}</th>
                                                <th>{{ __('searchcomplaint.complainant_name') }}</th>
                                                <!-- <th>{{ __('searchcomplaint.complaint_category') }}</th> -->
                                                <th>{{ __('searchcomplaint.employer_name') }}</th>
                                                <th>{{ __('searchcomplaint.epf_no') }}</th>
                                                <th>{{ __('searchcomplaint.employee_mem_no') }}</th>
                                                <th>{{ __('searchcomplaint.current_status') }}</th>
                                                <th>{{ __('searchcomplaint.complaint_date') }}</th>
                                                <th>{{ __('searchcomplaint.labour_officer_to_assign') }}</th>
                                                <th>{{ __('searchcomplaint.complaint_office') }}</th>
                                                <!-- <th>{{ __('searchcomplaint.view') }}</th> -->
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
                    </article>
                    <!-- WIDGET END -->
                </div>
                <!-- end row -->
                <!-- end row -->
            </section>
            {{-- @endif --}}
        </div>
    </div>
    <x-slot name="script">
        {{-- <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#province-form').parsley();
            });
        </script> --}}

        <script src="{{ asset('public/back/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>

        <script src="{{ asset('public/back/js/plugin/datatable-buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.colVis.min.js') }}"></script>
        <script>
            $(".select2").select2();
        </script>
        <script>
            $(document).ready(function(){

                fill_datatable();

                function fill_datatable(external_ref_no = '', ref_no = '', complainant_identify_no = '', complainant_mobile = '', complainant_full_name = '', complaint_status = '', complain_category = '', from_date = '', to_date = '', employer_name = '', updated_status = '', epf_no = '', province_id = '', district_id = '', city_id = '', labour_officer_id = '', mail_template = '', employee_mem_no = '', labour_office = '', remark = '', status_type = '')
                {
                    var dataTable = $('#customer_data').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        ajax:{
                            url: "{{ route('report-search') }}",
                            data:{external_ref_no:external_ref_no, ref_no:ref_no, complainant_identify_no:complainant_identify_no, complainant_mobile:complainant_mobile, complainant_full_name:complainant_full_name, complaint_status:complaint_status, complain_category:complain_category, from_date:from_date, to_date:to_date, employer_name:employer_name, updated_status:updated_status, epf_no:epf_no, province_id:province_id, district_id:district_id , city_id:city_id, labour_officer_id:labour_officer_id, mail_template:mail_template, employee_mem_no:employee_mem_no, labour_office:labour_office, remark:remark, status_type:status_type}

                            // console.log(data);
                        },
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        columns: [
                            {
                                data:'external_ref_no',
                                name:'external_ref_no'
                            },
                            {
                                data:'ref_no',
                                name:'ref_no'
                            },
                            {
                                data:'complainant_identify_no',
                                name:'complainant_identify_no'
                            },
                            {
                                data:'complainant_mobile',
                                name:'complainant_mobile'
                            },
                            {
                                data:'complainant_full_name',
                                name:'complainant_full_name'
                            },
                            // {
                            //     data:'complain_category',
                            //     name:'complain_category'
                            // },
                            {
                                data:'employer_name',
                                name:'employer_name'
                            },
                            {
                                data:'epf_no',
                                name:'epf_no'
                            },
                            {
                                data:'employee_mem_no',
                                name:'employee_mem_no'
                            },
                            {
                                data:'complaint_status',
                                name:'complaint_status'
                            },
                            {
                                data:'created_at',
                                name:'created_at'
                            },
                            {
                                data:'lo_name',
                                name:'lo_name'
                            },
                            {
                                data:'office_name_en',
                                name:'office_name_en'
                            },
                            // {
                            //     data: 'id' , render : function ( data, type, row, meta ) {
                            //     return type === 'display'  ?
                            //         '<a href="{{ url('view') }}/'+ data +'" ><i class="fa fa-file-text"></i></a>' :
                            //         data;

                            //         // console.log(external_ref_no);
                            // }},
                        ],
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });

                }

                $('#button1id').click(function(){
                    var external_ref_no = $('#external_ref_no').val();
                    var ref_no = $('#ref_no').val();
                    var complainant_identify_no = $('#complainant_identify_no').val();
                    var complainant_mobile = $('#complainant_mobile').val();
                    var complainant_full_name = $('#complainant_full_name').val();
                    var complain_category = $('#complain_category').val();
                    var complaint_status = $('#complaint_status').val();
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var employer_name = $('#employer_name').val();
                    var epf_no = $('#epf_no').val();
                    var province_id = $('#province_id').val();
                    var district_id = $('#district_id').val();
                    var city_id = $('#city_id').val();
                    var labour_officer_id = $('#labour_officer_id').val();
                    var mail_template = $('#mail_template').val();
                    var updated_status = $('#updated_status').val();
                    var employee_mem_no = $('#employee_mem_no').val();
                    var labour_office = $('#labour_office').val();
                    var remark = $('#remark').val();
                    var status_type = $('#status_type').val();

                    if(external_ref_no != '' || ref_no != '' || complainant_identify_no != '' || complainant_mobile != '' || complainant_full_name != '' || complain_category != '' || from_date != '' || to_date != '' || complaint_status != '' || employer_name != '' || updated_status != '' || epf_no != '' || province_id != '' || district_id != '' || city_id != '' || labour_officer_id != '' || mail_template != '' || employee_mem_no != '' || labour_office != '' || remark != '' || status_type != '')
                    {
                        $('#customer_data').DataTable().destroy();
                        fill_datatable(external_ref_no, ref_no, complainant_identify_no, complainant_mobile, complainant_full_name, complaint_status, complain_category, from_date, to_date, employer_name, updated_status, epf_no, province_id, district_id, city_id, labour_officer_id, mail_template, employee_mem_no, labour_office, remark, status_type);
                    }
                    else
                    {

                    }
                });

                $('#reset').click(function(){
                    $('#external_ref_no').val('');
                    $('#ref_no').val('');
                    $('#complainant_identify_no').val('');
                    $('#complainant_mobile').val('');
                    $('#complainant_full_name').val('');
                    $('#complain_category').val('');
                    $('#to_date').val('');
                    $('#from_date').val('');
                    $('#employer_name').val('');
                    $('#updated_status').val('');
                    $('#epf_no').val('');
                    $('#province_id').val('');
                    $('#district_id').val('');
                    $('#city_id').val('');
                    $('#labour_officer_id').val('');
                    $('#mail_template').val('');
                    $('#employee_mem_no').val('');
                    $('#labour_office').val('');
                    $('#complaint_status').val('');
                    $('#remark').val('');
                    $('#status_type').val('');
                    $('.select2').val(null).trigger('change');
                    $('#customer_data').DataTable().destroy();
                    fill_datatable();
                });

            });
        </script>
        <script>
             $('#province_id').change(function() {

            var provinceID = $(this).val();
            var lang = $('#lang').val();

            if (provinceID) {

                $.ajax({
                    type: "GET",
                    url: "{{ url('getDistrict') }}?province_id=" + provinceID,
                    success: function(res) {

                        if (res) {
                            // console.log(res);
                            $("#district_id").empty();
                            $("#city_id").empty();
                            $('#city_id').val(null).trigger('change');
                            $('#district_id').val(null).trigger('change');
                            $("#district_id").append('<option value=""></option>');
                            $.each(res, function(key, value) {

                                if(lang == "SI") {

                                    $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_sin'] +
                                    '</option>');

                                } else if(lang == "TA") {

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

            console.log(districtID);

            if (districtID) {

                $.ajax({
                    type: "GET",
                    url: "{{ url('getCity') }}?district_id=" + districtID,
                    success: function(res) {

                        if (res) {
                            $("#city_id").empty();
                            $('#city_id').val(null).trigger('change');
                            $("#city_id").append('<option value=""></option>');
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

            $('#status_type').on('change', function() {

                var districtID = $(this).val();
                var lang = $('#lang').val();

                console.log(districtID);

                if (districtID) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getComplaintStatus') }}?status_type=" + districtID,
                        success: function(res) {

                            if (res) {
                                $("#updated_status").empty();
                                $('#updated_status').val(null).trigger('change');
                                $("#updated_status").append('<option value=""></option>');
                                $.each(res, function(key, value) {

                                    if(lang == "SI") {

                                        $("#updated_status").append('<option value="' + value['id'] + '">' + value['status_si'] +
                                        '</option>');

                                    } else if(lang == "TA") {

                                        $("#updated_status").append('<option value="' + value['id'] + '">' + value['status_ta'] +
                                        '</option>');

                                    } else {

                                        $("#updated_status").append('<option value="' + value['id'] + '">' + value['status_en'] +
                                        '</option>');
                                    }
                                });

                            } else {

                                $("#updated_status").empty();
                            }
                        }
                    });
                } else {

                    $("#updated_status").empty();
                }
                });
    </script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="js/libs/jquery-ui.min.js"><\/script>');
            }
        </script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <!-- <script type="text/javascript" src="{{ asset('public/back/js/datepicker/bootstrap-datepicker.min.js') }}"></script> -->
    <script>
        $(document).ready(function () {
            var currentDate = new Date();
            $('#from_date').datepicker({
                dateFormat: 'yy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#from_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });

        $(document).ready(function () {
            var currentDate = new Date();
            $('#to_date').datepicker({
                dateFormat: 'yy-mm-dd',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                autoclose:true,
                endDate: "currentDate",
                maxDate: currentDate
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('#to_date').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
        });
    </script>

    </x-slot>
</x-app-layout>
