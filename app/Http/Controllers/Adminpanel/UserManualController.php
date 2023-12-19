<?php

namespace App\Http\Controllers\Adminpanel;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Complain_Category;
use App\Models\LabourOfficeDivision;
use Illuminate\Http\Request;
use DB;

class UserManualController extends Controller
{
    public function index()
    {
        $role = Auth::user()->roles->first();

        $path = storage_path('app/'.$role->user_manual);

        return response()->file($path);

    }

    public function officeCodeList()
    {
        $divisionofficecodes = LabourOfficeDivision::where('status', 'Y')->where('is_delete', 0)->whereIn('office_type_id', [1, 2])->orderBy('office_code', 'ASC')->get();

        $zonalofficecodes = LabourOfficeDivision::where('status', 'Y')->where('is_delete', 0)->where('office_type_id', 3)->orderBy('office_code', 'ASC')->get();

        $districtofficecodes = LabourOfficeDivision::where('status', 'Y')->where('is_delete', 0)->where('office_type_id', 4)->orderBy('office_code', 'ASC')->get();

        $subofficecodes = LabourOfficeDivision::where('status', 'Y')->where('is_delete', 0)->where('office_type_id', 5)->orderBy('office_code', 'ASC')->get();

        return view('adminpanel.info.office_code_list', compact('divisionofficecodes', 'zonalofficecodes', 'districtofficecodes', 'subofficecodes'));
    }

    public function categoryCodeList()
    {
        $categorycodedetails = Complain_Category::where('status', 'Y')->orderBy('category_prefix', 'ASC')->get();

        return view('adminpanel.info.category_code_list', compact('categorycodedetails'));
    }

    public function accountRequestFormList()
    {
        $path = storage_path('app/public/forms/user_account_request_form_lo.docx');

        return response()->file($path);
    }

    public function cmscircular()
    {
        $path = storage_path('app/public/forms/cms_circular.pdf');

        return response()->file($path);
    }

}
