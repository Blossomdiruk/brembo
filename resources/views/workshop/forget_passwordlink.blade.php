<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="description" content=""/>
    <link rel="canonical" href="" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <meta name="og:image" content=""/>
    <meta name="twitter:card" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:title" content="" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="{{ asset('public/css/brembo.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/mediaquery.css') }}" rel="stylesheet">
    <!-- Custom CSS -->

    <title>Brembo | Reset Password</title>

    <!--favicon-->
    <link rel="shortcut icon" href="{{ asset('public/images/favicon.jpg') }}" />
    <!--favicon-->

    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Add icon library -->

    <!--loading effect-->
    <link rel="stylesheet" href="{{ asset('public/css/loading_styles.css') }}" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ asset('public/css/aos.css') }}" type="text/css" media="screen"/>
    <!--loading effect-->

    <!-- owl carousel -->
    <link href="{{ asset('public/owl/owl.carousel.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/owl/owl_css.css') }}">
    <!-- owl carousel -->

    <!--jarallax js & css-->
    <link href="{{ asset('public/jarallax/jarallax_css.css') }}" rel="stylesheet" type="text/css" media="screen">
    <!--jarallax js & css-->

    <!-- animate -->
    <link rel="stylesheet" href="{{ asset('public/css/animate.min.css') }}">
    <!-- animate -->


    <!--scroll bar style-->
    <style>

      ::-webkit-scrollbar {
        background: #000000;
        height: 5px;
        width: 5px;
      }

      ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 2px #E43038;
      }

      ::-webkit-scrollbar-thumb {
        background: #E43038;
        border-radius: 2px;
      }

      ::-webkit-scrollbar-thumb:hover {
        background: #E43038; 
      }
    </style>
    <!--scroll bar style-->


  </head>
  <body class="bg_none" style="background-image: url({{ asset('public/images/login_bg.jpg') }}); height: 100vh; background-position: center; background-color: #E43039; background-attachment: fixed;">

    <div class="container-fluid">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 ">
            <div class="login_left d-flex align-content-center flex-wrap">
              <div>
                <div class="d-lg-none d-block top_logo">
                  <div>
                    <img src="{{ asset('public/images/logo_white.svg') }}" alt="">
                  </div>
                    <br>
                    <h2 class="text-white mb-2">Only the top range models have Brembo calipers</h2>
                    <br>
                </div>
                <h1 class="big_caps">Reset Password</h1>
                <p class="text-white">Create your new password</p>
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
              </div>
              
              <div class="main_form">
                <form action="{{ route('workshop.password-reset') }}" enctype="multipart/form-data" method="post" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <input type="text" class="form-control" id="email" name="email"  placeholder="Email" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                  </div>  
                <div class="mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                  </div>
                  <div>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                  </div>
                  <br>
                  <button type="submit" class="btn btn-primary w-100 login_btn">Change Password</button>
                </form>
              </div>

              <!-- <div class="text-center text-white mt-4 w-100">
                <p><a href="#" class="text-white text-decoration-underline">Back to Login</a></p>
              </div> -->
            </div>
            
            <div class="breambo_kit d-lg-block d-none">
              <img src="{{ asset('public/images/brembo_kit.png') }}" alt="">
            </div>
          </div>
          <div class="col-lg-6 d-lg-block d-none">
            <div class="login_right">
              <div>
                <div class="top_logo">
                  <img src="{{ asset('public/images/logo_white.svg') }}" alt="">
                </div>
                <br>
                <h2 class="text-white">Only the top range models have Brembo calipers</h2>
                <p class="text-white">Brembo produces high-tech brake calipers installed as original equipment in the leading car models in every category.</p>
              </div>
            </div>
            <div class="text-end text-white" style="position: absolute; bottom: 0px; right: 50px;">
              <p class="fst-italic">Copyright © 2023 Brembo. All rights reserved.</p>
            </div>
          </div>
        </div>        
      </div>
    </div>

    <div class="container-fluid d-lg-none d-block" style="background-color:#E43038 ;">
      <div class="text-center text-white">
        <hr class="mt-0 mb-1">
        <p class="fst-italic mb-0 pb-1">Copyright © 2023 Brembo. All rights reserved.</p>
      </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="{{ asset('public/js/jquery-3.2.1.min.js') }}"></script>
      <script src="{{ asset('public/js/popper.min.js') }}" ></script> 
      <script src="{{ asset('public/js/bootstrap.min.js') }}" ></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>


    <!--loading effects-->
    <script src="{{ asset('public/js/aos.js') }}"></script>

    <script>
      AOS.init({
      easing: 'ease-out-back',
              duration: 1000
      });
    </script>
    <!--loading effects-->

    <!-- owl carousel -->
    <script src="{{ asset('public/owl/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/owl/owl_js.js') }}"></script>
    <!-- owl carousel -->

    <!--jarallax js-->
    <script src="{{ asset('public/jarallax/jarallax_js.js') }}"></script>
    <!--jarallax js-->

    <!--jarallax-->
    <script type="text/javascript">
        /* init Jarallax */
        $('.jarallax').jarallax({
            speed: 0.5,
            imgWidth: 1366,
            imgHeight: 768
        })
    </script>
    <!--jarallax-->

    <!-- scroll top -->
    <script type="module">
      import ScrollTop from 'https://cdn.skypack.dev/smooth-scroll-top';
      const scrollTop = new ScrollTop();
      scrollTop.init();
        </script>
    <!-- scroll top -->

    </body>
</html>


