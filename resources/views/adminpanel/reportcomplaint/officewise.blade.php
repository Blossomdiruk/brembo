@section('title', 'Report Complaint')
<x-app-layout>
<style>
        .datepicker { 
            z-index: 9999 !important;
        }
        .select2-selection__rendered {
        padding-left: 5px !important;
    }
    </style>
    <x-slot name="header">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/datatable-buttons/buttons.bootstrap4.min.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<!-- <link rel="stylesheet" href="{{ asset('public/back/css/datepicker/bootstrap-datepicker3.min.css') }}" /> -->

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
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        <font style=" font-size: 22px"> {{ __('reportcomplaint.officewise') }} </font> </span>
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
                                        <label class="label">{{ __('reportcomplaint.year') }} ( Example :<font style=" color:  #008000"> 2022 </font> )</label>
                                        <label class="input">
                                        <input type="text" id="year" name="year" minlength="4" maxlength="4" required value="" pattern="[0-4]{4}" data-parsley-type="integer" >
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportcomplaint.month') }} </label>
                                        <select name="month" id="month" class="select2" required>
                                                <option value=""></option>
                                                <option value='01'>Janaury</option>
                                                <option value='02'>February</option>
                                                <option value='03'>March</option>
                                                <option value='04'>April</option>
                                                <option value='05'>May</option>
                                                <option value='06'>June</option>
                                                <option value='07'>July</option>
                                                <option value='08'>August</option>
                                                <option value='09'>September</option>
                                                <option value='10'>October</option>
                                                <option value='11'>November</option>
                                                <option value='12'>December</option>
                                            </select>
                                            <i></i>
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
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.lo') }}</th>
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.onging_cases') }} <span class="from_date"></span> {{ __('reportcomplaint.viewed_on') }} <span class="to_date"></span></th>
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.previous_ongoing') }} <span class="from_date"></span> & <span class="to_date"></span></th>
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.actual_balance') }} <span class="from_date"></th>
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.received') }}</th>
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.settled') }}</th>
                                                <th rowspan="2" style="text-align:center;">{{ __('reportcomplaint.balance') }}</th>
                                                <th colspan="4" style="text-align:center;">{{ __('reportcomplaint.time_analysis') }}</th>
                                                
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">{{ __('reportcomplaint.onemonth') }}</th>
                                                <th style="text-align:center;">{{ __('reportcomplaint.threemonth') }}</th>
                                                <th style="text-align:center;">{{ __('reportcomplaint.morethanthree') }}</th>
                                                <th style="text-align:center;">{{ __('reportcomplaint.total') }}</th>
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

                function fill_datatable(year = '', month = '')
                {
                    var dataTable = $('#customer_data').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        ajax:{
                            url: "{{ route('report-complaint-officewise') }}",
                            data:{year:year, month:month}

                            // console.log(data);
                        },
                        columns: [
                            {
                                data:'office',
                                name:'office'
                            },
                            {
                                data:'previousbalance',
                                name:'previousbalance'
                            },
                            {
                                data:'settledprevious',
                                name:'settledprevious'
                            },
                            {
                                data:'previoustotalcount',
                                name:'previoustotalcount'
                            },
                            {
                                data:'receivedcount',
                                name:'receivedcount'
                            },
                            {
                                data:'settledcount',
                                name:'settledcount'
                            },
                            {
                                data:'balancecount',
                                name:'balancecount'
                            },
                            {
                                data:'lessthanone',
                                name:'lessthanone'
                            },
                            {
                                data:'lessthanthree',
                                name:'lessthanthree'
                            },
                            {
                                data:'morethanthree',
                                name:'morethanthree'
                            },
                            {
                                data:'total',
                                name:'total'
                            },
                            
                        ],
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });

                }

                $('#month').parsley();
                $('#year').parsley();

                $('#button1id').click( function(event) {
                        event.preventDefault();
                        // Validate all input fields.
                        var isValid = true;
                        $('#month').each( function() {
                            if ($('#month').parsley().validate() !== true) isValid = false;
                        });
                        $('#year').each( function() {
                            if ($(this).parsley().validate() !== true) isValid = false;
                        });

                        // if ($('#year').val() != "" && $(select_elem).siblings('ul.parsley-errors-list').length > 0) {
                        //     $(select_elem).siblings('ul.parsley-errors-list').children('span').remove();
                        // }
                        if (isValid) {
                            var year = $('#year').val();
                            var month = $('#month').val();

                            $('#customer_data').DataTable().destroy();
                            fill_datatable(year, month);

                            var from_date = new Date(year, month -1, 1);
                            $('.from_date').html(formatDate(from_date));

                            var to_date = new Date(year, month, 0);
                            $('.to_date').html(formatDate(to_date));
                        }
                    });

                $('#reset').click(function(){
                    $('#year').val('');
                    $('#month').val('');
                    $('.select2').val(null).trigger('change');
                    $('#customer_data').DataTable().destroy();
                    fill_datatable();
                });

                function formatDate(date) {
                    var d = new Date(date),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2) 
                        month = '0' + month;
                    if (day.length < 2) 
                        day = '0' + day;

                    return [year, month, day].join('-');
                }

            });

        </script>
    </x-slot>
</x-app-layout>
