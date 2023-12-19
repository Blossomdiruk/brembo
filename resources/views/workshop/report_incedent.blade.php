<x-workshop-layout>
<?php
  if($savestatus=='A')
  {
      $part_number = "";
      $vMake = "";
      $vModel = "";
      $vYOM = "";
      $vVINNo = "";
      $purchased_date = "";
      $vKMFitted = "";
      $vODOmeter = "";
      $cCityuse = "";
      $cHightWayUse = "";
      $cOffRoadUse = "";
      $cTowingUse = "";
      $cOtherUse = "";
      $cMountainUse = "";
      $vOtherUseReason = "";
      $cNewDrums = "";
      $cDiskMachined = "";
      $cNewPads = "";
      $cSlideGreased = "";
      $vWheelTorque = "";
      $vAntiSequel = "";
      $vDiskClean = "";
      $description = "";
      $warrenty_status = "";

  }else{
      $part_number = $info[0]->product_id;
      $vMake = $info[0]->vMake;
      $vModel = $info[0]->vModel;
      $vYOM = $info[0]->vYOM;
      $vVINNo = $info[0]->vVINNo;
      $purchased_date = $info[0]->purchased_date;
      $date= new \DateTime($purchased_date);
      $purchased_date =  $date->format('Y-m-d');

      $vKMFitted = $info[0]->vKMFitted;
      $vODOmeter = $info[0]->vODOmeter;
      $cCityuse = $info[0]->cCityuse;
      $cHightWayUse = $info[0]->cHightWayUse;
      $cOffRoadUse = $info[0]->cOffRoadUse;
      $cTowingUse = $info[0]->cTowingUse;
      $cOtherUse = $info[0]->cOtherUse;
      $cMountainUse = $info[0]->cMountainUse;
      $vOtherUseReason = $info[0]->vOtherUseReason;
      $cNewDrums = $info[0]->cNewDrums;
      $cDiskMachined = $info[0]->cDiskMachined;
      $cNewPads = $info[0]->cNewPads;
      $cSlideGreased = $info[0]->cSlideGreased;
      $vWheelTorque = $info[0]->vWheelTorque;
      $vAntiSequel = $info[0]->vAntiSequel;
      $vDiskClean = $info[0]->vDiskClean;
      $description = $info[0]->description;
      $warrenty_status = $info[0]->warrenty_status;
  }
