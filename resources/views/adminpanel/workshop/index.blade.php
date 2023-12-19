@section('title', 'Profile')
@php
if ($savestatus == 'A'){
$name = '';
$email = '';
$phone = '';
$status = '';
$vAddressline1= '';
$vAddressline2= '';
$postcode= '';
$stateID= '';
$cityID= '';
$vABN= '';
$vContactperson = '';
$branchID='';
}else{
$name = $info[0]->business_name;
$email = $info[0]->email;
$phone = $info[0]->phone;
$status = $info[0]->status; 
$vContactperson = $info[0]->Contact_person;
$vABN= $info[0]->ABN;
$branchID = $info[0]->branchID;

$vAddressline1= $addressinfo[0]->vAddressline1;
$vAddressline2= $addressinfo[0]->vAddressline2;
$postcode= $addressinfo[0]->postcode;
$cityID= $addressinfo[0]->cityID;
$stateID= $addressinfo[0]->stateID;

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
                        <a href="{{ route('new-workshop') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">ADD NEW</button>
                        </a>

                        <a href="{{ route('workshop-list') }}">
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
                    <h2>Workshop</h2>
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
                        <form action="{{ route('new-workshop') }}" enctype="multipart/form-data" method="post" id="store_details_form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row ">

                                    <section class="col col-6">
                                        <label class="label">Business Name <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="business_name" name="business_name" required value="{{$name}}">
                                        </label>
                                    </section>

                                    <section class="col col-6">
                                        <label class="label">Email <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="email" id="email" name="email" required value="{{$email}}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row " >
                                    <section class="col col-6">
                                        <label class="label">Phone Number <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="phone" name="phone" required value="{{$phone}}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">Contact Person <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vContactperson" name="vContactperson" required value="{{$vContactperson}}">
                                        </label>
                                    </section>

                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">ABN <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vABN" name="vABN" required value="{{$vABN}}">
                                        </label>
                                    </section>

                                    <section class="col col-6">
                                        <label class="label">Branch <span style=" color: red;">*</span></label>

                                        <label class="select inp-holder">  
                                            <div class="existing_brand">
                                            <select name="branchID" id="branchID" required="">
                                                <option value="" ></option>
                                                    @foreach($city as $row)

                                                    <option value="{{  $row->id }}" @if($row->id== $branchID)selected="selected" @endif > {{$row->name}}</option>

                                                    @endforeach
                                            </select>
                                            </div>
                                            <i></i>

                                        </label>

                                    </section>

                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Password <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="password" name="password" required >
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">Confirm Password <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="confirm_password" name="confirm_password" required >
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    
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
                                
                                <div class="cleafix"></div>
                                <header style="background:none;"><b>Address</b></header>
                                <br>
                                <div class="row ">
                                    <section class="col col-6">
                                        <label class="label">Address Line 1 <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vAddressline1" name="vAddressline1" required value="{{$vAddressline1}}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">Address Line 2 <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vAddressline2" name="vAddressline2" required value="{{$vAddressline2}}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row ">
                                    <section class="col col-6">
                                        <label class="label">State <span style=" color: red;">*</span></label>

                                        <label class="select inp-holder">  
                                            <div class="existing_brand">
                                                <select name="stateID" id="stateID" required="">
                                                    <option value="" ></option>
                                                    @foreach($state as $row)
                                                    <option value="{{  $row->id }}" @if($row->id== $stateID)selected="selected" @endif >{{ $row->iso2 }} - {{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <i></i>

                                        </label>

                                    </section>
                                    <div class="row " id="city_div">
                                    
                                    <section class="col col-6">
                                        <label class="label">City <span style=" color: red;">*</span></label>
                                        
                                            <label class="select inp-holder">  
                                                <div class="existing_city">
                                            <select name="cityID" id="cityID" required="">
                                                <option value="" ></option>
                                                    @foreach($city as $row)

                                                    <option value="{{  $row->id }}" @if($row->id== $cityID)selected="selected" @endif > {{$row->name}}</option>

                                                    @endforeach
                                            </select>
                                                    </div>
                                            <i></i>
                                            
                                        </label>
                                            
                                    </section>
                                        </div>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Post Code <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="postcode" name="postcode" required value="{{$postcode}}">
                                        </label>
                                    </section>    
                            
                                </div>
                               
                                
                            </fieldset>
                            <footer>
                                @if($savestatus !='A')
                                <input type="hidden" id="id" name="id" value="{{encrypt($info[0]->id)}}" />
                                <input type="hidden" id="adddressid" name="adddressid" value="{{encrypt($info[0]->addressID)}}" />
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
            $(function () {
                //window.ParsleyValidator.setLocale('ta');
                $('#category-form').parsley();
            });
        </script>
        <script>
            $(document).ready(function () {
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
                        function (value, element, regexp) {
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
                        business_name: {
                            required: true,
                            maxlength: 50,
                        },
                        email: {
                            required: true,
                            email: true,
                        },
                        phone: {
                            required: true,
                            //matches   : "[0-9]+",
                            number: true,
                            minlength: 10,
                            maxlength: 20
                        },
                        vAddressline1: {
                            required: true,
                            maxlength: 100
                        },
                        vAddressline2: {
                            required: true,
                            maxlength: 100
                        },
                        status: {
                            required: true,
                        },
                        cityID: {
                            required: true,
                        },
                        stateID: {
                            required: true,
                        },
                        vContactperson: {
                            required: true,
                            maxlength: 50,
                        },
                        vABN: {
                            required: true,
                            maxlength: 10,
                        },

                    },
                    messages: {

                        business_name: {
                            required: "Please enter business name",
                            maxlength: "Maximum length is 50",
                        },
                        email: {
                            required: "Please enter email address",
                            email: "Please enter a valid email address",
                        },
                        phone: {
                            required: "Please enter phone number",
                            //matches   : "Please eneter valid phone number",
                            number: "Please enter the numbers only",
                            minlength: "Minimum length is 10",
                            maxlength: "Maximum length is 20"
                        },
                        vAddressline1: {
                            required: "Please enter the content for address line 1",
                            maxlength: "Maximum length is 100 characters",
                        },
                        vAddressline2: {
                            required: "Please enter the content for address line 2",
                            maxlength: "Maximum length is 100 characters",
                        },
                        status: {
                            required: "Please the status",
                        },
                        stateID: {
                            required: "Please select a state",
                        },
                        cityID: {
                            required: "Please select a city",
                        },
                        vContactperson: {
                            required: "Please enter the contact person name" ,                          
                            maxlength: "Maximum length is 50",
                        },
                        vABN: {
                            required: "Please enter the ABN number",
                            maxlength: "Maximum length is 10",
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
                    invalidHandler: function (form, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors) {
                            $("#page_top_error_message").show();
                            window.scrollTo(0, 0);
                            //validator.errorList[0].element.focus();

                        }
                    }
                });


            });
            $(document).on('change', '#stateID', function (e) {

                var id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('get-state-cities') }}",
                    type: 'get',
                    dataType: 'json',
                    data: {
                        stateID: id
                    },
                    success: function (response) {

                        $('#city_div').html('');
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        $(".existing_city").html('');

                        // Read data and create <option >
                        var dropdown = ' <section class="col col-6"> <label class="label">City <span style=" color: red;">*</span></label><label class="select inp-holder"><div class="existing_city"> <select name="cityID" id="cityID" required=""> <option value="" ></option>';
                        if (len > 0) {

                            for (var i = 0; i < len; i++) {

                                var id = response['data'][i].id;
                                var name = response['data'][i].name;

                                dropdown += "<option value='" + id + "'>" + name + "</option>";
                            }

                        }
                        dropdown += ' </select></select></div> <i></i> </label></section>';
                        $("#city_div").append(dropdown);
                    }
                });

            });
            

        </script>
    </x-slot>
</x-app-layout>
