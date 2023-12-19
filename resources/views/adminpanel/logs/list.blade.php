@section('title', 'Log Activity')

<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('public/back/css/datatable-buttons/buttons.bootstrap4.min.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
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

            .data-table th:nth-child(2),
            .data-table th:nth-child(3) {
                width: 200px;
                max-width: 200px;
                word-break: break-all;
                white-space: pre-line;
            }
            .data-table td:nth-child(2),
            .data-table td:nth-child(3) {
                width: 200px;
                max-width: 200px;
                word-break: break-all;
                white-space: pre-line;
            }
        </style>
    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon"></div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        <font style=" font-size: 22px"> {{ __('logs.form_title') }} </font> </span>
                    </h1>
                </div>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif

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
                        <div id="search-log-form" class="smart-form">
                            {{-- @csrf --}}
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('logs.user') }} </label>
                                        <label class="select">
                                            <select name="user_id" id="user_id" required>
                                                <option value=""></option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('logs.office') }} </label>
                                        <label class="select">
                                            <select name="office_id" id="office_id" required>
                                                <option value=""></option>
                                                @foreach ($labouroffices as $labouroffice)
                                                    <option value="{{ $labouroffice->id }}">{{ $labouroffice->office_name_en }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    {{-- <section class="col col-4">
                                        <label class="label">{{ __('logs.role') }} </label>
                                        <label class="select">
                                            <select name="role_id" id="role_id" required>
                                                <option value=""></option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section> --}}
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('logs.search') }}
                                </button>
                                <button name="reset" id="reset" type="button" class="btn btn-default"> {{ __('logs.clear') }}</button>
                            </footer>
                        </div>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">
                    <!-- NEW WIDGET START -->

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->

                        <div class="jarviswidget jarviswidget-color-darken" id="user_types" data-widget-editbutton="false">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>{{ __('logs.form_title') }}</h2>
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
                                                <th>{{ __('logs.id') }}</th>
                                                <th>{{ __('logs.subject') }}</th>
                                                <th>{{ __('logs.url') }}</th>
                                                <th>{{ __('logs.method') }}</th>
                                                <th>{{ __('logs.ip') }}</th>
                                                <th>{{ __('logs.user_id') }}</th>
                                                <th>{{ __('logs.office') }}</th>
                                                <th>{{ __('logs.date') }}</th>
                                                <th>{{ __('logs.time') }}</th>
                                                {{-- <th width="100px">{{ __('logs.delete') }}</th> --}}
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

        <script src="{{ asset('public/back/js/plugin/datatable-buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-buttons/buttons.colVis.min.js') }}"></script>

        <script>
            $(document).ready(function(){ 
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('[name="_token"]').val()
                        }
                });

                fill_datatable();

                function fill_datatable(role_id,user_id,office_id)
                {
                    var dataTable = $('#customer_data').DataTable({
                        processing: true,
                        serverSide: true,
                        autoWidth: false,
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        ajax:{
                            url: "{{ route('log-activity-list') }}",
                            data:{role_id:role_id, user_id:user_id, office_id:office_id}

                            // console.log(data);
                        },
                        order: [ 0, 'desc' ],
                        columnDefs: [{
                           "defaultContent": "-",
                          "targets": "_all"
                        }],
                        columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'subject',
                            name: 'subject'
                        },
                        {
                            data: 'url',
                            name: 'url'
                        },
                        {
                            data: 'method',
                            name: 'method'
                        },
                        {
                            data: 'ip',
                            name: 'ip'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'office_name_en',
                            name: 'office_name_en'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'time',
                            name: 'time'
                        },
                        // {
                        //     data: 'blocklog',
                        //     name: 'blocklog',
                        //     orderable: false,
                        //     searchable: false
                        // },
                            // {
                            //    data: 'id' , render : function ( data, type, row, meta ) {
                            //     return type === 'display'  ?
                            //        '<a href="{{ url('view') }}/'+ data +'" ><i class="fa fa-file-text"></i></a>' :
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
                    var role_id = $('#role_id').val();
                    var user_id = $('#user_id').val();
                    var office_id = $('#office_id').val();

                    if(role_id != '' || user_id != '' || office_id != '' )
                    {
                        // console.log(office_id);
                        // alert('data coming');
                        $('#customer_data').DataTable().destroy();
                        fill_datatable(role_id,user_id,office_id);
                    }
                    else
                    {

                    }
                });

                $('#reset').click(function(){
                    $('#role_id').val('');
                    $('#user_id').val('');
                    $('#office_id').val('');
                    $('#customer_data').DataTable().destroy();
                    fill_datatable();
                });

            });
        </script>
    </x-slot>
</x-app-layout>
