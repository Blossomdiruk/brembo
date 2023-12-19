<?php

namespace App\Http\Controllers\adminpanel\masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Competitor_brands;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CompetitorbrandsContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:competitor-brands-list|competitor-brands-create|competitor-brands-edit|competitor-brands-delete', ['only' => ['list']]);
        $this->middleware('permission:competitor-brands-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:competitor-brands-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:competitor-brands-list', ['only' => ['list']]);
      $this->middleware('permission:competitor-brands-list|competitor-brands-create|competitor-brands-edit|competitor-brands-delete', ['only' => ['list']]);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        return view('adminpanel.masterdata.competitor_brands.index')->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Competitor_brands::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.masterdata.competitor_brands.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.competitor_brands.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.competitor_brands.list');
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
            
            $id= Competitor_brands::create($data_arry);
             \LogActivity::addToLog('New competitor brands '.$request->name.' added('.$id.').');
            return redirect('new-competitor-brands')->with('success', 'New competitor brands created successfully');
        }else{
            
            $recid = $request->id;
             $id=Competitor_brands::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('competitor brands '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-competitor-brands/'.$recid.'')->with('success', 'competitor brands updated successfully');
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
        $info = Competitor_brands::where('id', '=', $ID)->get();
        $savestatus = 'E';
        return view('adminpanel.masterdata.competitor_brands.index')->with('info',$info)->with('savestatus',$savestatus);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Competitor_brands::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('competitor brands record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('competitor-brands-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('competitor brands record '.$data->name.' activated('.$id.')');

            return redirect()->route('competitor-brands-list')
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
