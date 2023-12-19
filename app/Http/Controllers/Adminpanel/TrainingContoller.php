<?php

namespace App\Http\Controllers\adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Workshops;
use App\Models\TrainingMeterial;
use App\Models\Trainning;
use App\Models\State;
use App\Models\City;
use App\Models\Address;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Storage;

class TrainingContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:training-list|training-create|training-edit|training-delete', ['only' => ['list']]);
        $this->middleware('permission:training-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:training-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:training-list', ['only' => ['list']]);
        $this->middleware('permission:training-list|training-create|training-edit|training-delete', ['only' => ['list']]);
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        $state =State::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        return view('adminpanel.training.index')->with('state',$state)->with('savestatus',$savestatus)->with('city',$city);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Trainning::select('trainnings.*')
                    ->orderBy('trainnings.id','DESC')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.training.actionsEdit')
                ->addColumn('activation', 'adminpanel.training.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.training.list');
    }

    /**
     * Store a newly created resource in storage.
     * update existing resources 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->savestatus == 'A') {
            $request->validate([
                'vName' => 'required|max:50',
                'session_startdate' => 'required',
                'session_enddate' => 'required',
                'tTrainning_description' => 'required|max:2500',
                'file' => 'mimes:pdf|max:2048',
            ]);
        } else {
            $request->validate([
                'vName' => 'required|max:50',
                'session_startdate' => 'required',
                'session_enddate' => 'required',
                'tTrainning_description' => 'required|max:2500',
                'file' => 'mimes:pdf|max:2048',
            ]);
        }
      
        $data_arry = array();
        $data_arry['vName'] = $request->vName;
        $data_arry['dStartDate'] = $request->session_startdate;
        $data_arry['dEndDate'] = $request->session_enddate;
        $data_arry['tTrainning_description'] = $request->tTrainning_description;
        $data_arry['status'] = $request->status;
        
       
        if($request->savestatus == 'A'){
            
            //$addressID = Address::create($addresses_arry);
            //$data_arry['addressID'] = $addressID->id;
            $id= Trainning::create($data_arry);
                //file upload 
                if($request->hasfile('files'))
                {
                    foreach($request->file('files') as $key => $file)
                    {
                        $path = $file->store('public/files');
                        $insert[$key]['fImage'] = $path;
                        $insert[$key]['iTrainingID'] = $id->id;
                        $insert[$key]['vMeterailaName'] = $request->meterial_name[ $key];
                        $insert[$key]['status'] = '1';
                    }
                    TrainingMeterial::insert($insert);
                    \LogActivity::addToLog('New Traning session file ('.$id->id.') uploaded ');
                }
             \LogActivity::addToLog('New Traning'.$request->business_name.' added('.$id->id.').');
            return redirect('new-training')->with('success', 'New Training created successfully');
        }else{
            
            $recid = $request->id;
            //$addressid = $request->adddressid;
            //Address::where('id', decrypt($addressid))->update($addresses_arry);
            Trainning::where('id', decrypt($recid))->update($data_arry);
             //file upload 
             if($request->hasfile('files'))
             {
                 foreach($request->file('files') as $key => $file)
                 {
                     $path = $file->store('public/files');
                     $insert[$key]['fImage'] = $path;
                     $insert[$key]['iTrainingID'] = decrypt($recid);
                     $insert[$key]['vMeterailaName'] = $request->meterial_name[ $key];
                     $insert[$key]['status'] = '1';
                 }
                 TrainingMeterial::insert($insert);
                 \LogActivity::addToLog('New Traning session file ('.$recid.') uploaded ');
             }
            \LogActivity::addToLog('training ' . $request->business_name . ' updated(' . decrypt($recid) . ').');
            return redirect('/edit-training/'.$recid.'')->with('success', 'Training updated successfully');
        }

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
        $info = Trainning::where('id', '=', $ID)->get();
        $trainingmeterial =TrainingMeterial::select('*')->where('iTrainingID', $ID)->orderBy('id','ASC')->get();
        // $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        // $addressID = $info[0]->addressID; 
        // $addressinfo = Address::where('id','=',$addressID)->get();
        $savestatus = 'E';
        return view('adminpanel.training.index')->with('info',$info)->with('savestatus',$savestatus)->with('meterials',$trainingmeterial);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);
        
        $data =  Trainning::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Training record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('trainings-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Training record '.$data->name.' activated('.$id.')');

            return redirect()->route('trainings-list')
            ->with('success', 'Record activate successfully.');
        }

    }
    
     public function get_state_cities(Request $request)
    {
        $cityID =  $request->stateID;
        $city['data'] = City::select('*')->where("state_id", $cityID)->orderBy('name','ASC')->get();
        return response()->json($city);
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
    public function deletemeterial($id)
    {
        $training_meterials = TrainingMeterial::select('fImage')->where('id', decrypt($id))->first();
       
        if(Storage::exists($training_meterials->fImage)){
            Storage::delete($training_meterials->fImage);
            $res=TrainingMeterial::where('id',decrypt($id))->delete();
            return redirect()->back()->with('success',"Training meterial removed");
        }else{
            return redirect()->back()->with('error',"There is s problem in meterial removal.Please try again.");
        }
    }
}
