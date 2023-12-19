<?php

namespace App\Http\Controllers\adminpanel\masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehiclemodel;
use App\Models\Vehiclebrand;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VehiclemodelContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:vehicle-model-list|vehicle-model-create|vehicle-model-edit|vehicle-model-delete', ['only' => ['list']]);
        $this->middleware('permission:vehicle-model-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:vehicle-model-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:vehicle-model-list', ['only' => ['list']]);
      $this->middleware('permission:vehicle-model-list|vehicle-model-create|vehicle-model-edit|vehicle-model-delete', ['only' => ['list']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        $brand =Vehiclebrand::all()->where('status', '1'); 
        return view('adminpanel.masterdata.vehicle_model.index')->with('brand',$brand)->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Vehiclemodel::join('vehicle_brand', 'vehicle_model.brandID', '=', 'vehicle_brand.id')->select('vehicle_model.*','vehicle_brand.name as brand')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.masterdata.vehicle_model.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.vehicle_model.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.vehicle_model.list');
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
            //'brandID' => 'required',
        ]);
        
        $data_arry = array();
        $data_arry['name'] = $request->name;
        $data_arry['status'] = $request->status;
        $data_arry['brandID'] = $request->brandID;

        //$id = Complain_Category::create($request->all())->id;
        if($request->savestatus == 'A'){
            
            $id= Vehiclemodel::create($data_arry);
             \LogActivity::addToLog('New vehicle model '.$request->name.' added('.$id.').');
            return redirect('new-vehiclemodel')->with('success', 'New vehicle model created successfully');
        }else{
            
            $recid = $request->id;
             $id=Vehiclemodel::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('vehicle model '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-vehiclemodel/'.$recid.'')->with('success', 'Vehicle model updated successfully');
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
        $info = Vehiclemodel::where('id', '=', $ID)->get();
        $brand =Vehiclebrand::all()->where('status', '1'); 
        $savestatus = 'E';
        return view('adminpanel.masterdata.vehicle_model.index')->with('info',$info)->with('savestatus',$savestatus)->with('brand',$brand);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Vehiclemodel::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('vehicle model record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('vehiclemodel-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('vehicle model record '.$data->name.' activated('.$id.')');

            return redirect()->route('vehiclemodel-list')
            ->with('success', 'Record activate successfully.');
        }

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
