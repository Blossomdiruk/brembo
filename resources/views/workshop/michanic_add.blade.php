<x-workshop-layout>
<?php
if ($savestatus == 'A'){
    $michanic_name = "";
    $michanic_title = "";
    $michanic_email = "";
    $michanic_phone = "";
}else{
    $michanic_name = $michanics[0]->name;
    $michanic_title = $michanics[0]->title;
    $michanic_email = $michanics[0]->email;
    $michanic_phone = $michanics[0]->phone;
}
 
       
?>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>Add mechanics information</h1>
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
            <form action="{{ route('workshop.add-michanics') }}" id="michanicForm" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
            @csrf
              <div class="row dashboard_forms">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="row">
                    <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12">
                      <label class="form-label">Title</label>
                      <select class="form-select mb-3" name="michanicstitle" id="michanicstitle" aria-label="Default select example" required>
                        <option @if($michanic_title=='Mr') selected @endif value="Mr" >Mr.</option>
                        <option @if($michanic_title=='Mrs') selected @endif value="Mrs">Mrs.</option>
                        <option @if($michanic_title=='Miss') selected @endif value="Miss">Miss.</option>
                      </select>
                    </div>
                    <div class="col-xxl-9 col-lg-8 col-md-8 col-sm-7 col-12">
                      <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ $michanic_name }}" class="form-control" placeholder="Name" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ $michanic_email }}" class="form-control" placeholder="Email Address" required>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Telephone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ $michanic_phone }}" class="form-control" placeholder="Telephone Number" required>
                  </div>
                </div>
               
              </div>
              <br>
              @if($savestatus !='A')
                <input type="hidden" id="id" name="id" value="{{encrypt($michanics[0]->id)}}" />
              @endif
                <input type="hidden" id="savestatus" name="savestatus" value="{{$savestatus}}" />

              <button class="main_btn" >submit</button>
             
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
        $("#michanicForm").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                phone: {
                    required: true,
                },
                
            },

            messages: {
                name: "Please enter mechanic name",
                email: "Please enter mechanic email",
                phone: "Please enter mechanic phone",
              
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