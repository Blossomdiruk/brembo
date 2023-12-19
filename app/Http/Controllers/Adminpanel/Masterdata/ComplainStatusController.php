<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintStatus;
use DataTables;
use App\Models\ComplaintStatusType;

class ComplainStatusController extends Controller
{
    function __construct()
    {

        //$this->middleware('permission:complaint-status-list|complaint-status-create|complaint-status-edit|complaint-status-delete', ['only' => ['list']]);
        //$this->middleware('permission:complaint-status-create', ['only' => ['index', 'store']]);
       // $this->middleware('permission:complaint-status-edit', ['only' => ['edit', 'update','activation']]);
        //$this->middleware('permission:complaint-status-delete', ['only' => ['block']]);

        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['list']]);
        $this->middleware('permission:category-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update','activation']]);

    }

    public function index()
    {
        $statustypes = ComplaintStatusType::where('status', 'Y')->where('is_delete', '0')->get();

        return view('adminpanel.masterdata.complain_status.index', ['statustypes' => $statustypes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_en' => 'required',
            'complaint_status_type_id' => 'required'
        ]);

        $id = ComplaintStatus::create($request->all())->id;

        \LogActivity::addToLog('New complaint status '.$request->status_en.' added('.$id.').');

        return redirect()->route('complain-status')
            ->with('success', 'Record created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = ComplaintStatus::where('is_delete',0)->orderBy('status_en', 'ASC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-complain-status/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-complain-status/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                ->addColumn('blockcomplainstatus', function($row){
                    if ( $row->status == "1" )
                        $dltstatus ='fa fa-ban';
                    else
                        $dltstatus ='fa fa-trash';

                    $btn = '<a href="blockcomplainstatus/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';

                    return $btn;
                })
                ->rawColumns(['edit', 'activation', 'blockcomplainstatus'])
                ->make(true);
        }

        return view('adminpanel.masterdata.complain_status.list');
    }

    public function edit($id)
    {
        $statusID = decrypt($id);
        $data = ComplaintStatus::find($statusID);

        $statustypes = ComplaintStatusType::where('status', 'Y')->where('is_delete', '0')->orderBy('type_name_en', 'ASC')->get();

        return view('adminpanel.masterdata.complain_status.edit', ['data' => $data, 'statustypes' => $statustypes]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'status_en' => 'required',
            'complaint_status_type_id' => 'required'
        ]);

        $data =  ComplaintStatus::find($request->id);
        $data->status_en = $request->status_en;
        $data->status_si = $request->status_si;
        $data->status_ta = $request->status_ta;
        $data->status = $request->status;
        $data->complaint_status_type_id = $request->complaint_status_type_id;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Complaint status record '.$data->status_en.' updated('.$id.').');

        return redirect()->route('complain-status-list')
            ->with('success', 'Record updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  ComplaintStatus::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Complaint status record '.$data->status_en.' deactivated('.$id.').');

            return redirect()->route('complain-status-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Complaint Status record '.$data->status_en.' activated('.$id.').');

            return redirect()->route('complain-status-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  ComplaintStatus::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Complaint status record '.$data->status_en.' deleted('.$id.').');

        return redirect()->route('complain-status-list')
            ->with('success', 'Record deleted successfully.');
    }

}
