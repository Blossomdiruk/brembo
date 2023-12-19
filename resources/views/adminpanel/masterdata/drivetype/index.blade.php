@section('title', 'Profile')
@php
    if ($savestatus == 'A'){
    $name = '';
    $status = '';
    }else{
    $name = $info[0]->name;
    $status = $info[0]->status;
    }
    @endphp
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
                        <a href="{{ route('new-drivetype') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">ADD NEW</button>
                        </a>

                        <a href="{{ route('drivetype-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">VIEW ALL</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            @can('role-create')
                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('complain-category') }}">
                                    <h5>{{ __('complaincategory.add_new') }}</h5>
                                </a>
                            </li>
                            @endcan
                            @can('role-list')
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('complain-category-list') }}">
                                    <h5>{{ __('complaincategory.view_all') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
                                </a>
                            </li>
                            @endcan
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
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"  >×</button>       
                <p>{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('danger'))
            
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"  >×</button>       
                <p>{{ $message }}</p>
            </div>
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                    <h2>Drive type</h2>
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
                        <form action="{{ route('new-drivetype') }}" enctype="multipart/form-data" method="post" id="store_details_form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row ">
                                    <section class="col col-6">
                                        <label class="label">Drive Type Name <span style=" color: red;">*</span> </label>
                                        <label class="input inp-holder">
                                            <input type="text" id="name" name="name" required value="{{$name}}">
                                        </label>
                                    </section>
                                    
                                </div>

                                
                                
                                <div class="row ">
                                   
                                    <section class="col col-6">
                                        <label class="label">Status</label>
                                        <label class="select">
                                            <select name="status" id="status">
                                                <option value="1" @if( $status == '1') selected="selected" @endif>Active</option>
                                                <option value="0" @if( $status == '0') selected="selected" @endif>Inactive</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                @if($savestatus !='A')
            <input type="hidden" id="id" name="id" value="{{encrypt($info[0]->id)}}" />
            @endif
            <input type="hidden" id="savestatus" name="savestatus" value="{{$savestatus}}" />
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    Back
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
                $('#category-form').parsley();
            });
        </script>
           <script>
        $(document).ready(function() {
//            $("[data-hide]").on("click", function() {
//                $(this).closest("." + $(this).attr("data-hide")).hide();
//            });
//            $(".selectpicker, .multiselect").chosen({
//
//                disable_search_threshold: 5,
//                search_contains: false,
//                enable_split_word_search: false,
//                single_backstroke_delete: false,
//                allow_single_deselect: true,
//                display_selected_options: false
//            });
//            $('.blocks').on('click', 'a.chosen-single', function() {
//                if ($(this).next().width() < $(this).outerWidth()) {
//                    $(this).next().css('width', '100%');
//                }
//            });
            /*$("#filter_search_invoices").chosen({
             disable_search_threshold: 0
             });*/

            $.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Please enter only digits and ' - '."
            );
            $.validator.setDefaults({
                ignore: ":hidden:not(.selectpicker)"
            });
            $('#store_details_form').validate({
                onfocusout: false,
                rules: {

//                    branch_address_contactEmail: {
//                        required: true,
//                        //ExistingEmail: true,
//                        email: true,
//                    },
                    name: {
                        required: true,
                        maxlength: 30,
                    },
                    status: {
                        required: true,
                    },
                   

                },
                messages: {
//                   branch_address_contactEmail: {
//                required: "{{ trans('validation.correct_email') }}",
//                email: "{{ trans('login_form.valied_email') }}",
//            },
            name: {
                required: "Please enter drive type name",
            },
            status: {
                required: "Please select drive type status",
                maxlength: "Maximum length is 30",
            },
            

                },
                errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.inp-holder').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          },
          invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $("#page_top_error_message").show();
                    window.scrollTo(0, 0);                    
                    //validator.errorList[0].element.focus();

                }
            }
            });


        })
    </script>
    </x-slot>
</x-app-layout>
