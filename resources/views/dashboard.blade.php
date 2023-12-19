@section('title', 'Dashboard')

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row m-auto">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h1 class="page-title txt-color-blueDark mb-0"><i class="fa-fw fa fa-home"></i>  {{ __('dashboard.title') }} <span>@if(!empty($officename->office_name_en)){{ $officename->office_name_en }}@endif </span></h1>
                  <p style="color: #ffffff;">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                  </p>
                  <br>
                </div>

            </div>



            <!-- widget grid -->
            <section id="widget-grid" class="mt-4">
				
              <div class="row m-auto">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  
                  <div class="widget-body smart-form">
                    <fieldset>
                      <img src="{{ asset('public/back/img/brembo_kit.png') }}" alt="Brembo Managment System" class="img-responsive">
                    </fieldset>
                  </div>
                  
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-8">
                  
                  <div class="row">
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    	 <div class="widget-body smart-form">
                            <fieldset>
                              <img src="{{ asset('public/back/img/logo_white.svg') }}" alt="Brembo Managment System" class="img-responsive">
                            </fieldset>
                          </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    	 <div class="widget-body smart-form">
                            <fieldset>
                              <img src="{{ asset('public/back/img/logo_white.svg') }}" alt="Brembo Managment System" class="img-responsive">
                            </fieldset>
                          </div>
                    </div>
                    
                  </div>
                  
                  
                  <div class="widget-body smart-form">
                    <fieldset>
                      <img src="{{ asset('public/back/img/logo_red.svg') }}" alt="Brembo Managment System" class="img-responsive">
                    </fieldset>
                  </div>
                  
                  <div class="row">
                    
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    	 <div class="widget-body smart-form">
                            <fieldset>
                              <img src="{{ asset('public/back/img/logo_white.svg') }}" alt="Brembo Managment System" class="img-responsive">
                            </fieldset>
                          </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    	 <div class="widget-body smart-form">
                            <fieldset>
                              <img src="{{ asset('public/back/img/logo_white.svg') }}" alt="Brembo Managment System" class="img-responsive">
                            </fieldset>
                          </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    	 <div class="widget-body smart-form">
                            <fieldset>
                              <img src="{{ asset('public/back/img/logo_white.svg') }}" alt="Brembo Managment System" class="img-responsive">
                            </fieldset>
                          </div>
                    </div>
                    
                  </div>
                  
                </div>
                
              </div>
                
            </section>
            <!-- end widget grid -->
        </div>
    </div>
    <x-slot name="script">

        <!-- Morris Chart Dependencies -->
        
        <script src="{{ asset('public/js/plugin/morris/morris.min.js') }}"></script>
        <script src="{{ asset('public/js/plugin/chartjs/chart.min.js') }}"></script> --}}
        <script src="https://lcms.tekgeeks.net/public/js/plugin/morris/raphael.min.js"></script>
        <script src="https://lcms.tekgeeks.net/public/js/plugin/morris/morris.min.js"></script>
        <script src="https://lcms.tekgeeks.net/public/js/plugin/chartjs/chart.min.js"></script>

        
    </x-slot>
</x-app-layout>
