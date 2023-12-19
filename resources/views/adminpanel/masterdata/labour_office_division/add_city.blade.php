@section('title', 'City')
<x-app-layout>
    <x-slot name="header">

        <style>
            .multiselect {
                height: 25% !important;
            }
        </style>

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
                <div class="col-lg-4">
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
                        <form action="{{ route('new-add-city') }}" enctype="multipart/form-data" method="post" id="new-add-city-form" class="smart-form">
                            @csrf
                            <fieldset>
                                <input type="hidden" id="office_id" name="office_id" required value="{{ $data->id }}" readonly>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Office Code<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="office_code" name="office_code" required value="{{ $data->office_code }}" readonly>
                                        </label>
                                    </section>
                                    @if($data->office_type_id == 3)
                                    <section class="col col-2">
                                        <label>Click here to sync district office cities</label><br>
                                        {{-- <input type="text" value="{{ $data->id }}" name="office_id" id="office_id"> --}}
                                        <button id="sync" name="sync" type="button" style="padding: 8px 30px;" value="{{ $data->id }}" class="btn btn-primary">
                                        Sync
                                        </button>
                                    </section>
                                    @endif
                                    <section class="col col-4">
                                        @inject('provider', 'App\Http\Controllers\Adminpanel\Masterdata\LabourOfficeDivisionController')

                                        <label class="label">Select Cities</label>
                                        <label class="select">

                                            <select multiple="multiple" size='18' id="multiselect1"  name="city_id[]" class="custom-scroll multiselect" title="Click to Select a City">
                                                @foreach ($cities as $city)

                                                    @if (in_array($city->id, $map_cities))
                                                    <option selected value="{{ $city->id }}">@if($lang == "SI"){{ $city->city_name_sin }}@elseif($lang == "TA"){{ $city->city_name_tam }}@else{{ $city->city_name_en }}@endif - ({{ $provider::getOfficeCodes($city->id) }}  )</option>
                                                    @else
                                                    <option  value="{{ $city->id }}">@if($lang == "SI"){{ $city->city_name_sin }}@elseif($lang == "TA"){{ $city->city_name_tam }}@else{{ $city->city_name_en }}@endif - ({{ $provider::getOfficeCodes($city->id) }}  )</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('city.submit') }}
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-default">{{ __('city.back') }}</a>
                                {{-- <button type="button" class="btn btn-default" onclick="viewlist()"> --}}

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
                $('#new-add-city-form').parsley();
            });

            $('#sync').on('click', function() {

                var officeId = $('#sync').val();

                    if(officeId != '') {
                        window.location.replace("sync-cities/" + officeId);
                    }
                });
        </script>

    </x-slot>
</x-app-layout>
