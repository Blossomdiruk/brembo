@section('title', 'Profile')
@php
if ($savestatus == 'A'){
$vName = '';
$description = '';
$duration = '';
$status = '1';
$starting_date= '';
$starting_time= '';
$mcq_quez = '';
$structured_quiz = '';
$quizes_list = $quizes;
$structuredquiz = $struct_quiz;
$exam_quest = array();
}else{
$vName = $info[0]->name;
$description = $info[0]->description;
$duration = $info[0]->duration;
$status = $info[0]->status; 
$starting_date = $info[0]->starting_date;
$starting_time = $info[0]->starting_time;
$mcq_quez = $info[0]->mcq_quez;
$structured_quiz = $info[0]->structured_quiz;
$quizes_list = $quizes;
$structuredquiz = $struct_quiz;
$exam_quest = $exam_quest;
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
                        <a href="{{ route('new-exam') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">ADD NEW</button>
                        </a>

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
                    <h2>Exam</h2>
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
                        <form action="{{ route('new-exam') }}" enctype="multipart/form-data" method="post" id="store_details_form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row ">

                                    <section class="col col-6">
                                        <label class="label">Session Name <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vName" name="vName" required value="{{$vName}}">
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">Start Date </label>
                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                        <input type="text" id="starting_date" name="starting_date" value="{{ $starting_date }}" class="datepicker" data-date-format='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                        </label>
                                        <span class="hide start-date-error" style="color:red;">Start date should be less than End date</span>
                                    </section>
                                   
                                  
                                </div>
                                <div class="row " >
                                
                                    <!-- <section class="col col-6">
                                        <label class="label">End Date </label>
                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                        <input type="text" id="session_enddate" name="session_enddate" value="" class="datepicker" data-date-format='yyyy-mm-dd' placeholder="YYYY-MM-DD" data-parsley-type="date">
                                        </label>
                                        <span class="hide end-date-error" style="color:red;">Start date should be greater than End date</span>
                                    </section> -->
                                    <section class="col col-6">
										
                                    <label  class="label">Start Time:</label>
										<div class="input-group">
											<input class="form-control" id="clockpicker" name="clockpicker" type="text" style="width:97%;height:27px;" placeholder="Select time" data-autoclose="true" value="{{ $starting_time }}">
												<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											</div>
												
																					
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">Duration <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="duration" name="duration" required value="{{$duration}}">
                                        </label>
                                    </section>
                                  
                                   
                                </div>
                                <div class="row " >
                              
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
                                <div class="row " >
                                   
                                </div>
                                <div class="row " >
                                    
                                <section class="col-12" style="padding-right: 15px;padding-left: 15px;">
                                        <label class="label">Description<span style="color: #FF0000;">*</span></label>
                                            <label class="textarea">
                                                <textarea  id="description" class="summernote" name="description" style="height: 100px;">{{ $description }}</textarea>
                                            </label>
                                    </section>
                                    
                                    
                                </div>
                                <div class="row">

                                    <section class="col col-4">
                                        <label class="label">MCQ Questions <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="mcq_quez" name="mcq_quez" required value="{{$mcq_quez}}">
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">Structured Questions <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="structured_quiz" name="structured_quiz" required value="{{$structured_quiz}}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <div class="input-group-btn">              
                                            <button class="btn btn-info btn-sm" id="addrow" type="button" style="background-color: #5D98CC;height: 32px; width: 100px;  padding :7px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add</button>
                                        </div>
                                    </section>
                                </div>
                                
                                <div class=" cleafix"></div>
                                <div class="row">
                                    <section class="col col-10">
                                        <ul id="sortable">
                                            @php
                                                $i=1;
                                            @endphp
                                            @foreach($exam_quest  as $quizlst)
                                            @if($quizlst->quiz_type == 'MCQ')
                                                <li class="ui-state-default" style="height:75px;"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                    <section>
                                                        <label class="label">MCQ Question {{ $i }}</label>
                                                            <label class="select">
                                                                <select name="mcq_quiz[]" id="mcq_quiz">
                                                                    @foreach($quizes_list as $quizlist)
                                                                    <option value="MCQ_{{ $quizlist->id }}" @if($quizlst->question_id == $quizlist->id ) selected  @endif>{{ $quizlist->name }}</option>
                                                                    @endforeach
                                                                </select> <i></i> </label>
                                                    </section>
                                                </li>
                                            @else

                                            <li class="ui-state-default" style="height:75px;"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                    <section>
                                                        <label class="label">Structured Question {{ $i }}</label>
                                                            <label class="select">
                                                                <select name="mcq_quiz[]" id="mcq_quiz">
                                                                    @foreach($structuredquiz as $str_quiz)
                                                                    <option value="STRU_{{ $str_quiz->id }}" @if($quizlst->question_id == $str_quiz->id ) selected  @endif>{{ $str_quiz->name }}</option>
                                                                    @endforeach
                                                                </select> <i></i> </label>
                                                    </section>
                                                </li>

                                           
                                            @endif
                                                @php
                                                $i++;
                                            @endphp
                                            @endforeach
                                            <!-- <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</li>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li> -->
                                        </ul>
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> -->
 <script>
        $( function() {
            $( "#sortable" ).sortable();
        } );
  </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

        <script>
            $(document).ready(function() {

            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    // ['para', ['ul', 'ol', 'paragraph']],
                    ['para', ['paragraph']],
                    ['height', ['height']],
                    // ['table', ['table']],
                    // ['insert', ['link', 'picture', 'hr']],
                    // ['view', ['fullscreen', 'codeview', 'help']]
                    ['view', ['codeview']]

                ]
            });
        });
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
                $("#addrow").click(function() {
                    var mcqquiz = Number($('#mcq_quez').val()); 
                    var structuredquiz = Number($('#structured_quiz').val()); 
                    //const totalquiz = Number(mcqquiz)+Number(structuredquiz);alert(totalquiz);
                    var uldesign = '';
                    var k=1;
                    var l=1;
                    for(j=0;j<mcqquiz;j++)
                    {
                        uldesign +='<li class="ui-state-default" style="height:75px;"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'+ 
                                    '<section>'+
										'<label class="label">MCQ Question '+k+'</label>'+
											'<label class="select">'+
												'<select name="mcq_quiz[]" id="mcq_quiz">'+
                                                '<option value="">Choose name</option>'+
                                                    @foreach($quizes_list as $quizlist)
													'<option value="MCQ_{{ $quizlist->id }}">{{ $quizlist->name }}</option>'+
                                                    @endforeach
												'</select> <i></i> </label>'+
										'</section>'+
                                    '</li>';
                                    k++;
                    }
                    for(i=0;i<structuredquiz;i++)
                    {
                        uldesign +='<li class="ui-state-default" style="height:75px;"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'+ 
                                    '<section>'+
										'<label class="label">Structured Question '+l+'</label>'+
											'<label class="select">'+
												'<select name="mcq_quiz[]" id="mcq_quiz">'+
													'<option value="">Choose name</option>'+
                                                    @foreach($structuredquiz as $str_quiz)
													'<option value="STRU_{{ $str_quiz->id }}">{{ $str_quiz->name }}</option>'+
                                                    @endforeach
												'</select> <i></i> </label>'+
										'</section>'+
                                    '</li>';
                                    l++;
                    }
                    $("#sortable").append(uldesign);
                });
                

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


                        vName: {
                            required: true,
                            maxlength: 150,
                        },
                        description: {
                            required: true,
                            maxlength: 2500,
                        },
                        session_startdate: {
                            required: true, 
                        },
                        clockpicker: {
                            required: true,
                            
                        },duration: {
                            required: true,
                            
                        },
                    },
                    messages: {

                        vName: {
                            required: "Please Enter Exam Name",
                            maxlength: "Maximum length is 150",
                        },
                        description: {
                            required: "Please Enter Exam Description",
                            maxlength: "Maximum length is 2500",
                        },
                        session_startdate: {
                            required: "Please Select Exam Start Date",   
                        },
                        clockpicker: {
                            required: "Please Select Exam Start Time",
                           
                        },
                        duration: {
                            required: "Please Enter Exam Duration",
                           
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
                var currentDate = new Date();
                $('#session_startdate').datepicker({
                    format: 'yyyy-mm-dd',
                    prevText : '<i class="fa fa-chevron-left"></i>',
                    nextText : '<i class="fa fa-chevron-right"></i>',
                    autoclose:true,
                    endDate: "currentDate",
                    maxDate: currentDate
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
                $('#session_startdate').keyup(function () {
                    if (this.value.match(/[^0-9]/g)) {
                        this.value = this.value.replace(/[^0-9^-]/g, '');
                    }
                });
                //End Date
                $('#session_enddate').datepicker({
                    format: 'yyyy-mm-dd',
                    prevText : '<i class="fa fa-chevron-left"></i>',
                    nextText : '<i class="fa fa-chevron-right"></i>',
                    autoclose:true,
                    endDate: "currentDate",
                    maxDate: currentDate
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
                $('#session_enddate').keyup(function () {
                    if (this.value.match(/[^0-9]/g)) {
                        this.value = this.value.replace(/[^0-9^-]/g, '');
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
