<?php

namespace App\Http\Controllers\Adminpanel;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\LabourOfficeDivision;
use Illuminate\Http\Request;
use App\Models\RegisterComplaint;
use App\Models\Complain_Category;
use Carbon\Carbon;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DataTables;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $office_id = Auth::user()->office_id;

        $officename = LabourOfficeDivision::where('id', $office_id)->first();

        // dd($officename);

        $userrole = Auth::user()->roles->pluck('name')->first();

        $newcomplaintcount = RegisterComplaint::where('complaint_status', 'New')
            ->where('action_type','Pending')
            ->where('current_office_id', $office_id)
            ->count();

        $pendingcomplaintcount = RegisterComplaint::where('action_type', 'Pending')
           ->where('complaint_status', '<>', 'New')
            ->where('current_office_id', $office_id)
            ->count();

        $ongoingcomplaintcount = RegisterComplaint::where('action_type', 'Ongoing')
            ->where('current_office_id', $office_id)
            ->count();

        $tempclosedcomplaintcount = RegisterComplaint::where('action_type', 'TempClosed')
            ->where('current_office_id', $office_id)
            ->count();

        $closedcomplaintcount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $office_id)
            ->count();

        $recoverycomplaintcount = RegisterComplaint::where('action_type', 'Pending_recovery')
            ->where('current_office_id', $office_id)
            ->count();

        $appealcomplaintcount = RegisterComplaint::where('action_type', 'Waiting')
            ->where('current_office_id', $office_id)
            ->count();

        $legalcomplaintcount = RegisterComplaint::where('action_type', 'Pending_legal')
            ->where('current_office_id', $office_id)
            ->count();

        $chargecomplaintcount = RegisterComplaint::where('action_type', 'Pending_plaint_charge_sheet')
            ->where('current_office_id', $office_id)
            ->count();

        $date = \Carbon\Carbon::now();
        $lastMonth =  $date->subMonth()->format('F');

        $lastmonthallcomplaints = RegisterComplaint::where('current_office_id', $office_id)->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

        $lastmonthpendingcomplaintcount = RegisterComplaint::where('action_type', 'Pending')
            ->where('current_office_id', $office_id)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        if($lastmonthpendingcomplaintcount > 0) {

            $lastmonpendingcomplaintcountper = ($lastmonthpendingcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonpendingcomplaintcount = round($lastmonpendingcomplaintcountper, 1);

        } else {

            $lastmonpendingcomplaintcount = $lastmonthpendingcomplaintcount;

        }

        $lastmonthongoingcomplaintcount = RegisterComplaint::where('action_type', 'Ongoing')
            ->where('current_office_id', $office_id)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        if($lastmonthongoingcomplaintcount > 0) {

            $lastmonongoingcomplaintcountper = ($lastmonthongoingcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonongoingcomplaintcount = round($lastmonongoingcomplaintcountper, 1);

        } else {

            $lastmonongoingcomplaintcount = $lastmonthongoingcomplaintcount;

        }

        $lastmonthtempclosedcomplaintcount = RegisterComplaint::where('action_type', 'Tempclosed')
            ->where('current_office_id', $office_id)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        if($lastmonthtempclosedcomplaintcount > 0) {

            $lastmontempclosedcomplaintcountper = ($lastmonthtempclosedcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmontempclosedcomplaintcount = round($lastmontempclosedcomplaintcountper, 1);

        } else {

            $lastmontempclosedcomplaintcount = $lastmonthtempclosedcomplaintcount;

        }

        $lastmonthclosedcomplaintcount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $office_id)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        if($lastmonthclosedcomplaintcount > 0) {

            $lastmonclosedcomplaintcountper = ($lastmonthclosedcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonclosedcomplaintcount = round($lastmonclosedcomplaintcountper, 1);

        } else {

            $lastmonclosedcomplaintcount = $lastmonthclosedcomplaintcount;

        }

        $lastyearallcomplaints = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->count();

        $lastyearpendingcomplaintcount = RegisterComplaint::where('action_type', 'Pending')
            ->where('current_office_id', $office_id)
            ->whereYear('created_at', date('Y', strtotime('-1 year')))
            ->count();

        if($lastyearpendingcomplaintcount > 0) {

            $lastyearpendingcomplaintcountper = ($lastyearpendingcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrpendingcomplaintcount = round($lastyearpendingcomplaintcountper, 1);

        } else {
            $lastyrpendingcomplaintcount = $lastyearpendingcomplaintcount;
        }

        $lastyearongoingcomplaintcount = RegisterComplaint::where('action_type', 'Ongoing')
            ->where('current_office_id', $office_id)
            ->whereYear('created_at', date('Y', strtotime('-1 year')))
            ->count();

        if($lastyearongoingcomplaintcount > 0) {

            $lastyrongoingcomplaintcountper = ($lastyearongoingcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrongoingcomplaintcount = round($lastyrongoingcomplaintcountper, 1);

        } else {
            $lastyrongoingcomplaintcount = $lastyearongoingcomplaintcount;
        }

        $lastyeartempclosedcomplaintcount = RegisterComplaint::where('action_type', 'Tempclosed')
            ->where('current_office_id', $office_id)
            ->whereMonth('created_at', date('Y', strtotime('-1 year')))
            ->count();

        if($lastyeartempclosedcomplaintcount > 0) {

            $lastyrtempclosedcomplaintcountper = ($lastyeartempclosedcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrtempclosedcomplaintcount = round($lastyrtempclosedcomplaintcountper, 1);

        } else {
            $lastyrtempclosedcomplaintcount = $lastyeartempclosedcomplaintcount;
        }

        $lastyearclosedcomplaintcount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $office_id)
            ->whereMonth('created_at', '=', date('Y', strtotime('-1 year')))
            ->count();

        if($lastyearclosedcomplaintcount > 0) {

            $lastyrclosedcomplaintcountper = ($lastyearclosedcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrclosedcomplaintcount = round($lastyrclosedcomplaintcountper, 1);

        } else {
            $lastyrclosedcomplaintcount = $lastyearclosedcomplaintcount;
        }

        $approvecount = RegisterComplaint::where('action_type', 'Pending_approve')
        ->where('current_office_id', $office_id)
        ->count();

        $currentyear = Carbon::now()->format('Y');

        $jancomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '01')->count();
        $febcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '02')->count();
        $marcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '03')->count();
        $aprcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '04')->count();
        $maycomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '05')->count();
        $juncomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '06')->count();
        $julcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '07')->count();
        $augcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '08')->count();
        $sepcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '09')->count();
        $octcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '10')->count();
        $novcomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '11')->count();
        $deccomplaincount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '12')->count();

        /****************LAST 5 YEARS CHART DETAILS********************* */

        $lastyear = Carbon::now()->year - 1;
        $lastyearcount = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->count();

        $year5 = Carbon::now()->year - 2;
        $yearcount5 = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-2 year')))->count();

        $year4 = Carbon::now()->year - 3;
        $yearcount4 = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-3 year')))->count();

        $year3 = Carbon::now()->year - 4;
        $yearcount3 = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-4 year')))->count();

        $year2 = Carbon::now()->year - 5;
        $yearcount2 = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-5 year')))->count();

        $year1 = Carbon::now()->year - 6;
        $yearcount1 = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', date('Y', strtotime('-6 year')))->count();

        /****************END LAST 5 YEARS CHART DETAILS********************* */

        /****************PAST COMPLAINTS SUMMERY************************* */

        $last_three_month = Carbon::now()->startOfMonth()->subMonth(3);
        $last_six_month = Carbon::now()->startOfMonth()->subMonth(6);
        $last_twel_month = Carbon::now()->startOfMonth()->subMonth(12);
        $this_month = Carbon::now()->startOfMonth();

        // < 1 month
        $pastMonthComplaints = RegisterComplaint::where('current_office_id', $office_id)->where('complaint_status', '<>', 'Closed')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

        // 1 - 3 months
        $lastThreeMonthComplaints = RegisterComplaint::where('current_office_id', $office_id)->where('complaint_status', '<>', 'Closed')->whereBetween('created_at', [$last_three_month,$this_month])->count();

        // 3 - 6 months
        $lastThreeToSixMonthComplaints = RegisterComplaint::where('current_office_id', $office_id)->where('complaint_status', '<>', 'Closed')->whereBetween('created_at', [$last_six_month,$last_three_month])->count();

        // 6 - 12 months
        $lastSixToTwelMonthComplaints = RegisterComplaint::where('current_office_id', $office_id)->where('complaint_status', '<>', 'Closed')->whereBetween('created_at', [$last_twel_month,$last_six_month])->count();

        // > 1 year
        $pastYearComplaints = RegisterComplaint::where('current_office_id', $office_id)->where('complaint_status', '<>', 'Closed')->where('created_at', '<=', $last_twel_month)->count();


        /****************END PAST COMPLAINTS SUMMERY********************* */


        // $dataYear1 = RegisterComplaint::select(DB::raw("(COUNT(*)) as count"), DB::raw('MONTH(created_at) month'))
        //                         ->whereYear('created_at', date('Y', strtotime('-2 year')))
        //                         ->groupby('month')
        //                         ->get();

        // $dataYear2 = RegisterComplaint::select(DB::raw("(COUNT(*)) as count"), DB::raw('MONTH(created_at) month'))
        //                         ->whereYear('created_at', date('Y', strtotime('-2 year')))
        //                         ->groupby('month')
        //                         ->get();

        // $dataByMonth = [];
        // foreach ($dataYear1 as $dataYear) {
        //     for ($m=1; $m<=24; $m++) {
        //         $month = date('Y', mktime(0,0,0,$m, 1, date('Y')));

        //         if ($month == $dataYear->month) {
        //             $dataByMonth[] = $dataYear->count;
        //         }
        //     }
        // }
        // dd($dataByMonth);

        $labels = [];
        $dataByMonth = [];
        $period = now()->subMonths(12)->monthsUntil(now());
        foreach ($period as $date) {
            $labels[] = $date->year . ' ' . $date->monthName;

            // $newcomplaints[] = RegisterComplaint::where('current_office_id', $office_id)->where('complaint_status', 'New')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $newcomplaints[] = RegisterComplaint::where('current_office_id', $office_id)->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();

            // $closedcomplaints[] = RegisterComplaint::where('current_office_id', $office_id)->where('action_type', 'Closed')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $closedcomplaints[] = RegisterComplaint::where('current_office_id', $office_id)->where('action_type', 'Closed')->whereYear('updated_at', $date->year)->whereMonth('updated_at', $date->month)->count();
            // dump($dataByMonth);
        }
        // dd($labels);

        // $dataYear = RegisterComplaint::select(DB::raw("(COUNT(*)) as count"), DB::raw('MONTH(created_at) month'))
        //                         ->whereYear('created_at', date('Y', strtotime('-2 year')))
        //                         ->groupby('month')
        //                         ->get();

        $categorylist = Complain_Category::where('status', 'Y')->get();

        $datalist = [];
        $complaintcatwise = [];
        foreach ($categorylist as $category) {
            // \DB::enableQueryLog();
            $complaintcatwise = RegisterComplaint::where('current_office_id', $office_id)->whereIn('complain_category', [$category->id])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
                        // $events = \DB::getQueryLog();
                        // print_r($events);
                        // exit();
            $datalist[] = ['label'=>$category->category_name_en, 'values'=>array($complaintcatwise)];
        }

        $datalist2 = [];
        $complaintcatwise2 = [];
        foreach ($categorylist as $category2) {
            $complaintcatwise2 = RegisterComplaint::where('current_office_id', $office_id)->whereIn('complain_category', [$category2->id])->whereYear('created_at', date('Y', strtotime('-1 year')))->count();

            $datalist2[] = ['label'=>$category2->category_name_en, 'values'=>array($complaintcatwise2)];
        }

        if($office_id == 1 || $office_id == 16) {

            $alloffices = LabourOfficeDivision::get();


        } else {

            $alloffices = LabourOfficeDivision::where('labour_offices_divisions.zone_id', $office_id)
                                                ->get();

        }

        $complaints = RegisterComplaint::count();

        $zonalofficesavailability = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

                                                        // dd($zonalofficesavailability);
        return view('dashboard', compact('newcomplaintcount','pendingcomplaintcount','ongoingcomplaintcount',
                                        'tempclosedcomplaintcount','closedcomplaintcount', 'lastmonpendingcomplaintcount',
                                        'lastMonth','lastmonongoingcomplaintcount','lastmontempclosedcomplaintcount',
                                        'lastmonclosedcomplaintcount','jancomplaincount', 'lastyrpendingcomplaintcount',
                                        'lastyrongoingcomplaintcount', 'lastyrtempclosedcomplaintcount', 'lastyrclosedcomplaintcount',
                                        'febcomplaincount','marcomplaincount','aprcomplaincount','maycomplaincount',
                                        'juncomplaincount','julcomplaincount','augcomplaincount','sepcomplaincount',
                                        'octcomplaincount','novcomplaincount','deccomplaincount','lastyear','lastyearcount',
                                        'year5','yearcount5','year4','yearcount4','year3','yearcount3','year2','yearcount2',
                                        'year1','yearcount1', 'labels', 'newcomplaints','closedcomplaints', 'officename', 'datalist',
                                        'datalist2','alloffices', 'legalcomplaintcount', 'chargecomplaintcount', 'recoverycomplaintcount',
                                        'complaints', 'appealcomplaintcount','approvecount','zonalofficesavailability','office_id','userrole',
                                        'pastMonthComplaints','lastThreeMonthComplaints','lastThreeToSixMonthComplaints','lastSixToTwelMonthComplaints',
                                        'pastYearComplaints'));
    }

    public function officesSummery()
    {

        $office_id = Auth::user()->office_id;

        $officename = LabourOfficeDivision::where('id', $office_id)->first();

        $userrole = Auth::user()->roles->pluck('name')->first();

        // dd($officename);

        if($officename->office_type_id == 1 || $officename->id == 16) {

            $newcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->where('register_complaints.complaint_status', 'New')
                                                ->where('register_complaints.action_type','Pending')
                                                ->count();

            $pendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending')
                                                    ->where('complaint_status', '<>', 'New')
                                                        ->count();

            $ongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Ongoing')
                                                        ->count();

            $tempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'TempClosed')
                                                        ->count();

            $closedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Closed')
                                                        ->count();

            $recoverycomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_recovery')
                                                        ->count();

            $appealcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Waiting')
                                                        ->count();

            $legalcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_legal')
                                                        ->count();

            $chargecomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_plaint_charge_sheet')
                                                        ->count();

            $approvecount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->where('register_complaints.action_type', 'Pending_approve')
                                            ->count();

            $date = \Carbon\Carbon::now();
            $lastMonth =  $date->subMonth()->format('F');

            $lastmonthallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                        ->count();

            $lastmonthpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Pending')
                                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                                ->count();

            if($lastmonthpendingcomplaintcount > 0) {

                $lastmonpendingcomplaintcountper = ($lastmonthpendingcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmonpendingcomplaintcount = round($lastmonpendingcomplaintcountper, 1);

            } else {

                $lastmonpendingcomplaintcount = $lastmonthpendingcomplaintcount;

            }

            $lastmonthongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Ongoing')
                                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                                ->count();

            if($lastmonthongoingcomplaintcount > 0) {

                $lastmonongoingcomplaintcountper = ($lastmonthongoingcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmonongoingcomplaintcount = round($lastmonongoingcomplaintcountper, 1);

            } else {

                $lastmonongoingcomplaintcount = $lastmonthongoingcomplaintcount;

            }

            $lastmonthtempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Tempclosed')
                                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                                ->count();

            if($lastmonthtempclosedcomplaintcount > 0) {

                $lastmontempclosedcomplaintcountper = ($lastmonthtempclosedcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmontempclosedcomplaintcount = round($lastmontempclosedcomplaintcountper, 1);

            } else {

                $lastmontempclosedcomplaintcount = $lastmonthtempclosedcomplaintcount;

            }

            $lastmonthclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Closed')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                            ->count();

            if($lastmonthclosedcomplaintcount > 0) {

                $lastmonclosedcomplaintcountper = ($lastmonthclosedcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmonclosedcomplaintcount = round($lastmonclosedcomplaintcountper, 1);

            } else {

                $lastmonclosedcomplaintcount = $lastmonthclosedcomplaintcount;

            }

            $lastyearallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                        ->count();

            $lastyearpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Pending')
                                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                            ->count();

            if($lastyearpendingcomplaintcount > 0) {

                $lastyrpendingcomplaintcountper = ($lastyearpendingcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrpendingcomplaintcount = round($lastyrpendingcomplaintcountper, 1);

            } else {

                $lastyrpendingcomplaintcount = $lastyearpendingcomplaintcount;

            }

            $lastyearongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Ongoing')
                                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                            ->count();

            if($lastyearongoingcomplaintcount > 0) {

                $lastyrongoingcomplaintcountper = ($lastyearongoingcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrongoingcomplaintcount = round($lastyrongoingcomplaintcountper, 1);

            } else {

                $lastyrongoingcomplaintcount = $lastyearongoingcomplaintcount;

            }

            $lastyeartempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Tempclosed')
                                                                ->whereMonth('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                                ->count();

            if($lastyeartempclosedcomplaintcount > 0) {

                $lastyrtempclosedcomplaintcountper = ($lastyeartempclosedcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrtempclosedcomplaintcount = round($lastyrtempclosedcomplaintcountper, 1);

            } else {

                $lastyrtempclosedcomplaintcount = $lastyeartempclosedcomplaintcount;

            }

            $lastyearclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Closed')
                                                            ->whereMonth('register_complaints.created_at', '=', date('Y', strtotime('-1 year')))
                                                            ->count();

            if($lastyearclosedcomplaintcount > 0) {

                $lastyrclosedcomplaintcountper = ($lastyearclosedcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrclosedcomplaintcount = round($lastyrclosedcomplaintcountper, 1);

            } else {

                $lastyrclosedcomplaintcount = $lastyearclosedcomplaintcount;

            }

            $currentyear = Carbon::now()->format('Y');

            $jancomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '01')
                                                    ->count();

            $febcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '02')
                                                    ->count();

            $marcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '03')
                                                    ->count();

            $aprcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '04')
                                                    ->count();

            $maycomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '05')
                                                    ->count();

            $juncomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '06')
                                                    ->count();

            $julcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '07')
                                                    ->count();

            $augcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '08')
                                                    ->count();

            $sepcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '09')
                                                    ->count();

            $octcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '10')
                                                    ->count();

            $novcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '11')
                                                    ->count();

            $deccomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '12')
                                                    ->count();

            $lastyear = Carbon::now()->year - 1;
            $lastyearcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->count();

            $year5 = Carbon::now()->year - 2;
            $yearcount5 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-2 year')))
                                            ->count();

            $year4 = Carbon::now()->year - 3;
            $yearcount4 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-3 year')))
                                            ->count();

            $year3 = Carbon::now()->year - 4;
            $yearcount3 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-4 year')))
                                            ->count();

            $year2 = Carbon::now()->year - 5;
            $yearcount2 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-5 year')))
                                            ->count();

            $year1 = Carbon::now()->year - 6;
            $yearcount1 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-6 year')))
                                            ->count();

            $labels = [];
            $dataByMonth = [];
            $period = now()->subMonths(12)->monthsUntil(now());
            foreach ($period as $date) {
                $labels[] = $date->year . ' ' . $date->monthName;

                $newcomplaints[] = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.complaint_status', 'New')
                                                    ->whereYear('register_complaints.created_at', $date->year)
                                                    ->whereMonth('register_complaints.created_at', $date->month)
                                                    ->count();

                $closedcomplaints[] = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('action_type', 'Closed')
                                                        ->whereYear('register_complaints.created_at', $date->year)
                                                        ->whereMonth('register_complaints.created_at', $date->month)
                                                        ->count();
            }

            $categorylist = Complain_Category::where('status', 'Y')->get();

            $datalist = [];
            $complaintcatwise = [];
            foreach ($categorylist as $category) {

                $complaintcatwise = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.complain_category', '=', $category->id)
                                                    ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                    ->count();

                $datalist[] = ['label'=>$category->category_name_en, 'values'=>array($complaintcatwise)];
            }

            $datalist2 = [];
            $complaintcatwise2 = [];
            foreach ($categorylist as $category2) {
                $complaintcatwise2 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.complain_category', '=', $category2->id)
                                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                        ->count();

                $datalist2[] = ['label'=>$category2->category_name_en, 'values'=>array($complaintcatwise2)];
            }

            /****************PAST COMPLAINTS SUMMERY************************* */

                $last_three_month = Carbon::now()->startOfMonth()->subMonth(3);
                $last_six_month = Carbon::now()->startOfMonth()->subMonth(6);
                $last_twel_month = Carbon::now()->startOfMonth()->subMonth(12);
                $this_month = Carbon::now()->startOfMonth();

                // < 1 month
                $pastMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

                // 1 - 3 months
                $lastThreeMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')->whereBetween('created_at', [$last_three_month,$this_month])->count();

                // 3 - 6 months
                $lastThreeToSixMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')->whereBetween('created_at', [$last_six_month,$last_three_month])->count();

                // 6 - 12 months
                $lastSixToTwelMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')->whereBetween('created_at', [$last_twel_month,$last_six_month])->count();

                // > 1 year
                $pastYearComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')->where('created_at', '<=', $last_twel_month)->count();


            /****************END PAST COMPLAINTS SUMMERY********************* */

            return view('summery_dashboard', compact('officename','newcomplaintcount','pendingcomplaintcount','ongoingcomplaintcount','tempclosedcomplaintcount',
                                                    'closedcomplaintcount','lastmonpendingcomplaintcount',
                                                    'lastMonth','lastmonongoingcomplaintcount','lastmontempclosedcomplaintcount',
                                                    'lastmonclosedcomplaintcount','jancomplaincount', 'lastyrpendingcomplaintcount',
                                                    'lastyrongoingcomplaintcount', 'lastyrtempclosedcomplaintcount', 'lastyrclosedcomplaintcount',
                                                    'febcomplaincount','marcomplaincount','aprcomplaincount','maycomplaincount',
                                                    'juncomplaincount','julcomplaincount','augcomplaincount','sepcomplaincount',
                                                    'octcomplaincount','novcomplaincount','deccomplaincount','lastyear','lastyearcount',
                                                    'year5','yearcount5','year4','yearcount4','year3','yearcount3','year2','yearcount2',
                                                    'year1','yearcount1', 'labels', 'newcomplaints','closedcomplaints', 'datalist', 'datalist2',
                                                    'legalcomplaintcount', 'chargecomplaintcount', 'recoverycomplaintcount', 'appealcomplaintcount','approvecount','userrole',
                                                    'pastMonthComplaints','lastThreeMonthComplaints','lastThreeToSixMonthComplaints','lastSixToTwelMonthComplaints',
                                                    'pastYearComplaints'));

        } else {

            $newcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->where('register_complaints.complaint_status', 'New')
                                                ->where('register_complaints.action_type','Pending')
                                                ->where('labour_offices_divisions.zone_id', $office_id)
                                                ->count();

            $pendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->where('complaint_status', '<>', 'New')
                                                        ->count();

            $ongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Ongoing')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $tempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'TempClosed')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $closedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Closed')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $recoverycomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_recovery')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $appealcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Waiting')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $legalcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_legal')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $chargecomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_plaint_charge_sheet')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $approvecount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending_approve')
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $date = \Carbon\Carbon::now();
            $lastMonth =  $date->subMonth()->format('F');

            $lastmonthallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $lastmonthpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Pending')
                                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                                ->where('labour_offices_divisions.zone_id', $office_id)
                                                                ->count();

            if($lastmonthpendingcomplaintcount > 0) {

                $lastmonpendingcomplaintcountper = ($lastmonthpendingcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmonpendingcomplaintcount = round($lastmonpendingcomplaintcountper, 1);

            } else {

                $lastmonpendingcomplaintcount = $lastmonthpendingcomplaintcount;

            }

            $lastmonthongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Ongoing')
                                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                                ->where('labour_offices_divisions.zone_id', $office_id)
                                                                ->count();

            if($lastmonthongoingcomplaintcount > 0) {

                $lastmonongoingcomplaintcountper = ($lastmonthongoingcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmonongoingcomplaintcount = round($lastmonongoingcomplaintcountper, 1);

            } else {

                $lastmonongoingcomplaintcount = $lastmonthongoingcomplaintcount;

            }

            $lastmonthtempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Tempclosed')
                                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                                ->where('labour_offices_divisions.zone_id', $office_id)
                                                                ->count();

            if($lastmonthtempclosedcomplaintcount > 0) {

                $lastmontempclosedcomplaintcountper = ($lastmonthtempclosedcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmontempclosedcomplaintcount = round($lastmontempclosedcomplaintcountper, 1);

            } else {

                $lastmontempclosedcomplaintcount = $lastmonthtempclosedcomplaintcount;

            }

            $lastmonthclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Closed')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                                            ->count();

            if($lastmonthclosedcomplaintcount > 0) {

                $lastmonclosedcomplaintcountper = ($lastmonthclosedcomplaintcount/$lastmonthallcomplaints) * 100;

                $lastmonclosedcomplaintcount = round($lastmonclosedcomplaintcountper, 1);

            } else {

                $lastmonclosedcomplaintcount = $lastmonthclosedcomplaintcount;

            }

            $lastyearallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

            $lastyearpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Pending')
                                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                                            ->count();

            if($lastyearpendingcomplaintcount > 0) {

                $lastyrpendingcomplaintcountper = ($lastyearpendingcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrpendingcomplaintcount = round($lastyrpendingcomplaintcountper, 1);

            } else {

                $lastyrpendingcomplaintcount = $lastyearpendingcomplaintcount;

            }

            $lastyearongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Ongoing')
                                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                                            ->count();

            if($lastyearongoingcomplaintcount > 0) {

                $lastyrongoingcomplaintcountper = ($lastyearongoingcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrongoingcomplaintcount = round($lastyrongoingcomplaintcountper, 1);

            } else {

                $lastyrongoingcomplaintcount = $lastyearongoingcomplaintcount;

            }

            $lastyeartempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                                ->where('register_complaints.action_type', 'Tempclosed')
                                                                ->whereMonth('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                                ->where('labour_offices_divisions.zone_id', $office_id)
                                                                ->count();

            if($lastyeartempclosedcomplaintcount > 0) {

                $lastyrtempclosedcomplaintcountper = ($lastyeartempclosedcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrtempclosedcomplaintcount = round($lastyrtempclosedcomplaintcountper, 1);

            } else {

                $lastyrtempclosedcomplaintcount = $lastyeartempclosedcomplaintcount;

            }

            $lastyearclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Closed')
                                                            ->whereMonth('register_complaints.created_at', '=', date('Y', strtotime('-1 year')))
                                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                                            ->count();

            if($lastyearclosedcomplaintcount > 0) {

                $lastyrclosedcomplaintcountper = ($lastyearclosedcomplaintcount/$lastyearallcomplaints) * 100;

                $lastyrclosedcomplaintcount = round($lastyrclosedcomplaintcountper, 1);

            } else {

                $lastyrclosedcomplaintcount = $lastyearclosedcomplaintcount;

            }
            // dd($lastMonth);

            $currentyear = Carbon::now()->format('Y');

            $jancomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '01')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $febcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '02')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $marcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '03')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $aprcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '04')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $maycomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '05')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $juncomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '06')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $julcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '07')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $augcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '08')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $sepcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '09')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $octcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '10')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $novcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '11')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $deccomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->whereMonth('register_complaints.created_at', '12')
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

            $lastyear = Carbon::now()->year - 1;
            $lastyearcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->where('labour_offices_divisions.zone_id', $office_id)
                                                ->count();

            $year5 = Carbon::now()->year - 2;
            $yearcount5 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-2 year')))
                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                            ->count();

            $year4 = Carbon::now()->year - 3;
            $yearcount4 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-3 year')))
                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                            ->count();

            $year3 = Carbon::now()->year - 4;
            $yearcount3 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-4 year')))
                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                            ->count();

            $year2 = Carbon::now()->year - 5;
            $yearcount2 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-5 year')))
                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                            ->count();

            $year1 = Carbon::now()->year - 6;
            $yearcount1 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-6 year')))
                                            ->where('labour_offices_divisions.zone_id', $office_id)
                                            ->count();

            $labels = [];
            $dataByMonth = [];
            $period = now()->subMonths(12)->monthsUntil(now());
            foreach ($period as $date) {
                $labels[] = $date->year . ' ' . $date->monthName;

                $newcomplaints[] = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.complaint_status', 'New')
                                                    ->whereYear('register_complaints.created_at', $date->year)
                                                    ->whereMonth('register_complaints.created_at', $date->month)
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

                $closedcomplaints[] = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('action_type', 'Closed')
                                                        ->whereYear('register_complaints.created_at', $date->year)
                                                        ->whereMonth('register_complaints.created_at', $date->month)
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();
            }

            $categorylist = Complain_Category::where('status', 'Y')->get();

            $datalist = [];
            $complaintcatwise = [];
            foreach ($categorylist as $category) {

                $complaintcatwise = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.complain_category', '=', $category->id)
                                                    ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                    ->where('labour_offices_divisions.zone_id', $office_id)
                                                    ->count();

                $datalist[] = ['label'=>$category->category_name_en, 'values'=>array($complaintcatwise)];
            }

            $datalist2 = [];
            $complaintcatwise2 = [];
            foreach ($categorylist as $category2) {
                $complaintcatwise2 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.complain_category', '=', $category2->id)
                                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                        ->where('labour_offices_divisions.zone_id', $office_id)
                                                        ->count();

                $datalist2[] = ['label'=>$category2->category_name_en, 'values'=>array($complaintcatwise2)];
            }

            /****************PAST COMPLAINTS SUMMERY************************* */

                $last_three_month = Carbon::now()->startOfMonth()->subMonth(3);
                $last_six_month = Carbon::now()->startOfMonth()->subMonth(6);
                $last_twel_month = Carbon::now()->startOfMonth()->subMonth(12);
                $this_month = Carbon::now()->startOfMonth();

                // < 1 month
                $pastMonthComplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->where('labour_offices_divisions.zone_id', $office_id)
                ->where('register_complaints.complaint_status', '<>', 'Closed')
                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                ->count();

                // 1 - 3 months
                $lastThreeMonthComplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->where('labour_offices_divisions.zone_id', $office_id)
                ->where('register_complaints.complaint_status', '<>', 'Closed')
                ->whereBetween('register_complaints.created_at', [$last_three_month,$this_month])
                ->count();

                // 3 - 6 months
                $lastThreeToSixMonthComplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->where('labour_offices_divisions.zone_id', $office_id)
                ->where('register_complaints.complaint_status', '<>', 'Closed')
                ->whereBetween('register_complaints.created_at', [$last_six_month,$last_three_month])
                ->count();

                // 6 - 12 months
                $lastSixToTwelMonthComplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->where('labour_offices_divisions.zone_id', $office_id)
                ->where('register_complaints.complaint_status', '<>', 'Closed')
                ->whereBetween('register_complaints.created_at', [$last_twel_month,$last_six_month])
                ->count();

                // > 1 year
                $pastYearComplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->where('labour_offices_divisions.zone_id', $office_id)
                ->where('register_complaints.complaint_status', '<>', 'Closed')
                ->where('register_complaints.created_at', '<=', $last_twel_month)
                ->count();


            /****************END PAST COMPLAINTS SUMMERY********************* */

            return view('summery_dashboard', compact('officename','newcomplaintcount','pendingcomplaintcount','ongoingcomplaintcount','tempclosedcomplaintcount','closedcomplaintcount','lastmonpendingcomplaintcount',
                                                    'lastMonth','lastmonongoingcomplaintcount','lastmontempclosedcomplaintcount',
                                                    'lastmonclosedcomplaintcount','jancomplaincount', 'lastyrpendingcomplaintcount',
                                                    'lastyrongoingcomplaintcount', 'lastyrtempclosedcomplaintcount', 'lastyrclosedcomplaintcount',
                                                    'febcomplaincount','marcomplaincount','aprcomplaincount','maycomplaincount',
                                                    'juncomplaincount','julcomplaincount','augcomplaincount','sepcomplaincount',
                                                    'octcomplaincount','novcomplaincount','deccomplaincount','lastyear','lastyearcount',
                                                    'year5','yearcount5','year4','yearcount4','year3','yearcount3','year2','yearcount2',
                                                    'year1','yearcount1', 'labels', 'newcomplaints','closedcomplaints', 'datalist', 'datalist2',
                                                    'legalcomplaintcount', 'chargecomplaintcount', 'recoverycomplaintcount', 'appealcomplaintcount','approvecount','userrole',
                                                    'pastMonthComplaints','lastThreeMonthComplaints','lastThreeToSixMonthComplaints','lastSixToTwelMonthComplaints',
                                                    'pastYearComplaints'));

        }


    }

    public function individualOfficeSummery(Request $request)
    {
        $office_id = Auth::user()->office_id;

        $office = LabourOfficeDivision::where('id', $office_id)->first();

        $userrole = Auth::user()->roles->pluck('name')->first();

        $newcomplaintcount = RegisterComplaint::where('complaint_status', 'New')
                                                ->where('action_type','Pending')
                                                ->where('current_office_id', $request->office_id)
                                                ->count();

        $pendingcomplaintcount = RegisterComplaint::where('action_type', 'Pending')
                                                    ->where('complaint_status', '<>', 'New')
                                                    ->where('current_office_id', $request->office_id)
                                                    ->count();

        $ongoingcomplaintcount = RegisterComplaint::where('action_type', 'Ongoing')
                                                    ->where('current_office_id', $request->office_id)
                                                    ->count();

        $tempclosedcomplaintcount = RegisterComplaint::where('action_type', 'TempClosed')
            ->where('current_office_id', $request->office_id)
            ->count();

        $closedcomplaintcount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $request->office_id)
            ->count();

        $recoverycomplaintcount = RegisterComplaint::where('action_type', 'Pending_recovery')
            ->where('current_office_id', $request->office_id)
            ->count();

        $appealcomplaintcount = RegisterComplaint::where('action_type', 'Waiting')
            ->where('current_office_id', $request->office_id)
            ->count();

        $legalcomplaintcount = RegisterComplaint::where('action_type', 'Pending_legal')
            ->where('current_office_id', $request->office_id)
            ->count();

        $chargecomplaintcount = RegisterComplaint::where('action_type', 'Pending_plaint_charge_sheet')
            ->where('current_office_id', $request->office_id)
            ->count();

        $approvecount = RegisterComplaint::where('action_type', 'Pending_approve')
                    ->where('current_office_id', $request->office_id)
                    ->count();

        $date = \Carbon\Carbon::now();
        $lastMonth =  $date->subMonth()->format('F');

        $lastmonthallcomplaints = RegisterComplaint::where('current_office_id', $request->office_id)
                                                    ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
                                                    ->count();

        $lastmonthpendingcomplaintcount = RegisterComplaint::where('action_type', 'Pending')
                                                        ->where('current_office_id', $request->office_id)
                                                        ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
                                                        ->count();

        if($lastmonthpendingcomplaintcount > 0) {

            $lastmonpendingcomplaintcountper = ($lastmonthpendingcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonpendingcomplaintcount = round($lastmonpendingcomplaintcountper, 1);

        } else {

            $lastmonpendingcomplaintcount = $lastmonthpendingcomplaintcount;

        }

        $lastmonthongoingcomplaintcount = RegisterComplaint::where('action_type', 'Ongoing')
                                                            ->where('current_office_id', $request->office_id)
                                                            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
                                                            ->count();

        if($lastmonthongoingcomplaintcount > 0) {

            $lastmonongoingcomplaintcountper = ($lastmonthongoingcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonongoingcomplaintcount = round($lastmonongoingcomplaintcountper, 1);

        } else {

            $lastmonongoingcomplaintcount = $lastmonthongoingcomplaintcount;

        }

        $lastmonthtempclosedcomplaintcount = RegisterComplaint::where('action_type', 'Tempclosed')
            ->where('current_office_id', $request->office_id)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        if($lastmonthtempclosedcomplaintcount > 0) {

            $lastmontempclosedcomplaintcountper = ($lastmonthtempclosedcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmontempclosedcomplaintcount = round($lastmontempclosedcomplaintcountper, 1);

        } else {

            $lastmontempclosedcomplaintcount = $lastmonthtempclosedcomplaintcount;

        }

        $lastmonthclosedcomplaintcount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $request->office_id)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        if($lastmonthclosedcomplaintcount > 0) {

            $lastmonclosedcomplaintcountper = ($lastmonthclosedcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonclosedcomplaintcount = round($lastmonclosedcomplaintcountper, 1);

        } else {

            $lastmonclosedcomplaintcount = $lastmonthclosedcomplaintcount;

        }

        $lastyearallcomplaints = RegisterComplaint::where('current_office_id', $request->office_id)
                                                    ->whereYear('created_at', date('Y', strtotime('-1 year')))
                                                    ->count();

        $lastyearpendingcomplaintcount = RegisterComplaint::where('action_type', 'Pending')
                                                        ->where('current_office_id', $request->office_id)
                                                        ->whereYear('created_at', date('Y', strtotime('-1 year')))
                                                        ->count();

        if($lastyearpendingcomplaintcount > 0) {

            $lastyrpendingcomplaintcountper = ($lastyearpendingcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrpendingcomplaintcount = round($lastyrpendingcomplaintcountper, 1);

        } else {

            $lastyrpendingcomplaintcount = $lastyearpendingcomplaintcount;

        }

        $lastyearongoingcomplaintcount = RegisterComplaint::where('action_type', 'Ongoing')
                                                        ->where('current_office_id', $request->office_id)
                                                        ->whereYear('created_at', date('Y', strtotime('-1 year')))
                                                        ->count();

        if($lastyearongoingcomplaintcount > 0) {

            $lastyrongoingcomplaintcountper = ($lastyearongoingcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrongoingcomplaintcount = round($lastyrongoingcomplaintcountper, 1);

        } else {

            $lastyrongoingcomplaintcount = $lastyearongoingcomplaintcount;

        }

        $lastyeartempclosedcomplaintcount = RegisterComplaint::where('action_type', 'Tempclosed')
            ->where('current_office_id', $request->office_id)
            ->whereMonth('created_at', date('Y', strtotime('-1 year')))
            ->count();

        if($lastyeartempclosedcomplaintcount > 0) {

            $lastyrtempclosedcomplaintcountper = ($lastyeartempclosedcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrtempclosedcomplaintcount = round($lastyrtempclosedcomplaintcountper, 1);

        } else {

            $lastyrtempclosedcomplaintcount = $lastyeartempclosedcomplaintcount;

        }

        $lastyearclosedcomplaintcount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $request->office_id)
            ->whereMonth('created_at', '=', date('Y', strtotime('-1 year')))
            ->count();

        if($lastyearclosedcomplaintcount > 0) {

            $lastyrclosedcomplaintcountper = ($lastyearclosedcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrclosedcomplaintcount = round($lastyrclosedcomplaintcountper, 1);

        } else {

            $lastyrclosedcomplaintcount = $lastyearclosedcomplaintcount;

        }
        // dd($lastMonth);

        $currentyear = Carbon::now()->format('Y');

        $jancomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '01')->count();
        $febcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '02')->count();
        $marcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '03')->count();
        $aprcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '04')->count();
        $maycomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '05')->count();
        $juncomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '06')->count();
        $julcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '07')->count();
        $augcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '08')->count();
        $sepcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '09')->count();
        $octcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '10')->count();
        $novcomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '11')->count();
        $deccomplaincount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->whereMonth('created_at', '12')->count();

        $lastyear = Carbon::now()->year - 1;
        $lastyearcount = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-1 year')))->count();

        $year5 = Carbon::now()->year - 2;
        $yearcount5 = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-2 year')))->count();

        $year4 = Carbon::now()->year - 3;
        $yearcount4 = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-3 year')))->count();

        $year3 = Carbon::now()->year - 4;
        $yearcount3 = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-4 year')))->count();

        $year2 = Carbon::now()->year - 5;
        $yearcount2 = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-5 year')))->count();

        $year1 = Carbon::now()->year - 6;
        $yearcount1 = RegisterComplaint::where('current_office_id', $request->office_id)->whereYear('created_at', date('Y', strtotime('-6 year')))->count();

        $labels = [];
        $dataByMonth = [];
        $period = now()->subMonths(12)->monthsUntil(now());
        foreach ($period as $date) {
            $labels[] = $date->year . ' ' . $date->monthName;

            $newcomplaints[] = RegisterComplaint::where('current_office_id', $request->office_id)->where('complaint_status', 'New')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();

            $closedcomplaints[] = RegisterComplaint::where('current_office_id', $request->office_id)->where('action_type', 'Closed')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();

        }


        $categorylist = Complain_Category::where('status', 'Y')->get();

        $datalist = [];
        $complaintcatwise = [];
        foreach ($categorylist as $category) {

            $complaintcatwise = RegisterComplaint::where('current_office_id', $request->office_id)->where('complain_category', '=', $category->id)->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

            $datalist[] = ['label'=>$category->category_name_en, 'values'=>array($complaintcatwise)];
        }

        $datalist2 = [];
        $complaintcatwise2 = [];
        foreach ($categorylist as $category2) {
            $complaintcatwise2 = RegisterComplaint::where('current_office_id', $request->office_id)->where('complain_category', '=', $category2->id)->whereYear('created_at', date('Y', strtotime('-1 year')))->count();

            $datalist2[] = ['label'=>$category2->category_name_en, 'values'=>array($complaintcatwise2)];
        }

        /****************PAST COMPLAINTS SUMMERY************************* */

        $last_three_month = Carbon::now()->startOfMonth()->subMonth(3);
        $last_six_month = Carbon::now()->startOfMonth()->subMonth(6);
        $last_twel_month = Carbon::now()->startOfMonth()->subMonth(12);
        $this_month = Carbon::now()->startOfMonth();

        // < 1 month
        $pastMonthComplaints = RegisterComplaint::where('current_office_id', $request->office_id)
        ->where('complaint_status', '<>', 'Closed')
        ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
        ->count();

        // 1 - 3 months
        $lastThreeMonthComplaints = RegisterComplaint::where('current_office_id', $request->office_id)
        ->where('complaint_status', '<>', 'Closed')
        ->whereBetween('created_at', [$last_three_month,$this_month])
        ->count();

        // 3 - 6 months
        $lastThreeToSixMonthComplaints = RegisterComplaint::where('current_office_id', $request->office_id)
        ->where('complaint_status', '<>', 'Closed')
        ->whereBetween('created_at', [$last_six_month,$last_three_month])
        ->count();

        // 6 - 12 months
        $lastSixToTwelMonthComplaints = RegisterComplaint::where('current_office_id', $request->office_id)
        ->where('complaint_status', '<>', 'Closed')
        ->whereBetween('created_at', [$last_twel_month,$last_six_month])
        ->count();

        // > 1 year
        $pastYearComplaints = RegisterComplaint::where('current_office_id', $request->office_id)
        ->where('complaint_status', '<>', 'Closed')
        ->where('created_at', '<=', $last_twel_month)
        ->count();


    /****************END PAST COMPLAINTS SUMMERY********************* */


        return view('office_summery_dashboard', compact('newcomplaintcount','pendingcomplaintcount','ongoingcomplaintcount',
                                        'tempclosedcomplaintcount','closedcomplaintcount', 'lastmonpendingcomplaintcount',
                                        'lastMonth','lastmonongoingcomplaintcount','lastmontempclosedcomplaintcount',
                                        'lastmonclosedcomplaintcount','jancomplaincount', 'lastyrpendingcomplaintcount',
                                        'lastyrongoingcomplaintcount', 'lastyrtempclosedcomplaintcount', 'lastyrclosedcomplaintcount',
                                        'febcomplaincount','marcomplaincount','aprcomplaincount','maycomplaincount',
                                        'juncomplaincount','julcomplaincount','augcomplaincount','sepcomplaincount',
                                        'octcomplaincount','novcomplaincount','deccomplaincount','lastyear','lastyearcount',
                                        'year5','yearcount5','year4','yearcount4','year3','yearcount3','year2','yearcount2',
                                        'year1','yearcount1', 'labels', 'newcomplaints','closedcomplaints', 'office', 'datalist', 'datalist2',
                                        'legalcomplaintcount', 'chargecomplaintcount', 'recoverycomplaintcount', 'appealcomplaintcount','approvecount','userrole',
                                        'pastMonthComplaints','lastThreeMonthComplaints','lastThreeToSixMonthComplaints','lastSixToTwelMonthComplaints',
                                        'pastYearComplaints'));
    }

    public function mainDashboard(Request $request)
    {
        $newcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->where('register_complaints.complaint_status', 'New')
                                                ->where('register_complaints.action_type','Pending')
                                                ->count();

        $pendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Pending')
                                                    ->where('complaint_status', '<>', 'New')
                                                    ->count();

        $ongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Ongoing')
                                                    ->count();

        $tempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'TempClosed')
                                                    ->count();

        $closedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Closed')
                                                    ->count();

        $recoverycomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Pending_recovery')
                                                    ->count();

        $appealcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Waiting')
                                                    ->count();

        $legalcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Pending_legal')
                                                    ->count();

        $chargecomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.action_type', 'Pending_plaint_charge_sheet')
                                                    ->count();

        $approvecount = RegisterComplaint::where('action_type', 'Pending_approve')
                                        ->count();

        $date = \Carbon\Carbon::now();
        $lastMonth =  $date->subMonth()->format('F');

        $lastmonthallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                    ->count();

        $lastmonthpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Pending')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                            ->count();

        if($lastmonthpendingcomplaintcount > 0) {

            $lastmonpendingcomplaintcountper = ($lastmonthpendingcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonpendingcomplaintcount = round($lastmonpendingcomplaintcountper, 1);

        } else {

            $lastmonpendingcomplaintcount = $lastmonthpendingcomplaintcount;

        }

        $lastmonthongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Ongoing')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                            ->count();

        if($lastmonthongoingcomplaintcount > 0) {

            $lastmonongoingcomplaintcountper = ($lastmonthongoingcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonongoingcomplaintcount = round($lastmonongoingcomplaintcountper, 1);

        } else {

            $lastmonongoingcomplaintcount = $lastmonthongoingcomplaintcount;

        }

        $lastmonthtempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Tempclosed')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                            ->count();

        if($lastmonthtempclosedcomplaintcount > 0) {

            $lastmontempclosedcomplaintcountper = ($lastmonthtempclosedcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmontempclosedcomplaintcount = round($lastmontempclosedcomplaintcountper, 1);

        } else {

            $lastmontempclosedcomplaintcount = $lastmonthtempclosedcomplaintcount;

        }

        $lastmonthclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Closed')
                                                        ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                        ->count();

        if($lastmonthclosedcomplaintcount > 0) {

            $lastmonclosedcomplaintcountper = ($lastmonthclosedcomplaintcount/$lastmonthallcomplaints) * 100;

            $lastmonclosedcomplaintcount = round($lastmonclosedcomplaintcountper, 1);

        } else {

            $lastmonclosedcomplaintcount = $lastmonthclosedcomplaintcount;

        }

        $lastyearallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->count();

        $lastyearpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Pending')
                                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                        ->count();

        if($lastyearpendingcomplaintcount > 0) {

            $lastyrpendingcomplaintcountper = ($lastyearpendingcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrpendingcomplaintcount = round($lastyrpendingcomplaintcountper, 1);

        } else {

            $lastyrpendingcomplaintcount = $lastyearpendingcomplaintcount;

        }

        $lastyearongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Ongoing')
                                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                        ->count();

        if($lastyearongoingcomplaintcount > 0) {

            $lastyrongoingcomplaintcountper = ($lastyearongoingcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrongoingcomplaintcount = round($lastyrongoingcomplaintcountper, 1);

        } else {

            $lastyrongoingcomplaintcount = $lastyearongoingcomplaintcount;

        }

        $lastyeartempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Tempclosed')
                                                            ->whereMonth('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                            ->count();

        if($lastyeartempclosedcomplaintcount > 0) {

            $lastyrtempclosedcomplaintcountper = ($lastyeartempclosedcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrtempclosedcomplaintcount = round($lastyrtempclosedcomplaintcountper, 1);

        } else {

            $lastyrtempclosedcomplaintcount = $lastyeartempclosedcomplaintcount;

        }

        $lastyearclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Closed')
                                                        ->whereMonth('register_complaints.created_at', '=', date('Y', strtotime('-1 year')))
                                                        ->count();

        if($lastyearclosedcomplaintcount > 0) {

            $lastyrclosedcomplaintcountper = ($lastyearclosedcomplaintcount/$lastyearallcomplaints) * 100;

            $lastyrclosedcomplaintcount = round($lastyrclosedcomplaintcountper, 1);

        } else {

            $lastyrclosedcomplaintcount = $lastyearclosedcomplaintcount;

        }
        // dd($lastMonth);

        $currentyear = Carbon::now()->format('Y');

        $jancomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '01')
                                                ->count();

        $febcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '02')
                                                ->count();

        $marcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '03')
                                                ->count();

        $aprcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '04')
                                                ->count();

        $maycomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '05')
                                                ->count();

        $juncomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '06')
                                                ->count();

        $julcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '07')
                                                ->count();

        $augcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '08')
                                                ->count();

        $sepcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '09')
                                                ->count();

        $octcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '10')
                                                ->count();

        $novcomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '11')
                                                ->count();

        $deccomplaincount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                ->whereMonth('register_complaints.created_at', '12')
                                                ->count();

        $lastyear = Carbon::now()->year - 1;
        $lastyearcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                            ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                            ->count();

        $year5 = Carbon::now()->year - 2;
        $yearcount5 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-2 year')))
                                        ->count();

        $year4 = Carbon::now()->year - 3;
        $yearcount4 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-3 year')))
                                        ->count();

        $year3 = Carbon::now()->year - 4;
        $yearcount3 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-4 year')))
                                        ->count();

        $year2 = Carbon::now()->year - 5;
        $yearcount2 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-5 year')))
                                        ->count();

        $year1 = Carbon::now()->year - 6;
        $yearcount1 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                        ->whereYear('register_complaints.created_at', date('Y', strtotime('-6 year')))
                                        ->count();

        $labels = [];
        $dataByMonth = [];
        $period = now()->subMonths(12)->monthsUntil(now());
        foreach ($period as $date) {
            $labels[] = $date->year . ' ' . $date->monthName;

            $newcomplaints[] = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->where('register_complaints.complaint_status', 'New')
                                                ->whereYear('register_complaints.created_at', $date->year)
                                                ->whereMonth('register_complaints.created_at', $date->month)
                                                ->count();

            $closedcomplaints[] = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('action_type', 'Closed')
                                                    ->whereYear('register_complaints.created_at', $date->year)
                                                    ->whereMonth('register_complaints.created_at', $date->month)
                                                    ->count();
        }

        $categorylist = Complain_Category::where('status', 'Y')->get();

        $datalist = [];
        $complaintcatwise = [];
        foreach ($categorylist as $category) {

            $complaintcatwise = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                ->where('register_complaints.complain_category', '=', $category->id)
                                                ->whereMonth('register_complaints.created_at', '=', Carbon::now()->subMonth()->month)
                                                ->count();

            $datalist[] = ['label'=>$category->category_name_en, 'values'=>array($complaintcatwise)];
        }

        $datalist2 = [];
        $complaintcatwise2 = [];
        foreach ($categorylist as $category2) {
            $complaintcatwise2 = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->where('register_complaints.complain_category', '=', $category2->id)
                                                    ->whereYear('register_complaints.created_at', date('Y', strtotime('-1 year')))
                                                    ->count();

            $datalist2[] = ['label'=>$category2->category_name_en, 'values'=>array($complaintcatwise2)];
        }


        $thisMonth =  $date->format('F');

        $thismonthallcomplaints = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                    ->whereMonth('register_complaints.created_at', '=', Carbon::now()->month)
                                                    ->count();

        $thismonthpendingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Pending')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->month)
                                                            ->count();

        if($thismonthpendingcomplaintcount > 0) {

            $thismonpendingcomplaintcountper = ($thismonthpendingcomplaintcount/$thismonthallcomplaints) * 100;

            $thismonpendingcomplaintcount = round($thismonpendingcomplaintcountper, 1);

        } else {

            $thismonpendingcomplaintcount = $thismonthpendingcomplaintcount;

        }

        $thismonthongoingcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Ongoing')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->month)
                                                            ->count();

        if($thismonthongoingcomplaintcount > 0) {

            $thismonongoingcomplaintcountper = ($thismonthongoingcomplaintcount/$thismonthallcomplaints) * 100;

            $thismonongoingcomplaintcount = round($thismonongoingcomplaintcountper, 1);

        } else {

            $thismonongoingcomplaintcount = $thismonthongoingcomplaintcount;

        }

        $thismonthtempclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                            ->where('register_complaints.action_type', 'Tempclosed')
                                                            ->whereMonth('register_complaints.created_at', '=', Carbon::now()->month)
                                                            ->count();

        if($thismonthtempclosedcomplaintcount > 0) {

            $thismontempclosedcomplaintcountper = ($thismonthtempclosedcomplaintcount/$thismonthallcomplaints) * 100;

            $thismontempclosedcomplaintcount = round($thismontempclosedcomplaintcountper, 1);

        } else {

            $thismontempclosedcomplaintcount = $thismonthtempclosedcomplaintcount;

        }

        $thismonthclosedcomplaintcount = RegisterComplaint::join('labour_offices_divisions','labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                                                        ->where('register_complaints.action_type', 'Closed')
                                                        ->whereMonth('register_complaints.created_at', '=', Carbon::now()->month)
                                                        ->count();

        if($thismonthclosedcomplaintcount > 0) {

            $thismonclosedcomplaintcountper = ($thismonthclosedcomplaintcount/$thismonthallcomplaints) * 100;

            $thismonclosedcomplaintcount = round($thismonclosedcomplaintcountper, 1);

        } else {

            $thismonclosedcomplaintcount = $thismonthclosedcomplaintcount;

        }

        /****************PAST COMPLAINTS SUMMERY************************* */

        $last_three_month = Carbon::now()->startOfMonth()->subMonth(3);
        $last_six_month = Carbon::now()->startOfMonth()->subMonth(6);
        $last_twel_month = Carbon::now()->startOfMonth()->subMonth(12);
        $this_month = Carbon::now()->startOfMonth();

        // < 1 month
        $pastMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')
        ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
        ->count();

        // 1 - 3 months
        $lastThreeMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')
        ->whereBetween('created_at', [$last_three_month,$this_month])
        ->count();

        // 3 - 6 months
        $lastThreeToSixMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')
        ->whereBetween('created_at', [$last_six_month,$last_three_month])
        ->count();

        // 6 - 12 months
        $lastSixToTwelMonthComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')
        ->whereBetween('created_at', [$last_twel_month,$last_six_month])
        ->count();

        // > 1 year
        $pastYearComplaints = RegisterComplaint::where('complaint_status', '<>', 'Closed')
        ->where('created_at', '<=', $last_twel_month)
        ->count();


        /****************END PAST COMPLAINTS SUMMERY********************* */

        return view('main-dashboard', compact('newcomplaintcount','pendingcomplaintcount','ongoingcomplaintcount','tempclosedcomplaintcount','closedcomplaintcount','lastmonpendingcomplaintcount',
                                                'lastMonth','lastmonongoingcomplaintcount','lastmontempclosedcomplaintcount',
                                                'lastmonclosedcomplaintcount','jancomplaincount', 'lastyrpendingcomplaintcount',
                                                'lastyrongoingcomplaintcount', 'lastyrtempclosedcomplaintcount', 'lastyrclosedcomplaintcount',
                                                'febcomplaincount','marcomplaincount','aprcomplaincount','maycomplaincount',
                                                'juncomplaincount','julcomplaincount','augcomplaincount','sepcomplaincount',
                                                'octcomplaincount','novcomplaincount','deccomplaincount','lastyear','lastyearcount',
                                                'year5','yearcount5','year4','yearcount4','year3','yearcount3','year2','yearcount2',
                                                'year1','yearcount1', 'labels', 'newcomplaints','closedcomplaints', 'datalist', 'datalist2',
                                                'legalcomplaintcount', 'chargecomplaintcount', 'recoverycomplaintcount', 'appealcomplaintcount','approvecount',
                                                'thisMonth','thismonongoingcomplaintcount','thismontempclosedcomplaintcount','thismonpendingcomplaintcount',
                                                'thismonclosedcomplaintcount', 'pastMonthComplaints','lastThreeMonthComplaints','lastThreeToSixMonthComplaints','lastSixToTwelMonthComplaints',
                                                'pastYearComplaints'));
    }

}
