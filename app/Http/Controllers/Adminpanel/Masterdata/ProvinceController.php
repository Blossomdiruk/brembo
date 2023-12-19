<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use DataTables;

class ProvinceController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:province-list|province-create|province-edit|province-delete', ['only' => ['list']]);
        $this->middleware('permission:province-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:province-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:province-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('adminpanel.masterdata.province.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_name_en' => 'required'
        ]);

        $id = Province::create($request->all())->id;

        \LogActivity::addToLog('New province '.$request->province_name_en.' added('.$id.').');

        return redirect()->route('province')
            ->with('success', 'Record created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Province::select('*')->where('is_delete',0);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-province/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-province/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                // ->addColumn('blockprovince', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';


                //     return $btn;
                // })
                ->addColumn('blockprovince', 'adminpanel.masterdata.province.actionsBlock')
                ->rawColumns(['edit', 'activation', 'blockprovince'])
                ->make(true);
        }

        return view('adminpanel.masterdata.province.list');
    }

    public function edit($id)
    {
        $provinceID = decrypt($id);
        $data = Province::find($provinceID);
        return view('adminpanel.masterdata.province.edit', ['data' => $data]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'province_name_en' => 'required'
        ]);

        $data =  Province::find($request->id);
        $data->province_name_en = $request->province_name_en;
        $data->province_name_sin = $request->province_name_sin;
        $data->province_name_tamil = $request->province_name_tamil;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Province record '.$data->province_name_en.' updated('.$id.').');

        return redirect()->route('province-list')
            ->with('success', 'Record updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  Province::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Province record '.$data->province_name_en.' deactivated('.$id.').');

            return redirect()->route('province-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Province record '.$data->province_name_en.' activated('.$id.').');

            return redirect()->route('province-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  Province::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Province record '.$data->province_name_en.' deleted('.$id.').');

        return redirect()->route('province-list')
            ->with('success', 'Record deleted successfully.');
    }
}
