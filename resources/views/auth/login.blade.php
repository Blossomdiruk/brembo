@section('title', 'Login')
<!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Add icon library -->

<x-guest-layout>
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content" class="container" >

            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-offset-4 col-md-4 col-lg-offset-4 col-lg-4 login_form_div shadow">

                    <!-- <p class="lang_p" style="text-align: right; margin-top: 15px; position: relative; margin-bottom: 25px; padding-right: 15px;">
                        <a href="#"><span class="lan_text_e">{{ Config::get('languages')[App::getLocale()] }}</span></a>
                        @foreach (Config::get('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                        <a href="{{ route('lang.switch', $lang) }}"><span class="lan_text_s">{{$language}}</span></a>
                        @endif
                        @endforeach
                    </p> -->

                    <div class="well no-padding" style="box-shadow: none; background-color: transparent;">


                        <img src="{{ asset('public/back/img/logo_white.svg') }}" alt="" class="img-responsive labor_logo center-block" style="padding-left: 15px; padding-right: 15px; margin-bottom: 10px;">

                    

                        <form method="POST" action="{{ route('login') }}" class="smart-form client-form">
                            @csrf
                            <header style="background-color: transparent; border:none; padding-bottom: 0px;">
                                <!-- <b style="color: #fee73d;"> {{ __('login.signIn') }}</b> -->
                              <p style="text-align: center; color: #ffffff;">
                                <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </small>
                              </p>
                            </header>
                            <!-- Email Address -->
                            <fieldset style=" background-color: transparent;">
                                <section><x-auth-validation-errors class="mb-4" :errors="$errors" /></section>
                                <section>
                                    <x-label class="label" for="email" :value="__('login.email')" />
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                                    </label>
                                </section>

                                <section>
                                    <x-label class="label" for="password" :value="__('login.password')" />
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <x-input id="password"  type="password" name="password" required autocomplete="current-password" />
                                    <div class="note">
                                    </div>
                                </section>

                            </fieldset>
                            <footer style="background: transparent !important; border:none;">
                                
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
                      
                      <small style="position: absolute; right:0px; left:0px; bottom: 15px; color: #d1d1d1; width: 100%; text-align: center;">
                      	Copyright © 2023 BREMBO. All Rights Reserved.
                      </small>



                </div>


            </div>
        </div>

    </div>
</x-guest-layout>