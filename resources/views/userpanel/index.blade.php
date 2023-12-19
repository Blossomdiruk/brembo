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

    <title>Brembo | Login</title>

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

            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"  >×</button>       
                <p>{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('danger'))

            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"  >×</button>       
                <p>{{ $message }}</p>
            </div>
            @endif
                <h1 class="big_caps">LOGIN</h1>
                <br>
              </div>
              
              <div class="main_form">
                <form id="registrationForm" method="POST" action="{{ route('workshop.login') }}">
                @csrf
                  <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Username" name="email" id="email" required>
                  </div>
                  <div class="mb-2">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                  </div>
                  <div class="text-end mb-3">
                    <a href="{{ route('workshop.forgot-password')}}" class="fst-italic text-decoration-none text-white">Forgot Password?</a>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 login_btn">LOGIN</button>
                </form>
              </div>

              <div class="text-center text-white mt-4 w-100">
                <p>Don’t have an account yet? <a href="{{ route('workshop.register') }}" class="text-white text-decoration-underline">Register</a></p>
              </div>
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

    <!-- search bar -->
    <script type="text/javascript">
     
     $(document).ready(function() {
        $("#registrationForm").validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },

            messages: {
                name: "Please enter your name",
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

    });
    </script>
    <!-- search bar -->


  </body>
</html>


