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
                        <a href="{{ route('complain-category') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('complaincategory.add_new') }}</button>
                        </a>

                        <a href="{{ route('complain-category-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('complaincategory.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('complain-category') }}">
                                    <h5>{{ __('complaincategory.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('complain-category-list') }}">
                                    <h5>{{ __('complaincategory.view_all') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
                                </a>
                            </li>
                        </ul>
                    </ul>
                </div> -->
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
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
                    <h2>{{ __('complaincategory.title') }}</h2>
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
                        <form action="{{ route('save-complain-category') }}" enctype="multipart/form-data" method="post" id="category-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.category_name_en') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="category_name_en" name="category_name_en" required value="{{ $data->category_name_en }}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.category_name_si') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="category_name_si" name="category_name_si" required value="{{ $data->category_name_si }}">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.category_name_ta') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="category_name_ta" name="category_name_ta" required value="{{ $data->category_name_ta }}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.expiry_days') }}<span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="expiry_days" name="expiry_days" required value="{{ $data->expiry_days }}" data-parsley-type="integer">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.category_prefix') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="category_prefix" name="category_prefix" required value="{{ $data->category_prefix }}" data-parsley-maxlength="1"  >
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.order') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="order" name="order" required value="{{ $data->order }}" data-parsley-type="integer">
                                        </label>
                                    </section>                                    
                                </div>
                                <div class="row">                                   
                                    <section class="col col-6">
                                        <label class="label">{{ __('complaincategory.status') }}</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="Y" {{ $data->status == 'Y' ? "selected" : "" }}>{{ __('complaincategory.status_active') }}</option>
                                                <option value="N" {{ $data->status == 'N' ? "selected" : ""  }}>{{ __('complaincategory.status_inactive') }}</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $data->id }}>">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                {{ __('complaincategory.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                {{ __('complaincategory.back') }}
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
                $('#category-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
