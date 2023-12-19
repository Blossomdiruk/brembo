@section('title', 'Calculation')
@php
$officeName = 'office_name_en';
$forwardType = 'type_name';
$complaintcatName = 'category_name_en';
$updatestatus = 'status_en';
@endphp
@if(Session()->get('applocale')=='ta')
@php
$officeName = 'office_name_tam';
$forwardType = 'type_name_tam';
$complaintcatName = 'category_name_ta';
$updatestatus = 'status_ta';
@endphp
@endif
@if(Session()->get('applocale')=='si')
@php
$officeName = 'office_name_sin';
$forwardType = 'type_name_sin';
$complaintcatName = 'category_name_si';
$updatestatus = 'status_si';
@endphp
@endif


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
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-table fa-fw "></i>
                        {{ __('gratuity.form_title') }}
                    </h1>
                </div>
            </div>
            {{-- <h1 class="alert alert-info"> {{ __('gratuity.complaint_ref_no') }} : {{ $data->ref_no }} </h1> --}}
            <div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="alert alert-info fade in">
                    <h5><strong>{{ __('complaintstatus.internal_complaint_no') }}</strong> : {{ $data->ref_no }}</h5>
                    <h5><strong>{{ __('complaintstatus.external_complaint_no') }}</strong> : {{ $data->external_ref_no }}</h5>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
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
            <div class="jarviswidget" id="user_register" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ __('gratuity.title') }}</h2>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->
                    <!---------------------------------------------Tab widget--------------------------------------------------------------->
                    <div class="widget-body padding-10">
                        <!--<hr class="simple">-->
                        <ul id="myTab1" class="nav nav-tabs bordered">
                            <li class="active" id="s1A">
                                <a href="#s1" data-toggle="tab">{{ __('gratuity.title') }}</a>
                            </li>
                            <li class="" id="s2B">
                                <a href="#s2" data-toggle="tab">{{ __('gratuity.title_minimum_wage') }}</a>
                            </li>
                        </ul>
                        <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">
                            <!------------------------------- Gratuity Calculation ---------------------------------------------------->
                            <div class="tab-pane fade in active" id="s1">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <form action="{{ route('calculate-gratuity') }}" enctype="multipart/form-data" method="post" id="gratuity-form" class="smart-form">
                                            @csrf
                                            <div class="row">
                                                <section class="col col-lg-12">
                                                    <label class="label">{{ __('gratuity.conditions') }}</label>
                                                    <div class="col col-12">
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="condition" id="condition1"  value="condition1" required> <i></i>At least one day during the previous year of employment, the company should have had 15 employees.
                                                        </label>
                                                    </div>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="label">{{ __('gratuity.select_wages_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="select">
                                                        <select name="wage_type" id="wage_type" required>
                                                            <option value="0" selected></option>
                                                            <option value="1" @if($gratuityDetails != ""){{ $gratuityDetails->wage_type == '1' ? "selected" : "" }}@endif>{{ __('gratuity.monthly_wage') }}</option>
                                                            <option value="2" @if($gratuityDetails != ""){{ $gratuityDetails->wage_type == '2' ? "selected" : "" }}@endif>{{ __('gratuity.daily_wage') }}</option>
                                                            <option value="3" @if($gratuityDetails != ""){{ $gratuityDetails->wage_type == '3' ? "selected" : "" }}@endif>{{ __('gratuity.piece_wage') }}</option>
                                                        </select>
                                                        <i></i>
                                                    </label>
                                                </section>

                                                <section class="col col-6">
                                                    <label class="label">{{ __('gratuity.gratuity_paid_status') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="select">
                                                        <select name="paid_status" id="paid_status" required>
                                                            <option value="" selected></option>
                                                            <option value="partially_paid" @if($gratuityDetails != ""){{ $gratuityDetails->paid_status == 'partially_paid' ? "selected" : "" }}@endif>{{ __('gratuity.partially_paid') }}</option>
                                                            <option value="not_paid" @if($gratuityDetails != ""){{ $gratuityDetails->paid_status == 'not_paid' ? "selected" : "" }}@endif>{{ __('gratuity.not_paid') }}</option>
                                                        </select>
                                                        <i></i>
                                                    </label>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-6" id="g_date">
                                                    <label class="label">{{ __('gratuity.gratuity_paid_date') }}</label>
                                                    <label class="input">
                                                        {{-- @php
                                                           echo $todayDate = date('Y-m-d');
                                                        @endphp --}}
                                                        <input type="text" id="gratuity_paid_date" name="gratuity_paid_date" value="@if($gratuityDetails != ""){{ $gratuityDetails->gratuity_paid_date }}@endif" class="datepicker" dateformat='yyyy-mm-dd'>
                                                    </label>
                                                </section>
                                                <section class="col col-6" id="g_amount">
                                                    <label class="label">{{ __('gratuity.gratuity_paid_amount') }} </label>
                                                    <label class="input">
                                                        <input type="text" id="gratuity_paid_amount" name="gratuity_paid_amount" value="@if($gratuityDetails != ""){{ $gratuityDetails->gratuity_paid_amount }}@endif" >
                                                    </label>
                                                </section>
                                                <section class="col col-6" id="lastsal">
                                                    <label class="label">{{ __('gratuity.last_salary') }} </label>
                                                    <label class="input">
                                                        <input type="text" id="last_salary" name="last_salary" value="@if($gratuityDetails != ""){{ $gratuityDetails->last_salary }}@endif">
                                                    </label>
                                                </section>
                                                <section class="col col-6" id="dailysal">
                                                    <label class="label">{{ __('gratuity.daily_wage') }} </label>
                                                    <label class="input">
                                                        <input type="text" id="daily_salary" name="daily_salary" value="@if($gratuityDetails != ""){{ $gratuityDetails->daily_salary }}@endif">
                                                    </label>
                                                </section>
                                                <section class="col col-6" id="lastthreemonsal">
                                                    <label class="label">{{ __('gratuity.last_three_month_salary') }} </label>
                                                    <label class="input">
                                                        <input type="text" id="last_three_mon_salary" name="last_three_mon_salary" value="@if($gratuityDetails != ""){{ $gratuityDetails->received_sal }}@endif">
                                                    </label>
                                                </section>
                                                <section class="col col-6" id="lastthreemonwor">
                                                    <label class="label">{{ __('gratuity.last_three_month_work_days') }}</label>
                                                    <label class="input">
                                                        <input type="text" id="last_three_mon_work_days" name="last_three_mon_work_days" value="@if($gratuityDetails != ""){{ $gratuityDetails->working_days }}@endif">
                                                    </label>
                                                </section>
                                                <section class="col col-6">
                                                    <label class="label">{{ __('gratuity.gratuity_amount') }}<span style=" color: red;">*</span> </label>
                                                    <label class="input">
                                                        <input type="text" id="gratuity_amount" name="gratuity_amount" required value="@if($gratuityDetails != ""){{ $gratuityDetails->gratuity_amount }}@endif" readonly>
                                                    </label>
                                                </section>
                                                <section class="col col-6">
                                                    <label class="label">{{ __('gratuity.surcharge') }}<span style=" color: red;">*</span> </label>
                                                    <label class="input">
                                                        <input type="text" id="surcharge" name="surcharge" required value="@if($gratuityDetails != ""){{ $gratuityDetails->surcharge }}@endif" readonly>
                                                    </label>
                                                </section>
                                                <section class="col col-6">
                                                    <label class="label">{{ __('gratuity.total_gratuity_amount') }}<span style=" color: red;">*</span> </label>
                                                    <label class="input">
                                                        <input type="text" id="tot_gratuity_amount" name="tot_gratuity_amount" required value="@if($gratuityDetails != ""){{ $gratuityDetails->total_gratuity }}@endif" readonly>
                                                    </label>
                                                </section>
                                            </div>

                                            <input type="hidden" id="terminate_date" name="terminate_date" value="{{ $data->terminate_date }}">
                                            <input type="hidden" id="gratuity_due_date" name="gratuity_due_date" value="{{ $data->gratuity_due_date }}">
                                            <input type="hidden" id="working_years" name="working_years" value="{{ $data->working_years }}">
                                            <input type="hidden" id="surcharge_percentage" name="surcharge_percentage" value="{{ $data->surcharge_percentage }}">
                                            <input type="hidden" id="join_date" name="join_date" value="{{ $data->join_date }}">

                                            <div class="row">
                                                <section class="col col-12">
                                                <p id="error-msg" style="color: red; display:none;">The conditions are not eligible to calculate the gratuity</p>
                                                <p id="error-msg-date" style="color: red; display:none;">Please set the employee's join date, terminate date </p>
                                                </section>
                                            </div>

                                            <button type="button" id="calculate" class="btn btn-primary">{{ __('gratuity.calculate') }}</button>


                                            <input type="hidden" id="complain_id" name="complain_id" value="{{ $data->id }}" >

                                            <footer style="background-color: #fff; border-top: transparent;">
                                                <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                <button type="submit" class="btn btn-primary">{{ __('gratuity.submit') }}</button>
                                                <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                            </footer>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            <!---------------------------------------------------------------- Minimum Wages Calcualtion Tab ------------------------------------------->
                            <div class="tab-pane fade in" id="s2">
                                <div class="widget-body no-padding">
                                    <fieldset>
                                        <div class="smart-form" style="@if($minWageMainDetails == "")display: none;@endif">
                                            <div class="row">
                                                <section class="col col-4">
                                                    <label class="label">{{ __('gratuity.select_wages_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="input">
                                                        <input type="text" value="@if($minWageMainDetails != "")@if($minWageMainDetails->wage_type == "1"){{ __('gratuity.monthly_wage') }}@else{{ __('gratuity.daily_wage') }}@endif @endif" readonly>
                                                    </label>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-4">
                                                    <label class="label">{{ __('gratuity.total_dificit_budget_allowance') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="input">
                                                        <input type="text" id="" name="" value="@if($minWageMainDetails != ""){{ $minWageMainDetails->tot_months_days_underpaid }}@endif" readonly>
                                                    </label>
                                                </section>
                                                <section class="col col-4">
                                                    <label class="label">{{ __('gratuity.total_dificit_budget_allowance') }}<span style="color: #FF0000;"> *</span> </label>
                                                    <label class="input">
                                                        <input type="text" id="" name="" value="@if($minWageMainDetails != ""){{ $minWageMainDetails->tot_dificit_budget_allowance }}@endif" readonly>
                                                    </label>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-lg-8">
                                                    <div class="table-bordered">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th width="40%">{{ __('gratuity.months') }}</th>
                                                                    <th width="50%">{{ __('gratuity.monthly_salary') }}</th>
                                                                    <th width="10%">{{ __('gratuity.deficit_budget_allowance') }}</th>
                                                                </tr>
                                                            </thead>
                                                            @if($minWageDetails != "")
                                                            @foreach ($minWageDetails as $minWageDetail)
                                                            <tbody>
                                                                <tr>
                                                                    <td>{{ $minWageDetail->months_days_underpaid }} </td>
                                                                    <td>{{ $minWageDetail->salary_underpaid }}</td>
                                                                    <td>{{ $minWageDetail->dificit_budget_allowance }}</td>
                                                                </tr>

                                                            </tbody>
                                                            @endforeach
                                                            @endif
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col-lg-12" style="margin-top: 2%; margin-left:16px;">
                                                    <button class="btn btn-primary delete-confirm" value="@if($minWageMainDetails != ""){{ $minWageMainDetails->id }}@endif">Recalculate</button>
                                                </section>
                                            </div>
                                        </div>
                                        <div style="@if($minWageMainDetails != "")display: none;@endif">
                                            <form action="{{ route('calculate-minimum-wage') }}" enctype="multipart/form-data" method="post" id="minimum-wages-form" class="smart-form">
                                                @csrf
                                                {{-- <div class="row"> --}}
                                                    <tr>
                                                        <td>
                                                            <section class="col col-4">
                                                                <label class="label">{{ __('gratuity.select_wages_type') }}<span style="color: #FF0000;"> *</span> </label>
                                                                <label class="select">
                                                                    <select name="wage_type_min_wages" id="wage_type_min_wages" required>
                                                                        <option value="1">{{ __('gratuity.monthly_wage') }}</option>
                                                                        <option value="2">{{ __('gratuity.daily_wage') }}</option>
                                                                    </select>
                                                                    <i></i>
                                                                </label>
                                                            </section>
                                                        </td>
                                                    </tr>
                                                {{-- </div> --}}
                                                <div class="row">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-info btn-sm" id="addrow" value="Add" type="button" onClick="addRow('dataTable')" style="background-color: #5D98CC;height: 32px; width: 100px;  padding :7px; float: right; margin-right: 2%;"><i class="glyphicon glyphicon-plus"></i>&nbsp;{{ __('gratuity.add') }}</button>
                                                    </div>
                                                    {{-- <input type="button" value="Add" onClick="addRow('dataTable')" /> --}}
                                                    <table id="dataTable" style="width: 100%">
                                                        <tr class="row_to_clone"id="row_0">
                                                            <td>
                                                                <section class="" style="margin-left: 5%; margin-right: 5%;">
                                                                    <label class="label months" id="months">{{ __('gratuity.months') }}<span style="color: #FF0000;"> *</span> </label>
                                                                    <label class="label days" id="days">{{ __('gratuity.days') }}<span style="color: #FF0000;"> *</span> </label>
                                                                    <label class="input">
                                                                        <input type="text" id="month_days_count" name="month_days_count[]" class="month_days_count" required value="">
                                                                    </label>
                                                                </section>
                                                            </td>
                                                            <td>
                                                                <section class="" style="margin-left: 5%; margin-right: 5%;">
                                                                    <label class="label monthly_salary" id="monthly_salary">{{ __('gratuity.monthly_salary') }}<span style="color: #FF0000;"> *</span> </label>
                                                                    <label class="label daily_salary_min_wages" id="daily_salary_min_wages">{{ __('gratuity.daily_salary') }}<span style="color: #FF0000;"> *</span> </label>
                                                                    <label class="input">
                                                                        <input type="text monthly_daily_salary" id="monthly_daily_salary" name="monthly_daily_salary[]" class="monthly_daily_salary" onkeydown="calculate('row_0')" required value="">
                                                                    </label>
                                                                </section>
                                                            </td>
                                                            <td>
                                                                <section class="" style="margin-left: 5%; margin-right: 5%;">
                                                                    <label class="label">{{ __('gratuity.deficit_budget_allowance') }}<span style="color: #FF0000;"> *</span> </label>
                                                                    <label class="input">
                                                                        <input type="text" id="deficit_budget_allowance" name="deficit_budget_allowance[]" class="txt deficit_budget_allowance" required value="" readonly>
                                                                    </label>
                                                                </section>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <input type="hidden" id="terminate_date" name="terminate_date" value="{{ $data->terminate_date }}">
                                                <input type="hidden" id="join_date" name="join_date" value="{{ $data->join_date }}">

                                                {{-- <button type="button" id="calculate_min_wages" class="btn btn-primary">{{ __('gratuity.calculate') }}</button> --}}

                                                <tr>
                                                    {{-- <span>Sum</span> --}}
                                                    {{-- <span id="totalOfTotals">0</span> --}}
                                                    <td>
                                                        <section class="col col-md-4">
                                                            <label class="label">{{ __('gratuity.total_dificit_budget_allowance') }}<span style="color: #FF0000;"> *</span> </label>
                                                            <label class="input">
                                                                <input type="text" id="total_dates" name="total_dates" class="txt total_dates" required value="" readonly>
                                                            </label>
                                                        </section>
                                                    </td>

                                                    <td>
                                                        <section class="col col-md-4" style="margin-left: 1%;">
                                                            <label class="label">{{ __('gratuity.total_dificit_budget_allowance') }}<span style="color: #FF0000;"> *</span> </label>
                                                            <label class="input">
                                                                <input type="text" id="total_dificit_budget_allowance" name="total_dificit_budget_allowance" class="txt total_dificit_budget_allowance" required value="" readonly>
                                                            </label>
                                                        </section>
                                                    </td>
                                                </tr>

                                                <input type="hidden" id="complain_id" name="complain_id" value="{{ $data->id }}" >

                                                <footer style="background-color: #fff; border-top: transparent;">
                                                    <input type="hidden" name="complaint_id" value="{{ $data->id }}" >
                                                    <button type="submit" class="btn btn-primary">{{ __('gratuity.submit') }}</button>
                                                    <button type="button" style=" float: right;" class="btn btn-default" onclick="window.history.back();"> {{ __('complaintstatus.back') }} </button>
                                                </footer>
                                            </form>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---------------------------------------------------------------- End Tab ------------------------------------------->

                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // var currentDate = new Date();
            $('#gratuity_paid_date').datepicker({
                dateFormat: 'yy-mm-dd',
            });

            var tot_gratuity_amount = $('#tot_gratuity_amount').val();

            var paid_status = $('#paid_status').val();

            if(tot_gratuity_amount != "" && paid_status == "not_paid") {
                $('#g_date').hide();
                $('#g_amount').hide();
            }
        });

        var myinput = document.getElementById('last_salary');

        myinput.addEventListener('keyup', function() {
            var val = this.value;
            val = val.replace(/[^0-9\.]/g,'');

            if(val != "") {
                valArr = val.split('.');
                valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
                val = valArr.join('.');
            }

            this.value = val;
        });

        var myinput = document.getElementById('gratuity_paid_amount');

        myinput.addEventListener('keyup', function() {
            var val = this.value;
            val = val.replace(/[^0-9\.]/g,'');

            if(val != "") {
                valArr = val.split('.');
                valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
                val = valArr.join('.');
            }

            this.value = val;
        });
    </script>

        <script language="javascript">

            function addRow(tableID) {
              var table = document.getElementById(tableID);
              var rowCount = table.rows.length;
            //   if (rowCount < 12) {
                // limit the user from creating fields more than your limits
                var row = table.insertRow(rowCount);

                var colCount = table.rows[0].cells.length;
                row.id = "row_" + rowCount;
                for (var i = 0; i < colCount; i++) {
                  var newcell = row.insertCell(i);
                  newcell.outerHTML = table.rows[0].cells[i].outerHTML;
                }
                var listitems = row.querySelectorAll("input");

                for (i = 0; i < listitems.length; i++) {
                  listitems[i].setAttribute("oninput", "calculate('" + row.id + "')");
                }
            //   } else {
            //     alert("Maximum Passenger per ticket is 4.");
            //   }
            }

                var joindate = new Date($('#join_date').val());
                var joinyear = joindate.getFullYear();
                var joinmonth = joindate.getMonth() + 1;
                var joinday = joindate.getDate();

                function calculate(elementID) {

                    // $('#wage_type_min_wages').change(function(){

                    var wagetype = $('#wage_type_min_wages').val();
                    var mainRow = document.getElementById(elementID);
                    var monthdayscount = mainRow.querySelectorAll(".month_days_count")[0].value;
                    var monthlydailysalary = mainRow.querySelectorAll(".monthly_daily_salary")[0].value;
                    var dificitbudgetallowance = mainRow.querySelectorAll(".deficit_budget_allowance")[0];

                    if(wagetype == '1') {

                        if(joinyear <= '2021' && joinmonth <= '7' && joinday <= '15') {

                            // var dificitbudgetallowance = (10000 - monthlydailysalary) * monthdayscount;
                            // console.log(dificitbudgetallowance);

                            // $('.deficit_budget_allowance').val(dificitbudgetallowance);

                            var myResult1 = (10000 - monthlydailysalary) * monthdayscount;
                            dificitbudgetallowance.value = myResult1;

                        } else {

                            // var dificitbudgetallowance = (12500 - monthlydailysalary) * monthdayscount;
                            // console.log(dificitbudgetallowance);

                            // $('.deficit_budget_allowance').val(dificitbudgetallowance);

                            var myResult1 = (12500 - monthlydailysalary) * monthdayscount;
                            dificitbudgetallowance.value = myResult1;
                        }

                    } else {

                        if(joinyear <= '2021' && joinmonth <= '7' && joinday <= '15') {

                            // var dificitbudgetallowance = (400 - monthlydailysalary) * monthdayscount;
                            // console.log(dificitbudgetallowance);

                            // $('.deficit_budget_allowance').val(dificitbudgetallowance);

                            var myResult1 = (400 - monthlydailysalary) * monthdayscount;
                            dificitbudgetallowance.value = myResult1;

                        } else {

                            // var dificitbudgetallowance = (500 - monthlydailysalary) * monthdayscount;
                            // console.log(dificitbudgetallowance);

                            // $('.deficit_budget_allowance').val(dificitbudgetallowance);

                            var myResult1 = (500 - monthlydailysalary) * monthdayscount;
                            dificitbudgetallowance.value = myResult1;
                        }
                    }

                    // calculate the totale of every total
                    var sumContainer = document.getElementById("total_dificit_budget_allowance");
                    var totalContainers = document.querySelectorAll(".deficit_budget_allowance"),
                        i;
                    var sumValue = 0;
                    for (i = 0; i < totalContainers.length; ++i) {
                        sumValue += parseInt(totalContainers[i].value);

                    }
                    sumContainer.textContent = sumValue;

                    $('#total_dificit_budget_allowance').val(sumValue);


                    var sumDatesContainer = document.getElementById("total_dates");
                    var totalDatesContainers = document.querySelectorAll(".month_days_count"),
                        i;
                    var sumDatesValue = 0;
                    for (i = 0; i < totalDatesContainers.length; ++i) {
                        sumDatesValue += parseInt(totalDatesContainers[i].value);

                    }
                    sumDatesContainer.textContent = sumDatesValue;

                    $('#total_dates').val(sumDatesValue);
                }
                // });
          </script>


    <x-slot name="script">
        <script>
            $(function() {
                $('#gratuity-form').parsley();
                $('#minimum-wages-form').parsley();
            });
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="js/libs/jquery-ui.min.js"><\/script>');
            }
        </script>

        <script type="text/javascript">


            $(document).ready(function() {
                // $('#t1').val();

                var lastSal = $("#lastsal").show();
                var dailySal = $("#dailysal").hide();
                var lastThreeMonSal = $("#lastthreemonsal").hide();
                var lastThreeMonWor =  $("#lastthreemonwor").hide();
                var days = $('#days').hide();
                var dailySalMinWage = $('#daily_salary_min_wages').hide();
                var selWageType = $('#wage_type').val();

                if(selWageType != "") {
                    if(selWageType == 1) {
                        $("#lastsal").show();
                        $("#dailysal").hide();
                        $("#lastthreemonwor").hide();
                        $("#lastthreemonsal").hide();
                    } else if(selWageType == 2) {
                        $("#lastsal").hide();
                        $("#dailysal").show();
                        $("#lastthreemonwor").hide();
                        $("#lastthreemonsal").hide();
                    } else {
                        $("#lastsal").hide();
                        $("#dailysal").hide();
                        $("#lastthreemonwor").show();
                        $("#lastthreemonsal").show();
                    }
                }
            });

            $(function() {
                $("#wage_type").change(function() {

                    $('#last_salary').val('');
                    $('#daily_salary').val('');
                    $('#last_three_mon_salary').val('');
                    $('#last_three_mon_work_days').val('');
                    // $('#terminate_date').val('');
                    $('#gratuity_paid_date').val('');
                    $('#gratuity_amount').val('');
                    $('#tot_gratuity_amount').val('');
                    $('#surcharge').val('');
                    $('#gratuity_paid_amount').val('');
                    $('#paid_status').val('');

                    // var test = $(this).val();
                    if ($(this).val() == "1") {
                        $("#lastsal").show();
                        $("#dailysal").hide();
                        $("#lastthreemonwor").hide();
                        $("#lastthreemonsal").hide();
                    } else if ($(this).val() == "2") {
                        $("#lastsal").hide();
                        $("#dailysal").show();
                        $("#lastthreemonwor").hide();
                        $("#lastthreemonsal").hide();
                    } else {
                        $("#lastsal").hide();
                        $("#dailysal").hide();
                        $("#lastthreemonwor").show();
                        $("#lastthreemonsal").show();
                    }
                })

                $("#paid_status").change(function() {

                    if ($(this).val() == "partially_paid") {
                        $("#g_date").show();
                        $("#g_amount").show();
                    } else {
                        $("#g_date").hide();
                        $("#g_amount").hide();
                    }
                })
            });

        </script>

        <script>
            $(document).on("click", "#calculate", function() {
                $("#error-msg").hide();
                //    var condition1 = $('#condition1').val();

               var condition1 = $('#condition1:checked').val();
               var terminatedate = $('#terminate_date').val();
               var joindate = $('#join_date').val();
               var lastsalarytext = $('#last_salary').val();

               var lastsalary=lastsalarytext.replace(/\,/g,'');

               if(joindate != "" && terminatedate != "") {

                    if(condition1 != undefined) {

                        // if(condition1 == )
                        // if option is not selected shows product price
                        $('#gratuity_amount').empty();
                        var wagetype = $('#wage_type').val();
                        // var lastsalary = $('#last_salary').val();
                        var dailysalary = $('#daily_salary').val();
                        var lastthreemonsal = $('#last_three_mon_salary').val();
                        var lastthreemonworkdays = $('#last_three_mon_work_days').val();
                        // var terminatedate = $('#terminate_date').val();
                        var paidgratuityamounttext = $('#gratuity_paid_amount').val();

                        var paidgratuityamount=paidgratuityamounttext.replace(/\,/g,'');

                        var paidstatus = $('#paid_status').val();

                        var terminationdate = new Date($('#terminate_date').val());
                        // var terminateyear = terminationdate.getFullYear();

                        var joindate = new Date($('#join_date').val());
                        // var joinyear = joindate.getFullYear();

                        var gratuitypaiddate = new Date($('#gratuity_paid_date').val());

                        var paydate = moment(terminatedate, "YYYY-MM-DD").add(30, 'days').format('YYYY/MM/DD');

                        var paydateformat = new Date(paydate);

                        var millisBetween = gratuitypaiddate.getTime() - paydateformat.getTime();

                        $('#gratuity_due_date').val(formatDate(paydateformat));

                        var gratuitydelaydays = millisBetween / (1000 * 3600 * 24);

                        // workingyears = terminateyear - joinyear;
                        var Difference_In_Time = terminationdate.getTime() - joindate.getTime();
  
                        // To calculate the no. of days between two dates
                        var numberOfDays = Difference_In_Time / (1000 * 3600 * 24);
                        workingyears = Math.floor(numberOfDays / 365);
                        // console.log(workingyears);

                        $('#working_years').val(workingyears);

                        if(wagetype == "1") {
                            gratuityAmount = (lastsalary/2)*workingyears;
                        } else if (wagetype == "2") {
                            gratuityAmount = dailysalary*14*workingyears;
                        } else {
                            gratuityAmount = (lastthreemonsal/lastthreemonworkdays)*14*workingyears;
                        }

                        if(paidstatus == 'partially_paid') {

                            remain_gratuity = gratuityAmount - paidgratuityamount;

                            if(gratuitydelaydays>=365) {

                                surcharge = remain_gratuity*(30/100);
                                $('#surcharge_percentage').val("30");
                            } else if(gratuitydelaydays >= 183) {

                                surcharge = remain_gratuity*(25/100);
                                $('#surcharge_percentage').val("25");
                            } else if(gratuitydelaydays >= 92) {

                                surcharge = remain_gratuity*(20/100);
                                $('#surcharge_percentage').val("20");
                            } else if(gratuitydelaydays >= 30) {

                                surcharge = remain_gratuity*(15/100);
                                $('#surcharge_percentage').val("15");
                            } else  {

                                surcharge = remain_gratuity*(10/100);
                                $('#surcharge_percentage').val("10");
                            }

                            totalgratuityamount = gratuityAmount + surcharge;

                            $('#gratuity_amount').val(gratuityAmount);

                            $('#surcharge').val(surcharge);

                            $('#tot_gratuity_amount').val(totalgratuityamount);

                        } else if(paidstatus == 'not_paid') {

                            var todayDate = new Date();

                            var millisBetweendate = todayDate.getTime() - paydateformat.getTime();

                            var gratuitydelaydayscount = millisBetweendate / (1000 * 3600 * 24);

                            if(gratuitydelaydayscount>=365) {

                                surcharge = gratuityAmount*(30/100);
                                $('#surcharge_percentage').val("30");

                            } else if(gratuitydelaydayscount >= 183) {

                                surcharge = gratuityAmount*(25/100);
                                $('#surcharge_percentage').val("25");

                            } else if(gratuitydelaydayscount >= 92) {

                                surcharge = gratuityAmount*(20/100);
                                $('#surcharge_percentage').val("20");

                            } else if(gratuitydelaydayscount >= 30) {

                                surcharge = gratuityAmount*(15/100);
                                $('#surcharge_percentage').val("15");

                            } else  {

                                surcharge = gratuityAmount*(10/100);
                                $('#surcharge_percentage').val("10");

                            }

                            totalgratuityamount = gratuityAmount + surcharge;

                            var fixGratuityAmount = formatNumber(gratuityAmount);

                            var fixSurcharge = formatNumber(surcharge);

                            var fixTotalGratuityAmount = formatNumber(totalgratuityamount);

                            $('#gratuity_amount').val(fixGratuityAmount);

                            $('#surcharge').val(fixSurcharge);

                            $('#tot_gratuity_amount').val(fixTotalGratuityAmount);

                            $('#gratuity_paid_amount').val('');

                        } else {
                            $('#gratuity_amount').val('');

                            $('#surcharge').val('');

                            $('#tot_gratuity_amount').val('');

                            $('#gratuity_paid_amount').val('');
                        }

                    } else {


                        $("#error-msg").show();
                    }
                } else {

                        //window.ParsleyValidator.setLocale('ta');
                    // $('#register-complaint-form').parsley();
                        // $('#register-complaint-form').parsley().on('form:error', function(formInstance) {
                        $("#error-msg-date").show();

                    }
            });

            $('#wage_type_min_wages').change(function (){

                $('#month_days_count').val('');
                $('#monthly_daily_salary').val('');
                $('#deficit_budget_allowance').val('');
                var wagetype = $('#wage_type_min_wages').val();

                if(wagetype == '1') {
                    $('#days').hide();
                    $('#daily_salary_min_wages').hide();
                    $('#monthly_salary').show();
                    $('#months').show();
                } else if (wagetype == '2') {
                    $('#days').show();
                    $('#daily_salary_min_wages').show();
                    $('#monthly_salary').hide();
                    $('#months').hide();
                }
            })

            $(document).on("click", "#calculate_min_wages", function() {

                $('#deficit_budget_allowance').empty();
                var wagetype = $('#wage_type_min_wages').val();
                var monthdayscount = $('#month_days_count').val();
                var monthlydailysalary = $('#monthly_daily_salary').val();
                var dailysalary = $('#daily_salary').val();
                var joindate = new Date($('#join_date').val());
                var joinyear = joindate.getFullYear();
                var joinmonth = joindate.getMonth() + 1;
                var joinday = joindate.getDate();

                if(wagetype == '1') {

                    // console.log(wagetype);

                    if(joinyear <= '2021' && joinmonth <= '7' && joinday <= '15') {

                        var dificitbudgetallowance = (10000 - monthlydailysalary) * monthdayscount;
                        // console.log(dificitbudgetallowance);

                        $('#deficit_budget_allowance').val(dificitbudgetallowance);

                    } else {

                        var dificitbudgetallowance = (12500 - monthlydailysalary) * monthdayscount;
                        // console.log(dificitbudgetallowance);

                        $('#deficit_budget_allowance').val(dificitbudgetallowance);
                    }

                } else {

                    if(joinyear <= '2021' && joinmonth <= '7' && joinday <= '15') {

                        var dificitbudgetallowance = (400 - monthlydailysalary) * monthdayscount;
                        // console.log(dificitbudgetallowance);

                        $('#deficit_budget_allowance').val(dificitbudgetallowance);

                    } else {

                        var dificitbudgetallowance = (500 - monthlydailysalary) * monthdayscount;
                        // console.log(dificitbudgetallowance);

                        $('#deficit_budget_allowance').val(dificitbudgetallowance);
                    }
                }

            });

            function formatNumber(number) {
                // return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                number = number.toFixed(2) + '';
                x = number.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2;
            }

            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) 
                    month = '0' + month;
                if (day.length < 2) 
                    day = '0' + day;

                return [year, month, day].join('-');
            }

        </script>

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('.delete-confirm').on('click', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');
                var id = $(this).val();
                swal({
                    title: 'Are you sure?',
                    text: 'This record and it`s details will be permanantly deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                }).then(function(value) {
                    if (value == true) {
                        window.location.replace("/calculation/delete/" + id);
                    }
                });
            });
        </script>

    </x-slot>
</x-app-layout>
