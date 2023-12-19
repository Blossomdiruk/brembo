<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintRemark;
use DataTables;

class ComplaintRemarkController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:complaint-remark-list|complaint-remark-create|complaint-remark-edit|complaint-remark-delete', ['only' => ['list']]);
        $this->middleware('permission:complaint-remark-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:complaint-remark-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:complaint-remark-delete', ['only' => ['block']]);

    }

    public function index()
    {
        return view('adminpanel.masterdata.complaint_remark.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'remark_en' => 'required'
        ]);

        $id = ComplaintRemark::create($request->all())->id;

        \LogActivity::addToLog('New complaint remark '.$request->remark_en.' added('.$id.').');

        return redirect()->route('complaint-remark')
            ->with('success', 'Record created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = ComplaintRemark::where('is_delete',0);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-complaint-remark/' . encrypt($row->id) . '');
                    if($row->id == 2){
                        $btn = '';
                    } else {
                        $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    }
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    if($row->id == 2){
                        $btn = '';
                    } else {
                        $btn = '<a href="changestatus-complaint-remark/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';
                    }
                    return $btn;
                })
                ->addColumn('blockcomplaintremark', function($row){
                    if ( $row->status == "1" )
                        $dltstatus ='fa fa-ban';
                    else
                        $dltstatus ='fa fa-trash';

                    if($row->id == 2){
                        $btn = '';
                    } else {
                        $btn = '<a href="blockcomplaintremark/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['edit', 'activation', 'blockcomplaintremark'])
                ->make(true);
        }

        return view('adminpanel.masterdata.complaint_remark.list');
    }

    public function edit($id)
    {
        $statusID = decrypt($id);
        $data = ComplaintRemark::find($statusID);

        return view('adminpanel.masterdata.complaint_remark.edit', ['data' => $data]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'remark_en' => 'required'
        ]);

        $data =  ComplaintRemark::find($request->id);
        $data->remark_en = $request->remark_en;
        $data->remark_si = $request->remark_si;
        $data->remark_ta = $request->remark_ta;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Complaint remark record '.$data->remark_en.' updated('.$id.').');

        return redirect()->route('complaint-remark-list')
            ->with('success', 'Record updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  ComplaintRemark::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Complaint remark record '.$data->remark_en.' deactivated('.$id.').');

            return redirect()->route('complaint-remark-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Complaint remark record '.$data->remark_en.' activated('.$id.').');

            return redirect()->route('complaint-remark-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  ComplaintRemark::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Complaint remark record '.$data->remark_en.' deleted('.$id.').');

        return redirect()->route('complaint-remark-list')
            ->with('success', 'Record deleted successfully.');
    }

}
