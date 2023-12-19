@section('title', 'Mail Template')
<x-app-layout>
    <x-slot name="header">
        <style>
            .note-editable {
                min-height: 550px !important;
            }
        </style>
    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
            <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;">
                        <a href="{{ route('mail-template') }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('province.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('mail-template') }}">
                                    <h5>{{ __('province.view_all') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
                                </a>
                            </li>
                        </ul>
                    </ul>
                </div> -->
            </div>
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
                    <h2>{{ __('mailtemplate.title') }}</h2>
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
                        <form action="{{ route('save-mail-template') }}" enctype="multipart/form-data" method="post" id="mail-template-form" class="smart-form">
                        @csrf
                        @method('PUT')
                            <fieldset>
                                <div class="row">
                                    {{-- <section class="col col-6">
                                        <label class="label">{{ __('mailtemplate.name') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="mail_template_title" name="mail_template_title" required value="{{ $data->mail_template_title }}">
                                        </label>
                                    </section> --}}
                                    <section class="col col-4">
                                        <label class="label">{{ __('mailtemplate.subject') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="mail_template_name_en" name="mail_template_name_en" required value="{{ $data->mail_template_name_en }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('mailtemplate.subject_sin') }}</label>
                                        <label class="input">
                                            <input type="text" id="mail_template_name_sin" name="mail_template_name_sin" value="{{ $data->mail_template_name_sin }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('mailtemplate.subject_tam') }}</label>
                                        <label class="input">
                                            <input type="text" id="mail_template_name_tam" name="mail_template_name_tam" required value="{{ $data->mail_template_name_tam }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('mailtemplate.other_email') }}</label>
                                        <label class="input">
                                            <input type="text" id="other_email" name="other_email" value="{{ $data->other_email }}">
                                        </label>
                                    </section>
                                      <section class="col col-4">
                                        <label class="label">{{ __('mailtemplate.category') }}</label>
                                        <label class="select">
                                        <select id="category" name="category">
                                         <option value="" {{ $data->category == " " ? "selected" : "" }}> Select</option>
                                            <option value="L" {{ $data->category == "L" ? "selected" : "" }}>Letter</option>
                                             <option value="E" {{ $data->category == "E" ? "selected" : "" }}>Email</option>
                                              <option value="ND" {{ $data->category == "ND" ? "selected" : "" }}>Notices and Directives</option>
                                        </select>
                                        <i></i>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-11"  style="width: 100%;">
                                        <label class="label">{{ __('mailtemplate.body_content_en') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control summernote" id="body_content_en" name="body_content_en" rows="3" required>{{ $data->body_content_en }}</textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-11"  style="width: 100%;">
                                        <label class="label">{{ __('mailtemplate.body_content_sin') }}</label>
                                        <label class="input">
                                            <textarea class="form-control summernote" id="body_content_sin" name="body_content_sin" rows="3">{{ $data->body_content_sin }}</textarea>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <section class="col col-11"  style="width: 100%;">
                                        <label class="label">{{ __('mailtemplate.body_content_tam') }}</label>
                                        <label class="input">
                                            <textarea class="form-control summernote" id="body_content_tam" name="body_content_tam" rows="3">{{ $data->body_content_tam }}</textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $data->id }}>">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('mailtemplate.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('mailtemplate.back') }}
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

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

        <script>
            $(document).ready(function() {

            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    // ['para', ['ul', 'ol', 'paragraph']],
                    ['para', ['paragraph']],
                    ['height', ['height']],
                    // ['table', ['table']],
                    // ['insert', ['link', 'picture', 'hr']],
                    // ['view', ['fullscreen', 'codeview', 'help']]
                    ['view', ['codeview']]

                ]
            });
        });
        </script>
        <script>
            $(function(){
                $('#mail-template-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
