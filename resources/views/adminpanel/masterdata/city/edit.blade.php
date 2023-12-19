@section('title', 'City')
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
                        <a href="{{ route('city') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('city.add_new') }}</button>
                        </a>

                        <a href="{{ route('city-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('city.city_list') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('city') }}">
                                    <h5>{{ __('city.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('city-list') }}">
                                    <h5>{{ __('city.city_list') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
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
                    <h2>{{ __('city.title') }}</h2>
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
                        <form action="{{ route('save-city') }}" enctype="multipart/form-data" method="post" id="city-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('city.province') }} <span style=" color: red;">*</span></label>
                                        <label class="select">
                                            <select name="province_id" id="province_id" required>
                                                @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}" {{$data->province_id == $province->id ? 'selected' : ''}}>@if($lang == "SI"){{ $province->province_name_sin }}@elseif($lang == "TA"){{ $province->province_name_tamil }}@else{{ $province->province_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('city.district') }} <span style=" color: red;">*</span></label>
                                        <label class="select">
                                            <select name="district_id" id="district_id" required>
                                                @foreach ($districts as $district)
                                                <option value="{{ $district->id }}" {{$data->district_id == $district->id ? 'selected' : ''}}>@if($lang == "SI"){{ $district->district_name_sin }}@elseif($lang == "TA"){{ $district->district_name_tamil }}@else{{ $district->district_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('city.city_name_en') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="city_name_en" name="city_name_en" required value="{{ $data->city_name_en }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('city.city_name_sin') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="city_name_sin" name="city_name_sin" required value="{{ $data->city_name_sin }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('city.city_name_tam') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="city_name_tam" name="city_name_tam" required value="{{ $data->city_name_tam }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('city.status') }}</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="Y" {{ $data->status == 'Y' ? "selected" : "" }}>{{ __('city.status_active') }}</option>
                                                <option value="N" {{ $data->status == 'N' ? "selected" : ""  }}>{{ __('city.status_inactive') }}</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $data->id }}>">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('city.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('city.back') }}
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
            $(function() {
                //window.ParsleyValidator.setLocale('ta');
                $('#city-form').parsley();
            });
        </script>

        <script type="text/javascript">
            $('#province_id').change(function() {

                var provinceID = $(this).val();
                // console.log(provinceID);

                if (provinceID) {
                    var lang = $('#lang').val();
                    $.ajax({
                        type: "GET",
                        url: "{{ url('getDistrict') }}?province_id=" + provinceID,
                        success: function(res) {

                            if (res) {
                                // console.log(res);
                                $("#district_id").empty();
                                $("#district_id").append('<option>Select District</option>');
                                $.each(res, function(key, value) {
                                    
                                    if (lang == "SI") {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_sin'] +
                                            '</option>');

                                    } else if (lang == "TA") {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_tamil'] +
                                            '</option>');

                                    } else {

                                        $("#district_id").append('<option value="' + value['id'] + '">' + value['district_name_en'] +
                                            '</option>');
                                    }
                                });

                            } else {

                                $("#district_id").empty();
                            }
                        }
                    });
                } else {

                    $("#district_id").empty();
                }
            });
        </script>

    </x-slot>
</x-app-layout>