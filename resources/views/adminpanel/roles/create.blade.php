@section('title', 'Profile')
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

                <div class="col-lg-4">

                    <h1 class="page-title txt-color-blueDark">

                        <i class="fa fa-table fa-fw "></i>

                        User Types

                    </h1>

                </div>





                <div class="col-lg-8">

                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="https://www.primelands.lk/adminpanel/master/user_type/add_user_type">
                                    <h5>Add New</h5>
                                </a>

                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="https://www.primelands.lk/adminpanel/master/user_type/view_user_type">
                                    <h5>View All<span class="txt-color-blue" style="text-align: center"><i class=""></i>4</span></h5>
                                </a>

                            </li>

                        </ul>

                    </ul>


                </div>



            </div>









            <!-- NEW WIDGET START -->

            <!-- Widget ID (each widget will need unique ID)-->

            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">

                <!-- widget options:

                    usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">



                    data-widget-colorbutton="false"

                    data-widget-editbutton="false"

                    data-widget-togglebutton="false"

                    data-widget-deletebutton="false"

                    data-widget-fullscreenbutton="false"

                    data-widget-custombutton="false"

                    data-widget-collapsed="true"

                    data-widget-sortable="false"



                    -->

                <header>

                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>

                    <h2></h2>



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





                        <form action="{{ route('roles.store') }}" method="post" id="utype-form" class="smart-form">



                            @csrf
                            <header>

                                User Type
                            </header>





                            <fieldset>

                                <section class="col col-6">
                                    <label class="label">User Type Name</label>
                                    <label class="input">
                                    {!! Form::text('name', null, array('placeholder' => 'Name')) !!}
                                    </label>
                                </section>







                            </fieldset>



                            <fieldset>
                                <div class="widget-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width='100%'>
                                            <thead>
                                                <tr>
                                                    <th width='8%' style="text-align:center;">#</th>
                                                    <th style="text-align:left;">Form Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach($dynamicMenu as $menu)
                                                @if($menu->is_parent==1)
                                                <tr style="background-color: #5acee880">
                                                    @else
                                                <tr>
                                                    @endif
                                                    <td>1</td>
                                                    <td>
                                                        {!! $menu->is_parent=='0' ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '' !!}{{ $menu->title }} <input type="hidden" name="formid[]" value="1">
                                                    </td>
                                                </tr>
                                                @if($menu->is_parent==0)
                                                  @foreach($permission as $value)
                                                    @if($value->dynamic_menu_id==$menu->id)
                                                    <tr>
                                                        <td>1 </td>
                                                        <td>
                                                            <section>
                                                                <label class="toggle">
                                                                {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                                                    <i data-swchon-text="ON" data-swchoff-text="OFF" style="margin-right: 35px; margin-top: -4px;"></i>
                                                                </label>
                                                            </section>
                                                            {{ $value->name }}</td>
                                                    </tr>
                                                    @endif
                                                  @endforeach
                                                @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </fieldset>
                            <footer>
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="button" class="btn btn-default" onclick="viewlist()">
                                    Back
                                </button>
                            </footer>
                            {!! Form::close() !!}
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>

    <x-slot name="script">

    </x-slot>
</x-app-layout>
