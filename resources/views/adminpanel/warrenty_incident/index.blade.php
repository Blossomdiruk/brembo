@section('title', 'Profile')
@php
if ($savestatus == 'A'){
$product_id = '';
$product_name = '';
$description = '';
$comment = '';
$warrenty_status= '';
$purchased_date= '';
$workshop_id = '';
$reject_reason = '';


}else{
$product_id = $info[0]->product_id;
$vMake = $info[0]->vMake;
$vModel = $info[0]->vModel;
$vYOM = $info[0]->vYOM;
$vVINNo = $info[0]->vVINNo;
$vKMFitted = $info[0]->vKMFitted;
$description = $info[0]->description;
$comment = $info[0]->comment; 
$warrenty_status = $info[0]->warrenty_status;
$purchased_date = $info[0]->purchased_date;
$workshop_id = $info[0]->workshop_id;
$reject_reason = $info[0]->reject_reason;

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

                <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Are you Sure?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" id="modal-btn-si" data-meterial="">Yes</button>
                            <button type="button" class="btn" id="modal-btn-no">No</button>
                        </div>
                        </div>
                    </div>
                </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;"> 
                        <!-- <a href="{{ route('new-exam') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">ADD NEW</button>
                        </a> -->

                        <a href="{{ route('exam-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">VIEW ALL</button>
                        </a>
                    </div>
                </div>
               
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
                    <h2>Warrenty Incident</h2>
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
                        <form action="{{ route('new-warrenty-incedent') }}" enctype="multipart/form-data" method="post" id="store_details_form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row ">

                                    <section class="col col-2">
                                        <label class="label">Product Number <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="product_id" name="product_id" required value="{{$product_id}}">
                                        </label>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Make <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vMake" name="vMake" required value="{{$vMake}}">
                                        </label>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Model <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vModel" name="vModel" required value="{{$vModel}}">
                                        </label>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Year <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vYOM" name="vYOM" required value="{{$vYOM}}">
                                        </label>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">VIN <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vVINNo" name="vVINNo" required value="{{$vVINNo}}">
                                        </label>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Date Fitted <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vKMFitted" name="vKMFitted" required value="{{ $vKMFitted}}">
                                        </label>
                                    </section>
                                  
                                </div>
                                
                                <div class="row " >
                                    
                                <section class="col col-6" style="padding-left: 15px;">
                                        <label class="label">Description<span style="color: #FF0000;">*</span></label>
                                            <label class="textarea">
                                                <textarea  id="description"  name="description" style="height: 100px;">{{ $description }}</textarea>
                                            </label>
                                    </section>
                                    <section class="col col-6" style="padding-left: 15px;">
                                        <label class="label">Feedback<span style="color: #FF0000;">*</span></label>
                                            <label class="textarea">
                                                <textarea  id="comment"  name="comment" style="height: 100px;">{{ $comment }}</textarea>
                                            </label>
                                    </section>
                                    
                                </div>
                                <div class="row " >
                              
                              <section class="col col-6">
                                  <label class="label">Warrenty Status</label>
                                  <label class="select">
                                      <select name="warrenty_status" id="warrenty_status">
                                          <option value="P" @if( $warrenty_status == 'P') selected="selected" @endif>Inprogress</option>
                                          <option value="A" @if( $warrenty_status == 'A') selected="selected" @endif>Approved</option>
                                          <option value="C" @if( $warrenty_status == 'C') selected="selected" @endif>Close</option>
                                      </select>
                                      <i></i>
                                  </label>
                              </section>
                              <section class="col col-6 hide reject_div" style="padding-left: 15px;">
                                        <label class="label">Reject Reason<span style="color: #FF0000;">*</span></label>
                                            <label class="textarea">
                                                <textarea  id="reject_reason"  name="reject_reason" style="height: 100px;">{{ $reject_reason }}</textarea>
                                            </label>
                                    </section>
                            
                          </div>
                                
                        
                                
                            </fieldset>
                            <footer>
                                @if($savestatus !='A')
                                <input type="hidden" id="id" name="id" value="{{encrypt($info[0]->id)}}" />
                                <input type="hidden" id="workshop_id" name="workshop_id" value="{{ $workshop_id }}" />
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> -->
 <script>
        $( function() {
            $( "#sortable" ).sortable();
            $(document).on('change', '#warrenty_status', function (e) {
                if($(this).val() == '0')
                {
                    $('.reject_div').removeClass('hide');
                }else{
                    $('.reject_div').addClass('hide');
                }
            });
        } );
  </script>
   
    <script src="{{ asset('public/back/js/plugin/clockpicker/clockpicker.min.js') }}"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('#clockpicker').clockpicker({
				placement: 'top',
			    donetext: 'Done'
			});
            $('.meterial_removebtn').on('click', function(event) {
                event.preventDefault();
                const url = '<?php echo  url('/')  ?>';
                var id = $(this).val();
                swal({
                    title: 'Are you sure?',
                    text: 'This meterial will be permanantly deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                }).then(function(value) {
                    if (value == true) {
                        window.location.replace(url+"/edit-training/delete/" + id);
                    }
                });
            });
        </script>
        <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
        <script>
            $(function () { 
                $('#category-form').parsley();
            }); 
        </script>
        <script>
            $(document).ready(function () {
               

                $.validator.addMethod(
                        "checkfromdate",
                        function (value, element, regexp) {
                            
                            const startDate = new Date(value).getTime();
                            const endDate = new Date($('#session_enddate').val()).getTime();

                            if(startDate < endDate)
                            {
                                $(".start-date-error").addClass('hide');
                                return true;
                            }else{
                                $(".start-date-error").removeClass('hide');
                                return false;
                            }
                        },
                        "");

                        $.validator.addMethod(
                        "checktodate",
                        function (value, element, regexp) {
                            console.log( new Date(value).getTime());
                            const endDate = new Date(value).getTime();
                            const startDate = new Date($('#session_startdate').val()).getTime();

                            if(startDate < endDate)
                            {
                                $(".end-date-error").addClass('hide');
                                return true;
                            }else{
                                $(".end-date-error").removeClass('hide');
                                return false;
                            }
                        },
                        "");
                $.validator.setDefaults({
                    ignore: ":hidden:not(.selectpicker)"
                });
                $('#store_details_form').validate({
                    onfocusout: false,
                    rules: {


                        product_id: {
                            required: true,
                           
                        },
                        product_name: {
                            required: true,
                           
                        },
                        description: {
                            required: true, 
                            maxlength: 2500,
                        },
                       
                    },
                    messages: {

                        product_id: {
                            required: "Please Enter Product ID",
                           
                        },
                        product_name: {
                            required: "Please Enter Product Name",
                           
                        },
                        description: {
                            required: "Please Enter Description",   
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
           
            
        </script>
    </x-slot>
</x-app-layout>
