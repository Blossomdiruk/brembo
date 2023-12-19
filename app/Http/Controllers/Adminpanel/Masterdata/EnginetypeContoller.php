<?php

namespace App\Http\Controllers\adminpanel\masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Enginetype;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EnginetypeContoller extends Controller
{
     function __construct()
    {

        $this->middleware('permission:enginetype-list|enginetype-create|enginetype-edit|enginetype-delete', ['only' => ['list']]);
        $this->middleware('permission:enginetype-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:enginetype-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:enginetype-list', ['only' => ['list']]);
      $this->middleware('permission:enginetype-list|enginetype-create|enginetype-edit|enginetype-delete', ['only' => ['list']]);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        return view('adminpanel.masterdata.enginetype.index')->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Enginetype::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.masterdata.enginetype.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.enginetype.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.enginetype.list');
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
            
            $id= Enginetype::create($data_arry);
             \LogActivity::addToLog('New enginetype '.$request->name.' added('.$id.').');
            return redirect('new-enginetype')->with('success', 'New enginetype created successfully');
        }else{
            
            $recid = $request->id;
             $id=Enginetype::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('enginetype '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-enginetype/'.$recid.'')->with('success', 'enginetype updated successfully');
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
        $info = Enginetype::where('id', '=', $ID)->get();
        $savestatus = 'E';
        return view('adminpanel.masterdata.enginetype.index')->with('info',$info)->with('savestatus',$savestatus);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Enginetype::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('enginetype record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('enginetype-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('enginetype record '.$data->name.' activated('.$id.')');

            return redirect()->route('enginetype-list')
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
