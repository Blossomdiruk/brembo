<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessNature;
use DataTables;

class BusinessNatureController extends Controller
{
    function __construct()
    {

       // $this->middleware('permission:business-nature-list|business-nature-create|business-nature-edit|business-nature-delete', ['only' => ['list']]);
       // $this->middleware('permission:business-nature-create', ['only' => ['index', 'store']]);
      //  $this->middleware('permission:business-nature-edit', ['only' => ['edit', 'update']]);
     //  $this->middleware('permission:category-delete', ['only' => ['destroy']]);

     $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['list']]);
     $this->middleware('permission:category-create', ['only' => ['index', 'store']]);
     $this->middleware('permission:category-edit', ['only' => ['edit', 'update','activation']]);

    }

    public function index()
    {
        return view('adminpanel.masterdata.business_nature.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_nature_en' => 'required'
        ]);

        $id = BusinessNature::create($request->all())->id;

        \LogActivity::addToLog('New Business nature '.$request->business_nature_en.' added('.$id.').');

        return redirect()->route('business-nature')
            ->with('success', 'Record created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = BusinessNature::where('is_delete',0)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.masterdata.business_nature.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.business_nature.actionsStatus')
                ->addColumn('blockbusinessnature', 'adminpanel.masterdata.business_nature.actionsBlock')
                ->rawColumns(['edit', 'activation','blockbusinessnature'])
                ->make(true);
                // ->addColumn('edit', function ($row) {
                //     $edit_url = url('/edit-business-nature/' . encrypt($row->id) . '');
                //     $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                //     return $btn;
                // })
                // ->addColumn('activation', function($row){
                //     if ( $row->status == "Y" )
                //         $status ='fa fa-check';
                //     else
                //         $status ='fa fa-remove';

                //     $btn = '<a href="changestatus-business-nature/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                //     return $btn;
                // })
                // ->addColumn('blockbusinessnature', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';


                //     return $btn;
                // })
                // ->rawColumns(['edit', 'activation', 'blockbusinessnature'])
                // ->make(true);
        }

        return view('adminpanel.masterdata.business_nature.list');
    }

    public function edit($id)
    {
        $businessNatureID = decrypt($id);
        $data = BusinessNature::find($businessNatureID);
        return view('adminpanel.masterdata.business_nature.edit', ['data' => $data]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_nature_en' => 'required'
        ]);

        $data =  BusinessNature::find($request->id);
        $data->business_nature_en = $request->business_nature_en;
        $data->business_nature_si = $request->business_nature_si;
        $data->business_nature_ta = $request->business_nature_ta;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Business nature record '.$data->business_nature_en.' updated('.$id.').');

        return redirect()->route('business-nature-list')
            ->with('success', 'Record updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $id = decrypt($request->id);

        $data =  BusinessNature::find($id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Business nature record '.$data->business_nature_en.' deactivated('.$id.').');

            return redirect()->route('business-nature-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Business nature record '.$data->business_nature_en.' activated('.$id.').');

            return redirect()->route('business-nature-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  BusinessNature::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Business nature record '.$data->business_nature_en.' deleted('.$id.').');

        return redirect()->route('business-nature-list')
            ->with('success', 'Record deleted successfully.');
    }
}
