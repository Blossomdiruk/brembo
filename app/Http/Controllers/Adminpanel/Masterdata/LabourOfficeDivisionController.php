<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabourOfficeDivision;
use App\Models\Province;
use App\Models\District;
use App\Models\City;
use App\Models\LabourOfficeCityDetail;
use App\Models\OfficeType;
use Illuminate\Support\Facades\Auth;
use DataTables;

class LabourOfficeDivisionController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:labour-office-create|labour-office-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:labour-office-list|labour-office-edit|labour-office-delete|labour-office-add-city', ['only' => ['list']]);
        $this->middleware('permission:labour-office-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:labour-office-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:labour-office-add-city', ['only' => ['addCity', 'saveCities']]);
    }

    public function index()
    {
        $provinces = Province::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('province_name_en', 'ASC')
            ->get();

        $officetypes = OfficeType::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('office_type_name_en', 'ASC')
            ->get();

        $zone = LabourOfficeDivision::where('status', 'Y')
            ->where('is_delete', '0')
            ->where('office_type_id', '3')
            ->orderBy('office_name_en', 'ASC')
            ->get();

        $districtOffice = LabourOfficeDivision::where('status', 'Y')
            ->where('is_delete', '0')
            ->where('office_type_id', '4')
            ->orderBy('office_name_en', 'ASC')
            ->get();

        return view('adminpanel.masterdata.labour_office_division.index', compact('provinces', 'officetypes', 'zone', 'districtOffice'));
    }

    public function getDistrict(Request $request)
    {
        $districts = District::where('province_id', $request->province_id)
                            ->where('status','Y')
                            ->where('is_delete','0')
                            ->orderBy('district_name_en', 'ASC')->get();
        return response()->json($districts);
    }

    public function getCity(Request $request)
    {
        $cities = City::where('district_id', $request->district_id)
                        ->where('status','Y')
                        ->where('is_delete','0')
                        ->orderBy('city_name_en', 'ASC')
                        ->get();
        return response()->json($cities);
    }

    public function getCityforOffice(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $cities = LabourOfficeCityDetail::Join('cities', 'cities.id', '=', 'labour_office_city_details.city_id')
                        ->where('cities.status','Y')
                        ->where('cities.district_id',$request->district_id)
                        ->where('labour_office_city_details.office_id',$office_id)
                        ->where('cities.is_delete','0')
                        ->orderBy('cities.city_name_en', 'ASC')
                        ->get();
        return response()->json($cities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'office_type_id' => 'required',
            'office_name_en' => 'required',
            'office_name_sin' => 'required',
            'office_name_tam' => 'required',
            'address' => 'required',
            'address_sin' => 'required',
            'address_tam' => 'required',
            'office_code' => 'required',
            'tel' => 'required',
            'letter_head' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        if (!$request->file('letter_head') == "") {

            $letterhead = $request->file('letter_head')->getClientOriginalName();

            $path = $request->file('letter_head')->store('public/letterhead');
        } else {
            $path = "";
        }

        $office = new LabourOfficeDivision;

        $office->office_name_en = $request->office_name_en;
        $office->office_name_sin = $request->office_name_sin;
        $office->office_name_tam = $request->office_name_tam;
        $office->address = $request->address;
        $office->address_sin = $request->address_sin;
        $office->address_tam = $request->address_tam;
        $office->tel = $request->tel;
        $office->fax = $request->fax;
        $office->email = $request->email;
        $office->letter_head = $path;
        $office->status = $request->status;
        $office->province_id = $request->province_id;
        $office->district_id = $request->district_id;
        $office->city_id = $request->city_id;
        $office->office_type_id = $request->office_type_id;
        $office->sub_district_id = $request->sub_district_id;
        $office->zone_id = $request->zone_id;
        $office->office_code = $request->office_code;
        $office->save();
        $id = $office->id;

        // $officeCity = new LabourOfficeCityDetail();

        // $officeCity->office_id = $office->id;
        // $officeCity->office_code = $request->office_code;
        // $officeCity->city_id = $request->city_id;
        // $officeCity->save();

        \LogActivity::addToLog('New labour office division '.$request->office_name_en.' added('.$id.').');

        return redirect()->route('labour-office-division')->with('success', 'Labour Office created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = LabourOfficeDivision::leftJoin('provinces', 'provinces.id', '=', 'labour_offices_divisions.province_id')
                ->leftJoin('districts', 'districts.id', '=', 'labour_offices_divisions.district_id')
                ->leftJoin('cities', 'cities.id', '=', 'labour_offices_divisions.city_id')
                ->join('office_types', 'office_types.id', '=', 'labour_offices_divisions.office_type_id')
                ->select(array('labour_offices_divisions.id', 'labour_offices_divisions.office_name_en', 'labour_offices_divisions.office_name_sin', 'labour_offices_divisions.office_name_tam', 'labour_offices_divisions.address', 'labour_offices_divisions.tel', 'office_types.office_type_name_en', 'provinces.province_name_en', 'districts.district_name_en', 'labour_offices_divisions.status', 'labour_offices_divisions.office_code'))
                ->where('labour_offices_divisions.is_delete', 0);
            // ->get();
            // die(var_dump($data));
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('citydetail', function ($row) {
                    // $city = 'office_code';
                    $btn = '<button class="citydet" value="'.$row->id.'"><i class="fa fa-list-alt"></i></button>';
                    return $btn;
                })
                ->addColumn('addcity', function ($row) {
                    $city_url = url('/add-city/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $city_url . '"><i class="fa fa-plus-square"></i></a>';
                    return $btn;
                })
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-labour-office-division/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function ($row) {
                    if ($row->status == "Y") {
                        $status = 'fa fa-check';
                    } else {
                        $status = 'fa fa-remove';
                    }

                    $btn = '<a href="changestatus-labour-office-division/' . $row->id . '/' . $row->cEnable . '"><i class="' . $status . '"></i></a>';

                    return $btn;
                })
                // ->addColumn('blocklabourofficedivision', function ($row) {
                //     if ($row->status == "1")
                //         $dltstatus = 'fa fa-ban';
                //     else
                //         $dltstatus = 'fa fa-trash';

                //     //$btn = '<a href="blocklabourofficedivision/' . $row->id . '/' . $row->cEnable . '"><i class="' . $dltstatus . '"></i></a>';
                //     $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';
                //     return $btn;
                // })
                ->addColumn('blocklabourofficedivision', 'adminpanel.masterdata.labour_office_division.actionsBlock')
                ->filterColumn('office_type_name_en', function ($query, $keyword) {
                    $query->whereRaw('LOWER(office_types.office_type_name_en) LIKE ?', ["%{$keyword}%"]);
                })
                ->filterColumn('province_name_en', function ($query, $keyword) {
                    $query->whereRaw('LOWER(provinces.province_name_en) LIKE ?', ["%{$keyword}%"]);
                })
                ->filterColumn('district_name_en', function ($query, $keyword) {
                    $query->whereRaw('LOWER(districts.district_name_en) LIKE ?', ["%{$keyword}%"]);
                })
                ->filterColumn('city_name_en', function ($query, $keyword) {
                    $query->whereRaw('LOWER(cities.city_name_en) LIKE ?', ["%{$keyword}%"]);
                })
                ->rawColumns(['citydetail','addcity', 'edit', 'activation', 'blocklabourofficedivision'])
                ->make(true);
        }

        return view('adminpanel.masterdata.labour_office_division.list');
    }

    public function edit($id)
    {
        $labourOfficeID = decrypt($id);
        $data = LabourOfficeDivision::with('officetypes')->find($labourOfficeID);
        $provinces = Province::where('status', 'Y')->where('is_delete', '0')->orderBy('province_name_en', 'ASC')->get();
        $zone = LabourOfficeDivision::where('office_type_id', '3')->where('status', 'Y')->where('is_delete', '0')->orderBy('office_name_en', 'ASC')->get();
        $districtoffice = LabourOfficeDivision::where('office_type_id', '4')->where('status', 'Y')->where('is_delete', '0')->orderBy('office_name_en', 'ASC')->get();
        $cities = City::pluck('city_name_en', 'id');
        $officetype = OfficeType::pluck('office_type_name_en', 'id');

        // dd($officetypes);

        //$titles = OfficeType::pluck('office_type_name_en', 'id');


        return view('adminpanel.masterdata.labour_office_division.edit', ['data' => $data, 'provinces' => $provinces, 'districts' => $districtoffice, 'cities' => $cities, 'zone' => $zone]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'office_name_en' => 'required',
        ]);

        if ($request->hasFile('letter_head')) {

            $letterhead = $request->file('letter_head')->getClientOriginalName();

            $path = $request->file('letter_head')->store('public/letterhead');

            $data = LabourOfficeDivision::find($request->id);
            $data->office_name_en = $request->office_name_en;
            $data->office_name_sin = $request->office_name_sin;
            $data->office_name_tam = $request->office_name_tam;
            $data->address = $request->address;
            $data->address_sin = $request->address_sin;
            $data->address_tam = $request->address_tam;
            $data->tel = $request->tel;
            $data->fax = $request->fax;
            $data->email = $request->email;
            $data->letter_head = $path;
            $data->status = $request->status;
            $data->province_id = $request->province_id;
            $data->district_id = $request->district_id;
            // $data->city_id = $request->city_id;
            // $data->office_type_id = $request->office_type_id;
            $data->office_code = $request->office_code;
            $data->zone_id = $request->zone_id;
            $data->sub_district_id = $request->sub_district_id;
            $data->save();
        }

        $data = LabourOfficeDivision::find($request->id);
        $data->office_name_en = $request->office_name_en;
        $data->office_name_sin = $request->office_name_sin;
        $data->office_name_tam = $request->office_name_tam;
        $data->address = $request->address;
        $data->address_sin = $request->address_sin;
        $data->address_tam = $request->address_tam;
        $data->tel = $request->tel;
        $data->fax = $request->fax;
        $data->email = $request->email;
        // $data->letter_head = $path;
        $data->status = $request->status;
        $data->province_id = $request->province_id;
        $data->district_id = $request->district_id;
        // $data->city_id = $request->city_id;
        // $data->office_type_id = $request->office_type_id;
        $data->office_code = $request->office_code;
        $data->zone_id = $request->zone_id;
        $data->sub_district_id = $request->sub_district_id;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Labour office division record '.$data->office_name_en.' updated('.$id.').');

        return redirect()->route('labour-office-division-list')
            ->with('success', 'Labour office updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  LabourOfficeDivision::find($request->id);

        if ($data->status == "Y") {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Labour office division record '.$data->office_name_en.' deactivated('.$id.').');

            return redirect()->route('labour-office-division-list')->with('success', 'Labour office deactivate successfully.');
        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Labour office division record '.$data->office_name_en.' activated('.$id.').');

            return redirect()->route('labour-office-division-list')->with('success', 'Labour office activate successfully.');
        }
    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  LabourOfficeDivision::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Labour office division record '.$data->office_name_en.' deleted('.$id.').');

        return redirect()->route('labour-office-division-list')
            ->with('success', 'Labour office deleted successfully.');
    }

    public function addCity($id)
    {

        $officeId = decrypt($id);
        $data = LabourOfficeDivision::find($officeId);


        // $cities = City::where('province_id', $data->province_id)->where('status', 'Y')->where('is_delete', '0')->orderBy('city_name_en', 'ASC')->get();
        $cities = City::where('status', 'Y')->where('is_delete', '0')->orderBy('city_name_en', 'ASC')->get();

        /*
        $cities = LabourOfficeCityDetail::select('labour_office_city_details.city_id as id', 'cities.city_name_en as city_name_en')
        ->where('cities.province_id', $data->province_id)
        ->where('cities.status', 'Y')
        ->where('cities.is_delete', '0')
        ->orderBy('city_name_en', 'ASC')
        ->leftJoin('cities','cities.id', '=', 'labour_office_city_details.city_id')
        ->selectRaw('GROUP_CONCAT(labour_office_city_details.office_code) as office_code')
        ->groupBy('city_name_en','labour_office_city_details.city_id')
        ->get();

        */

        //dd($offices);
        $map_cities = LabourOfficeCityDetail::where('office_id', $officeId)->pluck('city_id')->toArray();

       // dd($map_cities);

        return view('adminpanel.masterdata.labour_office_division.add_city', compact('cities', 'data', 'map_cities'));
    }

    public static function getOfficeCodes($id)
    {
        $office_code = "";
        $map_cities = LabourOfficeCityDetail::where('city_id', $id)->pluck('office_code')->toArray();
        foreach($map_cities as $cRow){
            $office_code .= $cRow." - ";
        }
        return $office_code;
    }

    public function saveCities(Request $request)
    {

        $officeid = $request->office_id;

        if($request->city_id != "") {

            $deleterecords = LabourOfficeCityDetail::where('office_id',$officeid)->delete();

            $count = count($request->city_id);

                for ($i = 0; $i < $count; $i++) {

                    $has_record = LabourOfficeCityDetail::select('id')->where('city_id', $request->city_id[$i])->where('office_id', $request->office_id)->exists();

                    if (!$has_record) {
                        $city = new LabourOfficeCityDetail();
                        $city->office_id = $request->office_id;
                        $city->office_code = $request->office_code;
                        $city->city_id = $request->city_id[$i];
                        $city->save();
                        $id = $city->city_id;

                        $citydata = City::where('id', $id)->first();

                        // $citydata = City::find($id);

                        \LogActivity::addToLog('City '.$citydata->city_name_en.' added to labour office division ' . $request->office_code . '');
                    }
                }
                //   dd($city);
        } else {

            $assignCities = LabourOfficeCityDetail::where('office_id', $officeid)->delete();
        }

        return redirect()->route('labour-office-division-list')->with('success', 'Labour Office city added successfully.');
    }

    public function getZone(Request $request)
    {
        $zone = LabourOfficeDivision::where("province_id", $request->province_id)->where('status', 'Y')->where('is_delete', '0')->where("office_type_id", 3)->get();
        return response()->json($zone);
    }

    public function getLabourDistrict(Request $request)
    {
        $district = LabourOfficeDivision::where("zone_id", $request->zone_id)->where('status', 'Y')->where('is_delete', '0')->where("office_type_id", 4)->get();
        return response()->json($district);
    }

    function cityDetail(Request $request){
        //dd($request);
        $cities = LabourOfficeCityDetail::join('cities', 'cities.id', '=', 'labour_office_city_details.city_id')
                        ->select('cities.city_name_en')
                        ->where('labour_office_city_details.office_id', $request->id)
                        ->where('cities.is_delete','0')
                        ->get();
        return response()->json($cities);
    }

    function syncCities(Request $request) {

        $office_id = $request->id;

        $officecode = LabourOfficeDivision::where('id', $office_id)->get();

        // dd($officecode[0]->office_code);

        $deleteassignedcities = LabourOfficeCityDetail::where('office_id', $office_id)->delete();

        $districtoffices = LabourOfficeDivision::where('zone_id', $office_id)->get();

        $officecount = $districtoffices->count();

        for ($i = 0; $i < $officecount; $i++) {

            $assigncities = LabourOfficeCityDetail::where('office_id',$districtoffices[$i]->id)->get();

            $citycount = $assigncities->count();

            for($c = 0; $c < $citycount; $c++) {

                $assignnewcity = new LabourOfficeCityDetail();

                $assignnewcity->office_id = $office_id;
                $assignnewcity->office_code = $officecode[0]->office_code;
                $assignnewcity->city_id = $assigncities[$c]->city_id;

                $validatecity = LabourOfficeCityDetail::where('city_id',$assigncities[$c]->city_id)->where('office_code',$officecode[0]->office_code)->get();
                $assignnewcity->save();

            }

        }



        return redirect()->route('labour-office-division-list')->with('success', 'Labour Office city added successfully.');
    }
}
