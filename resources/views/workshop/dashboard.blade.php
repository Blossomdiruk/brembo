<x-workshop-layout>
<div class="container">
      <div class="row mx-auto align-items-center padding_sec" data-aos="fade-up">
        <div class="col-lg-7">
          <img src="{{ asset('public/images/car_repair.png') }}" class="m-auto w-100" alt="">
        </div>
        
        <div class="col-lg-5">
          <div class="mt-3 d-lg-none d-block">
          </div>
          <h2>Welcome to brembo</h2>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
          <div>
            <hr>
            <div class="d-flex align-items-center gap-2">
              <div class="certified_icon">
                <img src="{{ asset('public/images/certified.png') }}" alt="" style="width: 20px;">
              </div>
              <div>
                <p class="mb-0">Certified by <b>lorem ipsum dolor sit amet</b></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid grey_sec d-none">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-3 border_right">
            <h2 class="fw-bold">CERTIFIED BY</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
          </div>
          <div class="col-lg-9">
            <div class="slider">
              <div class="owl-carousel certified_slider">
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto w-100" src="{{ asset('public/images/cer_1.png') }}" alt="">
                  </div>
                </div>
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto" src="{{ asset('public/images/cer_2.png') }}" alt="">
                  </div>
                </div>
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto" src="{{ asset('public/images/cer_3.png') }}" alt="">
                  </div>
                </div>
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto" src="{{ asset('public/images/cer_4.png') }}" alt="">
                  </div>
                </div>
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto" src="{{ asset('public/images/cer_5.png') }}" alt="">
                  </div>
                </div>
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto" src="{{ asset('public/images/cer_1.png') }}" alt="">
                  </div>
                </div>
                <div class="slider-card cer_logos">
                  <div class="text-center">
                    <img class="m-auto" src="{{ asset('public/images/cer_2.png') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container overlap_sec">
      <div class="row">
        <div class="col-lg-2 d-lg-block d-none">
          <img class="m-auto w-100" src="{{ asset('public/images/mech_1.png') }}" alt="">
        </div>
        <div class="col-lg-5 col-md-6">
          <div class="padding_sec" data-aos="fade-up">
            <h2>Mechanic Management</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
            <a href="{{ route('workshop.michanics'); }}" class="main_btn">Explore</a>
          </div>
          <div class="padding_sec mt-3" data-aos="fade-down">
            <h2>Training</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
            <a href="{{ route('workshop.session_calendar')}}" class="main_btn">Explore</a>
          </div>
        </div>
        
        <div class="col-lg-5 col-md-6">
          <br class="d-md-none d-block">
          <div class="padding_sec" data-aos="fade-up">
            <h2>Scanning Products for points</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
            <a href="{{ route('workshop.scan-for-points') }}" class="main_btn">scan product</a>
            <div class="text-end">
              <br class="d-lg-none d-md-block d-block">
              <img src="{{ asset('public/images/mech_2.png') }}" alt="" class="m-auto" style="width: 250px;">
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>

    <div class="container">
      <div class="row">
        <div class="col-lg-9">
          <div class="bg-white" style="border-radius: 10px;" data-aos="fade-up">
            <div class="row align-items-center">
              <div class="col-lg-7 col-md-7 ps-5 card_padding_mob">
                <h2>Testing module (exams)</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                <a href="{{ route('workshop.exam_calendar') }}" class="main_btn">do the exam</a>
              </div>
              <div class="col-lg-5 col-md-5 d-md-block d-none">
                <img src="{{ asset('public/images/mech_3.png') }}" alt="" class="m-auto w-100">
              </div>
            </div>
          </div>
          <br class="d-lg-none d-block">
        </div>
        <div class="col-lg-3" data-aos="fade-down">
          <div class="padding_sec">
            <h2>Product warranty details</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
            <a href="{{ route('workshop.report-incedent'); }}" class="main_btn">CHECK DETAILS</a>
          </div>
        </div>
      </div>
    </div>
    <br>
</x-workshop-layout>