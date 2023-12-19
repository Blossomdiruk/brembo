<x-workshop-layout>
<?php
    $business_name = $workshop[0]->business_name;
    $email = $workshop[0]->email;
    $phone = $workshop[0]->phone;
    $Contact_person = $workshop[0]->Contact_person;
    $ABN = $workshop[0]->ABN;
    $branchID = $workshop[0]->branchID;

    $AddressLine1 = $Address[0]->vAddressline1;
    $vAddressline2 = $Address[0]->vAddressline2;
    $stateID = $Address[0]->stateID;
    $cityID = $Address[0]->cityID;
    $postcode = $Address[0]->postcode;
?>
    <div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>my profile</h1>
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
            <form action="{{ route('workshop.profile') }}" id="profileupdateForm" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
            @csrf
              <div class="row dashboard_forms">
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Name</label>
                    <input type="text" class="form-control" placeholder="Contact Person Name" id="Contact_person" name="Contact_person" value="{{ $Contact_person }}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  
                  <div class="mb-3">
                  <label class="form-label">Business Name</label>
                    <input type="text" class="form-control" placeholder="Business Name" name="business_name" id="business_name" value="{{ $business_name }}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                <label class="form-label">Branch Name</label>
                  <select class="form-select mb-3" id="branchID" name="branchID" aria-label="Default select example" required>
                    <option disabled selected>Select Branch</option>
                    @foreach($city as $row)

                        <option value="{{  $row->id }}" @if($row->id== $branchID)selected="selected" @endif > {{$row->name}}</option>

                    @endforeach
                  </select>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">ABN Number</label>
                    <input type="text" class="form-control" id="vABN" name="vABN" placeholder="ABN Number" value="{{ $ABN }}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Address</label>
                    <input type="text" class="form-control" placeholder="Address" id="vAddressline1" name="vAddressline1" value="{{ $AddressLine1 }}">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                <label class="form-label">State</label>
                  <select class="form-select mb-3" id="stateID" name="stateID" aria-label="Default select example">
                    <option disabled selected>Select State</option>
                    @foreach($state as $row)

                        <option value="{{  $row->id }}" @if($row->id== $stateID)selected="selected" @endif > {{$row->name}}</option>

                    @endforeach
                  </select>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                <label class="form-label">City</label>
                  <select class="form-select mb-3" id="cityID" name="cityID" aria-label="Default select example">
                    <option disabled selected>Select City</option>
                    @foreach($city as $row)

                        <option value="{{  $row->id }}" @if($row->id== $cityID)selected="selected" @endif > {{$row->name}}</option>

                    @endforeach
                  </select>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Postal Code" value="{{ $postcode }}">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" placeholder="Contact Number" name="phone" id="phone" value="{{ $phone }}" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="Email Address" id="email" name="email" value="{{ $email }}" required>
                  </div>
                </div>
              </div>
              <br>
              <input type="hidden" id="id" name="id" value="{{encrypt($workshop[0]->id)}}" />
              <input type="hidden" id="adddressid" name="adddressid" value="{{encrypt($workshop[0]->addressID)}}" />
              <button class="main_btn" type="submit">save changes</button>
              <!-- <a href="#" >save changes</a> -->
            </form>
            <br>
            <br>
            <h5>Create New Password</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
            <form action="{{ route('workshop.password_update') }}" enctype="multipart/form-data" id="passwordresetForm" method="post" class="needs-validation" novalidate>
            @csrf
              <div class="row dashboard_forms">
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Old Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Old Password" required>
                  </div>
                </div>
              </div>
              <div class="row dashboard_forms">
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                  <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm Password" required>
                  </div>
                </div>
              </div>
              <br>
              <button class="main_btn" type="submit">submit</button>
              <!-- <a href="#" class="main_btn">submit</a> -->
            </form>
          </div>
        </div>
      </div>
    </div>

    <br>
    <x-slot name="styles">
      <style>
      .error{
        font-style: italic !important;
        color: red !important;
      }
      </style>
    </x-slot> 
    <x-slot name="scripts">
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
        $("#profileupdateForm").validate({
            rules: {
                Contact_person: {
                    required: true,
                },
                business_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                },
                vABN: {
                    required: true,
                },
                branchID: {
                    required: true,
                },
                vAddressline1: {
                    required: true,
                },
                stateID: {
                    required: true,
                },
                cityID: {
                    required: true,
                },
                postcode: {
                    required: true,
                },
            },

            messages: {
              Contact_person: "Please enter conact person name",
              business_name: "Please enter business name",
              phone: "Please enter phone number",
              vABN: "Please enter your ABN",
              vAddressline1: "Please enter address line 1 ",
              email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
              },
              branchID: {
                    required: "Please select a  branch"
              },
              stateID: {
                    required: "Please select a state"
              },
              cityID: {
                    required: "Please select a city"
              },
              postcode: {
                    required: "Please enter a post code"
              },
            },
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        $("#passwordresetForm").validate({
            rules: {
              current_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                },
                new_password_confirmation: {
                    required: true,
                },
                
            },

            messages: {
              current_password: "Please enter current password",
              new_password: "Please enter new password",
              new_password_confirmation: "Please enter new password",
              
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