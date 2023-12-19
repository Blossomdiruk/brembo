<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec text-center">
            <div>
              <img style="width: 120px;" src="images/done.gif" alt="">
            </div>
            <h1 class="fw-bold text-dark">All Done!</h1>
            <p class="mt-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer. </p>
            <a href="{{ route('workshop.dashboard') }}" class="main_btn border-0">Back to home</a>
            
          </div>
        </div>
      </div>
    </div>
    </x-workshop-layout>