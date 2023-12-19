@section('title', 'Action Pending')

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
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.action_pending') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $pendingCount }}</span></button>
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
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('actionpendinglist.leagle') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $certificateCount }}</span></button>
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
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.approve') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $pendingApprovalCount }}</span></button>
                        </a>

                        @if($office_id == 15)
                        <a href="{{ route('wca-complaint-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('actionpendinglist.wca_complaint_list') }} <br><span class="txt-color-blue" onclick="" style=" text-align: center;display: contents;">{{ $totalWcaComplaint }}</span></button>
                        </a>
                        @endif
                    </div>
                </div>
                <!-- <div class="col-lg-12">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="" style="position: relative; top: 10px;">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto;  transform: translate(0%, -12%);">
                                <a href="{{ 'action-pending-list' }}">
                                    <h5> {{ __('pendingcertificatelist.action_pending') }} <span class="txt-color-blue" onclick="" style=" text-align: center">{{ $pendingCount }}</span></h5>
                                </a>
                            </li>

                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto; max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('investigation-ongoing-list') }}">
                                    <h5> {{ __('pendingcertificatelist.investigation_ongoing') }} <span class="txt-color-blue" onclick="" style=" text-align: center">{{ $ongoingCount }}</span></h5>
                                </a>
                            </li>

                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 27px 10px !important; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('recovery-pending-list') }}">
                                    <h5> {{ __('pendingcertificatelist.recovery_pending') }} <span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $recoveryCount }}</span></h5>
                                </a>
                            </li>

                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('legal-certificate-pending-list') }}">
                                    <h5> {{ __('pendingcertificatelist.leagle') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $certificateCount }}</span></h5>
                                </a>
                            </li>

                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('plaint-chargesheet-pending-list') }}">
                                    <h5> {{ __('pendingcertificatelist.plaint') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $chargesheetCount }}</span></h5>
                                </a>
                            </li>

                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('temporary-closed-list') }}">
                                    <h5> {{ __('pendingcertificatelist.temp_close') }}<span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $tempClosedCount }}</span></h5>
                                </a>
                            </li>

                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 27px 10px !important; min-width: auto;  max-width: auto; transform: translate(0%, -12%);">
                                <a href="{{ route('closed-list') }}">
                                    <h5> {{ __('pendingcertificatelist.close') }} <span class="txt-color-blue" style=" text-align: center"><i class=""></i>{{ $closedCount }}</span></h5>
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
                                <h2>{{ __('pendingcertificatelist.title') }}</h2>
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

                                                <th width='2%' style="text-align:center;">{{ __('pendingcertificatelist.id') }}</th>
                                                <th width='21%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.ref_num') }}</th>
                                                <th width='21%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.external_ref_no') }}</th>
                                                <th style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.complaint_name') }}</th>
                                                <th width='9%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.nic') }}</th>
                                                <th width='9%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.complainant_mobile') }}</th>
                                                <th width='4%' style="text-align: center">{{ __('pendingcertificatelist.date') }}</th>
                                                <th width='2%' style="text-align:center;">{{ __('actionpendinglist.online_manual') }}</th>
                                                <th width='4%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.status') }} </th>
                                                <!-- <th width='5%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.action') }}</th> -->
                                                <th width='5%' style="text-align: center; font-size: 10px">{{ __('pendingcertificatelist.upload') }}</th>
                                                <th width='4%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.modify') }}</th>
                                                <th width='4%' style="text-align: center; font-size: 11px">{{ __('pendingcertificatelist.view') }}</th>
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
                    ajax: "{{ route('legal-certificate-pending-list') }}",
                    order: [ 0, 'desc' ],
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
                            data: 'complainant_mobile',
                            name: 'complainant_mobile'
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
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action'
                        // },
                        {
                            data: 'upload',
                            name: 'upload',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'modify',
                            name: 'modify',
                            orderable: false,
                            searchable: false
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
