@section('title', 'Pending Approval')

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
                        <a href="{{ route('pending-approval-list') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('pendingapprovallist.pending') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $pendingCount }}</span></button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                    <ul id="sparks" class="" style="position: relative; top: 10px;">
                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto;  transform: translate(0%, -12%);">
                                <a href="{{ route('pending-approval-list') }}">
                                    <h5> {{ __('pendingapprovallist.pending') }} <span class="txt-color-blue" onclick="" style=" text-align: center">{{ $pendingCount }}</span></h5>
                                </a>

                            </li> -->
                            <!-- <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('approved-list') }}">
                                    <h5> {{ __('rejectedlist.approved') }} <span class="txt-color-blue" onclick="" style=" text-align: center">{{ $approveCount }}</span></h5>
                                </a>

                            </li>

                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('rejected-list') }}">
                                    <h5> {{ __('rejectedlist.rejected') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $rejectCount }}</span></h5>
                                </a>

                            </li> -->
                        <!-- </ul>
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
                                <h2>{{ __('pendingapprovallist.title') }}</h2>
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
                                            <tr>

                                                <th width='2%' style="text-align:center;">{{ __('pendingapprovallist.id') }}</th>
                                                <th width='15%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.ref_num') }}</th>
                                                <th width='15%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.external_ref_num') }}</th>
                                                <th width='15%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.complaint_name') }}</th>
                                                <th width='10%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.nic') }}</th>
                                                <th width='10%' style="text-align: center">{{ __('pendingapprovallist.date') }}</th>
                                                <th width='2%' style="text-align:center;">{{ __('actionpendinglist.online_manual') }}</th>
                                                <th width='5%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.status') }} </th>
                                                <th width='5%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.action') }}</th>
                                                <th width='5%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.view') }}</th>
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
        <script type="text/javascript">
            $(function() {


                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [ 0, 'asc' ],
                    ajax: "{{ route('pending-approval-list') }}",
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
                            data: 'complainant_full_name',
                            name: 'complainant_full_name'
                        },
                        {
                            data: 'complainant_identify_no',
                            name: 'complainant_identify_no'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'online_manual',
                            name: 'online_manual'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                        {
                            data: 'view',
                            name: 'view',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });


            });
        </script>
    </x-slot>
</x-app-layout>
