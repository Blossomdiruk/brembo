@section('title', 'Event')

<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            #sparks li {
                display: inline-block;
                max-height: 47px;
                overflow: hidden;
                text-align: left;
                box-sizing: content-box;
                -moz-box-sizing: content-box;
                -webkit-box-sizing: content-box;
                width: 95px;
            }

            #sparks li h5 {
                color: #555;
                float: none;
                font-size: 11px;
                font-weight: 400;
                margin: -3px 0 0 0;
                padding: 0;
                border: none;
                font-weight: 900;
                text-transform: uppercase;
                webkit-transition: all 500ms ease;
                -moz-transition: all 500ms ease;
                -ms-transition: all 500ms ease;
                -o-transition: all 500ms ease;
                transition: all 500ms ease;
                text-align: center;
            }

            #sparks li span {
                color: #324b7d;
                display: block;
                font-weight: 900;
                margin-top: 5px;
                webkit-transition: all 500ms ease;
                -moz-transition: all 500ms ease;
                -ms-transition: all 500ms ease;
                -o-transition: all 500ms ease;
                transition: all 500ms ease;
            }

            #sparks li h5:hover {
                color: #999999;
            }

            #sparks li span:hover {
                color: #ffffff;
            }
        </style>
    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon"></div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;">
                        <a href="{{ route('event-list') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('event.view_all') }}</button>
                        </a>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">
                    <!-- NEW WIDGET START -->

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->

                        <div class="jarviswidget jarviswidget-color-darken" id="user_types" data-widget-editbutton="false">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>{{ __('event.event_list') }}</h2>
                            </header>
                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->
                                <!-- widget content -->
                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                <div id="search-log-form" class="smart-form">
                                    {{-- @csrf --}}
                                    <fieldset>
                                        <div class="row input-daterange">
                                        <section class="col col-4">
                                            <label class="label">{{ __('searchcomplaint.labour_office') }}</label>
                                            <select class="select2" id="office_id" name="office_id">
                                                    <option value=""></option>
                                                    @foreach ($labouroffices as $labouroffice)
                                                    <option value="{{ $labouroffice->id }}">{{ $labouroffice->office_name_en }}</option>
                                                    @endforeach
                                                </select> <i></i>
                                        </section>
                                    </div>
                                    </fieldset>
                                    <footer>
                                        <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                            {{ __('logs.search') }}
                                        </button>
                                        <button name="reset" id="reset" type="button" class="btn btn-default"> {{ __('logs.clear') }}</button>
                                    </footer>
                                </div>

                                <div class="widget-body no-padding table-responsive">
                                    <table class="table table-bordered data-table" width="100%" id="event-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('event.no') }}</th>
                                                <th>{{ __('event.ref_num') }}</th>
                                                <th>{{ __('event.external_ref_no') }}</th>
                                                <th>{{ __('event.complaint_name') }}</th>
                                                <th>{{ __('calendar.event_title') }}</th>
                                                <th>{{ __('calendar.event_date') }}</th>
                                                <th>{{ __('calendar.start_time') }}</th>
                                                <th>{{ __('calendar.end_time') }}</th>
                                                <th>{{ __('event.edit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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
        </div>
    </div>
    <x-slot name="script">
        <script src="{{ asset('public/back/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>

        {{-- <script type="text/javascript">
            $(function() {
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('event-list') }}",
                    order: [ 0, 'asc' ],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'ref_no',
                            name: 'ref_no'
                        },
                        {
                            data: 'external_ref_no',
                            name: 'external_ref_no'
                        },
                        {
                            data: 'complainant_f_name',
                            name: 'complainant_f_name'
                        },
                        {
                            data: 'event_title',
                            name: 'event_title'
                        },
                        {
                            data: 'event_date',
                            name: 'event_date'
                        },
                        {
                            data: 'start_time',
                            name: 'start_time',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'end_time',
                            name: 'end_time',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'edit',
                            name: 'edit',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

            });
        </script> --}}
        <script>
            $(document).ready(function(){ 
                // $.ajaxSetup({
                //         headers: {
                //             'X-CSRF-TOKEN': $('[name="_token"]').val()
                //         }
                // });

                fill_datatable();

                function fill_datatable(office_id)
                {
                    var dataTable = $('.data-table').DataTable({
                        processing: true,
                        serverSide: true,
                        autoWidth: false,
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        ajax:{
                            url: "{{ route('event-list') }}",
                            data:{office_id:office_id}

                            // console.log(data);
                        },
                        order: [ 0, 'desc' ],
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                            }],
                        columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'ref_no',
                            name: 'ref_no'
                        },
                        {
                            data: 'external_ref_no',
                            name: 'external_ref_no'
                        },
                        {
                            data: 'complainant_f_name',
                            name: 'complainant_f_name'
                        },
                        {
                            data: 'event_title',
                            name: 'event_title'
                        },
                        {
                            data: 'event_date',
                            name: 'event_date'
                        },
                        {
                            data: 'start_time',
                            name: 'start_time',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'end_time',
                            name: 'end_time',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'edit',
                            name: 'edit',
                            orderable: false,
                            searchable: false
                        }],
                        // dom: 'Blfrtip',
                        // buttons: [
                        //     'copy', 'csv', 'excel', 'pdf', 'print'
                        // ]
                    });

                }

                $('#button1id').click(function(){
                    var office_id = $('#office_id').val();

                    if(office_id != '' )
                    {
                        // console.log(office_id);
                        // alert('data coming');
                        $('.data-table').DataTable().destroy();
                        fill_datatable(office_id);
                    }
                    else
                    {

                    }
                });

                $('#reset').click(function(){
                    $('#office_id').val('');
                    $('.data-table').DataTable().destroy();
                    fill_datatable();
                });

            });
        </script>
    </x-slot>
</x-app-layout>
