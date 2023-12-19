<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailLog;
use DataTables;


class EmailLogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:email-log-list', ['only' => ['index', 'list']]);

    }

    public function index()
    {
        return view('adminpanel.logs.emaillist');
    }

    public function list(Request $request)
    {
        //dd($request->ajax());
        if ($request->ajax()) {

            $data = EmailLog::leftJoin('users','users.id', '=', 'email_logs.user_id')
            ->select(array('email_logs.id', 'email_logs.name as name', 'email_logs.subject', 'email_logs.body', 'email_logs.url', 'email_logs.email', 'email_logs.method', 'email_logs.ip', 'email_logs.created_at', 'users.name as username'))
            ->where('email_logs.is_delete',0);
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

        return view('adminpanel.logs.emaillist');
    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  EmailLog::find($request->id);
        $data->is_delete = 1;
        $data->save();

        return redirect()->route('email-log-list')
            ->with('success', 'Record deleted successfully.');
    }
}
