<x-workshop-layout>
<?php
//var_dump($enroll_exams);
if(isset($enroll_exams))
{
  $dt = new DateTime($enroll_exams[0]->enrolled_date);
  $dt2 = new DateTime($enroll_exams[0]->starting_date);
  $exam_id = $enroll_exams[0]->id;

  $enrolled_date = $dt->format('d / m / Y');
  $start_date = $dt2->format('d / m / Y');
}
?>
<div class="container">
      <div class="row">

      @include('workshop.workshopmenu') 

        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>exam</h1>
            <br>
            <div class="p-4 rounded" style="background-color: #F1F1F1;">
              <h2 class="fw-bold mb-3">ASE Practice Test</h2>
              <h6>Instructions</h6>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>

              <div class="collapse" id="collapseExample">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                    It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
              </div>
              <a class="collapse_btn" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <p class="fw-bold mb-4">Show More&nbsp;&nbsp;<i class="fa fa-chevron-down" aria-hidden="true"></i></p>
              </a>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <h5><i class="fa fa-calendar" aria-hidden="true">&nbsp;&nbsp;</i>Enrolled on</h5>
                  <h6>{{ $enrolled_date }}</h6>
                  <br class="d-md-none d-block">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <h5><i class="fa fa-calendar" aria-hidden="true">&nbsp;&nbsp;</i>Exam Date</h5>
                  <h6>{{ $start_date }}</h6>
                </div>
              </div>
              <div class="mt-3">
                <a href="{{ url('workshop/start-exam/'.encrypt($exam_id)) }}" class="main_btn">Start Exam</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
</x-workshop-layout>