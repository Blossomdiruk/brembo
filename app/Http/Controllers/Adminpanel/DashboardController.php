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
        
       
                                                       
        return view('dashboard');
    }

  

}
