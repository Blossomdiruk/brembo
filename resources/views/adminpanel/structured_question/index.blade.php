@section('title', 'Profile')
@php
if ($savestatus == 'A'){
$vName = '';
$vDuration = '';
$vDiscription = '';
$status = '';
$quiz_answer1 = '';


}else{
$vName = $info[0]->name;
$vDuration = $info[0]->duration;
$vDiscription = $info[0]->description;
$status = $info[0]->status; 
$quiz_answer1 = '';

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
                        <a href="{{ route('new-structured-question') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">ADD NEW</button>
                        </a>

                        <a href="{{ route('structured-questions-list') }}">
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
                    <h2>Structured Question</h2>
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
                        <form action="{{ route('new-structured-question') }}" enctype="multipart/form-data" method="post" id="store_details_form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row ">

                                    <section class="col col-8">
                                        <label class="label">Question Name <span style=" color: red;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="vName" name="vName" required value="{{$vName}}">
                                        </label>
                                    </section>
                                    <!-- <section class="col col-2">
                                        <label class="label">Duration <span style="color: #FF0000;">*</span></label>
                                        <label class="input inp-holder">
                                            <input type="text" id="duration" name="duration" required value="{{$vDuration}}">
                                        </label>
                                    </section> -->
                                    <section class="col col-2">
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
                                <section class="col-12" style="padding-right: 15px;padding-left: 15px;">
                                        <label class="label">Description<span style="color: #FF0000;">*</span></label>
                                            <label class="textarea">
                                                <textarea  id="description" class="summernote" name="description" style="height: 100px;">{{$vDiscription}}</textarea>
                                            </label>
                                    </section>
                                    
                                    
                                </div>
                                
                                
                                <div class=" cleafix"></div>
                                
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
        <!-- include summernote css/js -->
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
        <script>
            $(function () { 
                $('#category-form').parsley();
            }); 
        </script>
        <script>
            var answercount = 0;
            $(document).ready(function () {
                @if($savestatus !='A')
                $("#addrow").click(function() { 
                   
                    answercount++;
                    var answerlayout='<div id="apped_div'+answercount+'"> <section class="col col-8" style="padding-left: 15px;">'+
                                '<label class="label">Answer<span style="color: #FF0000;">*</span></label>'+
                                    '<label class="input inp-holder">'+
                                        '<input type="text" id="quiz_answer" name="quiz_answer[]" required value="{{$quiz_answer1}}">'+
                                    '</label>'+
                            '</section>'+
                            '<section class="col col-2">'+
                                '<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;</label>'+
                                    '<label class="checkbox">'+
										'<input type="checkbox" class="answer_checkbox"  name="correct_answer_checkbox'+answercount+'" id="correct_answer_checkbox" value="Y">'+
											'<i></i>Correct Answer</label>'+  
                                                    '</section>'+
                                                    '<section class="col col-2">'+
                                                    '<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;</label>'+
                                                            '<div class="input-group-btn">'+
                                                                '<button class="btn btn-danger remrow" id="remrows" type="button" data-rmvbtn="'+answercount+'" style="margin-top: 0px; height: 40px; width: 100px; border-color:  #383838; padding :7px;"><i class="glyphicon glyphicon-remove"></i>&nbsp;Remove</button>'+
                                                            '</div>'+
                                                   '</section></div>';
                            $('.increment2').append(answerlayout);
                            
                });
                @else
                        $("#addrow").click(function() { 
                            //var lsthmtl = $(".clone").html();
                            //$(".increment").after(lsthmtl);
                            answercount++;
                            var answerlayout='<div id="apped_div'+answercount+'"> <section class="col col-8" style="padding-left: 15px;">'+
                                        '<label class="label">Answer<span style="color: #FF0000;">*</span></label>'+
                                            '<label class="input inp-holder">'+
                                                '<input type="text" id="quiz_answer" name="quiz_answer[]" required value="{{$quiz_answer1}}">'+
                                            '</label>'+
                                    '</section>'+
                                    '<section class="col col-2">'+
                                        '<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;</label>'+
                                            '<label class="checkbox">'+
                                                '<input type="checkbox" class="answer_checkbox"  name="correct_answer_checkbox'+answercount+'" id="correct_answer_checkbox" value="Y">'+
                                                    '<i></i>Correct Answer</label>'+  
                                                            '</section>'+
                                                            '<section class="col col-2">'+
                                                            '<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;</label>'+
                                                                    '<div class="input-group-btn">'+
                                                                        '<button class="btn btn-danger remrow" id="remrows" type="button" data-rmvbtn="'+answercount+'" style="margin-top: 0px; height: 40px; width: 100px; border-color:  #383838; padding :7px;"><i class="glyphicon glyphicon-remove"></i>&nbsp;Remove</button>'+
                                                                    '</div>'+
                                                        '</section></div>';
                                    $('.increment').append(answerlayout);
                                    
                        });
                @endif
                
                $("body").on("click", ".answer_checkbox", function() {
                    //alert('Jiiii');
                   
                   
                });
                $('.answer_checkbox').click(function(){
                    console.log('fdfsfds');
                    //$('input:checkbox').removeAttr('checked');
                    $(".answer_checkbox").prop("checked", false);
                    $(this).prop("checked", true);
                });

                $("body").on("click", ".remrow", function() {
                    var removebtn_val = $(this).data('rmvbtn');
                    $('#apped_div'+removebtn_val).remove();
                    answercount = removebtn_val - 1;
                   
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
                            maxlength: 50,
                        },
                        tTrainning_description: {
                            required: true,
                            maxlength: 2500,
                        },
                        session_startdate: {
                            required: true,
                            checkfromdate : true,
                        },
                        session_enddate: {
                            required: true,
                            checktodate : true,
                        },
                    },
                    messages: {

                        vName: {
                            required: "Please enter traning session name",
                            maxlength: "Maximum length is 50",
                        },
                        tTrainning_description: {
                            required: "Please enter traning description",
                            maxlength: "Maximum length is 2500",
                        },
                        session_startdate: {
                            required: "Please Select Session Start Date",   
                        },
                        session_enddate: {
                            required: "Please Select Session End Date",
                           
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
