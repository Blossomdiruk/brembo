@section('title', 'Province')

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
                        <!-- <a href="{{ route('new-exam') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">ADD NEW</button>
                        </a>

                        <a href="{{ route('exam-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">VIEW ALL</button>
                        </a> -->
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            @can('role-create')
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('complain-category') }}">
                                    <h5>{{ __('complaincategory.add_new') }}</h5>
                                </a>
                            </li>
                            @endcan
                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('complain-category-list') }}">
                                    <h5>{{ __('complaincategory.view_all') }}</h5>
                                </a>
                            </li>
                        </ul>
                    </ul>
                </div> -->
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
                                <h2>Warrenty Incidents List</h2>
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
                                    <table class="table table-bordered data-table" width="100%">
                                        <thead>
                                            <tr >
                                                <th width="5%">No</th>
                                                <th width="20%">Product ID</th>  
                                                
                                                <th width="15%">Status</th>
                                                <th width="10%">EDIT</th>
                                                <!-- <th width="10%" align="center" >ACTIVATION</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <!-- end widget content -->
                                <div class="row m-auto">
                                    <div class="col-lg-12">
                                    <form method="POST" id="excel_form_D" name="excel_form_D" action="{{ route('exportto_excel') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="warrentyState" id="warrentyState">
                                        <a class="btn btn-success btn-lg export_excel" onclick="return add_valuesto_excel_details();" > Expot to Excel </a> 

                                    </form>
                                </div>
                                </div>
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
        <script type="text/javascript">
            $(function() {

                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        url: "{{ route('warrenty-incedent-list') }}",
                        data: function (d) {
                                d.warrenty_status = $('#warrenty_status').val()
                               
                            }
                        },
                    order: [ 1, 'asc' ],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'product_id',
                            name: 'product_id'
                        },
                       
                        {
                            data: 'warrenty_status',
                            name: 'warrenty_status'
                        },   
                        {
                            data: 'edit',
                            name: 'edit',
                            "className": "text-center",
                            orderable: false,
                            searchable: false
                        },
                       
                    ]
                });
                            $('#filter_search_view').on('click', function (e) {
                                 var warrenty_status = $("#warrenty_status").val();
                                 localStorage.setItem("warrenty_status", warrenty_status);
                               
                             });

                             $('#search-form').on('submit', function (e) { 
                                 //$("#hid_itdid").val(warrenty_status);
                                
                                 table.draw();
                                 e.preventDefault();
                             });
               
               
            });
                function add_valuesto_excel_details()
                {
                    var w_status = $('#warrenty_status').val();
                    
                          
                          $.ajaxSetup({
                                 headers: {
                                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                 }
                             });

                             $.ajax({
                                 url: "{{ route('export_excel') }}",
                                 type: 'get',
                                 dataType: 'json',
                                 data: {
                                    warrenty_status : w_status,   
                                 },
                                 success: function (response) {
                                     alert(response);
                                        // if((inst == '1' && year == '' && response >3000)  || (inst =='' && year == '' && response >3000 )){
                                        //     $('#div_year').show();
                                        //     return false;
                                        // }
                                    }
                                });
                    //$('#excel_form_D').submit();
                }
        </script>
    </x-slot>
</x-app-layout>
