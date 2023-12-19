<?php

namespace App\Http\Controllers\adminpanel\masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle_submodel;
use App\Models\Vehiclemodel;
use App\Models\Vehiclebrand;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VehiclesubmodelContoller extends Controller
{
     function __construct()
    {

        $this->middleware('permission:vehicle-submodel-list|vehicle-submodel-create|vehicle-submodel-edit|vehicle-submodel-delete', ['only' => ['list']]);
        $this->middleware('permission:vehicle-submodel-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:vehicle-submodel-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:vehicle-submodel-list', ['only' => ['list']]);
      $this->middleware('permission:vehicle-submodel-list|vehicle-submodel-create|vehicle-submodel-edit|vehicle-submodel-delete', ['only' => ['list']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        $brand =Vehiclebrand::select('*')->where('status', '1')->orderBy('name','ASC')->get();
        $model =Vehiclemodel::select('*')->where('status', '1')->orderBy('name','ASC')->get(); 
        
        return view('adminpanel.masterdata.vehicle_submodel.index')->with('brand',$brand)->with('model',$model)->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Vehicle_submodel::join('vehicle_brand', 'vehicle_submodel.brandID', '=', 'vehicle_brand.id')
                    ->join('vehicle_model', 'vehicle_submodel.modelID', '=', 'vehicle_model.id')
                    ->select('vehicle_submodel.*','vehicle_brand.name as brand','vehicle_model.name as model')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.masterdata.vehicle_submodel.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.vehicle_submodel.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.vehicle_submodel.list');
    }

    /**
     * Store a newly created resource in storage.
     * update existing resources 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
            'status' => 'required |max:255',
            'brandID' => 'required',
            'modelID' => 'required',
        ]);
        
        $data_arry = array();
        $data_arry['name'] = $request->name;
        $data_arry['status'] = $request->status;
        $data_arry['brandID'] = $request->brandID;
        $data_arry['modelID'] = $request->modelID;

        //$id = Complain_Category::create($request->all())->id;
        if($request->savestatus == 'A'){
            
            $id= Vehicle_submodel::create($data_arry);
             \LogActivity::addToLog('New vehicle submodel '.$request->name.' added('.$id.').');
            return redirect('new-vehiclesubmodel')->with('success', 'New vehicle submodel created successfully');
        }else{
            
            $recid = $request->id;
             $id=Vehicle_submodel::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('vehicle submodel '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-vehiclesubmodel/'.$recid.'')->with('success', 'Vehicle submodel updated successfully');
        }

       
        //dd('log insert successfully.');

//        return redirect()->route('complain-category')
//            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ID = decrypt($id);
        $info = Vehicle_submodel::where('id', '=', $ID)->get();
        $brand =Vehiclebrand::select('*')->where('status', '1')->orderBy('name','ASC')->get();
        $model =Vehiclemodel::select('*')->where('status', '1')->orderBy('name','ASC')->get();
        $savestatus = 'E';
        return view('adminpanel.masterdata.vehicle_submodel.index')->with('info',$info)->with('savestatus',$savestatus)->with('brand',$brand)->with('model',$model);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Vehicle_submodel::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('vehicle submodel record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('vehiclesubmodel-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('vehicle submodel record '.$data->name.' activated('.$id.')');

            return redirect()->route('vehiclesubmodel-list')
            ->with('success', 'Record activate successfully.');
        }

    }
    
     public function get_vehicle_make_braches(Request $request)
    {
        $brandID =  $request->brandID;
        $brand['data'] = Vehiclemodel::select('*')->where("brandID", $brandID)->orderBy('name','ASC')->get();
        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
