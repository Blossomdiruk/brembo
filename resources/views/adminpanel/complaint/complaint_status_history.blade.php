@section('title', 'Complaint')
<x-app-layout>
    <x-slot name="header">

        {{-- <script type="text/javascript">
            $(document).ready(function(){
            $('$print').printPage();
            });
            </script> --}}

    </x-slot>

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
                        <font style=" font-size: 22px"> {{ __('complaintstatus.title') }} </font> </span>
                    </h1>
                </div>
            </div>
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('complaintstatus.internal_complaint_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('complaintstatus.external_complaint_no') }}</strong> : {{ $data->external_ref_no }}</h5>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="well well-sm">
                        <!-- Timeline Content -->
                        <div class="smart-timeline">
                            <ul class="smart-timeline-list">
                                @foreach ($complaintstatusdetails as $complaintstatusdetail)
                                    <li>
                                        <div class="smart-timeline-icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div class="smart-timeline-time">
                                            <small>{{ $complaintstatusdetail->created_at }}</small>
                                        </div>
                                        <div class="smart-timeline-content">
                                            <p><strong>{{ $complaintstatusdetail->status_des }}</strong></p>
                                            <p>&nbsp; &nbsp; &nbsp;{{ $complaintstatusdetail->remark }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- END Timeline Content -->
                    </div>
                    <footer>
                        <button type="button"  style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                        <br>
                        <br>
                    </footer>

                </div>

            </div>
            <!-- end widget -->
        </div>
    </div>
    <x-slot name="script">

        <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#register-complaint-form').parsley();
            });
        </script>

        <script>
            function addprint() {
                var refid = $(this).val('#refid');

                window.open('', '_blank');
            }

            function addexcel() {
                window.open('');
            }
        </script>


        <script type="text/javascript">
            $(document).ready(function() {
                $("#addrow").click(function(){
                    var lsthmtl = $(".clone").html();
                    $(".increment").after(lsthmtl);
                });
                $("body").on("click","#remrow",function(){
                    $(this).parents(".hdtuto").remove();
                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                var i = 0;
                $("#add").click(function(){
                    ++i;
                    var lsthmtl2 = $(".clone2").html();
                    $(".increment2").after(lsthmtl2);
                });
                $("body").on("click","#remove",function(){
                    $(this).parents(".hdtuto2").remove();
                });
            });
        </script>

        <script>
            function show_submit(cat) { //alert (cat);
                if (cat == 'T3') {
                    //alert (cat);
                    document.getElementById('button1id').style.display = "block";


                } else {
                    document.getElementById('button1id').style.display = "none";
                }
            }

            function changeactive(obj1, obj2) {
                $("#" + obj1).attr('class', 'active');
                $("#" + obj2).attr('class', '');
            }
        </script>

        {{-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}
        <script type="text/javascript">
            function unionfields() {
                if (document.getElementById("U").checked) {
                    // document.getElementById('union').style.visibility = 'visible';
                    document.getElementById('officer').style.visibility = 'visible';
                }
                else
                    // document.getElementById('union').style.visibility = 'hidden';
                    document.getElementById('officer','union').style.visibility = 'hidden';
            }
        </script>

        <script type="text/javascript">
            function complainavailable() {
                if (document.getElementById("yes").checked) {
                    // document.getElementById('union').style.visibility = 'visible';
                    document.getElementById('existcomplain').style.visibility = 'visible';
                }
                else
                    // document.getElementById('union').style.visibility = 'hidden';
                    document.getElementById('existcomplain').style.visibility = 'hidden';
                }
        </script>

    </x-slot>
</x-app-layout>
