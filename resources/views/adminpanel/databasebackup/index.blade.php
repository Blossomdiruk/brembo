@section('title', 'Province')
<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
            <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;">

                    </div>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
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
                    <h2>{{ __('databasebackup.title') }}</h2>
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
                        <form action="{{ route('our_backup_database') }}" enctype="multipart/form-data" method="post" id="database-backup-form" class="smart-form">
                            @csrf
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">{{ __('databasebackup.heading') }} </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                        {{ __('databasebackup.download') }}
                                    </button>
                                </div>
                            </fieldset>
                        </form>

                                                {{-- <form action="{{ route('our_backup_database') }}" method="get">
            <button style="submit" class="btn btn-primary"> download</button>
        </form> --}}

                        {{-- <form action="{{ route('our_backup_database') }}" method="get">
            <button style="submit" class="btn btn-primary"> download</button>
        </form> --}}
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
            $(function(){
                //window.ParsleyValidator.setLocale('ta');
                $('#province-form').parsley();
            });
        </script>
    </x-slot>
</x-app-layout>
