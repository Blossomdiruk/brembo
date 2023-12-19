<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisterComplaint;
use App\Models\Province;
use App\Models\District;
use App\Models\EstablishmentType;
use App\Models\ComplaintDocument;
use App\Models\Complain_Category;
use App\Models\ComplaintHistory;
use App\Models\LabourOfficeDivision;
use App\Models\UnionOfficerDetail;
use DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\MailTemplate;

class NewComplaintController extends Controller
{
    public function index()
    {
        $provinces = Province::where('status','Y')
                    ->where('is_delete','0')
                    ->get();

        $districts = District::where('status','Y')
                    ->where('is_delete','0')
                    ->get();

        $establishmenttypes = EstablishmentType::where('status','Y')
                            ->where('is_delete','0')
                            ->get();

        $complaincategories = Complain_Category::where('status','Y')
                            ->get();

        $labouroffices = LabourOfficeDivision::where('status','Y')
                        ->where('is_delete','0')
                        ->get();

        $maxRefId = RegisterComplaint::max('id');
        $referenceCode = "COM".str_pad(($maxRefId+1), 6, "0", STR_PAD_LEFT);

        return view('userpanel.new_complaint', compact('provinces','districts','establishmenttypes','complaincategories','labouroffices','referenceCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ref_no' => 'required',
            'complainant_identify_no' => 'required',
            'complainant_f_name' => 'required',
            'complainant_l_name' => 'required',
            // 'complainant_mobile' => 'required',
            'employer_name' => 'required',
            'employer_address' => 'required',
            // 'province_id' => 'required',
            'district_id' => 'required',
        ]);

        $complaint = new RegisterComplaint;

        $complaint->comp_type = $request->comp_type;
        $complaint->pref_lang = $request->pref_lang;
        $complaint->ref_no = $request->ref_no;
        $complaint->complainant_identify_no = $request->complainant_identify_no;
        $complaint->title = $request->title;
        $complaint->complainant_f_name = $request->complainant_f_name;
        $complaint->complainant_l_name = $request->complainant_l_name;
        $complaint->complainant_full_name = $request->complainant_full_name;
        $complaint->complainant_dob = $request->complainant_dob;
        $complaint->complainant_gender = $request->complainant_gender;
        $complaint->nationality = $request->nationality;
        $complaint->complainant_email = $request->complainant_email;
        $complaint->complainant_mobile = $request->complainant_mobile;
        $complaint->complainant_tel = $request->complainant_tel;
        $complaint->complainant_address = $request->complainant_address;
        $complaint->union_name = $request->union_name;
        $complaint->union_address = $request->union_address;
        $complaint->employer_name = $request->employer_name;
        $complaint->employer_address = $request->employer_address;
        // $complaint->province_id = $request->province_id;
        $complaint->district_id = $request->district_id;
        $complaint->employer_tel = $request->employer_tel;
        $complaint->business_nature = $request->business_nature;
        $complaint->establishment_type_id = $request->establishment_type_id;
        $complaint->establishment_reg_no = $request->establishment_reg_no;
        $complaint->employer_no = $request->employer_no;
        $complaint->ppe_no = $request->ppe_no;
        $complaint->employee_no = $request->employee_no;
        $complaint->epf_no = $request->epf_no;
        $complaint->employee_mem_no = $request->employee_mem_no;
        $complaint->designation = $request->designation;
        $complaint->join_date = $request->join_date;
        $complaint->terminate_date = $request->terminate_date;
        $complaint->last_sal_date = $request->last_sal_date;
        $complaint->basic_sal = $request->basic_sal;
        $complaint->allowance = $request->allowance;
        $complaint->submitted_office = $request->submitted_office;
        $complaint->submitted_date = $request->submitted_date;
        $complaint->case_no = $request->case_no;
        $complaint->received_relief = $request->received_relief;
        $complaint->complain_purpose = $request->complain_purpose;

        if($complaint->submitted_office != ""){
            $complaint->is_available = 1;
        } else {
            $complaint->is_available = 0;
        }
        $complaint->save();
        $id = $complaint->id;

        // $officerdetails = $request->union_officer;

        // for($i = 0; $i < count($officerdetails); $i++)
        // {
        //     $unionofficer                   = new UnionOfficerDetail();
        //     $unionofficer->ref_id       = $complaint->ref_no;
        //     $unionofficer->officer_name     = $officerdetails[$i]['union_officer_name'];
        //     $unionofficer->officer_address   = $officerdetails[$i]['union_officer_address'];
        //     $unionofficer->save();

        // }

        // foreach ($request->union_officer as $key => $value) {

        //     $value[$key] = $request->ref_no;
        //     UnionOfficerDetail::create($value);
        // }


        $validatedData = $request->validate([
            // 'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
        ]);


        if($request->hasfile('files'))
        {
            foreach($request->file('files') as $key => $file)
            {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();

            $ref_no = $complaint->id;
            $insert[$key]['ref_no'] = $ref_no;

                $insert[$key]['file_name'] = $path;

            }

            ComplaintDocument::insert($insert);
        }

        $count = count($request->union_officer_name)-1;

        for ($i=0; $i < $count; $i++) {

          $unionofficerdetail = new UnionOfficerDetail();
          $unionofficerdetail->ref_id = $complaint->id;
          $unionofficerdetail->officer_name = $request->union_officer_name[$i];
          $unionofficerdetail->officer_address = $request->union_officer_address[$i];
          $unionofficerdetail->save();
        }

        $regdata = RegisterComplaint::where('id', $id)
                ->get();

        $mailtem = MailTemplate::where('status', 'Y')
                ->where('id', 1)
                ->get();
        //dd();exit();
        \App::setLocale($regdata[0]->pref_lang);

        if($regdata[0]->pref_lang == 'EN'){
            $e_sub = $mailtem[0]->mail_template_name_en;
            $e_body = $mailtem[0]->body_content_en;
            $e_name = $mailtem[0]->mail_template_name_en;
        } else if($regdata[0]->pref_lang == 'SI'){
            $e_sub = $mailtem[0]->mail_template_name_sin;
            $e_body = $mailtem[0]->body_content_sin;
            $e_name = $mailtem[0]->mail_template_name_sin;
        } else if($regdata[0]->pref_lang == 'TA'){
            $e_sub = $mailtem[0]->mail_template_name_tam;
            $e_body = $mailtem[0]->body_content_tam;
            $e_name = $mailtem[0]->mail_template_name_tam;
        }

        $data = array(
            'ref_no' => $regdata[0]->ref_no,
            'name' => $regdata[0]->complainant_f_name,
            'subject' => $e_sub,
            'body' => $e_body,
        );

        Mail::to('cms@labourdept.gov.lk')->send(new SendMail($data));

        return redirect()->route('new-register')
            ->with('success', 'Complaint entered successfully.');
    }

