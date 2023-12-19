<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Province;
use DataTables;

class CityController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:city-list|city-create|city-edit|city-delete', ['only' => ['list']]);
        $this->middleware('permission:city-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:city-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:city-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $provinces = Province::where('status','Y')
                            ->where('is_delete', 0)
                            ->orderBy('province_name_en', 'ASC')
                            ->get();

        $districts = District::where('status','Y')
                            ->where('is_delete', 0)
                            ->orderBy('district_name_en', 'ASC')
                            ->get();
        return view('adminpanel.masterdata.city.index', compact('provinces','districts'));
    }

    public function getDistrict(Request $request)
    {
        $districts = District::where("province_id", $request->province_id)
                            ->where('status','Y')
                            ->where('is_delete', 0)
                            ->pluck("district_name_en", "id");
        return response()->json($districts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_name_en' => 'required',
            'province_id' => 'required',
            'district_id' => 'required'
        ]);

        // die(var_dump($request));

        $id = City::create($request->all())->id;

        \LogActivity::addToLog('New city '.$request->city_name_en.' added('.$id.').');

        return redirect()->route('city')
            ->with('success', 'City created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = City::join('provinces','provinces.id','=','cities.province_id')
                            ->join('districts','districts.id','=','cities.district_id')
                            ->select(array('cities.id','cities.city_name_en','cities.city_name_sin','cities.city_name_tam','provinces.province_name_en','districts.district_name_en','cities.status'))
                            ->where('cities.is_delete',0);
                            // ->get();
            // $data = City::where('cities.is_delete',0)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-city/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-city/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                // ->addColumn('blockcity', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     //$btn = '<a href="blockcity/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';
                //     $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';
                //     return $btn;
                // })
                ->addColumn('blockcity', 'adminpanel.masterdata.city.actionsBlock')
                ->rawColumns(['edit', 'activation','blockcity'])
                ->filterColumn('province_name_en', function($query, $keyword) {
                    $query->whereRaw('LOWER(provinces.province_name_en) LIKE ?', ["%{$keyword}%"]);
                })
                ->filterColumn('district_name_en', function($query, $keyword) {
                    $query->whereRaw('LOWER(districts.district_name_en) LIKE ?', ["%{$keyword}%"]);
                })
                ->make(true);
        }
        return view('adminpanel.masterdata.city.list');
    }

    public function edit($id)
    {
        $CityID = decrypt($id);
        $data = City::find($CityID);
        $provinces = Province::where('status', 'Y')->where('is_delete', 0)->orderBy('province_name_en', 'ASC')->get();
        $distrcts = District::where('status', 'Y')->where('is_delete', 0)->orderBy('district_name_en', 'ASC')->get();
        return view('adminpanel.masterdata.city.edit', ['data' => $data, 'provinces' => $provinces, 'districts' => $distrcts]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'city_name_en' => 'required'
        ]);

        $data =  City::find($request->id);
        $data->city_name_en = $request->city_name_en;
        $data->city_name_sin = $request->city_name_sin;
        $data->city_name_tam = $request->city_name_tam;
        $data->province_id = $request->province_id;
        $data->district_id = $request->district_id;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('City record '.$data->city_name_en.' updated('.$id.').');

        return redirect()->route('city-list')
            ->with('success', 'City updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  City::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('City record '.$data->city_name_en.' deactivated('.$id.').');

            return redirect()->route('city-list')
            ->with('success', 'City deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('City record '.$data->city_name_en.' activated('.$id.').');

            return redirect()->route('city-list')
            ->with('success', 'City activate successfully.');
        }
    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  City::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('City record '.$data->city_name_en.' deleted('.$id.').');

        return redirect()->route('city-list')
            ->with('success', 'City deleted successfully.');
    }

    public function checkDuplicate(Request $request)
    {
        $cities = City::where("province_id", $request->province_id)
                        ->where("district_id", $request->district_id)
                        ->where('city_name_en', $request->cityName)
                        ->get();

                        // dd($cities);die();

        return response()->json($cities);
    }

}
