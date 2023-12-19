<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsTemplate;
use DataTables;

class SmsTemplateController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:sms-template-list|sms-template-edit', ['only' => ['index', 'list']]);
        $this->middleware('permission:sms-template-edit', ['only' => ['edit', 'update']]);
    }

    public function index()
    {
        return view('adminpanel.masterdata.sms_template.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = SmsTemplate::where('is_delete',0);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-sms-template/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-sms-template/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                ->addColumn('blocksmstemplate', function($row){
                    if ( $row->status == "1" )
                        $dltstatus ='fa fa-ban';
                    else
                        $dltstatus ='fa fa-trash';

                    $btn = '<a href="blocksmstemplate/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';

                    return $btn;
                })
                ->rawColumns(['edit', 'activation', 'blocksmstemplate'])
                ->make(true);
        }

        return view('adminpanel.masterdata.sms_template.index');
    }

    public function edit($id)
    {
        $templateID = decrypt($id);
        $data = SmsTemplate::find($templateID);
        $smstemplates = SmsTemplate::where('status','Y')
                                    ->where('is_delete','0')
                                    ->get();
        return view('adminpanel.masterdata.sms_template.edit', ['data' => $data, 'smstemplates' => $smstemplates]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'sms_template_name_en' => 'required',
            'body_content_en' => 'required'
        ]);

        $data =  SmsTemplate::find($request->id);
        $data->sms_template_name_en = $request->sms_template_name_en;
        $data->sms_template_name_sin = $request->sms_template_name_sin;
        $data->sms_template_name_tam = $request->sms_template_name_tam;
        $data->body_content_en = $request->body_content_en;
        $data->body_content_sin = $request->body_content_sin;
        $data->body_content_tam = $request->body_content_tam;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('SMS templete record '.$data->sms_template_name_en.' updated('.$id.').');

        return redirect()->route('sms-template-list')
            ->with('success', 'SMS template updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  SmsTemplate::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('SMS templete record '.$data->sms_template_name_en.' deactivated('.$id.').');

            return redirect()->route('sms-template')
            ->with('success', 'SMS template deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('SMS templete record '.$data->sms_template_name_en.' activated('.$id.').');

            return redirect()->route('sms-template')
            ->with('success', 'SMS template activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  SmsTemplate::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('SMS templete record '.$data->sms_template_name_en.' deleted('.$id.').');

        return redirect()->route('sms-template')
            ->with('success', 'SMS template deleted successfully.');
    }

}