    public function complainttracking()
    {

        return view('userpanel.complaint_tracking');
    }

    public function searchcomplaint(Request $request)
    {
        // dd($randomNumber);

        $referenceno = $request->complaint_number;

        $data = RegisterComplaint::where('ref_no', '=', $referenceno)->get();

        if(count($data)>0) {

            $randomNumber = random_int(1000, 9999);

            $request->session()->put('otp', $randomNumber);

            $updateOtp = RegisterComplaint::where('ref_no', $referenceno)->update(['otp' => $randomNumber]);

            // dd($data);
            return view('userpanel.verification', ['data' => $data]);

        } else {

            return redirect()->route('complaint-tracking')->with('error', 'Entered complain number is not valid');

        }

        // $value = $request->session()->get('otp');

        // die(var_dump($data));

    }

    public function verification(Request $request)
    {
        // dd($randomNumber);

        $complaint_id = $request->complaint_id;
        $pin1 = $request->pin1;
        $pin2 = $request->pin2;
        $pin3 = $request->pin3;
        $pin4 = $request->pin4;

        $enteredotp = $pin1.''.$pin2.''.$pin3.''.$pin4;

        $value = $request->session()->get('otp');

        // dd($value);
        // dd($enteredotp);
        if($enteredotp != $value) {
            return redirect()->route('complaint-tracking')->with('error', 'Entered OTP is not valid');

        } else {

            $complaintdetails = RegisterComplaint::where('id',$complaint_id)->get();

            $complaintstatus = ComplaintHistory::where('complaint_id',$complaint_id)
                            ->orderBy('created_at','desc')
                            ->get();

            // dd($complaintstatus);

            return view('userpanel.complaint_status', compact('complaintdetails','complaintstatus'));
        }

    }

    // public function complaintstatus(Request $request)
    // {

    //     return view('userpanel.complaint_status');

    // }


}
