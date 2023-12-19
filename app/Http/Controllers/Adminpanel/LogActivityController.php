<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogActivity;
use App\Models\User;
use App\Models\LabourOfficeDivision;
use Spatie\Permission\Models\Role;
use DataTables;


class LogActivityController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:log-activity-list', ['only' => ['index', 'list']]);

    }

    public function index()
    {
        return view('adminpanel.logs.list');
    }

    public function list(Request $request)
    {

        $users = User::where('status', 'Y')->where('is_delete',0)->get();

        // dd($users);

        $labouroffices = LabourOfficeDivision::get();

        $roles = Role::select('id', 'name')->get();

        //dd($request->ajax());
    //     if ($request->ajax()) {

    //         $data = LogActivity::where('is_delete',0);
    //         //dd($data);
    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('blocklog', function($row){
    //                 if ( $row->status == "1" )
    //                     $dltstatus ='fa fa-ban';
    //                 else
    //                     $dltstatus ='fa fa-trash';

    //                 $btn = '<a href="blocklog/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';

    //                 return $btn;
    //             })
    //             ->rawColumns(['blocklog'])
    //             ->make(true);
    //    }

        if (request()->ajax()) {
            $query = LogActivity::leftJoin('users','users.id', '=', 'log_activities.user_id')
                                ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                                ->select('log_activities.id', 'log_activities.subject', 'log_activities.url', 'log_activities.method', 'log_activities.ip', 'log_activities.created_at', 'users.name', 'labour_offices_divisions.office_name_en');

            if (!empty($request->user_id)) {

                $query->where('log_activities.user_id', '=', $request->user_id);

            } if (!empty($request->office_id)) {

                // \DB::enableQueryLog();

                $query->where('labour_offices_divisions.id', '=', $request->office_id);

                        //  $query = \DB::getQueryLog();
                        // print_r($query);
                        // exit();
            }
            // } if(!empty($request->role_id)) {
            //     $query ->where('roles.name', '=', $request->role_id);
            // }


            $data = $query->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                })
                ->editColumn('time', function ($request) {
                    return $request->created_at->format('H:i:s'); // human readable format
                })
                // ->addColumn('blocklog', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';
                //     $btn = '<a href="blocklog/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';
                //     return $btn;
                // })
                ->rawColumns(['created_at','time','blocklog'])
                ->make(true);
        }

        return view('adminpanel.logs.list', compact('users','labouroffices','roles'));
    }

    public function searchLog(Request $request)
    {

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  LogActivity::find($request->id);
        $data->is_delete = 1;
        $data->save();

       // \LogActivity::addToLog('Log activity record id '.$request->id.' deleted.');

        return redirect()->route('log-activity-list')
            ->with('success', 'Record deleted successfully.');
    }
}
