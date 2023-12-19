@section('title', 'Profile')
<x-app-layout>
    <x-slot name="header">

    </x-slot>

    @if(Session()->get('applocale')=='ta')
        @php
        $lang = "TA";
        @endphp
        @elseif(Session()->get('applocale')=='si')
        @php
        $lang = "SI";
        @endphp
        @else
        @php
        $lang = "EN";
        @endphp
    @endif

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
        <div class="row">
                <div class="col-lg-4">
                </div>
                <div class="col-lg-8">
                    
                </div>
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
                <p>{{ $message }}</p>
            </div>
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                    <h2>{{ __('user.title') }}</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="post" id="user-form" class="smart-form needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('user.name') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="name" name="name" required value="{{ auth()->user()->name }}" required>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="label">{{ __('user.email') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="email" name="email" required value="{{ auth()->user()->email }}">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">{{ __('user.password') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="password" id="passward" name="password" autocomplete="new-password" required value="" minlength="6">
                                        </label>
                                    </section>

                                    <section class="col col-6">
                                        <label class="label">{{ __('user.confirmpassword') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="confirm-password" required value="">
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                {{ __('Update') }}
                                </button>
                            </footer>
                        </form>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>
    <x-slot name="script">
        <script>
            $(function() {
                //window.ParsleyValidator.setLocale('ta');
                $('#user-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
