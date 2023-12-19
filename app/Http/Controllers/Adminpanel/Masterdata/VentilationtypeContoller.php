<?php

namespace App\Http\Controllers\adminpanel\masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ventilation_type;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VentilationtypeContoller extends Controller
{
     function __construct()
    {

        $this->middleware('permission:ventilation-type-list|ventilation-type-create|ventilation-type-edit|ventilation-type-delete', ['only' => ['list']]);
        $this->middleware('permission:ventilation-type-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:ventilation-type-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:ventilation-type-list', ['only' => ['list']]);
      $this->middleware('permission:ventilation-type-list|ventilation-type-create|ventilation-type-edit|ventilation-type-delete', ['only' => ['list']]);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        return view('adminpanel.masterdata.ventilation_type.index')->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Ventilation_type::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.masterdata.ventilation_type.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.ventilation_type.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.ventilation_type.list');
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
        ]);
        
        $data_arry = array();
        $data_arry['name'] = $request->name;
        $data_arry['status'] = $request->status;

        //$id = Complain_Category::create($request->all())->id;
        if($request->savestatus == 'A'){
            
            $id= Ventilation_type::create($data_arry);
             \LogActivity::addToLog('New Ventilation type '.$request->name.' added('.$id.').');
            return redirect('new-ventilation-type')->with('success', 'New Ventilation type created successfully');
        }else{
            
            $recid = $request->id;
             $id=Ventilation_type::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('Ventilation type '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-ventilation-type/'.$recid.'')->with('success', 'Ventilation type updated successfully');
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
        $info = Ventilation_type::where('id', '=', $ID)->get();
        $savestatus = 'E';
        return view('adminpanel.masterdata.ventilation_type.index')->with('info',$info)->with('savestatus',$savestatus);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Ventilation_type::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Ventilation type record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('ventilation-type-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Ventilation type record '.$data->name.' activated('.$id.')');

            return redirect()->route('ventilation-type-list')
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
