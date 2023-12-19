@section('title', 'Mail Template')
<x-app-layout>
    <x-slot name="header">
        <style>
            .label {
                padding: 0px 0px 0px 0px !important;
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
                <div class="col-lg-4">
                </div>
                <div class="col-lg-8">
                    {{-- <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('province') }}">
                                    <h5>Add New</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('province-list') }}">
                                    <h5>View All<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
                                </a>
                            </li>
                        </ul>
                    </ul> --}}
                </div>
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
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('mailtemplate.select_mail_template') }}</label>
                                        <label class="select">
                                            <select name="mail_template_name_en" id="mail_template_name_en">
                                                <option value="">{{ __('mailtemplate.select_template') }}</option>
                                                @foreach ($mailtemplates as $mailtemplate)
                                                    <option value="{{ $mailtemplate->id }}">{{ $mailtemplate->mail_template_name_en }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('mailtemplate.other_email') }} </label>
                                        <label class="input">
                                            <input type="text" id="other_email" name="other_email" value="">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('mailtemplate.subject') }} </label>
                                        <label class="input">
                                            <input type="text" id="subject" name="subject" value="">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('mailtemplate.your_number') }} </label>
                                        <label class="input">
                                            <input type="text" id="subject" name="subject" placeholder="Enter your number if there's any" value="">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12">
                                        <label class="label">{{ __('mailtemplate.body_content_en') }} </label>
                                        <label class="input">
                                            <textarea row="5" id="summernote" name="summernote"></textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12">
                                        <label class="label">{{ __('mailtemplate.body_content_sin') }} </label>
                                        <label class="input">
                                            <textarea row="5" id="summernote" name="summernote"></textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12">
                                        <label class="label">{{ __('mailtemplate.body_content_tam') }} </label>
                                        <label class="input">
                                            <textarea row="5" id="summernote" name="summernote"></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('mailtemplate.subject') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="viewlist()">
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

        <script>
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#mail-template-form').parsley();
            });
        </script>

        <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
        {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
        {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}

        <!-- include summernote css/js -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.summernote').summernote();
            });
        </script>

        <script>
            $('#mail_template_name_en').change(function(){
                var id = $(this).val();
                var url = '{{ route("getTemplate", ":id") }}';
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function(response){
                        console.log(response);
                        if(response != null){
                            $('#summernote').val(response.body_content_en);
                            // $('#title').val(response.title);
                        }
                    }
                });
            });
        </script>

    </x-slot>
</x-app-layout>


