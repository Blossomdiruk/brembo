<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\EventTitle;
use Illuminate\Http\Request;
use DataTables;

class EventTitleController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:event-title-list|event-title-create|event-title-edit|event-title-delete', ['only' => ['list']]);
        $this->middleware('permission:event-title-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:event-title-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:event-title-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('adminpanel.masterdata.event_title.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_name_en' => 'required'
        ]);

        $id = EventTitle::create($request->all())->id;

        \LogActivity::addToLog('New event title '.$request->title_name_en.' added('.$id.').');

        return redirect()->route('event-title')
            ->with('success', 'Record created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = EventTitle::select('*')->where('is_delete',0);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-event-title/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-event-title/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                // ->addColumn('blockprovince', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';


                //     return $btn;
                // })
                ->addColumn('blockeventtitle', 'adminpanel.masterdata.event_title.actionsBlock')
                ->rawColumns(['edit', 'activation', 'blockeventtitle'])
                ->make(true);
        }

        return view('adminpanel.masterdata.event_title.list');
    }

    public function edit($id)
    {
        $eventTitleID = decrypt($id);
        $data = EventTitle::find($eventTitleID);
        return view('adminpanel.masterdata.event_title.edit', ['data' => $data]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'title_name_en' => 'required'
        ]);

        $data =  EventTitle::find($request->id);
        $data->title_name_en = $request->title_name_en;
        $data->title_name_si = $request->title_name_si;
        $data->title_name_ta = $request->title_name_ta;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Event title record '.$data->title_name_en.' updated('.$id.').');

        return redirect()->route('event-title-list')
            ->with('success', 'Record updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  EventTitle::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Event title record '.$data->title_name_en.' deactivated('.$id.').');

            return redirect()->route('event-title-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Event title record '.$data->title_name_en.' activated('.$id.').');

            return redirect()->route('event-title-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  EventTitle::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Event title record '.$data->province_name_en.' deleted('.$id.').');

        return redirect()->route('event-title-list')
            ->with('success', 'Record deleted successfully.');
    }
}
