<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        <div class="col-lg-9">
          <div class="padding_sec">
            <div class="row">
              <div class="col-lg-9 col-md-8 col-12">
                <h1>ASE Practice Set</h1>
              </div>
              <div class="col-lg-3 col-md-4 col-12">
                <div class="text-md-end">
                  <!-- OTP Countdown start -->
                  <div class="">
                    <p class="mb-0">Time Remaining</p>
                    <h6 class="mb-0 fw-bold" id="countdown"></h6>
                  </div>
                  <!-- OTP Countdown end -->
                </div>  
              </div>
              <p class="mb-0 mt-2 mt-md-0">Total Number of Questions: <b>20</b></p>
            </div>
            <hr>

            <!-- Exam Paper Section Start  -->
            <form action="{{ route('workshop.exampaper-submit') }}" enctype="multipart/form-data" method="post" id="exampaperForm">
            @csrf
            <div class="exam_paper dashboard_forms">
              <?php
                $quistion_id= 1;
                foreach($mcq_quiz as $mcq_quiz){
              ?>
              <div class="question_sec">
                <div class="d-flex  align-items-md-center align-items-start mb-3">
                  <b class="quest_num me-2">{{ sprintf('%02d',$quistion_id); }}</b>
                  <p class="single_quest mb-0">{{ $mcq_quiz->description }}</p>
                </div>
                <?php
                    $quiz_ansers = Exam::get_quiz_answers($mcq_quiz->id);
                    foreach($quiz_ansers as $quiz_ans)
                    {
                ?>
                <div class="answer_sec">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="radio" name="ANS_{{ $mcq_quiz->id }}_{{$quiz_ans->id}}" id="flexRadioDefault1" value="{{ $quiz_ans->marks }}">
                    <label class="form-check-label" for="flexRadioDefault1">
                      {{ $quiz_ans->answer }}
                    </label>
                  </div>
                </div>
                <?php
               
                     }  
                ?>
                

              </div>
             <?php
              $quistion_id++;
                }
             ?>
             <?php
                foreach($struct_quiz as $struct_qu)
                {
             ?>
              <div class="question_sec">
                <div class="d-flex align-items-md-center align-items-start mb-3">
                  <b class="quest_num me-2">{{ sprintf('%02d',$quistion_id); }}</b>
                  <p class="single_quest mb-0" style="font-size:16px;"><?php echo $struct_qu->description; ?></p>
                </div>
                <div class="answer_sec">
                  <textarea class="form-control h-auto" id="STRUC_{{ $struct_qu->id }}" name="STRUC_{{ $struct_qu->id }}" rows="5"></textarea>
                </div>
              </div>
              <?php
              $quistion_id++;
                }
              ?>
              <br>
              <div class="text-end">
                <input type="hidden" name="exam_id" id="exam_id" value="{{ encrypt($exam_id) }}" />
                <button type="submit" class="main_btn border-0">Submit</button>
              </div> 
            </div>
            </form>
            <!-- Exam Paper Section End  -->

          </div>
        </div>
      </div>
    </div>

    <x-slot name="scripts">
    <!-- Countdown Timer -->

    <script>
      function countdown(elementName, minutes, seconds) {
	var element, endTime, hours, mins, msLeft, time;

	function twoDigits(n) {
		return (n <= 9 ? "0" + n : n);
	}

	function updateTimer() {
		msLeft = endTime - (+new Date);
		if (msLeft < 1000) {
			element.innerHTML = "TIME'S UP!";
            
		} else {
			time = new Date(msLeft);
			hours = time.getUTCHours();
			mins = time.getUTCMinutes();
			element.innerHTML = (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time
																																													 .getUTCSeconds());
			setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
		}
	}

	element = document.getElementById(elementName);
	endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;
	updateTimer();
}
//CHANGE TIME (Minutes, Seconds)
countdown("countdown", 0, 30);
    </script>

    <!-- Countdown Timer -->
    </x-slot>
    </x-workshop-layout>