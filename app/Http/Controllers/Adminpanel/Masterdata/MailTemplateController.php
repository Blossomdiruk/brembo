<?php

namespace App\Http\Controllers\Adminpanel\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MailTemplate;
use DataTables;

class MailTemplateController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:letter-template-list|letter-template-edit', ['only' => ['index', 'list']]);
        $this->middleware('permission:letter-template-edit', ['only' => ['edit', 'update']]);
    }

    public function index()
    {
        // $mailtemplates = MailTemplate::where('status','Y')
        //                             ->where('is_delete','0')
        //                             ->get();

        // return view('adminpanel.masterdata.mail_template.index', compact('mailtemplates'));
        return view('adminpanel.masterdata.mail_template.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = MailTemplate::where('is_delete',0);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($data) {
                    if ( $data->category == 'L') {
                        return "Letter";
                    }else if($data->category == 'E') {
                        return "Email";
                    }else if($data->category == 'ND'){
                        return "Notices and Directives";
                    }
                })
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-mail-template/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('activation', function($row){
                    if ( $row->status == "Y" )
                        $status ='fa fa-check';
                    else
                        $status ='fa fa-remove';

                    $btn = '<a href="changestatus-mail-template/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';

                    return $btn;
                })
                // ->addColumn('blockmailtemplate', function($row){
                //     if ( $row->status == "1" )
                //         $dltstatus ='fa fa-ban';
                //     else
                //         $dltstatus ='fa fa-trash';

                //     $btn = '<a href="blockmailtemplate/'.$row->id.'/'.$row->cEnable.'"><i class="'.$dltstatus.'"></i></a>';

                //     return $btn;
                // })
                ->rawColumns(['edit', 'activation', 'blockmailtemplate'])
                ->make(true);
        }

        return view('adminpanel.masterdata.mail_template.index');
    }

    // public function getTemplate($id = 0)
    // {
    //     $templates = MailTemplate::where('id',$id)->first();
    //     return response()->json($templates);

    // //     $data = sopList::where('doc_no',$id)->first();
    // // return response()->json($data);
    // }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'summernote' => 'required'
    //     ]);

    //     $data = MailTemplate::find($request->id);
    //     $data->mail_template_name_en = $request->mail_template_name_en;
    //     $data->summernote = $request->summernote;
    //     $data->status = $request->status;
    //     $data->save();

    //     return redirect()->route('mail-template')
    //         ->with('success', 'Mail template updated successfully.');
    // }

    public function edit($id)
    {
        $templateID = decrypt($id);
        $data = MailTemplate::find($templateID);
        $mailtemplates = MailTemplate::where('status','Y')
                                    ->where('is_delete','0')
                                    ->get();
        return view('adminpanel.masterdata.mail_template.edit', ['data' => $data, 'mailtemplates' => $mailtemplates]);
    }

    public function update(Request $request)
    {
        $request->validate([
            // 'mail_template_title' => 'required',
            'mail_template_name_en' => 'required',
            'body_content_en' => 'required',
            'category'=> 'required',
        ]);

        $data =  MailTemplate::find($request->id);
        // $data->mail_template_title = $request->mail_template_title;
        $data->category = $request->category;
        $data->mail_template_name_en = $request->mail_template_name_en;
        $data->mail_template_name_sin = $request->mail_template_name_sin;
        $data->mail_template_name_tam = $request->mail_template_name_tam;
        $data->body_content_en = $request->body_content_en;
        $data->body_content_sin = $request->body_content_sin;
        $data->body_content_tam = $request->body_content_tam;
        $data->other_email = $request->other_email;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Mail templete record '.$data->mail_template_name_en.' updated('.$id.').');

        return redirect()->route('mail-template')
            ->with('success', 'Mail template updated successfully.');
    }

    public function activation(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  MailTemplate::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Mail templete record '.$data->mail_template_name_en.' deactivated('.$id.').');

            return redirect()->route('mail-template')
            ->with('success', 'Mail template deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Mail templete record '.$data->mail_template_name_en.' activated('.$id.').');

            return redirect()->route('mail-template')
            ->with('success', 'Mail template activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  MailTemplate::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Mail templete record '.$data->mail_template_name_en.' deleted('.$id.').');

        return redirect()->route('mail-template')
            ->with('success', 'Mail template deleted successfully.');
    }

}
