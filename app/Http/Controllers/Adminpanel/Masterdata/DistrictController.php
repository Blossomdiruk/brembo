<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Province;
use DataTables;

class DistrictController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:district-list|district-create|district-edit|district-delete', ['only' => ['list']]);
        $this->middleware('permission:district-create', ['only' => ['store, index']]);
        $this->middleware('permission:district-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:district-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $provinces = Province::where('status','Y')
                            ->where('is_delete','0')
                            ->get();
        return view('adminpanel.masterdata.district.index', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district_name_en' => 'required',
            'province_id' => 'required'
        ]);

        // die(var_dump($request));

        $id = District::create($request->all())->id;

        \LogActivity::addToLog('New district '.$request->district_name_en.' added('.$id.').');

        return redirect()->route('district')
            ->with('success', 'District created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = District::join('provinces','provinces.id','=','districts.province_id')
                            ->select(array('districts.id','provinces.province_name_en','provinces.province_name_sin','provinces.province_name_tamil','districts.district_name_en','districts.district_name_sin','districts.district_name_tamil', 'districts.status'))
                            ->where('districts.is_delete',0)
                            ->orderBy('districts.district_name_en', 'ASC')
                            ->get();
            // die(var_dump($data));
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-district/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                // ->addColumn('block', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     //$btn = '<a href="block/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';
                //     $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';
                //     return $btn;
                // })
                ->addColumn('block', 'adminpanel.masterdata.district.actionsBlock')
                ->rawColumns(['edit', 'activation','block'])
                ->make(true);
        }

        return view('adminpanel.masterdata.district.list');
    }

    public function edit($id)
    {
        $districtID = decrypt($id);
        $data = District::find($districtID);
        $provinces = Province::orderBy('province_name_en', 'ASC')->get();
        // die(var_dump($districtID));

        // dd($provinces);
        return view('adminpanel.masterdata.district.edit', ['data' => $data,'provinces' => $provinces]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'district_name_en' => 'required'
        ]);

        $data =  District::find($request->id);
        $data->district_name_en = $request->district_name_en;
        $data->district_name_sin = $request->district_name_sin;
        $data->district_name_tamil = $request->district_name_tamil;
        $data->province_id = $request->province_id;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('District record '.$data->district_name_en.' updated('.$id.').');

        return redirect()->route('district-list')
            ->with('success', 'District updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  District::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('District record '.$data->district_name_en.' deactivated('.$id.').');

            return redirect()->route('district-list')
            ->with('success', 'District deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('District record '.$data->district_name_en.' activated('.$id.').');

            return redirect()->route('district-list')
            ->with('success', 'District activate successfully.');
        }
    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  District::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('District record '.$data->district_name_en.' deleted('.$id.').');

        return redirect()->route('district-list')
            ->with('success', 'District deleted successfully.');
    }
}
