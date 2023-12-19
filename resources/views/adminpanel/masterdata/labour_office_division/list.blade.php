@section('title', 'Labour Office & Division')

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
                        <a href="{{ route('labour-office-division') }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('labourofficedivision.add_new') }}</button>
                        </a>

                        <a href="{{ route('labour-office-division-list') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('labourofficedivision.labour_office_division_list') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('labour-office-division') }}">
                                    <h5>{{ __('labourofficedivision.add_new') }}</h5>
                                </a>

                            </li>
                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('labour-office-division-list') }}">
                                    <h5>{{ __('labourofficedivision.labour_office_division_list') }}</h5>
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
                                <h2>{{ __('labourofficedivision.labour_office_division_list') }}</h2>
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
                                    <table class="table table-bordered data-table" width="100%" id="labourOffice-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('labourofficedivision.labour_office_division_no') }}</th>
                                                <th>{{ __('labourofficedivision.office_type') }}</th>
                                                <th>{{ __('labourofficedivision.office_name_en') }}</th>
                                                <!-- <th>{{ __('labourofficedivision.office_name_sin') }}</th>
                                                <th>{{ __('labourofficedivision.office_name_tam') }}</th> -->
                                                <th>{{ __('labourofficedivision.address') }}</th>
                                                <th>{{ __('labourofficedivision.tel') }}</th>
                                                <th>{{ __('labourofficedivision.province') }}</th>
                                                <!-- <th>{{ __('labourofficedivision.district') }}</th> -->
                                                <th>{{ __('labourofficedivision.office_code') }}</th>
                                                <th>{{ __('labourofficedivision.city_list') }}</th>
                                                <th>{{ __('labourofficedivision.add_city') }}</th>
                                                <th>{{ __('labourofficedivision.edit') }}</th>
                                                <th>{{ __('labourofficedivision.status') }}</th>
                                                <th>{{ __('labourofficedivision.is_delete') }}</th>
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="page-title txt-color-blueDark" style="margin:0px;">{{ __('labourofficedivision.city_list') }}</h1>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="widget-body no-padding table-responsive">
                    <table class="table table-bordered" width="100%">
                        <thead>
                            {{-- <tr>
                                <th width='20%' style="text-align: center; font-size: 11px">{{ __('actionpendinglist.complaint_name') }}</th>
                            </tr> --}}
                            <tr class="citylist"></tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
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
                    ajax: "{{ route('labour-office-division-list') }}",
                    order: [ 1, 'asc' ],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'office_type_name_en',
                            name: 'office_type_name_en'
                        },
                        {
                            data: 'office_name_en',
                            name: 'office_name_en'
                        },
                        // {
                        //     data: 'office_name_sin',
                        //     name: 'office_name_sin'
                        // },
                        // {
                        //     data: 'office_name_tam',
                        //     name: 'office_name_tam'
                        // },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'tel',
                            name: 'tel'
                        },
                        {
                            data: 'province_name_en',
                            name: 'province_name_en'
                        },
                        // {
                        //     data: 'district_name_en',
                        //     name: 'district_name_en'
                        // },
                        {
                            data: 'office_code',
                            name: 'office_code'
                        },
                        {
                            data: 'citydetail',
                            name: 'citydetail'
                        },
                        {
                            data: 'addcity',
                            name: 'addcity'
                        },
                        {
                            data: 'edit',
                            name: 'edit',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'activation',
                            name: 'activation',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'blocklabourofficedivision',
                            name: 'blocklabourofficedivision',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

            });
            $('#labourOffice-table').on('click', '.btn-delete', function(e) {
                event.preventDefault();
                const url = $(this).attr('href');
                var id = $(this).val();
                swal({
                    title: 'Are you sure?',
                    text: 'This record will be permanantly deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes"],
                }).then(function(value) {
                    if (value == true) {
                        window.location.replace("blocklabourofficedivision/" + id);
                    }
                });
            });

            // $(document).ready(function() {
            // });
        </script>

        <script>
            $('#labourOffice-table').on('click', '.citydet', function(e) {
                    var id = $(this).val();

                    $.ajax({
                        type:'GET',
                        url:"{{ url('cityDetail') }}?id=" + id,
                        success:function(res) {
                            console.log(res);
                            $("#exampleModal").modal("show");
                            if(res != ''){
                                $(".citylist").empty();
                                $.each(res, function(key, value) {
                                $('.citylist').append('<li>' + value.city_name_en + '</li>');
                                });
                            } else {

                                $("#exampleModal").modal("show");
                                $(".citylist").empty();
                                $('.citylist').append('No data available');
                            }

                    }
                    });
                });
        </script>
    </x-slot>
</x-app-layout>
