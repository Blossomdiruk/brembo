@section('title', 'Labour Office & Division')
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
                        <a href="{{ route('labour-office-division') }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('labourofficedivision.add_new') }}</button>
                        </a>

                        <a href="{{ route('labour-office-division-list') }}">
                            <button class="btn cms_top_btn top_btn_height">{{ __('labourofficedivision.labour_office_division_list') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('labour-office-division') }}">
                                    <h5>{{ __('labourofficedivision.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('labour-office-division-list') }}">
                                    <h5>{{ __('labourofficedivision.labour_office_division_list') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
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
                    <h2>{{ __('labourofficedivision.title') }}</h2>
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
                        <form action="{{ route('save-labour-office-division') }}" enctype="multipart/form-data" method="post" id="labour-office-division-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="lang" id="lang" value="{{ $lang }}">
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.office_type') }} <span style=" color: red;">*</span></label>
                                        <label class="input">
                                            <input type="hidden" id="officetypeid" value="{{ $data->office_type_id }}">
                                            <input type="text" id="office_type_id" name="office_type_id" required value="@if($lang == "TA"){{ $data->officetypes->office_type_name_tam }}@elseif($lang == "SI"){{ $data->officetypes->office_type_name_sin }}@else{{ $data->officetypes->office_type_name_en }}@endif" required readonly>
                                        </label>
                                    </section>

                                    <section class="col col-4" id="sec_province">
                                        <label class="label">{{ __('labourofficedivision.province') }}</label>
                                        <label class="select">
                                            <select name="province_id" id="province_id">
                                                <option value=""></option>
                                                @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}" {{$data->province_id == $province->id ? 'selected' : ''}}>@if($lang == "TA"){{ $province->province_name_tamil }}@elseif($lang == "SI"){{ $province->province_name_sin }}@else{{ $province->province_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>

                                    <section class="col col-4" id="sec_zone">
                                        <label class="label">{{ __('labourofficedivision.zone') }}</label>
                                        <label class="select">
                                            <select name="zone_id" id="zone_id">
                                                <option value=""></option>
                                                @foreach ($zone as $zn)
                                                <option value="{{ $zn->id }}" {{$data->zone_id == $zn->id ? 'selected' : ''}}>@if($lang == "TA"){{ $zn->office_name_tam }}@elseif($lang == "SI"){{ $zn->office_name_sin }}@else{{ $zn->office_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <section class="col col-4" id="sec_district">
                                        <label class="label">{{ __('labourofficedivision.district') }} </label>
                                        <label class="select">
                                            <select name="district_id" id="district_id" >
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}" {{ $data->district_id == $district->id ? 'selected' : ''}}>@if($lang == "TA"){{ $district->office_name_tam }}@elseif($lang == "SI"){{ $district->office_name_sin }}@else{{ $district->office_name_en }}@endif</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </section>

                                    {{-- <section class="col col-4" id="sec_subDistrict">
                                        <label class="label">{{ __('labourofficedivision.subdistrict') }}</label>
                                        <label class="select">
                                            <select name="sub_district_id" id="sub_district_id">
                                            </select>
                                            <i></i>
                                        </label>
                                    </section> --}}
                                </div>

                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.office_name_en') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="office_name_en" name="office_name_en" required value="{{ $data->office_name_en }}">
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.office_name_sin') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="office_name_sin" name="office_name_sin" required value="{{ $data->office_name_sin }}">
                                        </label>
                                    </section>

                                    <section class="col col-3">
                                        <label class="label">{{ __('labourofficedivision.office_name_tam') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="office_name_tam" name="office_name_tam" required value="{{ $data->office_name_tam }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.address') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ $data->address }}</textarea>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.address_sin') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" id="address_sin" name="address_sin" rows="3" required>{{ $data->address_sin }}</textarea>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.address_tam') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <textarea class="form-control" id="address_tam" name="address_tam" rows="3" required>{{ $data->address_tam }}</textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">

                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.tel') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="tel" name="tel" required value="{{ $data->tel }}">
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.fax') }}</label>
                                        <label class="input">
                                            <input type="text" id="fax" name="fax" value="{{ $data->fax }}">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.email') }} </label>
                                        <label class="input">
                                            <input type="text" id="email" name="email" value="{{ $data->email }}">
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.letter_head') }} (594 x 145)</label>
                                        <label class="input">
                                            <input type="file" class="form-control form-input" id="letter_head" name="letter_head" style="overflow: hidden;">
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <img id="preview-image-before-upload" src="../../storage/app/{{ $data->letter_head }}" alt="preview image" style="max-height: 250px;">
                                    </section>
                                </div>

                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.office_code') }} <span style=" color: red;">*</span></label>
                                        <label class="input">
                                            <input type="text" id="office_code" name="office_code" required value="{{ $data->office_code }}">
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('labourofficedivision.status') }}</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="Y" {{ $data->status == 'Y' ? "selected" : "" }}>{{ __('labourofficedivision.status_active') }}</option>
                                                <option value="N" {{ $data->status == 'N' ? "selected" : ""  }}>{{ __('labourofficedivision.status_inactive') }}</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $data->id }}>">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('labourofficedivision.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('labourofficedivision.back') }}
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
                $('#labour-office-division-form').parsley();
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function(e) {


                $('#letter_head').change(function() {

                    let reader = new FileReader();

                    reader.onload = (e) => {

                        $('#preview-image-before-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(this.files[0]);

                });

            });


            $('#province_id').change(function() {

                var provinceID = $(this).val();
                var lang = $('#lang').val();

                if (provinceID) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getZone') }}?province_id=" + provinceID,
                        success: function(res) {

                            if (res) {
                                // console.log(res);
                                $("#zone_id").empty();
                                $("#zone_id").append('<option value="">Select Zone</option>');
                                $.each(res, function(key, value) {

                                    // console.log(res);

                                    // $("#zone_id").append('<option value="' + key['id'] + '">' + value['office_name_tam'] +
                                    //     '</option>');

                                    if(lang == "SI") {

                                        $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_sin'] +
                                        '</option>');

                                    } else if(lang == "TA") {

                                        $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_tam'] +
                                        '</option>');

                                    } else {

                                        $("#zone_id").append('<option value="' + value['id'] + '">' + value['office_name_en'] +
                                        '</option>');
                                    }

                                });

                            } else {

                                $("#zone_id").empty();
                            }
                        }
                    });

                } else {

                    $("#zone_id").empty();
                    $("#district_id").empty();
                }
                });

                // when state dropdown changes
                $('#zone_id').on('change', function() {

                    var zoneID = $(this).val();
                    var lang = $('#lang').val();

                if (zoneID) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('getLabourDistrict') }}?zone_id=" + zoneID,
                        success: function(res) {

                            if (res) {
                                $("#district_id").empty();
                                $("#district_id").append('<option value="">Select District</option>');
                                $.each(res, function(key, value) {

                                        if(lang == "SI") {

                                            $("#district_id").append('<option value="' + value['id'] + '">' + value['office_name_sin'] +
                                            '</option>');

                                        } else if(lang == "TA") {

                                            $("#district_id").append('<option value="' + value['id'] + '">' + value['office_name_tam'] +
                                            '</option>');

                                        } else {

                                            $("#district_id").append('<option value="' + value['id'] + '">' + value['office_name_en'] +
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

        <script type="text/javascript">
            $(document).ready(function() {
                var officeid = $('#officetypeid').val();
                console.log(officeid);

                if (officeid == 1 || officeid == 2) {
                    $("#sec_province").hide();
                    $("#sec_zone").hide();
                    $("#sec_district").hide();
                    $("#sec_subDistrict").hide();

                } else if (officeid == 3) {
                    $("#sec_zone").hide();
                    $("#sec_district").hide();
                    $("#sec_subDistrict").hide();

                } else if (officeid == 4) {

                    $("#sec_subDistrict").hide();
                    $("#sec_district").hide();

                } else {
                    $("#sec_province").show();
                    $("#sec_zone").show();
                    $("#sec_district").show();
                    $("#sec_subDistrict").show();
                }
            });
        </script>

    </x-slot>


</x-app-layout>
