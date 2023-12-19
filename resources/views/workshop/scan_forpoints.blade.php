<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>reward points</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
            <div class="row">
              <div class="offset-lg-2 col-lg-4 col-md-6 col-12">
                <a href="{{ route('workshop.take-picture'); }}">
                  <div class="rew_box text-center mb-3">
                    <img class="mb-3" src="{{ asset('public/images/qr-code.png') }}" alt="">
                    <h3 class="mb-0">SCAN QR CODE</h3>
                  </div>
                </a>
              </div>
              <div class="col-lg-4 col-md-6 col-12">
                <a href="{{ route('workshop.redeem-list') }}">
                  <div class="rew_box text-center mb-3">
                    <img class="mb-3" src="{{ asset('public/images/box.png') }}" alt="">
                    <h3 class="mb-0">SCANNED HISTORY</h3>
                  </div>
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
    <x-slot name="scripts">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script language="JavaScript">
   
</script>

    </x-slot>
</x-workshop-layout>