?>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>Claim Warranty</h1>
            <br>
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

            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
              <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </symbol>
              <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
              </symbol>
              <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
              </symbol>
            </svg>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <div>
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
              {{ $message }}
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
            @endif
            @if ($message = Session::get('danger'))

            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
              <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </symbol>
              <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
              </symbol>
              <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
              </symbol>
            </svg>

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <div>
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
              {{ $message }}
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            @endif
            <form action="{{ route('workshop.report-incedent') }}" enctype="multipart/form-data" method="post" id="reportIncedent" class="needs-validation" novalidate>
            @csrf  
            <div class="row dashboard_forms">
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Part Number(s) for the Claim</label>
                    <input type="text" name="product_id" id="product_id" class="form-control" value="{{ $part_number}}" required>
                  </div>
                </div>
              </div>
              <div class="row dashboard_forms">
                <h2 class="fw-bold my-3">Vehicle Information</h2>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Make</label>
                    <input type="text" name="vMake" id="vMake" class="form-control" value="{{ $vMake}}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="vModel" id="vModel" class="form-control" value="{{ $vModel}}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Year</label>
                    <input type="text" name="vYOM" id="vYOM" class="form-control" value="{{ $vYOM}}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">VIN</label>
                    <input type="text" name="vVINNo" id="vVINNo" class="form-control" value="{{ $vVINNo}}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Date Fitted</label>
                    <input type="date" id="purchased_date" name="purchased_date" class="form-control" value="{{ $purchased_date }}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">KMs Fitted</label>
                    <input type="text" name="vKMFitted" id="vKMFitted" class="form-control" value="{{ $vKMFitted}}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">KMs Now</label>
                    <input type="text" name="vODOmeter" id="vODOmeter" class="form-control" value="{{ $vODOmeter}}" placeholder="" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Driving Use</label>
                    <div class="row mt-2">
                      <div class="col-lg-4 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" @if($cCityuse == 'Y') checked @endif value="Y" id="cCityuse" name="cCityuse">
                          <label class="form-check-label" for="cCityuse">
                            City
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" @if($cHightWayUse == 'Y') checked @endif value="Y" id="cHightWayUse" name="cHightWayUse">
                          <label class="form-check-label" for="cHightWayUse">
                            Highway
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" @if($cOffRoadUse == 'Y') checked @endif value="Y" id="cOffRoadUse" name="cOffRoadUse">
                          <label class="form-check-label" for="cOffRoadUse">
                            Off Road
                          </label>
                        </div>
                      </div>  
                      <div class="col-lg-4 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" @if($cTowingUse == 'Y') checked @endif value="Y" id="cTowingUse" name="cTowingUse">
                          <label class="form-check-label" for="cTowingUse">
                            Towing
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" @if($cMountainUse == 'Y') checked @endif value="Y" id="cMountainUse" name="cMountainUse">
                          <label class="form-check-label" for="cMountainUse">
                            Mountain
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" @if($cOtherUse == 'Y') checked @endif value="Y" id="cOtherUse" name="cOtherUse">
                          <label class="form-check-label mb-2" for="cOtherUse">
                            Other (Please Specify)
                          </label>
                          <input type="text" class="form-control" placeholder="" value="{{ $vOtherUseReason}}" id="vOtherUseReason" name="vOtherUseReason">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row dashboard_forms">
                <h2 class="fw-bold my-3">Parts Information</h2>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">New Discs/Drums?</label>
                    <div class="row mt-2">
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" @if($cNewDrums == 'Y') checked @endif value="Y" name="cNewDrums" id="cNewDrums">
                          <label class="form-check-label" for="cNewDrums">
                            Yes
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" @if($cNewDrums == 'N') checked @endif value="N" name="cNewDrums" id="cNewDrums">
                          <label class="form-check-label" for="cNewDrums">
                            No
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Discs/Drums Machined?</label>
                    <div class="row mt-2">
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="Y" @if($cDiskMachined == 'Y') checked @endif name="cDiskMachined" id="cDiskMachined">
                          <label class="form-check-label" for="cDiskMachined">
                            Yes
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="N" @if($cDiskMachined == 'N') checked @endif name="cDiskMachined" id="cDiskMachined">
                          <label class="form-check-label" for="cDiskMachined">
                            No
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">New Pads/Shoes?</label>
                    <div class="row mt-2">
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" @if($cNewPads == 'Y') checked @endif  value="Y" name="cNewPads" id="cNewPads">
                          <label class="form-check-label" for="cNewPads">
                            Yes
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" @if($cNewPads == 'N') checked @endif value="N" name="cNewPads" id="cNewPads">
                          <label class="form-check-label" for="cNewPads">
                            No
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Slides Greased?</label>
                    <div class="row mt-2">
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" @if($cSlideGreased == 'Y') checked @endif value="Y" name="cSlideGreased" id="cSlideGreased">
                          <label class="form-check-label" for="cSlideGreased">
                            Yes 
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" @if($cSlideGreased == 'N') checked @endif value="N" name="cSlideGreased" id="cSlideGreased">
                          <label class="form-check-label" for="cSlideGreased">
                            No
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Torque Value Used for Wheel Studs Tightening</label>
                    <input type="text" name="vWheelTorque" id="vWheelTorque" class="form-control" value="{{ $vWheelTorque}}" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">What (if any) Anti-Sequel/Shim Material Fitted?</label>
                    <input type="text" name="vAntiSequel" id="vAntiSequel" class="form-control" value="{{ $vAntiSequel}}" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">What Discs Cleaning Process or Produce was Used?</label>
                    <input type="text" name="vDiskClean" id="vDiskClean" class="form-control" value="{{ $vDiskClean}}" placeholder="">
                  </div>
                </div>
              </div>
              <div class="row dashboard_forms">
                <div class="col-lg-12 col-md-12 col-12">
                  <div class="mb-3">
                    <label class="form-label">Please Provide a Detailed Description of the Issue</label>
                    <textarea class="form-control h-auto" name="description" id="description"  rows="4" required>{{ $description}}</textarea>
                  </div>
                </div>
              </div>
              <br>
              @if($warrenty_status=='P')
              <input type="hidden" name="save_status" id="save_status" value="{{  $savestatus }}" />
              @if($savestatus=='E')
                  <input type="hidden" name="incedent_id" id="incedent_id" value="{{  $info[0]->id }}" />
              @endif
              <button type="submit" id="submitBtn" name="submitBtn" class="main_btn" >submit</button>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
    <x-slot name="styles">
      <style>
      .error{
        font-style: italic !important;
        color: red !important;
      }
      </style>
    </x-slot> 
    <x-slot name="scripts">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script type="text/javascript">
         (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
          .forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }

              form.classList.add('was-validated')
            }, false)
          })
      })();
      $(document).ready(function() {
        $("#reportIncedent").validate({
            rules: {
              product_id: {
                    required: true,
                },
                vMake: {
                    required: true,
                },
                vModel: {
                    required: true,
                },
                vYOM: {
                    required: true,
                },
                vVINNo: {
                    required: true,
                },
                purchased_date: {
                    required: true,
                },
                vKMFitted: {
                    required: true,
                },
                vODOmeter: {
                    required: true,
                },
                description: {
                    required: true,
                },
            },

            messages: {
              product_id: "Please enter part number",
              vMake: "Please enter make",
              vModel: "Please enter model",
              vYOM: "Please enter YOM",
              vVINNo: "Please enter vVINNo",
              purchased_date: "Please enter fitted date",
              vKMFitted: "Please enter KM fitted",
              vODOmeter: "Please enter ODO meter",
              description: "Please enter description",
            },
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

    });
    </script>

    </x-slot>
</x-workshop-layout>