<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Workshops;
use App\Models\Warrent_incident;
use App\Models\Address;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Storage;
use simplexlsxgen;

class WarrentyController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:warrenty-incedent-list|warrenty-incedent-create|warrenty-incedent-edit|warrenty-incedent-delete', ['only' => ['list']]);
        $this->middleware('permission:warrenty-incedent-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:warrenty-incedent-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:warrenty-incedent-list', ['only' => ['list']]);
        $this->middleware('permission:warrenty-incedent-list|warrenty-incedent-create|warrenty-incedent-edit|warrenty-incedent-delete', ['only' => ['list']]);

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
        return view('adminpanel.warrenty_incident.index')->with('state',$state)->with('savestatus',$savestatus)->with('city',$city);
    }

    public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Warrent_incident::select('warenty_incidents.*')
                    ->orderBy('warenty_incidents.id','DESC')
                    ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if ($request->get('warrenty_status') == '0' || $request->get('warrenty_status') == '1') {
                        //echo $request->get('warrenty_status');die();
                        $instance->where('warrenty_status', $request->get('warrenty_status'));
                    }
                    // if (!empty($request->get('search'))) {
                    //      $instance->where(function($w) use($request){
                    //         $search = $request->get('search');
                    //         $w->orWhere('name', 'LIKE', "%$search%")
                    //         ->orWhere('email', 'LIKE', "%$search%");
                    //     });
                    // }
                })
                ->addColumn('warrenty_status', function($row){
                    if($row->warrenty_status == 'P'){
                        $btn = 'In progress';
                    }else if($row->warrenty_status == 'A')
                    {
                        $btn = 'Active';
                    }else if($row->warrenty_status == 'C')
                    {  $btn = 'Complete';}
                     return $btn;
                }) 
                ->addColumn('edit', 'adminpanel.warrenty_incident.actionsEdit')
                //->addColumn('warrenty_status', 'adminpanel.warrenty_incident.actionsStatus')
                ->rawColumns(['edit', 'warrenty_status'])
                ->make(true);
        }

        return view('adminpanel.warrenty_incident.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'product_name' => 'required',
            'description' => 'required',
            'comment' => 'required',
            'warrenty_status' => 'required',
            'workshop_id' => 'required',
        ]);

        $data_arry = array();
        $data_arry['comment'] = $request->comment;
        if($request->warrenty_status==0)
        {
            $data_arry['reject_reason'] = $request->reject_reason;
        }  
        $recid = $request->id;
        $data_arry['warrenty_status'] = $request->warrenty_status;
        Warrent_incident::where('id', decrypt($recid))->update($data_arry);
        \LogActivity::addToLog('Warrenty Incident  updated(' . decrypt($recid) . ').');
        return redirect('/warrenty-incedent-list/')->with('success', 'Warrenty Incident updated successfully');
       

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
        $info = Warrent_incident::where('id', '=', $ID)->get();
        $savestatus = 'E';
        return view('adminpanel.warrenty_incident.index')->with('info',$info)->with('savestatus',$savestatus);
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
    public function export(Request $request)
    {
        //dd('Hiii');
        //dd(simplexlsxgen);
        $books = [
            ['ISBN', 'title', 'author', 'publisher', 'ctry' ],
            [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
            [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
        ];
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray( $books );
        $xlsx->downloadAs('books.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
        return json_encode('Hiii');

//         ini_set('error_reporting', E_ALL );
// ini_set('display_errors', 1 );

// $data = [
//     ['Debug', 123]
// ];

// \Shuchkin\SimpleXLSXGen::fromArray( $data )->saveAs('debug.xlsx');
     }
}
