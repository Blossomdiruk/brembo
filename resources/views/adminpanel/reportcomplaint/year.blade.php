@section('title', 'Report Complaint')
<x-app-layout>
    <x-slot name="header">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/datatable-buttons/buttons.bootstrap4.min.css') }}">
<style>
    .parsley-errors-list {
            padding-left: 0px;
        }

        .parsley-errors-list li {
            list-style: none;
            color: red;
            font-size: 12px;
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
                        <font style=" font-size: 22px"> {{ __('reportcomplaint.title2') }} </font> </span>
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
                                        <label class="label">{{ __('reportcomplaint.from_year') }} </label>
                                        <label class="input">
                                        <input type="text" id="from_year" name="from_year" minlength="4" maxlength="4" value="" pattern="[0-4]{4}" data-parsley-type="integer" autocomplete="off">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('reportcomplaint.to_year') }} </label>
                                        <label class="input">
                                        <input type="text" id="to_year" name="to_year" minlength="4" maxlength="4" pattern="[0-9]{4}" data-parsley-type="integer" autocomplete="off">
                                        </label>
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
                                                <th>{{ __('reportcomplaint.year') }}</th>
                                                <th>{{ __('reportcomplaint.total_count') }}</th>
                                                <th>{{ __('reportcomplaint.pending_count') }}</th>
                                                <th>{{ __('reportcomplaint.closed_count') }}</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#search-complaint-form').parsley();
            });
        </script> -->

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

                function fill_datatable(from_year = '', to_year = '', labour_office = '')
                {
                    var dataTable = $('#customer_data').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        ajax:{
                            url: "{{ route('report-complaint-year') }}",
                            data:{from_year:from_year, to_year:to_year, labour_office:labour_office}

                            // console.log(data);
                        },
                        columns: [
                            {
                                data:'year',
                                name:'year'
                            },
                            {
                                data:'totalcount',
                                name:'totalcount'
                            },
                            {
                                data:'pendingcount',
                                name:'pendingcount'
                            },
                            {
                                data:'closedcount',
                                name:'closedcount'
                            },

                        ],
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });

                }

                $('#labour_office').parsley();

                $('#button1id').click( function(event) {
                        event.preventDefault();
                        // Validate all input fields.
                        var isValid = true;
                        $('#labour_office').each( function() {
                            if ($(this).parsley().validate() !== true) isValid = false;
                        });
                        if (isValid) {
                            var from_year = $('#from_year').val();
                            var to_year = $('#to_year').val();
                            var labour_office = $('#labour_office').val();

                            if(from_year == '' && to_year == '' && labour_office != ''){
                                $('#customer_data').DataTable().destroy();
                                fill_datatable(from_year, to_year, labour_office);
                            }
                            if(from_year != '' && to_year == '' && labour_office != ''){
                                $('#customer_data').DataTable().destroy();
                                fill_datatable(from_year, to_year, labour_office);
                            }
                            if(from_year == '' && to_year != '' && labour_office != ''){
                                $('#customer_data').DataTable().destroy();
                                fill_datatable(from_year, to_year, labour_office);
                            }
                            if(from_year != '' && to_year != '' && labour_office != ''){
                                $('#customer_data').DataTable().destroy();
                                fill_datatable(from_year, to_year, labour_office);
                            }
                        }
                    });

                $('#reset').click(function(){
                    $('#from_year').val('');
                    $('#to_year').val('');
                    $('#labour_office').val('');
                    $('.select2').val(null).trigger('change');
                    $('#customer_data').DataTable().destroy();
                    fill_datatable();
                });



            });

        </script>


    </x-slot>
</x-app-layout>
