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
                        @if($userrole == "Labour Officer")
                        <a href="{{ 'assign-complaint-list' }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('actionpendinglist.assign_complaint') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $assignCount }}</span></button>
                        </a>
                        @endif
                        <a href="{{ 'action-pending-list' }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('actionpendinglist.action_pending') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $pendingCount }}</span></button>
                        </a>

                        <a href="{{ route('investigation-ongoing-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.investigation_ongoing') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $ongoingCount }}</span></button>
                        </a>

                        <a href="{{ route('recovery-pending-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.recovery_pending') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $recoveryCount }}</span></button>
                        </a>

                        <a href="{{ route('appeal-pending-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('appealpendinglist.appeal') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $appealCount }}</span></button>
                        </a>

                        <a href="{{ route('legal-certificate-pending-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.leagle') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $certificateCount }}</span></button>
                        </a>

                        <a href="{{ route('plaint-chargesheet-pending-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.plaint') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $chargesheetCount }}</span></button>
                        </a>

                        <a href="{{ route('temporary-closed-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.temp_close') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $tempClosedCount }}</span></button>
                        </a>

                        <a href="{{ route('closed-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.close') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $closedCount }}</span></button>
                        </a>

                        <a href="{{ route('sent-approval-list') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('actionpendinglist.approve') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $pendingApprovalCount }}</span></button>
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

                                                <th width='5%' style="text-align:center;">{{ __('pendingapprovallist.id') }}</th>
                                                <th width='15%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.ref_num') }}</th>
                                                <th width='15%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.external_ref_num') }}</th>
                                                <th width='15%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.complaint_name') }}</th>
                                                <th width='10%' style="text-align: center">{{ __('pendingapprovallist.date') }}</th>
                                                <th width='10%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.nic') }}</th>
                                                <th width='5%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.status') }} </th>
                                                {{-- <th width='5%' style="text-align: center; font-size: 11px">{{ __('pendingapprovallist.action') }}</th> --}}
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
                    ajax: "{{ route('sent-approval-list') }}",
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
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'complainant_identify_no',
                            name: 'complainant_identify_no'
                        },
                        {
                            data: 'status',
                            name: 'status'
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
