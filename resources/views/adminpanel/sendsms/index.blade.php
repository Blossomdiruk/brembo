@section('title', 'Send SMS')
<x-app-layout>
    <x-slot name="header">
        <!-- include summernote css/js -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
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
                    <h2>{{ __('sendsms.title') }}</h2>
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
                        <form action="{{ route('send-sms-to') }}" enctype="multipart/form-data" method="post" id="sms-form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row">
                                <div class="input-group hdtuto2 control-group lst increment2" style="width: 100%;">
                                    <section class="col col-4">
                                        <label class="label">{{ __('sendsms.mobile') }}<span style=" color: red;">*</span> </label>
                                            <label class="input">
                                                <input type="tel" id="complainant_mobile" required name="complainant_mobile[]" class="required" value="" pattern="[0-9]{10}">
                                            </label>
                                    </section>
                                    <section class="col col-1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="addrow" type="button" style="background-color: #5D98CC;height: 32px; width: 100%; margin-top:23px; padding :7px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;{{ __('registercomplaint.add') }}</button>
                                        </div>
                                    </section>
                                    </div>
                                </div>
                                <div class="clone2 hide">
                                <div class="hdtuto2 control-group lst input-group" style="margin-top:10px; width: 100%;">
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="tel" id="complainant_mobile" name="complainant_mobile[]" class="required" value="" pattern="[0-9]{10}">
                                            </label>
                                        </section>
                                        <section class="col col-1">
                                            <div class="input-group-btn">
                                            <button type="button" class="btn btn-danger" id="remove" style="height: 32px; width: 100%; margin-top:0px; padding :7px; border-color:  #383838"><i class="glyphicon glyphicon-remove"></i></i>&nbsp;{{ __('registercomplaint.remove') }}</button>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                                <div class="row">
                                    <section class="col col-10" style="width: 100%;">
                                        <label class="label">{{ __('sendsms.message') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" required id="message" name="message" rows="5"> </textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('sendsms.submit') }}
                                </button>
                            </footer>
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
                $('#sms-form').parsley();
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.summernote').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                    //['para', ['ul', 'ol', 'paragraph']],
                        //['height', ['height']],
                    // ['table', ['table']],
                        //['insert', ['link', 'picture', 'hr']],
                        //['view', ['fullscreen', 'codeview', 'help']]

                    ]
                });
            });
        </script>

<script type="text/javascript">
            $(document).ready(function() {
                var i = 0;
                $("#addrow").click(function() {
                    ++i;
                    var lsthmtl2 = $(".clone2").html();
                    $(".increment2").after(lsthmtl2);
                });
                $("body").on("click", "#remove", function() {
                    $(this).parents(".hdtuto2").remove();
                });
            });
        </script>
    </x-slot>
</x-app-layout>
