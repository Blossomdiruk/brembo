<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Productfamily;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductfamilyContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:product-family-list|product-family-create|product-family-edit|product-family-delete', ['only' => ['list']]);
        $this->middleware('permission:product-family-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:product-family-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:product-family-list', ['only' => ['list']]);
      $this->middleware('permission:product-family-list|product-family-create|product-family-edit|product-family-delete', ['only' => ['list']]);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        return view('adminpanel.masterdata.product_family.index')->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Productfamily::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.masterdata.product_family.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.product_family.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.product_family.list');
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
            
            $id= Productfamily::create($data_arry);
             \LogActivity::addToLog('New product family '.$request->name.' added('.$id.').');
            return redirect('new-productfamily')->with('success', 'New product family created successfully');
        }else{
            
            $recid = $request->id;
             $id=Productfamily::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('product family '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-productfamily/'.$recid.'')->with('success', 'product family updated successfully');
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
        $info = Productfamily::where('id', '=', $ID)->get();
        $savestatus = 'E';
        return view('adminpanel.masterdata.product_family.index')->with('info',$info)->with('savestatus',$savestatus);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Productfamily::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('product family record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('productfamily-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('product family record '.$data->name.' activated('.$id.')');

            return redirect()->route('productfamily-list')
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
