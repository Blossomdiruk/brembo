<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complain_Category;
use DataTables;

class ComplainCategoryController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['list']]);
        $this->middleware('permission:category-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update','activation']]);
      //  $this->middleware('permission:category-list', ['only' => ['list']]);
      //$this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['list']]);
    }

    public function index()
    {
        return view('adminpanel.masterdata.complain_category.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name_en' => 'required',
            'category_name_si' => 'required',
            'category_name_ta' => 'required',
            'expiry_days' => 'required',
            'category_prefix' => 'required|max:1',
            'order' => 'required'
        ]);

        $id = Complain_Category::create($request->all())->id;

        \LogActivity::addToLog('New complaint category '.$request->category_name_en.' added('.$id.').');
        //dd('log insert successfully.');

        return redirect()->route('complain-category')
            ->with('success', 'Category created successfully.');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Complain_Category::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.masterdata.complain_category.actionsEdit')
                ->addColumn('activation', 'adminpanel.masterdata.complain_category.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.masterdata.complain_category.list');
    }

    public function edit($id)
    {
        $categoryID = decrypt($id);
        $data = Complain_Category::find($categoryID);
        return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }


    public function update(Request $request)
    {
        $request->validate([
            'category_name_en' => 'required',
            'category_name_si' => 'required',
            'category_name_ta' => 'required',
            'expiry_days' => 'required',
            'category_prefix' => 'required|max:1',
            'order' => 'required'
        ]);

        $data =  Complain_Category::find($request->id);
        $data->category_name_en = $request->category_name_en;
        $data->category_name_si = $request->category_name_si;
        $data->category_name_ta = $request->category_name_ta;
        $data->expiry_days = $request->expiry_days;
        $data->category_prefix = $request->category_prefix;
        $data->order = $request->order;
        $data->status = $request->status;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Complaint category record '.$data->category_name_en.' updated('.$id.')');

        return redirect()->route('complain-category-list')
            ->with('success', 'Category updated successfully.');
    }

    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Complain_Category::find($id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Complaint category record '.$data->category_name_en.' deactivated('.$id.')');

            return redirect()->route('complain-category-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Complaint category record '.$data->category_name_en.' activated('.$id.')');

            return redirect()->route('complain-category-list')
            ->with('success', 'Record activate successfully.');
        }

    }
}
