<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsLog;
use DataTables;


class SmsLogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sms-log-list', ['only' => ['index', 'list']]);

    }

    public function index()
    {
        return view('adminpanel.logs.smslist');
    }

    public function list(Request $request)
    {
        //dd($request->ajax());
        if ($request->ajax()) {

            $data = SmsLog::leftJoin('users','users.id', '=', 'sms_logs.user_id')
            ->select(array('sms_logs.id', 'sms_logs.name as name', 'sms_logs.subject', 'sms_logs.url', 'sms_logs.mobile', 'sms_logs.method', 'sms_logs.ip', 'sms_logs.created_at', 'users.name as username'))
            ->where('sms_logs.is_delete',0);
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d'); // human readable format
                })
                ->addColumn('time', function ($row) {
                    return $row->created_at->format('H:i:s'); // human readable format
                })
                // ->addColumn('blocklog', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     $btn = '<a href="sms-blocklog/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';

                //     return $btn;
                // })
                ->filterColumn('username', function ($query, $keyword) {
                    $query->whereRaw('LOWER(users.name) LIKE ?', ["%{$keyword}%"]);
                })
                ->rawColumns(['time'])
                ->make(true);
       }

        return view('adminpanel.logs.smslist');
    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  SmsLog::find($request->id);
        $data->is_delete = 1;
        $data->save();

        return redirect()->route('sms-log-list')
            ->with('success', 'Record deleted successfully.');
    }
}
