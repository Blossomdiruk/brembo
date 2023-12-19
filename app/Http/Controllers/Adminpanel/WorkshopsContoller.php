<?php

namespace App\Http\Controllers\adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Workshops;
use App\Models\Vehiclemodel;
use App\Models\Vehiclebrand;
use App\Models\State;
use App\Models\City;
use App\Models\Address;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkshopsContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:workshops-list|workshop-create|workshop-edit|workshop-delete', ['only' => ['list']]);
        $this->middleware('permission:workshop-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:workshop-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:workshops-list', ['only' => ['list']]);
      $this->middleware('permission:workshops-list|workshop-create|workshop-edit|workshop-delete', ['only' => ['list']]);
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
        return view('adminpanel.workshop.index')->with('state',$state)->with('savestatus',$savestatus)->with('city',$city);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Workshops::join('address', 'workshops.addressID', '=', 'address.id')
                    ->join('cities', 'address.cityID', '=', 'cities.id')
                    ->join('states', 'address.stateID', '=', 'states.id')
                    ->select('workshops.*','cities.name as city','states.iso2 as state')
                    ->orderBy('workshops.business_name','ASC')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            }) 
            ->addColumn('edit', function($row){
               
                $btn = 'Edit';
               
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.workshop.actionsEdit')
                ->addColumn('activation', 'adminpanel.workshop.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.workshop.list');
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
                'business_name' => 'required|max:50|unique:workshops,business_name',
                'email' => 'required|max:50|email',
                'phone' => 'required|max:20|min:10',
                'vContactperson' => 'required|max:50',
                'vABN' => 'required|max:50',
                'vAddressline1' => 'required|max:50',
                'vAddressline2' => 'required|max:50',
                'status' => 'required',
                'stateID' => 'required',
                'cityID' => 'required',
                'password' => 'required',
                'postcode' => 'required|max:10',
            ]);
        } else {
            $request->validate([
                'business_name' => 'required|max:50',
                'email' => 'required|max:50|email',
                'phone' => 'required|max:20|min:10',
                'vContactperson' => 'required|max:50',
                'vABN' => 'required|max:50',
                'vAddressline1' => 'required|max:50',
                'vAddressline2' => 'required|max:50',
                'status' => 'required',
                'stateID' => 'required',
                'cityID' => 'required',
                'postcode' => 'required',
            ]);
        }
        
        $data_arry = array();
        $data_arry['business_name'] = $request->business_name;
        $data_arry['email'] = $request->email;
        $data_arry['phone'] = $request->phone;
        $data_arry['Contact_person'] = $request->vContactperson;
        $data_arry['ABN'] = $request->vABN;
        $data_arry['status'] = $request->status;
        $data_arry['branchID'] = $request->branchID;
        //$data_arry['status'] = $request->status;
        
        $addresses_arry = array(); 
            // address details
        $addresses_arry['vAddressline1'] =$request->vAddressline1;
        $addresses_arry['vAddressline2'] =$request->vAddressline2;
        $addresses_arry['stateID'] =$request->stateID;
        $addresses_arry['cityID'] =$request->cityID;
        $addresses_arry['postcode'] =$request->postcode;

        $input = array(); 

        $input['name'] = $request->vContactperson;
        $input['email'] = $request->email;
        $input['mobile_no'] = $request->phone;
        $input['type'] = 'W';  
        $input['status'] = 'N';
        

        
        if($request->savestatus == 'A'){
            
            $addressID = Address::create($addresses_arry);
            $data_arry['addressID'] = $addressID->id;
            $id= Workshops::create($data_arry);

            $input['workshopid'] = $id->id;
            $input['password'] = Hash::make($request->password);
            $user = User::create($input);

             \LogActivity::addToLog('New workshop'.$request->business_name.' added('.$id->id.').');
            return redirect('new-workshop')->with('success', 'New workshop created successfully');
        }else{
           
            $recid = $request->id;
            $addressid = $request->adddressid;
            Address::where('id', decrypt($addressid))->update($addresses_arry);
            Workshops::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('workshop ' . $request->business_name . ' updated(' . decrypt($recid) . ').');
            return redirect('/edit-workshop/'.$recid.'')->with('success', 'workshop updated successfully');
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
        $info = Workshops::where('id', '=', $ID)->get();
        $state =State::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $addressID = $info[0]->addressID; 
        $addressinfo = Address::where('id','=',$addressID)->get();
        $savestatus = 'E';
        return view('adminpanel.workshop.index')->with('info',$info)->with('savestatus',$savestatus)->with('state',$state)->with('city',$city)->with('addressinfo',$addressinfo);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Workshops::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('workshop record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('workshop-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('workshop record '.$data->name.' activated('.$id.')');

            return redirect()->route('workshop-list')
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
}
