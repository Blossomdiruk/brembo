@section('title', 'SMS Template')
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
                <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;">
                        <a href="{{ route('sms-template') }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('province.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('sms-template') }}">
                                    <h5>{{ __('smstemplate.view_all') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
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
                    <h2>{{ __('smstemplate.title') }}</h2>
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
                        <form action="{{ route('save-sms-template') }}" enctype="multipart/form-data" method="post" id="sms-template-form" class="smart-form">
                        @csrf
                        @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('smstemplate.sms_template_name_en') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="sms_template_name_en" name="sms_template_name_en" required value="{{ $data->sms_template_name_en }}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('smstemplate.body_content_en') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" id="body_content_en" name="body_content_en" required rows="3" >{{ $data->body_content_en }}</textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('smstemplate.sms_template_name_sin') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="sms_template_name_sin" name="sms_template_name_sin" required value="{{ $data->sms_template_name_sin }}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('smstemplate.body_content_sin') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" id="body_content_sin" name="body_content_sin" required rows="3">{{ $data->body_content_sin }}</textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('smstemplate.sms_template_name_tam') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="sms_template_name_tam" name="sms_template_name_tam" required value="{{ $data->sms_template_name_tam }}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('smstemplate.body_content_tam') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" id="body_content_tam" name="body_content_tam" required rows="3">{{ $data->body_content_tam }}</textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('smstemplate.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('smstemplate.back') }}
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
                $('#sms-template-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
