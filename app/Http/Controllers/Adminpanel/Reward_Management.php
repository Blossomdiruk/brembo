<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Reward_Management extends Controller
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
            $data = Exam::select('exam.*')
                    ->orderBy('exam.id','DESC')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.warrenty_incident.actionsEdit')
                ->addColumn('activation', 'adminpanel.warrenty_incident.actionsStatus')
                ->rawColumns(['edit', 'activation'])
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
        //
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
        //
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
