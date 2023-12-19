<x-guest-layout>
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content" class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm" style="padding-top: 6%;">
                    <!--<h1 class="txt-color-red login-header-big">WELCOME</h1>-->
                    <div class="hero" style="background-image: none;">


                    </div>


                </div>
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 login_form_div">

                    <p class="lang_p" style="text-align: right; margin-top: 15px; position: relative; margin-bottom: 25px; padding-right: 15px;">
                        <a href="#"><span class="lan_text_e">{{ Config::get('languages')[App::getLocale()] }}</span></a>
                        @foreach (Config::get('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                        <a href="{{ route('lang.switch', $lang) }}"><span class="lan_text_s">{{$language}}</span></a>
                        @endif
                        @endforeach
                    </p>

                    <div class="well no-padding" style="box-shadow: none; background-color: transparent;">




                        <div class="mx-auto logo_col no_padding">
                            <div class="logo_col">
                                <img src="https://cms.tekgeeks.net/public/back/img/logo1.png" alt="" class="logo">
                                <p class="logo_text_e">Department Labour</p>
                                <p class="logo_text_s">කම්කරු දෙපාර්තමේන්තුව</p>
                                <p class="logo_text_t">தொழில் திணைக்களம்</p>
                            </div>
                            <img src="https://cms.tekgeeks.net/public/back/img/l_logo1.png" alt="" class="l_logo">
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="smart-form client-form">
                            @csrf
                            <header style="background-color: trnsparent; border:none; padding-bottom: 0px;">
                                <b style="color: #fee73d;">SIGN IN</b>
                            </header>
                            <!-- Email Address -->
                            <fieldset>
                                <section><x-auth-validation-errors class="mb-4" :errors="$errors" /></section>
                                <section>
                                    <x-label class="label" for="email" :value="__('login.email')" />
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter User Name</b>
                                    </label>
                                </section>

                                <section>
                                    <x-label class="label" for="password" :value="__('login.password')" />
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <x-input id="password"  type="password" name="password" required autocomplete="current-password" />
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter Password</b> </label>
                                    <div class="note">
                                    </div>
                                </section>

                            </fieldset>
                            <footer style="background-color: transparent; border:none;">
                                
                                <x-button class="btn btn-primary save_btn" style="width: 100%;display: block;">
                                    {{ __('login.login') }}
                                </x-button>
                            </footer>

                       

                            <!-- Remember Me -->
                            <!-- <div class="block mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('login.rememberMe') }}</span>
                                </label>
                            </div> -->

                            <!-- <div class="flex items-center justify-end mt-4">
                                @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('login.frogotpassword') }}
                                </a>
                                @endif

                                <x-button class="ml-3">
                                    {{ __('login.login') }}
                                </x-button>
                            </div> -->
                        </form>

                    </div>



                </div>


            </div>
        </div>

    </div>
</x-guest-layout>