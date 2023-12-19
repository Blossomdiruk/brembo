@section('title', 'Complaint')
<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        {{ __('uploaddocument.form_title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('uploaddocument.complaint_ref_no') }} : {{ $data->ref_no }} </h1> --}}
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
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ __('uploaddocument.title') }}</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <form action="{{ route('save-upload-document') }}" enctype="multipart/form-data" method="post" id="upload-document-form" class="smart-form">
                            @csrf
                            <div class="widget-body padding-10">
                                <fieldset>
                                    <div class="row after-add-more">
                                        <!------------------------doc upload-------------->

                                        <section class="col col-6">
                                            <label class="label">{{ __('registercomplaint.support_files') }}</label>
                                            <div class=" hdtuto input-group control-group lst increment">
                                                <input type="file" name="files[]" class="myfrm form-control" multiple="multiple" required/>
                                                {{-- <input type="text" id="description" name="files[]" value="" class="form-control" style="margin-top: 4px" placeholder="Report Description"> --}}
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info btn-sm add-more" id="addrow" type="button" style="background-color: #5D98CC; height: 32px; width: 100px;  padding :7px;"><i class="glyphicon glyphicon-plus"></i>{{ __('registercomplaint.add') }}</button>
                                                </div>
                                            </div>
                                        </section>

                                        <div class="clone hide">
                                            <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                                <input type="file" name="files[]" class="myfrm form-control" multiple="multiple">
                                                {{-- <input type="text" id="description" name="description[]" value="" class="form-control" style="margin-top: 4px" placeholder="Report Description"> --}}
                                                {{-- <label class="label">&nbsp;</label> --}}
                                                <div class="input-group-btn">
                                                    <button class="btn btn-danger" id="remrow" type="button" style="margin-top: 0px; height: 32px; width: 100px;  padding :7px; border-color:  #383838"><i class="glyphicon glyphicon-remove"></i> {{ __('registercomplaint.remove') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <br>

                                </fieldset>
                                <!---------------------------------------------------------Complaint information---------------------------------------------->

                                <footer>
                                    <input type="hidden" name="cSaveStatus" value="A">
                                    <input type="hidden" name="complaint_id" value="{{ $data->id }}">
                                    <input type="hidden" name="previous_url" value="{{ url()->previous() }}" >
                                    <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                        {{ __('uploaddocument.submit') }}
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="window.history.back();">
                                        {{ __('uploaddocument.back') }}
                                    </button>
                                </footer>
                            </div>
                        </form>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>
    <x-slot name="script">
        <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#upload-document-form').parsley();
            });
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

    </x-slot>
</x-app-layout>
