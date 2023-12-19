<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EstablishmentType;
use DataTables;

class EstablishmentTypeController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:establishment-list|establishment-create|establishment-edit|establishment-delete', ['only' => ['list']]);
        $this->middleware('permission:establishment-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:establishment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:establishment-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('adminpanel.masterdata.establishment_type.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'establishment_name_en' => 'required'
        ]);

        $id = EstablishmentType::create($request->all())->id;

        \LogActivity::addToLog('New establishment type '.$request->establishment_name_en.' added('.$id.').');

        return redirect()->route('establishment-type')
            ->with('success', 'Establishment type created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = EstablishmentType::where('is_delete',0)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-establishment-type/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-establishment-type/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                // ->addColumn('blockestablishment', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //   //  $btn = '<a href="blockestablishment/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';
                //   $btn = '<button class="btn-delete" value="'.$row->id.'"><i class="'.$dltstatus.'"></i></button>';
                //     return $btn;
                // })
                ->addColumn('blockestablishment', 'adminpanel.masterdata.establishment_type.actionsBlock')
                ->rawColumns(['edit', 'activation', 'blockestablishment'])
                ->make(true);
        }

        return view('adminpanel.masterdata.establishment_type.list');
    }

    public function edit($id)
    {
        $EstablishmentID = decrypt($id);
        $data = EstablishmentType::find($EstablishmentID);
        return view('adminpanel.masterdata.establishment_type.edit', ['data' => $data]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'establishment_name_en' => 'required'
        ]);

        $data =  EstablishmentType::find($request->id);
        $data->establishment_name_en = $request->establishment_name_en;
        $data->establishment_name_sin = $request->establishment_name_sin;
        $data->establishment_name_tam = $request->establishment_name_tam;
        $data->order = $request->order;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Establishment type record '.$data->establishment_name_en.' updated('.$id.').');

        return redirect()->route('establishment-type-list')
            ->with('success', 'Establishment Type updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  EstablishmentType::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Establishment type record '.$data->establishment_name_en.' deactivated('.$id.').');

            return redirect()->route('establishment-type-list')
            ->with('success', 'Establishment type deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Establishment type record '.$data->establishment_name_en.' activated('.$id.').');

            return redirect()->route('establishment-type-list')
            ->with('success', 'Establishment type activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  EstablishmentType::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Establishment type record '.$data->establishment_name_en.' deleted('.$id.').');


        return redirect()->route('establishment-type-list')
            ->with('success', 'Establishment type deleted successfully.');
    }

}
