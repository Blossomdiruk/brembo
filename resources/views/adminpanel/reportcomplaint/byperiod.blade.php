@section('title', 'Report Complaint')
<x-app-layout>
    <style>
        .datepicker { 
            z-index: 9999 !important;
        }
    .parsley-errors-list {
            padding-left: 0px;
        }

        .parsley-errors-list li {
            list-style: none;
            color: red;
            font-size: 12px;
        }
    </style>
<x-slot name="header">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/datatable-buttons/buttons.bootstrap4.min.css') }}">
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
                        <font style=" font-size: 22px"> {{ __('reportcomplaint.title_period') }} </font> </span>
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
                                        <label class="label">{{ __('searchcomplaint.labour_office') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="labour_office" id="labour_office" class="select2">
                                                <option value=""></option>
                                                @foreach ($officedivisions as $loOffice)
                                                <option value="{{ $loOffice->id }}">@if($lang == "SI"){{ $loOffice->office_name_sin }}@elseif($lang == "TA"){{ $loOffice->office_name_tam }}@else{{ $loOffice->office_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('searchcomplaint.period') }} </label>
                                        <!-- <label class="select"> -->
                                            <select name="period" id="period" class="select2">
                                                <option value=""></option>
                                                @if($lang == "SI"){
                                                    <option value="<01">මාස 01 ට අඩු</option>
                                                    <option value="01>03">මාස 01-03 අතර</option>
                                                    <option value="03>06">මාස 03-06 අතර</option>
                                                    <option value="06>12">මාස 06 - අවුරුදු 01 අතර</option>
                                                    <option value="1<">අවුරුදු 01 ට වැඩි</option>
                                                } @elseif($lang == "TA"){
                                                    <option value="<01">01 மாதத்திற்கும் குறைவானது</option>
                                                    <option value="01>03">01-03 மாதங்களுக்கு இடையில்</option>
                                                    <option value="03>06">03-06 மாதங்களுக்கு இடையில்</option>
                                                    <option value="06>12">06 மாதத்திற்கும் - 01 வருடத்திற்கு இடையில்</option>
                                                    <option value="1<">01 வருடத்திற்கு மேல்</option>
                                                } @else {
                                                    <option value="<01">Less than 01 month</option>
                                                    <option value="01>03">Between 01-03 months</option>
                                                    <option value="03>06">Between 03-06 months</option>
                                                    <option value="06>12">Between 06 months- 01 Year</option>
                                                    <option value="1<">Over 01 Year</option>
                                                }
                                                @endif
                                            </select>
                                            <i></i>
                                        <!-- </label> -->
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('reportcomplaint.search') }}
                                </button>
                                <button name="reset" id="reset" type="button" class="btn btn-default"> {{ __('reportcomplaint.clear') }}</button>
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
                                <h2>{{ __('reportcomplaint.sub_title') }}</h2>
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
            $(document).ready(function(){

                fill_datatable();

                function fill_datatable(period = '', labour_office = '')
                {
                    var dataTable = $('#customer_data').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        ajax:{
                            url: "{{ route('report-complaint-by-period') }}",
                            data:{period:period, labour_office:labour_office}

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
                $('#labour_office').parsley();
                $('#button1id').click(function(){
                    var period = $('#period').val();
                    var labour_office = $('#labour_office').val();

                    if(period == '' && labour_office != ''){
                        $('#customer_data').DataTable().destroy();
                        fill_datatable(period, labour_office);
                    }
                    if(period != '' && labour_office != '')
                    {
                        $('#customer_data').DataTable().destroy();
                        fill_datatable(period, labour_office);
                    }
                    else
                    {
                        //alert('Select a filter option');
                    }
                });

                $('#reset').click(function(){
                    $('#period').val("");
                    $('#labour_office').val('');
                    $('.select2').val(null).trigger('change');
                    $('#customer_data').DataTable().destroy();
                    fill_datatable();
                });

            });

        </script>

    </x-slot>
</x-app-layout>
