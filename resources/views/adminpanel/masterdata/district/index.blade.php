@section('title', 'District')
<x-app-layout>
    <x-slot name="header">

    </x-slot>

    @if(Session()->get('applocale')=='ta')
        @php
        $lang = "TA";
        @endphp
        @elseif(Session()->get('applocale')=='si')
        @php
        $lang = "SI";
        @endphp
        @else
        @php
        $lang = "EN";
        @endphp
    @endif

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
            <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;"> 
                        <a href="{{ route('district') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('district.add_new') }}</button>
                        </a>

                        <a href="{{ route('district-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('district.district_list') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('district') }}">
                                    <h5>{{ __('district.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('district-list') }}">
                                    <h5>{{ __('district.district_list') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
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
                    <h2>{{ __('district.title') }}</h2>
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
                        <form action="{{ route('new-district') }}" enctype="multipart/form-data" method="post" id="district-form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('district.province') }}<span style=" color: red;">*</span></label>
                                        <label class="select">
                                            <select name="province_id" id="province_id" required>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}">@if($lang == "SI"){{ $province->province_name_sin }}@elseif($lang == "TA"){{ $province->province_name_tamil }}@else{{ $province->province_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('district.district_name_en') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="district_name_en" name="district_name_en" required value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('district.district_name_sin') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="district_name_sin" name="district_name_sin" required value="">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('district.district_name_tamil') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="district_name_tamil" name="district_name_tamil" required value="">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('district.status') }}</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="Y">{{ __('district.status_active') }}</option>
                                                <option value="N">{{ __('district.status_inactive') }}</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('district.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('district.back') }}
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
                $('#district-form').parsley();
            });
        </script>

        <script>
            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 5000);
        </script>
    </x-slot>
</x-app-layout>
