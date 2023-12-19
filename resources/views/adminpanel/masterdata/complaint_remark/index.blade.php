@section('title', 'Status')
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
                        <a href="{{ route('complaint-remark') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('complaintremark.add_new') }}</button>
                        </a>

                        <a href="{{ route('complaint-remark-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('complaintremark.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('complaint-remark') }}">
                                    <h5>{{ __('complaintremark.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('complaint-remark-list') }}">
                                    <h5>{{ __('complaintremark.view_all') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
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
                    <h2>{{ __('complaintremark.title') }}</h2>
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
                        <form action="{{ route('new-complaint-remark') }}" enctype="multipart/form-data" method="post" id="complaint-remark-form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaintremark.complain_remark_en') }} <span style=" color: red;">*</span> </label>
                                        <label class="textarea">
                                            <textarea type="text" row="5" id="remark_en" name="remark_en" required value=""></textarea>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaintremark.complain_remark_sin') }}<span style=" color: red;">*</span> </label>
                                        <label class="textarea">
                                            <textarea type="text" row="5" id="remark_si" name="remark_si" required value=""></textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaintremark.complain_remark_tamil') }}<span style=" color: red;">*</span> </label>
                                        <label class="textarea">
                                            <textarea type="text" row="5" id="remark_ta" name="remark_ta" required value=""></textarea>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">{{ __('complaintremark.status') }}</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="Y">{{ __('complaintremark.status_active') }}</option>
                                                <option value="N">{{ __('complaintremark.status_inactive') }}</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    {{-- <input type="hidden" id="is_delete" name="is_delete" value="0"> --}}
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('complaintremark.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('complaintremark.back') }}
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
                $('#complaint-remark-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
