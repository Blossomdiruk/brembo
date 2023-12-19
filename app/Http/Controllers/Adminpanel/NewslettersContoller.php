<?php

namespace App\Http\Controllers\adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Newsletters;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class NewslettersContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:newsletters-list|newsletters-create|newsletters-edit|newsletters-delete', ['only' => ['list']]);
        $this->middleware('permission:newsletters-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:newsletters-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:newsletters-list', ['only' => ['list']]);
      $this->middleware('permission:newsletters-list|newsletters-create|newsletters-edit|newsletters-delete', ['only' => ['list']]);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        return view('adminpanel.newsletters.index')->with('savestatus',$savestatus);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Newsletters::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            })    
                ->addColumn('edit', 'adminpanel.newsletters.actionsEdit')
                ->addColumn('activation', 'adminpanel.newsletters.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.newsletters.list');
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
            'status' => 'required ',
        ]);
        
        $data_arry = array();
        $data_arry['name'] = $request->name;
        $data_arry['status'] = $request->status;
        $data_arry['description'] = $request->vDescription;
        
        if($request->hasfile('fImage') == 'true')
            {
               
                    $file = $request->file('fImage');
                    //$request->file('fImage')->store('images');
                    $dte=date("ymdHms");
                    $name = $file->getClientOriginalName();
                    $name =str_replace(' ', '_',$dte.$name); 
                    $file->move(base_path().'/images/', $name);

                $data_arry['image'] = $name;
                
               
            }

        //$id = Complain_Category::create($request->all())->id;
        if($request->savestatus == 'A'){
            
            $id= Newsletters::create($data_arry);
             \LogActivity::addToLog('New newsletters '.$request->name.' added('.$id->id.').');
            return redirect('new-newsletters')->with('success', 'New newsletters created successfully');
        }else{
            
            $recid = $request->id;
             $id=Newsletters::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('newsletters '.$request->name.' updated('.decrypt($recid).').');
            return redirect('/edit-newsletters/'.$recid.'')->with('success', 'newsletters updated successfully');
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
        $info = Newsletters::where('id', '=', $ID)->get();
        $savestatus = 'E';
        return view('adminpanel.newsletters.index')->with('info',$info)->with('savestatus',$savestatus);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Newsletters::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('newsletters record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('newsletters-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('newsletters record '.$data->name.' activated('.$id.')');

            return redirect()->route('newsletters-list')
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
