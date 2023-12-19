@section('title', 'Profile')
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
                        <a href="{{ route('province') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('province.add_new') }}</button>
                        </a>

                        <a href="{{ route('province-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('province.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('province') }}">
                                    <h5>{{ __('province.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('province-list') }}">
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
                    <h2>{{ __('province.title') }}</h2>
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
                        <form action="{{ route('save-province') }}" enctype="multipart/form-data" method="post" id="province-form" class="smart-form">
                        @csrf
                        @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('province.province_name_en') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="province_name_en" name="province_name_en" required value="{{ $data->province_name_en }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('province.province_name_sin') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="province_name_sin" name="province_name_sin" required value="{{ $data->province_name_sin }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('province.province_name_tamil') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="province_name_tamil" name="province_name_tamil" required value="{{ $data->province_name_tamil }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('province.status') }}</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="Y" {{ $data->status == 'Y' ? "selected" : "" }}>{{ __('province.status_active') }}</option>
                                                <option value="N" {{ $data->status == 'N' ? "selected" : ""  }}>{{ __('province.status_inactive') }}</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $data->id }}>">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('province.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('province.back') }}
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
                $('#province-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
