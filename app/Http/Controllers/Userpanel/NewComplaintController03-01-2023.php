<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Models\City;
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
use App\Models\LabourOfficeCityDetail;
use App\Models\ComplaintCategoryDetail;
use App\Models\BusinessNature;
use Carbon\Carbon;
use DataTables;
use Mail;
use App\Models\MailTemplate;
use App\Library\MobitelSms;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\DB;

class NewComplaintController extends Controller
{
    public function index()
    {
        $provinces = Province::where('status','Y')
                    ->where('is_delete','0')
                    ->get();

        $districts = District::where('status','Y')
                    ->where('is_delete','0')
                    ->orderBy('district_name_en', 'ASC')
                    ->get();

        $cities = City::where('status','Y')
                    ->where('is_delete','0')
                    ->orderBy('city_name_en', 'ASC')
                    ->get();

        $establishmenttypes = EstablishmentType::where('status','Y')
                            ->where('is_delete','0')
                            ->orderBy('establishment_name_en', 'ASC')
                            ->get();

        $complaincategories = Complain_Category::where('status','Y')
                                                ->orderBy('order', 'ASC')
                                                ->get();

        $labouroffices = LabourOfficeDivision::where('status','Y')
                        ->where('is_delete','0')
                        ->get();


        $businessnatures = BusinessNature::where('status', 'Y')
                                            ->where('is_delete', '0')
                                            ->orderBy('business_nature_en', 'ASC')
                                            ->get();

        /* no need to assign complaint number on page load
          * comment by thushara
          *
        $result = RegisterComplaint::select('id', 'created_at', 'external_ref_no')
        ->orderby('id', 'desc')
        ->first();

        if (!empty($result)) {
            $refNo = $result->external_ref_no;
            $arrSerial = explode('/', $refNo);
            if ($arrSerial) {
                $oldYear = $arrSerial[1];
                $oldRef = $arrSerial[2];
            }

        } else {
            $oldYear = Carbon::now()->format('Y');
            $oldRef = 0;
        }

        $newYear = Carbon::now()->format('Y');

        if ($oldYear != $newYear) {
            $newRef = 0;

            $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

            $number_part1 = 'COM';
            $new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

        } else {
            $newRef = str_pad((int) $oldRef + 1, 5, '0', STR_PAD_LEFT);
            $number_part1 = 'COM';
            $new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;
        }
        */
        // $maxRefId = RegisterComplaint::max('id');
        // $referenceCode = "COM".str_pad(($maxRefId+1), 6, "0", STR_PAD_LEFT);

        return view('userpanel.new_complaint', compact('provinces','districts','cities','establishmenttypes','complaincategories','labouroffices','businessnatures'));
    }

    public function getCityFront(Request $request)
    {
        // $cities = City::where("district_id", $request->district_id)
        //                     ->where('status', 'Y')
        //                     ->where('is_delete', '0')
        //                     ->pluck("city_name_en", "id");
        // return response()->json($cities);
        $cities = City::where('district_id', $request->district_id)
                        ->where('status','Y')
                        ->where('is_delete','0')
                        ->orderBy('city_name_en', 'ASC')
                        ->get();
        return response()->json($cities);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            // 'ref_no' => 'required',
            'complainant_identify_no' => 'required',
            'complainant_f_name' => 'required',
            'complainant_l_name' => 'required',
            // 'complainant_full_name' => 'required',
            'complainant_address' => 'required',
            // 'complainant_email' => 'required',
            'employer_name' => 'required',
            'employer_address' => 'required',
            // 'complain_category' => 'required',
            // 'province_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'g-recaptcha-response' => 'recaptcha',
        ]);

        if($request->complainant_mobile != "") {
            $request->validate([
                'complainant_mobile' => 'min:10|max:15',
            ]);
        }
        if($request->complainant_tel != "") {
            $request->validate([
                'complainant_tel' => 'min:10|max:15',
            ]);
        }
        if($request->employer_tel != "") {
            $request->validate([
                'employer_tel' => 'min:10|max:15',
            ]);
        }
        if($request->current_employer_tel != "") {
            $request->validate([
                'current_employer_tel' => 'min:10|max:15',
            ]);
        }

        $category_array = [];
        $epf_category_array = [];
        // $child_category_array = [];
        // $women_category_array = [];
        // $termination_category_array = [];
        $other_category_array = [];
        $category_array = $request->complain_category_id;
        foreach ($category_array as $key => $value) {
            // if (in_array($value,array(4,6,7,12,15,16,20))) {
                if (in_array($value,array(15))) {
                    array_push($epf_category_array, $value);
                // }
                // if ($value == 7) {
                //     array_push($women_category_array, $value);
                // }
                // if ($value == 4) {
                //     array_push($termination_category_array, $value);
                // }
                // if (in_array($value,array(12,16,20))) {
                //     array_push($child_category_array, $value);
                // }
               //unset($category_array[$key]);
            } else {
                array_push($other_category_array, $value);
            }
        }

        $request->complain_category_id = $other_category_array;
        //dd($other_category_array);

        $districts = District::where('id', $request->district_id)->first();

        // dd($districts);

        if(!empty($request->complain_category_id)){

            $cityInfo = LabourOfficeCityDetail::select('labour_office_city_details.office_code','labour_office_city_details.office_id')->join('labour_offices_divisions','labour_offices_divisions.id', '=', 'labour_office_city_details.office_id')->where('labour_office_city_details.city_id', $request->city_id)->where('labour_offices_divisions.office_type_id', 4)->first();

            if (!empty($cityInfo)) {
                $office_code = $cityInfo->office_code;
                $office_id = $cityInfo->office_id;
            } else {
                $office_code = NULL;
                $office_id = NULL;
            }

            $cityInfo = LabourOfficeCityDetail::select('labour_office_city_details.office_code','labour_office_city_details.office_id')->join('labour_offices_divisions','labour_offices_divisions.id', '=', 'labour_office_city_details.office_id')->where('labour_office_city_details.city_id', $request->city_id)->where('labour_offices_divisions.office_type_id', 4)->first();

            if (!empty($cityInfo)) {
                $office_code = $cityInfo->office_code;
            } else {
                $office_code = NULL;
            }

            $complaint = new RegisterComplaint;

            $complaint->comp_type = $request->comp_type;
            $complaint->pref_lang = $request->pref_lang;
            // $complaint->ref_no = $request->ref_no;
            // $complaint->external_ref_no = $request->external_ref_no;
            $complaint->complainant_identify_no = $request->complainant_identify_no;
            $complaint->title = $request->title;
            $complaint->complainant_l_name = $request->complainant_l_name;

            if($request->comp_type == "U"){
                $complaint->complainant_full_name = $request->complainant_f_name.' '.$request->complainant_l_name;
            } else {
                $complaint->complainant_full_name = $request->complainant_full_name;
            }

            $complaint->complainant_dob = $request->complainant_dob;
            $complaint->complainant_gender = $request->complainant_gender;
            $complaint->nationality = $request->nationality;
            $complaint->complainant_email = $request->complainant_email;
            $complaint->complainant_mobile = $request->complainant_mobile;
            $complaint->complainant_tel = $request->complainant_tel;
            $complaint->union_name = $request->union_name;
            $complaint->union_address = $request->union_address;
            $complaint->province_id = $districts->province_id;
            $complaint->district_id = $request->district_id;
            $complaint->city_id = $request->city_id;
            $complaint->employer_tel = $request->employer_tel;
            $complaint->business_nature_id = $request->business_nature;
            $complaint->establishment_type_id = $request->establishment_type_id;
            $complaint->establishment_reg_no = $request->establishment_reg_no;
            $complaint->employer_no = $request->employer_no;
            // $complaint->ppe_no = $request->ppe_no;
            $complaint->employee_no = $request->employee_no;
            $complaint->epf_no = $request->epf_no;
            $complaint->employee_mem_no = $request->employee_mem_no;
            $complaint->designation = $request->designation;
            $complaint->join_date = $request->join_date;
            $complaint->terminate_date = $request->terminate_date;
            $complaint->last_sal_date = $request->last_sal_date;
            // $complaint->basic_sal = $request->basic_sal;
            // $complaint->allowance = $request->allowance;
            $complaint->submitted_office = $request->submitted_office;
            $complaint->submitted_date = $request->submitted_date;
            $complaint->case_no = $request->case_no;
            $complaint->received_relief = $request->received_relief;
            $complaint->complain_purpose = $request->complain_purpose;
            $complaint->current_employer_tel = $request->current_employer_tel;
            $complaint->complainant_f_name = $request->complainant_f_name;
            $complaint->complainant_address = $request->complainant_address;
            $complaint->employer_name = $request->employer_name;
            $complaint->employer_address = $request->employer_address;
            $complaint->current_employer_name = $request->current_employer_name;
            $complaint->current_employer_address = $request->current_employer_address;

            $allowance = $request->allowance;
            $basicsal = $request->basic_sal;
            $complaint->worked_employees = $request->worked_employees;

            $floatallowance = str_replace(',', '', $allowance);
            $floatbasicsal = str_replace(',', '', $basicsal);

            if(!empty($floatbasicsal)) {
                $complaint->basic_sal = $floatbasicsal;
            }

            if(!empty($floatallowance)) {
                $complaint->allowance = $floatallowance;
            }

            if($request->pref_lang == "SI") {

                $complaint->complainant_f_name_si = $request->complainant_f_name;
                $complaint->complainant_f_name_ta = "N/A";
                $complaint->complainant_address_si = $request->complainant_address;
                $complaint->complainant_address_ta = "N/A";
                $complaint->employer_name_si = $request->employer_name;
                $complaint->employer_name_ta = "N/A";
                $complaint->employer_address_si = $request->employer_address;
                $complaint->employer_address_ta = "N/A";
                $complaint->current_employer_name_si = $request->current_employer_name;
                $complaint->current_employer_name_ta = "N/A";
                $complaint->current_employer_address_si = $request->current_employer_address;
                $complaint->current_employer_address_ta = "N/A";
                $complaint->complainant_l_name_si = $request->complainant_l_name;
                $complaint->complainant_l_name_ta = "N/A";

            } else if($request->pref_lang == "TA") {

                $complaint->complainant_f_name_ta = $request->complainant_f_name;
                $complaint->complainant_f_name_si = "N/A";
                $complaint->complainant_address_ta = $request->complainant_address;
                $complaint->complainant_address_si = "N/A";
                $complaint->employer_name_ta = $request->employer_name;
                $complaint->employer_name_si = "N/A";
                $complaint->employer_address_ta = $request->employer_address;
                $complaint->employer_address_si = "N/A";
                $complaint->current_employer_name_si = "N/A";
                $complaint->current_employer_name_ta = $request->current_employer_name;
                $complaint->current_employer_address_si = "N/A";
                $complaint->current_employer_address_ta = $request->current_employer_address;
                $complaint->complainant_l_name_ta = $request->complainant_l_name;
                $complaint->complainant_l_name_si = "N/A";

            } else {
                $complaint->complainant_f_name_si = "N/A";
                $complaint->complainant_f_name_ta = "N/A";
                $complaint->complainant_address_si = "N/A";
                $complaint->complainant_address_ta = "N/A";
                $complaint->employer_name_ta = "N/A";
                $complaint->employer_name_si = "N/A";
                $complaint->employer_address_ta = "N/A";
                $complaint->employer_address_si = "N/A";
                $complaint->current_employer_name_si = "N/A";
                $complaint->current_employer_name_ta = "N/A";
                $complaint->current_employer_address_si = "N/A";
                $complaint->current_employer_address_ta = "N/A";
                $complaint->complainant_l_name_si = "N/A";
                $complaint->complainant_l_name_ta = "N/A";

            }

            if($request->complain_category_id != '') {
                $categoryarr = implode(',', $request->complain_category_id);
                $complaint->complain_category = $category['complain_category_id']=  $categoryarr;
            }

            if($complaint->submitted_office != ""){
                $complaint->is_available = 1;
            } else {
                $complaint->is_available = 0;
            }

            $category_prefix = "";
            if ($request->complain_category_id != '') {
                if (count($request->complain_category_id) > 1) {
                    $category_prefix = "M";
                } else {
                    $categoryInfo = Complain_Category::where('id', $request->complain_category_id[0])->first();

                    if (!empty($categoryInfo)) {
                        $category_prefix = $categoryInfo->category_prefix;
                    } else {
                        $category_prefix = NULL;
                    }
                }
                $categoryarr = implode(',', $request->complain_category_id);
                $complaint->complain_category = $category['complain_category_id'] =  $categoryarr;
            }


            $result = RegisterComplaint::select('id', 'created_at', 'external_ref_no')
                ->orderby('id', 'desc')
                ->first();

                if (!empty($result)) {
                    // dd($result->external_ref_no);
                    $refNo = $result->external_ref_no;
                    $arrSerial = explode('/', $refNo);
                    if ($arrSerial) {
                        $oldYear = $arrSerial[1];
                        $oldRef = $arrSerial[2];
                    }
                } else {
                    $oldYear = Carbon::now()->format('Y');
                    $oldRef = 0;
                }

            $newYear = Carbon::now()->format('Y');

            if ($oldYear != $newYear) {
                $newRef = 0;

                $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

                $number_part1 = 'COM';
                $new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

                $checkDuplicate = RegisterComplaint::where('external_ref_no', $new_complaint_no)->count();

                if($checkDuplicate > 0) {

                    $maxrec = RegisterComplaint::max('external_ref_no');

                    $splitref = explode('/', $maxrec);

                    $refno = $splitref[2];

                    $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

                    $new_complaint_no = $number_part1 . '/' . date('Y'). '/' . $newRef;
                }

            } else {
                $newRef = str_pad((int) $oldRef + 1, 5, '0', STR_PAD_LEFT);
                $number_part1 = 'COM';
                $new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

                $checkDuplicate = RegisterComplaint::where('external_ref_no', $new_complaint_no)->count();

                if($checkDuplicate > 0) {

                    $maxrec = RegisterComplaint::max('external_ref_no');

                    $splitref = explode('/', $maxrec);

                    $refno = $splitref[2];

                    $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

                    $new_complaint_no = $number_part1 . '/' . date('Y'). '/' . $newRef;
                }
            }

            $result2 = RegisterComplaint::select('id', 'created_at', 'ref_no')
                ->where('ref_no', 'LIKE', '%'.$office_code.'%')
                ->orderby('id', 'desc')
                ->first();

            if (!empty($result2)) {
                //dd($result2->ref_no);
                $refNo2 = $result2->ref_no;
                $arrSerial2 = explode('/', $refNo2);
                if ($arrSerial2) {
                    $oldYear2 = substr($arrSerial2[4], 0, 4);
                    $oldRef2 = $arrSerial2[5];
                }
            } else {
                $oldRef2 = 0;
            }

            $oldYear2 = Carbon::now()->format('Y');
            $number_part3 = "00";

            if ($oldYear2 != $newYear) {
                $newRef = 0;

                $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

                $number_part1 = 'COM';

                $internal_number = $office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;

                $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

                if($checkDuplicate > 0) {
                    $this->makeNewRef($office_code,$newRef);

                    $newRef = $this->makeNewRef($office_code, $newRef);

                    if($newRef != '') {
                        $internal_number = $office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                    }
                }

            } else {
                $newRef = str_pad((int) $oldRef2 + 1, 5, '0', STR_PAD_LEFT);
                $number_part1 = 'COM';
                $internal_number = $office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

                if($checkDuplicate > 0) {
                    $this->makeNewRef($office_code,$newRef);

                    $newRef = $this->makeNewRef($office_code, $newRef);

                    if($newRef != '') {
                        $internal_number = $office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                    }
                }
            }

            $complaint->external_ref_no = $new_complaint_no;
            $complaint->ref_no = $internal_number;
            $complaint->complaint_status = 'New';
            $complaint->action_type = 'Pending';
            $complaint->current_office_id = $office_id;
            $complaint->save();

            $countcat = count($request->complain_category_id);

            for ($i = 0; $i < $countcat; $i++) {

                $complaincategorydetail = new ComplaintCategoryDetail();
                $complaincategorydetail->complaint_id = $complaint->id;
                $complaincategoryid = $request->complain_category_id[$i];

                $categoriesexpdate = Complain_Category::select('expiry_days')->where('id', $complaincategoryid)->first();

                $expdayscount = Carbon::today()->addDays($categoriesexpdate->expiry_days);

                $expirydate = $expdayscount->toDateString();

                $complaincategorydetail->category_id = $request->complain_category_id[$i];

                $complaincategorydetail->expiry_date = $expirydate;

                $complaincategorydetail->save();
            }

            $id = $complaint->id;

            $insert['complaint_id'] = $complaint->id;
            $insert['status'] = 'New';
            $insert['sent_from_office'] = $office_id;
            $insert['sent_from_office_code'] = $office_code;
            $insert['sent_to_office'] = NULL;
            $insert['sent_to_office_code'] = NULL;
            $insert['action_type'] = 'New';
            $insert['remark'] = '';
            $insert['show_status'] = 'Ext';
            $insert['assigned_lo_id'] = NULL;
            $insert['forward_type_id'] = 0;
            $insert['user_id'] = 0;

            if($request->pref_lang == "SI") {
                $insert['status_des'] = 'ඔබගේ පැමිණිල්ල සාර්ථකව ලැබුණා. පැමිණිලි ක්‍රියාවලිය ඉක්මණින් ආරම්භ වනු ඇත';
            } else if($request->pref_lang == "TA") {
                $insert['status_des'] = 'உங்கள் முறைப்பாடு வெற்றிகரமாக பெறப்பட்டது. முறைப்பாட்டு செயன்முறை விரைவில் ஆரம்பமாகும் ';
            } else {
                $insert['status_des'] = 'Your Complain received successfully. Complain process will start soon';
            }

            ComplaintHistory::insert($insert);

            $validatedData = $request->validate([
                // 'files' => 'required',
                'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
            ]);


            if($request->hasfile('files'))
            {
                $insert = array();
                foreach($request->file('files') as $key => $file)
                {
                    $path = $file->store('public/files');
                    $name = $file->getClientOriginalName();

                $ref_no = $complaint->id;
                $insert[$key]['ref_no'] = $ref_no;
                $insert[$key]['file_name'] = $path;
                $insert[$key]['description'] = $name;
                }
                //dd($insert);exit();
                ComplaintDocument::insert($insert);
            }

            if($request->comp_type == "U" && $request->union_officer_name != "" && $request->union_officer_name != null) {
                $count = count($request->union_officer_name)-1;

                for ($i=0; $i < $count; $i++) {

                $unionofficerdetail = new UnionOfficerDetail();
                $unionofficerdetail->ref_id = $complaint->id;
                $unionofficerdetail->officer_name = $request->union_officer_name[$i];
                $unionofficerdetail->officer_address = $request->union_officer_address[$i];
                $unionofficerdetail->save();
                }
            }

            $regdata = RegisterComplaint::where('id', $id)
                    ->get();

            $officename = LabourOfficeDivision::where('id', $office_id)->first();

            if($request->complainant_email != ''){

                $mailitem = MailTemplate::where('status', 'Y')
                        ->where('is_delete', 0)
                        ->where('id', 1)
                        ->get();
                //dd();exit();
                //\App::setLocale($regdata[0]->pref_lang);

                if($regdata[0]->pref_lang == 'EN'){
                    $e_sub = $mailitem[0]->mail_template_name_en;
                    // $e_body = $mailitem[0]->body_content_en;
                    $e_name = $mailitem[0]->mail_template_name_en;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

                    $email_body = 'Dear'.' '.$request->complainant_f_name.', '.$e_body;

                } else if($regdata[0]->pref_lang == 'SI'){

                    $e_sub = $mailitem[0]->mail_template_name_sin;
                    // $e_body = $mailitem[0]->body_content_sin;
                    $e_name = $mailitem[0]->mail_template_name_sin;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name,$request->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

                    $email_body = 'හිතවත්'.' '.$request->complainant_f_name.', '.$e_body;

                } else if($regdata[0]->pref_lang == 'TA'){

                    $e_sub = $mailitem[0]->mail_template_name_tam;
                    // $e_body = $mailitem[0]->body_content_tam;
                    $e_name = $mailitem[0]->mail_template_name_tam;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name,$request->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

                    $email_body = 'அன்பார்ந்த'.' '.$request->complainant_f_name.', '.$e_body;
                }

                $mail_body_content = strip_tags($email_body);

                \Mail::send('mail.complaint-mail',
                    array(
                    'ref_no' => $regdata[0]->external_ref_no,
                    'date' => $regdata[0]->created_at,
                    'name' => $request->complainant_f_name,
                    'subject' => $e_sub,
                    'body' => $mail_body_content,
                    ), function($message) use ($e_name, $regdata)
                {
                    $message->from('cms@labourdept.gov.lk');
                    $message->to($regdata[0]->complainant_email)->subject($e_name);
                });

                // \EmailLog::addToLog($request->complainant_f_name, $regdata[0]->complainant_email, $e_sub, $mail_body_content);
            }

            if($request->complainant_mobile != ''){

                $smsitem = SmsTemplate::where('status', 'Y')
                    ->where('is_delete', 0)
                    ->where('id', 1)
                    ->get();

                $complainant_f_name = $request->complainant_f_name;

                $catlist = $request->complain_category_id;

                $TLMessageEn = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
                $TLMessageSi = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
                $TLMessageTa = "உங்களது புகார் மீண்டும் பணியமர்த்தல் அல்லது சேவையை நிறுத்துவது தொடர்பாக இழப்பீடு வழங்குவதாக இருந்தால், கடைசி வேலை நாளிலிருந்து 06 மாதங்களுக்குள் சம்பந்தப்பட்ட தொழிலாளர் தீர்ப்பாயத்தில் புகார் அளிக்குமாறும் உங்களுக்குத் தெரிவிக்கிறேன்.";

                if($regdata[0]->pref_lang == 'EN'){
                    $s_sub = $smsitem[0]->sms_template_name_en;
                    // $s_body = $smsitem[0]->body_content_en;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

                    if(in_array("4", $catlist)) {

                        $sms_body = $s_body.' '.$TLMessageEn;

                    } else {

                        $sms_body = $s_body;

                    }

                } else if($regdata[0]->pref_lang == 'SI'){
                    $s_sub = $smsitem[0]->sms_template_name_sin;
                    // $s_body = $smsitem[0]->body_content_sin;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name_si,$request->complainant_address];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

                    if(in_array("4", $catlist)) {

                        $sms_body = $s_body.' '.$TLMessageSi;

                    } else {

                        $sms_body = $s_body;

                    }

                } else if($regdata[0]->pref_lang == 'TA'){
                    $s_sub = $smsitem[0]->sms_template_name_tam;
                    // $s_body = $smsitem[0]->body_content_tam;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name_ta,$request->complainant_address];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                    if(in_array("4", $catlist)) {

                        $sms_body = $s_body.' '.$TLMessageTa;

                    } else {

                        $sms_body = $s_body;

                    }
                }

                $mobitelSms = new MobitelSms();
                $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
                $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($request->complainant_mobile),0);
                $mobitelSms->closeSession($session);

                \SmsLog::addToLog($request->complainant_f_name, $request->complainant_mobile, $sms_body);
            }
        }

        // For EPF complaint categories
        if(!empty($epf_category_array)){

            $complaint = new RegisterComplaint;

            $complaint->comp_type = $request->comp_type;
            $complaint->pref_lang = $request->pref_lang;
            // $complaint->ref_no = $request->ref_no;
            // $complaint->external_ref_no = $request->external_ref_no;
            $complaint->complainant_identify_no = $request->complainant_identify_no;
            $complaint->title = $request->title;
            $complaint->complainant_l_name = $request->complainant_l_name;

            if($request->comp_type == "U"){
                $complaint->complainant_full_name = $request->complainant_f_name.' '.$request->complainant_l_name;
            } else {
                $complaint->complainant_full_name = $request->complainant_full_name;
            }

            $complaint->complainant_dob = $request->complainant_dob;
            $complaint->complainant_gender = $request->complainant_gender;
            $complaint->nationality = $request->nationality;
            $complaint->complainant_email = $request->complainant_email;
            $complaint->complainant_mobile = $request->complainant_mobile;
            $complaint->complainant_tel = $request->complainant_tel;
            $complaint->union_name = $request->union_name;
            $complaint->union_address = $request->union_address;
            $complaint->province_id = $districts->province_id;
            $complaint->district_id = $request->district_id;
            $complaint->city_id = $request->city_id;
            $complaint->employer_tel = $request->employer_tel;
            $complaint->business_nature_id = $request->business_nature;
            $complaint->establishment_type_id = $request->establishment_type_id;
            $complaint->establishment_reg_no = $request->establishment_reg_no;
            $complaint->employer_no = $request->employer_no;
            // $complaint->ppe_no = $request->ppe_no;
            $complaint->employee_no = $request->employee_no;
            $complaint->epf_no = $request->epf_no;
            $complaint->employee_mem_no = $request->employee_mem_no;
            $complaint->designation = $request->designation;
            $complaint->join_date = $request->join_date;
            $complaint->terminate_date = $request->terminate_date;
            $complaint->last_sal_date = $request->last_sal_date;
            // $complaint->basic_sal = $request->basic_sal;
            // $complaint->allowance = $request->allowance;
            $complaint->submitted_office = $request->submitted_office;
            $complaint->submitted_date = $request->submitted_date;
            $complaint->case_no = $request->case_no;
            $complaint->received_relief = $request->received_relief;
            $complaint->complain_purpose = $request->complain_purpose;
            $complaint->current_employer_tel = $request->current_employer_tel;
            $complaint->complainant_f_name = $request->complainant_f_name;
            $complaint->complainant_address = $request->complainant_address;
            $complaint->employer_name = $request->employer_name;
            $complaint->employer_address = $request->employer_address;
            $complaint->current_employer_name = $request->current_employer_name;
            $complaint->current_employer_address = $request->current_employer_address;

            $allowance = $request->allowance;
            $basicsal = $request->basic_sal;
            $complaint->worked_employees = $request->worked_employees;

            $floatallowance = str_replace(',', '', $allowance);
            $floatbasicsal = str_replace(',', '', $basicsal);

            if(!empty($floatbasicsal)) {
                $complaint->basic_sal = $floatbasicsal;
            }

            if(!empty($floatallowance)) {
                $complaint->allowance = $floatallowance;
            }

            if($request->pref_lang == "SI") {

                $complaint->complainant_f_name_si = $request->complainant_f_name;
                $complaint->complainant_f_name_ta = "N/A";
                $complaint->complainant_address_si = $request->complainant_address;
                $complaint->complainant_address_ta = "N/A";
                $complaint->employer_name_si = $request->employer_name;
                $complaint->employer_name_ta = "N/A";
                $complaint->employer_address_si = $request->employer_address;
                $complaint->employer_address_ta = "N/A";
                $complaint->current_employer_name_si = $request->current_employer_name;
                $complaint->current_employer_name_ta = "N/A";
                $complaint->current_employer_address_si = $request->current_employer_address;
                $complaint->current_employer_address_ta = "N/A";

            } else if($request->pref_lang == "TA") {

                $complaint->complainant_f_name_ta = $request->complainant_f_name;
                $complaint->complainant_f_name_si = "N/A";
                $complaint->complainant_address_ta = $request->complainant_address;
                $complaint->complainant_address_si = "N/A";
                $complaint->employer_name_ta = $request->employer_name;
                $complaint->employer_name_si = "N/A";
                $complaint->employer_address_ta = $request->employer_address;
                $complaint->employer_address_si = "N/A";
                $complaint->current_employer_name_si = "N/A";
                $complaint->current_employer_name_ta = $request->current_employer_name;
                $complaint->current_employer_address_si = "N/A";
                $complaint->current_employer_address_ta = $request->current_employer_address;

            } else {
                $complaint->complainant_f_name_si = "N/A";
                $complaint->complainant_f_name_ta = "N/A";
                $complaint->complainant_address_si = "N/A";
                $complaint->complainant_address_ta = "N/A";
                $complaint->employer_name_ta = "N/A";
                $complaint->employer_name_si = "N/A";
                $complaint->employer_address_ta = "N/A";
                $complaint->employer_address_si = "N/A";
                $complaint->current_employer_name_si = "N/A";
                $complaint->current_employer_name_ta = "N/A";
                $complaint->current_employer_address_si = "N/A";
                $complaint->current_employer_address_ta = "N/A";

            }

            $epf_office_code = 'EPF';
            $epf_office_id = 13;

            if($epf_category_array != '') {
                $categoryarr = implode(',', $epf_category_array);
                $complaint->complain_category = $category['epf_category_array']=  $categoryarr;
            }

            if($complaint->submitted_office != ""){
                $complaint->is_available = 1;
            } else {
                $complaint->is_available = 0;
            }

            $category_prefix = "";
            if ($epf_category_array != '') {
                if (count($epf_category_array) > 1) {
                    $category_prefix = "M";
                } else {
                    $categoryInfo = Complain_Category::where('id', $epf_category_array[0])->first();

                    if (!empty($categoryInfo)) {
                        $category_prefix = $categoryInfo->category_prefix;
                    } else {
                        $category_prefix = NULL;
                    }
                }
                $categoryarr = implode(',', $epf_category_array);
                $complaint->complain_category = $category['epf_category_array'] =  $categoryarr;
            }

            $result = RegisterComplaint::select('id', 'created_at', 'external_ref_no')
            ->orderby('id', 'desc')
            ->first();

            if (!empty($result)) {
                // dd($result->external_ref_no);
                $refNo = $result->external_ref_no;
                $arrSerial = explode('/', $refNo);
                if ($arrSerial) {
                    $oldYear = $arrSerial[1];
                    $oldRef = $arrSerial[2];
                }
            } else {
                $oldYear = Carbon::now()->format('Y');
                $oldRef = 0;
            }

            $newYear = Carbon::now()->format('Y');

            if ($oldYear != $newYear) {
                $newRef = 0;

                $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

                $number_part1 = 'COM';
                $epf_new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

                $checkDuplicate = RegisterComplaint::where('external_ref_no', $epf_new_complaint_no)->count();

                if($checkDuplicate > 0) {

                    $maxrec = RegisterComplaint::max('external_ref_no');

                    $splitref = explode('/', $maxrec);

                    $refno = $splitref[2];

                    $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

                    $epf_new_complaint_no = $number_part1 . '/' . date('Y'). '/' . $newRef;
                }

            } else {
                $newRef = str_pad((int) $oldRef + 1, 5, '0', STR_PAD_LEFT);
                $number_part1 = 'COM';
                $epf_new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

                $checkDuplicate = RegisterComplaint::where('external_ref_no', $epf_new_complaint_no)->count();

                if($checkDuplicate > 0) {

                    $maxrec = RegisterComplaint::max('external_ref_no');

                    $splitref = explode('/', $maxrec);

                    $refno = $splitref[2];

                    $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

                    $epf_new_complaint_no = $number_part1 . '/' . date('Y'). '/' . $newRef;
                }
            }

            $result2 = RegisterComplaint::select('id', 'created_at', 'ref_no')
                                        ->where('ref_no', 'LIKE', '%'.$epf_office_code.'%')
                                        ->orderby('id', 'desc')
                                        ->first();

            if (!empty($result2)) {
                //dd($result2->ref_no);
                $refNo2 = $result2->ref_no;
                $arrSerial2 = explode('/', $refNo2);
                if ($arrSerial2) {
                    $oldYear2 = substr($arrSerial2[4], 0, 4);
                    $oldRef2 = $arrSerial2[5];
                }
            } else {
                $oldRef2 = 0;
            }

            $number_part3 = "00";

            $oldYear2 = Carbon::now()->format('Y');

            if ($oldYear2 != $newYear) {
                $newRef = 0;

                $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

                $number_part1 = 'COM';

                $internal_number = $epf_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;

                $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $epf_office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

                if($checkDuplicate > 0) {
                    $this->makeNewRef($epf_office_code,$newRef);

                    $newRef = $this->makeNewRef($epf_office_code, $newRef);

                    if($newRef != '') {
                        $internal_number = $epf_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                    }
                }

            } else {
                $newRef = str_pad((int) $oldRef2 + 1, 5, '0', STR_PAD_LEFT);
                $number_part1 = 'COM';
                $internal_number = $epf_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $epf_office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

                if($checkDuplicate > 0) {
                    $this->makeNewRef($epf_office_code,$newRef);

                    $newRef = $this->makeNewRef($epf_office_code, $newRef);

                    if($newRef != '') {
                        $internal_number = $epf_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                    }
                }
            }

            $complaint->external_ref_no = $epf_new_complaint_no;
            $complaint->ref_no = $internal_number;
            $complaint->complaint_status = 'New';
            $complaint->action_type = 'Pending';
            $complaint->current_office_id = $epf_office_id;
            $complaint->save();

            $epf_countcat = count($epf_category_array);

            for ($i = 0; $i < $epf_countcat; $i++) {

                $complaincategorydetail = new ComplaintCategoryDetail();
                $complaincategorydetail->complaint_id = $complaint->id;
                $complaincategoryid = $epf_category_array[$i];

                $categoriesexpdate = Complain_Category::select('expiry_days')->where('id', $complaincategoryid)->first();

                $expdayscount = Carbon::today()->addDays($categoriesexpdate->expiry_days);

                $expirydate = $expdayscount->toDateString();

                $complaincategorydetail->category_id = $epf_category_array[$i];

                $complaincategorydetail->expiry_date = $expirydate;

                $complaincategorydetail->save();
            }

            $id = $complaint->id;

            $epf_insert['complaint_id'] = $complaint->id;
            $epf_insert['status'] = 'New';
            $epf_insert['sent_from_office'] = $epf_office_id;
            $epf_insert['sent_from_office_code'] = $epf_office_code;
            $epf_insert['sent_to_office'] = NULL;
            $epf_insert['sent_to_office_code'] = NULL;
            $epf_insert['action_type'] = 'New';
            $epf_insert['remark'] = '';
            $epf_insert['show_status'] = 'Ext';
            $epf_insert['assigned_lo_id'] = NULL;
            $epf_insert['forward_type_id'] = 0;
            $epf_insert['user_id'] = 0;

            if($request->pref_lang == "SI") {
                $epf_insert['status_des'] = 'ඔබගේ පැමිණිල්ල සාර්ථකව ලැබුණා. පැමිණිලි ක්‍රියාවලිය ඉක්මණින් ආරම්භ වනු ඇත';
            } else if($request->pref_lang == "TA") {
                $epf_insert['status_des'] = 'உங்கள் முறைப்பாடு வெற்றிகரமாக பெறப்பட்டது. முறைப்பாட்டு செயன்முறை விரைவில் ஆரம்பமாகும் ';
            } else {
                $epf_insert['status_des'] = 'Your Complain received successfully. Complain process will start soon';
            }

            ComplaintHistory::insert($epf_insert);

            $validatedData = $request->validate([
                // 'files' => 'required',
                'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
            ]);


            if($request->hasfile('files'))
            {
                $insert = array();
                foreach($request->file('files') as $key => $file)
                {
                    $path = $file->store('public/files');
                    $name = $file->getClientOriginalName();

                $ref_no = $complaint->id;
                $insert[$key]['ref_no'] = $ref_no;
                $insert[$key]['file_name'] = $path;
                $insert[$key]['description'] = $name;
                }
                //dd($insert);exit();
                ComplaintDocument::insert($insert);
            }

            if($request->comp_type == "U" && $request->union_officer_name != "" && $request->union_officer_name != null) {
                $count = count($request->union_officer_name)-1;

                for ($i=0; $i < $count; $i++) {

                $unionofficerdetail = new UnionOfficerDetail();
                $unionofficerdetail->ref_id = $complaint->id;
                $unionofficerdetail->officer_name = $request->union_officer_name[$i];
                $unionofficerdetail->officer_address = $request->union_officer_address[$i];
                $unionofficerdetail->save();
                }
            }

            $regdata = RegisterComplaint::where('id', $id)
                ->get();

            $officename = LabourOfficeDivision::where('id', $epf_office_id)->first();

            if($request->complainant_email != ''){

                $mailitem = MailTemplate::where('status', 'Y')
                        ->where('is_delete', 0)
                        ->where('id', 1)
                        ->get();
                //dd();exit();
                //\App::setLocale($regdata[0]->pref_lang);

                if($regdata[0]->pref_lang == 'EN'){
                    $e_sub = $mailitem[0]->mail_template_name_en;
                    // $e_body = $mailitem[0]->body_content_en;
                    $e_name = $mailitem[0]->mail_template_name_en;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$epf_new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

                    $email_body = 'Dear'.' '.$request->complainant_f_name.', '.$e_body;

                } else if($regdata[0]->pref_lang == 'SI'){

                    $e_sub = $mailitem[0]->mail_template_name_sin;
                    // $e_body = $mailitem[0]->body_content_sin;
                    $e_name = $mailitem[0]->mail_template_name_sin;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$epf_new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name,$request->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

                    $email_body = 'හිතවත්'.' '.$request->complainant_f_name.', '.$e_body;

                } else if($regdata[0]->pref_lang == 'TA'){

                    $e_sub = $mailitem[0]->mail_template_name_tam;
                    // $e_body = $mailitem[0]->body_content_tam;
                    $e_name = $mailitem[0]->mail_template_name_tam;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$epf_new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name,$request->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

                    $email_body = 'அன்பார்ந்த'.' '.$request->complainant_f_name.', '.$e_body;
                }

                $mail_body_content = strip_tags($email_body);

                \Mail::send('mail.complaint-mail',
                    array(
                    'ref_no' => $regdata[0]->external_ref_no,
                    'date' => $regdata[0]->created_at,
                    'name' => $request->complainant_f_name,
                    'subject' => $e_sub,
                    'body' => $mail_body_content,
                    ), function($message) use ($e_name, $regdata)
                {
                    $message->from('cms@labourdept.gov.lk');
                    $message->to($regdata[0]->complainant_email)->subject($e_name);
                });

                // \EmailLog::addToLog($request->complainant_f_name, $regdata[0]->complainant_email, $e_sub, $mail_body_content);
            }

            if($request->complainant_mobile != ''){

                $smsitem = SmsTemplate::where('status', 'Y')
                    ->where('is_delete', 0)
                    ->where('id', 1)
                    ->get();

                $complainant_f_name = $request->complainant_f_name;

                $catlist = $request->complain_category_id;

                $TLMessageEn = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
                $TLMessageSi = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
                $TLMessageTa = "உங்களது புகார் மீண்டும் பணியமர்த்தல் அல்லது சேவையை நிறுத்துவது தொடர்பாக இழப்பீடு வழங்குவதாக இருந்தால், கடைசி வேலை நாளிலிருந்து 06 மாதங்களுக்குள் சம்பந்தப்பட்ட தொழிலாளர் தீர்ப்பாயத்தில் புகார் அளிக்குமாறும் உங்களுக்குத் தெரிவிக்கிறேன்.";

                if($regdata[0]->pref_lang == 'EN'){
                    $s_sub = $smsitem[0]->sms_template_name_en;
                    // $s_body = $smsitem[0]->body_content_en;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$epf_new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

                    if(in_array("4", $catlist)) {

                        $sms_body = $s_body.' '.$TLMessageEn;

                    } else {

                        $sms_body = $s_body;

                    }

                } else if($regdata[0]->pref_lang == 'SI'){
                    $s_sub = $smsitem[0]->sms_template_name_sin;
                    // $s_body = $smsitem[0]->body_content_sin;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$epf_new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name_si,$request->complainant_address];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

                    if(in_array("4", $catlist)) {

                        $sms_body = $s_body.' '.$TLMessageSi;

                    } else {

                        $sms_body = $s_body;

                    }

                } else if($regdata[0]->pref_lang == 'TA'){
                    $s_sub = $smsitem[0]->sms_template_name_tam;
                    // $s_body = $smsitem[0]->body_content_tam;

                    $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$epf_new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name_ta,$request->complainant_address];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                    if(in_array("4", $catlist)) {

                        $sms_body = $s_body.' '.$TLMessageTa;

                    } else {

                        $sms_body = $s_body;

                    }
                }

                $mobitelSms = new MobitelSms();
                $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
                $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($request->complainant_mobile),0);
                $mobitelSms->closeSession($session);

                \SmsLog::addToLog($request->complainant_f_name, $request->complainant_mobile, $sms_body);
            }
        }

        // For Child and women complaint categories
        // if(!empty($child_category_array) || !empty($women_category_array)){

        //     $complaint = new RegisterComplaint;

        //     $complaint->comp_type = $request->comp_type;
        //     $complaint->pref_lang = $request->pref_lang;
        //     // $complaint->ref_no = $request->ref_no;
        //     // $complaint->external_ref_no = $request->external_ref_no;
        //     $complaint->complainant_identify_no = $request->complainant_identify_no;
        //     $complaint->title = $request->title;
        //     $complaint->complainant_l_name = $request->complainant_l_name;

        //     if($request->comp_type == "U"){
        //         $complaint->complainant_full_name = $request->complainant_f_name.' '.$request->complainant_l_name;
        //     } else {
        //         $complaint->complainant_full_name = $request->complainant_full_name;
        //     }

        //     $complaint->complainant_dob = $request->complainant_dob;
        //     $complaint->complainant_gender = $request->complainant_gender;
        //     $complaint->nationality = $request->nationality;
        //     $complaint->complainant_email = $request->complainant_email;
        //     $complaint->complainant_mobile = $request->complainant_mobile;
        //     $complaint->complainant_tel = $request->complainant_tel;
        //     $complaint->union_name = $request->union_name;
        //     $complaint->union_address = $request->union_address;
        //     $complaint->province_id = $districts->province_id;
        //     $complaint->district_id = $request->district_id;
        //     $complaint->city_id = $request->city_id;
        //     $complaint->employer_tel = $request->employer_tel;
        //     $complaint->business_nature_id = $request->business_nature;
        //     $complaint->establishment_type_id = $request->establishment_type_id;
        //     $complaint->establishment_reg_no = $request->establishment_reg_no;
        //     $complaint->employer_no = $request->employer_no;
        //     // $complaint->ppe_no = $request->ppe_no;
        //     $complaint->employee_no = $request->employee_no;
        //     $complaint->epf_no = $request->epf_no;
        //     $complaint->employee_mem_no = $request->employee_mem_no;
        //     $complaint->designation = $request->designation;
        //     $complaint->join_date = $request->join_date;
        //     $complaint->terminate_date = $request->terminate_date;
        //     $complaint->last_sal_date = $request->last_sal_date;
        //     // $complaint->basic_sal = $request->basic_sal;
        //     // $complaint->allowance = $request->allowance;
        //     $complaint->submitted_office = $request->submitted_office;
        //     $complaint->submitted_date = $request->submitted_date;
        //     $complaint->case_no = $request->case_no;
        //     $complaint->received_relief = $request->received_relief;
        //     $complaint->complain_purpose = $request->complain_purpose;
        //     $complaint->current_employer_tel = $request->current_employer_tel;
        //     $complaint->complainant_f_name = $request->complainant_f_name;
        //     $complaint->complainant_address = $request->complainant_address;
        //     $complaint->employer_name = $request->employer_name;
        //     $complaint->employer_address = $request->employer_address;
        //     $complaint->current_employer_name = $request->current_employer_name;
        //     $complaint->current_employer_address = $request->current_employer_address;

        //     $allowance = $request->allowance;
        //     $basicsal = $request->basic_sal;
        //     $complaint->worked_employees = $request->worked_employees;

        //     $floatallowance = str_replace(',', '', $allowance);
        //     $floatbasicsal = str_replace(',', '', $basicsal);

        //     if(!empty($floatbasicsal)) {
        //         $complaint->basic_sal = $floatbasicsal;
        //     }

        //     if(!empty($floatallowance)) {
        //         $complaint->allowance = $floatallowance;
        //     }

        //     if($request->pref_lang == "SI") {

        //         $complaint->complainant_f_name_si = $request->complainant_f_name;
        //         $complaint->complainant_f_name_ta = "N/A";
        //         $complaint->complainant_address_si = $request->complainant_address;
        //         $complaint->complainant_address_ta = "N/A";
        //         $complaint->employer_name_si = $request->employer_name;
        //         $complaint->employer_name_ta = "N/A";
        //         $complaint->employer_address_si = $request->employer_address;
        //         $complaint->employer_address_ta = "N/A";
        //         $complaint->current_employer_name_si = $request->current_employer_name;
        //         $complaint->current_employer_name_ta = "N/A";
        //         $complaint->current_employer_address_si = $request->current_employer_address;
        //         $complaint->current_employer_address_ta = "N/A";

        //     } else if($request->pref_lang == "TA") {

        //         $complaint->complainant_f_name_ta = $request->complainant_f_name;
        //         $complaint->complainant_f_name_si = "N/A";
        //         $complaint->complainant_address_ta = $request->complainant_address;
        //         $complaint->complainant_address_si = "N/A";
        //         $complaint->employer_name_ta = $request->employer_name;
        //         $complaint->employer_name_si = "N/A";
        //         $complaint->employer_address_ta = $request->employer_address;
        //         $complaint->employer_address_si = "N/A";
        //         $complaint->current_employer_name_si = "N/A";
        //         $complaint->current_employer_name_ta = $request->current_employer_name;
        //         $complaint->current_employer_address_si = "N/A";
        //         $complaint->current_employer_address_ta = $request->current_employer_address;

        //     } else {
        //         $complaint->complainant_f_name_si = "N/A";
        //         $complaint->complainant_f_name_ta = "N/A";
        //         $complaint->complainant_address_si = "N/A";
        //         $complaint->complainant_address_ta = "N/A";
        //         $complaint->employer_name_ta = "N/A";
        //         $complaint->employer_name_si = "N/A";
        //         $complaint->employer_address_ta = "N/A";
        //         $complaint->employer_address_si = "N/A";
        //         $complaint->current_employer_name_si = "N/A";
        //         $complaint->current_employer_name_ta = "N/A";
        //         $complaint->current_employer_address_si = "N/A";
        //         $complaint->current_employer_address_ta = "N/A";

        //     }

        //     $child_office_code = 'WCA';
        //     $child_office_id = 15;

        //     $child_category_array = array_merge($child_category_array,$women_category_array);
        //     if($child_category_array != '') {
        //         $categoryarr = implode(',', $child_category_array);
        //         $complaint->complain_category = $category['child_category_array']=  $categoryarr;
        //     }

        //     if($complaint->submitted_office != ""){
        //         $complaint->is_available = 1;
        //     } else {
        //         $complaint->is_available = 0;
        //     }

        //     $category_prefix = "";
        //     if ($child_category_array != '') {
        //         if (count($child_category_array) > 1) {
        //             $category_prefix = "M";
        //         } else {
        //             $categoryInfo = Complain_Category::where('id', $child_category_array[0])->first();

        //             if (!empty($categoryInfo)) {
        //                 $category_prefix = $categoryInfo->category_prefix;
        //             } else {
        //                 $category_prefix = NULL;
        //             }
        //         }
        //         $categoryarr = implode(',', $child_category_array);
        //         $complaint->complain_category = $category['child_category_array'] =  $categoryarr;
        //     }

        //     $result = RegisterComplaint::select('id', 'created_at', 'external_ref_no')
        //     ->orderby('id', 'desc')
        //     ->first();

        //     if (!empty($result)) {
        //         // dd($result->external_ref_no);
        //         $refNo = $result->external_ref_no;
        //         $arrSerial = explode('/', $refNo);
        //         if ($arrSerial) {
        //             $oldYear = $arrSerial[1];
        //             $oldRef = $arrSerial[2];
        //         }
        //     } else {
        //         $oldYear = Carbon::now()->format('Y');
        //         $oldRef = 0;
        //     }

        //     $newYear = Carbon::now()->format('Y');

        //     if ($oldYear != $newYear) {
        //         $newRef = 0;

        //         $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

        //         $number_part1 = 'COM';
        //         $child_new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;
        //     } else {
        //         $newRef = str_pad((int) $oldRef + 1, 5, '0', STR_PAD_LEFT);
        //         $number_part1 = 'COM';
        //         $child_new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;
        //     }

        //     $result2 = RegisterComplaint::select('id', 'created_at', 'ref_no')
        //         ->where('ref_no', 'LIKE', '%'.$child_office_code.'%')
        //         ->orderby('id', 'desc')
        //         ->first();

        //     if (!empty($result2)) {
        //         //dd($result2->ref_no);
        //         $refNo2 = $result2->ref_no;
        //         $arrSerial2 = explode('/', $refNo2);
        //         if ($arrSerial2) {
        //             $oldYear2 = substr($arrSerial2[4], 0, 4);
        //             $oldRef2 = $arrSerial2[5];
        //         }
        //     } else {
        //         $oldRef2 = 0;
        //     }

        //     $number_part3 = "00";

        //     if ($oldYear2 != $newYear) {
        //         $newRef = 0;

        //         $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

        //         $number_part1 = 'COM';

        //         $internal_number = $child_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;
        //     } else {
        //         $newRef = str_pad((int) $oldRef2 + 1, 5, '0', STR_PAD_LEFT);
        //         $number_part1 = 'COM';
        //         $internal_number = $child_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;
        //     }

        //     $complaint->external_ref_no = $child_new_complaint_no;
        //     $complaint->ref_no = $internal_number;
        //     $complaint->complaint_status = 'New';
        //     $complaint->action_type = 'Pending';
        //     $complaint->current_office_id = $child_office_id;
        //     $complaint->save();

        //     $child_countcat = count($child_category_array);

        //     for ($i = 0; $i < $child_countcat; $i++) {

        //         $complaincategorydetail = new ComplaintCategoryDetail();
        //         $complaincategorydetail->complaint_id = $complaint->id;
        //         $complaincategoryid = $child_category_array[$i];

        //         $categoriesexpdate = Complain_Category::select('expiry_days')->where('id', $complaincategoryid)->first();

        //         $expdayscount = Carbon::today()->addDays($categoriesexpdate->expiry_days);

        //         $expirydate = $expdayscount->toDateString();

        //         $complaincategorydetail->category_id = $child_category_array[$i];

        //         $complaincategorydetail->expiry_date = $expirydate;

        //         $complaincategorydetail->save();
        //     }

        //     $id = $complaint->id;

        //     $child_insert['complaint_id'] = $complaint->id;
        //     $child_insert['status'] = 'New';
        //     $child_insert['sent_from_office'] = $child_office_id;
        //     $child_insert['sent_from_office_code'] = $child_office_code;
        //     $child_insert['sent_to_office'] = NULL;
        //     $child_insert['sent_to_office_code'] = NULL;
        //     $child_insert['action_type'] = 'New';
        //     $child_insert['remark'] = '';
        //     $child_insert['show_status'] = 'Ext';
        //     $child_insert['assigned_lo_id'] = NULL;
        //     $child_insert['forward_type_id'] = 0;
        //     $child_insert['user_id'] = 0;

        //     if($request->pref_lang == "SI") {
        //         $child_insert['status_des'] = 'ඔබගේ පැමිණිල්ල සාර්ථකව ලැබුණා. පැමිණිලි ක්‍රියාවලිය ඉක්මණින් ආරම්භ වනු ඇත';
        //     } else if($request->pref_lang == "TA") {
        //         $child_insert['status_des'] = 'உங்கள் முறைப்பாடு வெற்றிகரமாக பெறப்பட்டது. முறைப்பாட்டு செயன்முறை விரைவில் ஆரம்பமாகும் ';
        //     } else {
        //         $child_insert['status_des'] = 'Your Complain received successfully. Complain process will start soon';
        //     }

        //     ComplaintHistory::insert($child_insert);

        //     $validatedData = $request->validate([
        //         // 'files' => 'required',
        //         'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
        //     ]);


        //     if($request->hasfile('files'))
        //     {
        //         $insert = array();
        //         foreach($request->file('files') as $key => $file)
        //         {
        //             $path = $file->store('public/files');
        //             $name = $file->getClientOriginalName();

        //         $ref_no = $complaint->id;
        //         $insert[$key]['ref_no'] = $ref_no;
        //         $insert[$key]['file_name'] = $path;
        //         $insert[$key]['description'] = $name;
        //         }
        //         //dd($insert);exit();
        //         ComplaintDocument::insert($insert);
        //     }

        //     if($request->comp_type == "U" && $request->union_officer_name != "" && $request->union_officer_name != null) {
        //         $count = count($request->union_officer_name)-1;

        //         for ($i=0; $i < $count; $i++) {

        //         $unionofficerdetail = new UnionOfficerDetail();
        //         $unionofficerdetail->ref_id = $complaint->id;
        //         $unionofficerdetail->officer_name = $request->union_officer_name[$i];
        //         $unionofficerdetail->officer_address = $request->union_officer_address[$i];
        //         $unionofficerdetail->save();
        //         }
        //     }

        //     $regdata = RegisterComplaint::where('id', $id)
        //         ->get();

        //     $officename = LabourOfficeDivision::where('id', $child_office_id)->first();

        //     if($request->complainant_email != ''){

        //         $mailitem = MailTemplate::where('status', 'Y')
        //                 ->where('is_delete', 0)
        //                 ->where('id', 1)
        //                 ->get();
        //         //dd();exit();
        //         //\App::setLocale($regdata[0]->pref_lang);

        //         if($regdata[0]->pref_lang == 'EN'){
        //             $e_sub = $mailitem[0]->mail_template_name_en;
        //             // $e_body = $mailitem[0]->body_content_en;
        //             $e_name = $mailitem[0]->mail_template_name_en;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$child_new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

        //             $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

        //             $email_body = 'Dear'.' '.$request->complainant_f_name.', '.$e_body;

        //         } else if($regdata[0]->pref_lang == 'SI'){

        //             $e_sub = $mailitem[0]->mail_template_name_sin;
        //             // $e_body = $mailitem[0]->body_content_sin;
        //             $e_name = $mailitem[0]->mail_template_name_sin;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$child_new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name,$request->complainant_address];

        //             $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

        //             $email_body = 'හිතවත්'.' '.$request->complainant_f_name.', '.$e_body;

        //         } else if($regdata[0]->pref_lang == 'TA'){

        //             $e_sub = $mailitem[0]->mail_template_name_tam;
        //             // $e_body = $mailitem[0]->body_content_tam;
        //             $e_name = $mailitem[0]->mail_template_name_tam;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$child_new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name,$request->complainant_address];

        //             $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

        //             $email_body = 'அன்பார்ந்த'.' '.$request->complainant_f_name.', '.$e_body;
        //         }

        //         $mail_body_content = strip_tags($email_body);

        //         \Mail::send('mail.complaint-mail',
        //             array(
        //             'ref_no' => $regdata[0]->external_ref_no,
        //             'date' => $regdata[0]->created_at,
        //             'name' => $request->complainant_f_name,
        //             'subject' => $e_sub,
        //             'body' => $mail_body_content,
        //             ), function($message) use ($e_name, $regdata)
        //         {
        //             $message->from('cms@labourdept.gov.lk');
        //             $message->to($regdata[0]->complainant_email)->subject($e_name);
        //         });
        //     }

        //     if($request->complainant_mobile != ''){

        //         $smsitem = SmsTemplate::where('status', 'Y')
        //             ->where('is_delete', 0)
        //             ->where('id', 1)
        //             ->get();

        //         $complainant_f_name = $request->complainant_f_name;

        //         $catlist = $request->complain_category_id;

        //         $TLMessageEn = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
        //         $TLMessageSi = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
        //         $TLMessageTa = "உங்களது புகார் மீண்டும் பணியமர்த்தல் அல்லது சேவையை நிறுத்துவது தொடர்பாக இழப்பீடு வழங்குவதாக இருந்தால், கடைசி வேலை நாளிலிருந்து 06 மாதங்களுக்குள் சம்பந்தப்பட்ட தொழிலாளர் தீர்ப்பாயத்தில் புகார் அளிக்குமாறும் உங்களுக்குத் தெரிவிக்கிறேன்.";

        //         if($regdata[0]->pref_lang == 'EN'){
        //             $s_sub = $smsitem[0]->sms_template_name_en;
        //             // $s_body = $smsitem[0]->body_content_en;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$child_new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

        //             $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

        //             if(in_array("4", $catlist)) {

        //                 $sms_body = $s_body.' '.$TLMessageEn;

        //             } else {

        //                 $sms_body = $s_body;

        //             }

        //         } else if($regdata[0]->pref_lang == 'SI'){
        //             $s_sub = $smsitem[0]->sms_template_name_sin;
        //             // $s_body = $smsitem[0]->body_content_sin;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$child_new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name_si,$request->complainant_address];

        //             $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

        //             if(in_array("4", $catlist)) {

        //                 $sms_body = $s_body.' '.$TLMessageSi;

        //             } else {

        //                 $sms_body = $s_body;

        //             }

        //         } else if($regdata[0]->pref_lang == 'TA'){
        //             $s_sub = $smsitem[0]->sms_template_name_tam;
        //             // $s_body = $smsitem[0]->body_content_tam;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$child_new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name_ta,$request->complainant_address];

        //             $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

        //             if(in_array("4", $catlist)) {

        //                 $sms_body = $s_body.' '.$TLMessageTa;

        //             } else {

        //                 $sms_body = $s_body;

        //             }
        //         }

        //         $mobitelSms = new MobitelSms();
        //         $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
        //         $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($request->complainant_mobile),0);
        //         $mobitelSms->closeSession($session);

        //         \SmsLog::addToLog($request->complainant_f_name, $request->complainant_mobile, $sms_body);
        //     }
        // }

        // For Termination and women complaint categories
        // if(!empty($termination_category_array)){

        //     $complaint = new RegisterComplaint;

        //     $complaint->comp_type = $request->comp_type;
        //     $complaint->pref_lang = $request->pref_lang;
        //     // $complaint->ref_no = $request->ref_no;
        //     // $complaint->external_ref_no = $request->external_ref_no;
        //     $complaint->complainant_identify_no = $request->complainant_identify_no;
        //     $complaint->title = $request->title;
        //     $complaint->complainant_l_name = $request->complainant_l_name;

        //     if($request->comp_type == "U"){
        //         $complaint->complainant_full_name = $request->complainant_f_name.' '.$request->complainant_l_name;
        //     } else {
        //         $complaint->complainant_full_name = $request->complainant_full_name;
        //     }

        //     $complaint->complainant_dob = $request->complainant_dob;
        //     $complaint->complainant_gender = $request->complainant_gender;
        //     $complaint->nationality = $request->nationality;
        //     $complaint->complainant_email = $request->complainant_email;
        //     $complaint->complainant_mobile = $request->complainant_mobile;
        //     $complaint->complainant_tel = $request->complainant_tel;
        //     $complaint->union_name = $request->union_name;
        //     $complaint->union_address = $request->union_address;
        //     $complaint->province_id = $districts->province_id;
        //     $complaint->district_id = $request->district_id;
        //     $complaint->city_id = $request->city_id;
        //     $complaint->employer_tel = $request->employer_tel;
        //     $complaint->business_nature_id = $request->business_nature;
        //     $complaint->establishment_type_id = $request->establishment_type_id;
        //     $complaint->establishment_reg_no = $request->establishment_reg_no;
        //     $complaint->employer_no = $request->employer_no;
        //     // $complaint->ppe_no = $request->ppe_no;
        //     $complaint->employee_no = $request->employee_no;
        //     $complaint->epf_no = $request->epf_no;
        //     $complaint->employee_mem_no = $request->employee_mem_no;
        //     $complaint->designation = $request->designation;
        //     $complaint->join_date = $request->join_date;
        //     $complaint->terminate_date = $request->terminate_date;
        //     $complaint->last_sal_date = $request->last_sal_date;
        //     // $complaint->basic_sal = $request->basic_sal;
        //     // $complaint->allowance = $request->allowance;
        //     $complaint->submitted_office = $request->submitted_office;
        //     $complaint->submitted_date = $request->submitted_date;
        //     $complaint->case_no = $request->case_no;
        //     $complaint->received_relief = $request->received_relief;
        //     $complaint->complain_purpose = $request->complain_purpose;
        //     $complaint->current_employer_tel = $request->current_employer_tel;
        //     $complaint->complainant_f_name = $request->complainant_f_name;
        //     $complaint->complainant_address = $request->complainant_address;
        //     $complaint->employer_name = $request->employer_name;
        //     $complaint->employer_address = $request->employer_address;
        //     $complaint->current_employer_name = $request->current_employer_name;
        //     $complaint->current_employer_address = $request->current_employer_address;

        //     $allowance = $request->allowance;
        //     $basicsal = $request->basic_sal;
        //     $complaint->worked_employees = $request->worked_employees;

        //     $floatallowance = str_replace(',', '', $allowance);
        //     $floatbasicsal = str_replace(',', '', $basicsal);

        //     if(!empty($floatbasicsal)) {
        //         $complaint->basic_sal = $floatbasicsal;
        //     }

        //     if(!empty($floatallowance)) {
        //         $complaint->allowance = $floatallowance;
        //     }

        //     if($request->pref_lang == "SI") {

        //         $complaint->complainant_f_name_si = $request->complainant_f_name;
        //         $complaint->complainant_f_name_ta = "N/A";
        //         $complaint->complainant_address_si = $request->complainant_address;
        //         $complaint->complainant_address_ta = "N/A";
        //         $complaint->employer_name_si = $request->employer_name;
        //         $complaint->employer_name_ta = "N/A";
        //         $complaint->employer_address_si = $request->employer_address;
        //         $complaint->employer_address_ta = "N/A";
        //         $complaint->current_employer_name_si = $request->current_employer_name;
        //         $complaint->current_employer_name_ta = "N/A";
        //         $complaint->current_employer_address_si = $request->current_employer_address;
        //         $complaint->current_employer_address_ta = "N/A";

        //     } else if($request->pref_lang == "TA") {

        //         $complaint->complainant_f_name_ta = $request->complainant_f_name;
        //         $complaint->complainant_f_name_si = "N/A";
        //         $complaint->complainant_address_ta = $request->complainant_address;
        //         $complaint->complainant_address_si = "N/A";
        //         $complaint->employer_name_ta = $request->employer_name;
        //         $complaint->employer_name_si = "N/A";
        //         $complaint->employer_address_ta = $request->employer_address;
        //         $complaint->employer_address_si = "N/A";
        //         $complaint->current_employer_name_si = "N/A";
        //         $complaint->current_employer_name_ta = $request->current_employer_name;
        //         $complaint->current_employer_address_si = "N/A";
        //         $complaint->current_employer_address_ta = $request->current_employer_address;

        //     } else {
        //         $complaint->complainant_f_name_si = "N/A";
        //         $complaint->complainant_f_name_ta = "N/A";
        //         $complaint->complainant_address_si = "N/A";
        //         $complaint->complainant_address_ta = "N/A";
        //         $complaint->employer_name_ta = "N/A";
        //         $complaint->employer_name_si = "N/A";
        //         $complaint->employer_address_ta = "N/A";
        //         $complaint->employer_address_si = "N/A";
        //         $complaint->current_employer_name_si = "N/A";
        //         $complaint->current_employer_name_ta = "N/A";
        //         $complaint->current_employer_address_si = "N/A";
        //         $complaint->current_employer_address_ta = "N/A";

        //     }

        //     $termination_office_code = 'TEU';
        //     $termination_office_id = 12;

        //     if($termination_category_array != '') {
        //         $categoryarr = implode(',', $termination_category_array);
        //         $complaint->complain_category = $category['termination_category_array']=  $categoryarr;
        //     }

        //     if($complaint->submitted_office != ""){
        //         $complaint->is_available = 1;
        //     } else {
        //         $complaint->is_available = 0;
        //     }

        //     $category_prefix = "";
        //     if ($termination_category_array != '') {
        //         if (count($termination_category_array) > 1) {
        //             $category_prefix = "M";
        //         } else {
        //             $categoryInfo = Complain_Category::where('id', $termination_category_array[0])->first();

        //             if (!empty($categoryInfo)) {
        //                 $category_prefix = $categoryInfo->category_prefix;
        //             } else {
        //                 $category_prefix = NULL;
        //             }
        //         }
        //         $categoryarr = implode(',', $termination_category_array);
        //         $complaint->complain_category = $category['termination_category_array'] =  $categoryarr;
        //     }

        //     $result = RegisterComplaint::select('id', 'created_at', 'external_ref_no')
        //     ->orderby('id', 'desc')
        //     ->first();

        //     if (!empty($result)) {
        //         // dd($result->external_ref_no);
        //         $refNo = $result->external_ref_no;
        //         $arrSerial = explode('/', $refNo);
        //         if ($arrSerial) {
        //             $oldYear = $arrSerial[1];
        //             $oldRef = $arrSerial[2];
        //         }
        //     } else {
        //         $oldYear = Carbon::now()->format('Y');
        //         $oldRef = 0;
        //     }

        //     $newYear = Carbon::now()->format('Y');

        //     if ($oldYear != $newYear) {
        //         $newRef = 0;

        //         $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

        //         $number_part1 = 'COM';
        //         $termination_new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;
        //     } else {
        //         $newRef = str_pad((int) $oldRef + 1, 5, '0', STR_PAD_LEFT);
        //         $number_part1 = 'COM';
        //         $termination_new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;
        //     }

        //     $result2 = RegisterComplaint::select('id', 'created_at', 'ref_no')
        //         ->where('ref_no', 'LIKE', '%'.$termination_office_code.'%')
        //         ->orderby('id', 'desc')
        //         ->first();

        //     if (!empty($result2)) {
        //         //dd($result2->ref_no);
        //         $refNo2 = $result2->ref_no;
        //         $arrSerial2 = explode('/', $refNo2);
        //         if ($arrSerial2) {
        //             $oldYear2 = substr($arrSerial2[4], 0, 4);
        //             $oldRef2 = $arrSerial2[5];
        //         }
        //     } else {
        //         $oldRef2 = 0;
        //     }

        //     $number_part3 = "00";

        //     if ($oldYear2 != $newYear) {
        //         $newRef = 0;

        //         $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

        //         $number_part1 = 'COM';

        //         $internal_number = $termination_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;
        //     } else {
        //         $newRef = str_pad((int) $oldRef2 + 1, 5, '0', STR_PAD_LEFT);
        //         $number_part1 = 'COM';
        //         $internal_number = $termination_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;
        //     }

        //     $complaint->external_ref_no = $termination_new_complaint_no;
        //     $complaint->ref_no = $internal_number;
        //     $complaint->complaint_status = 'New';
        //     $complaint->action_type = 'Pending';
        //     $complaint->current_office_id = $termination_office_id;
        //     $complaint->save();

        //     $termination_countcat = count($termination_category_array);

        //     for ($i = 0; $i < $termination_countcat; $i++) {

        //         $complaincategorydetail = new ComplaintCategoryDetail();
        //         $complaincategorydetail->complaint_id = $complaint->id;
        //         $complaincategoryid = $termination_category_array[$i];

        //         $categoriesexpdate = Complain_Category::select('expiry_days')->where('id', $complaincategoryid)->first();

        //         $expdayscount = Carbon::today()->addDays($categoriesexpdate->expiry_days);

        //         $expirydate = $expdayscount->toDateString();

        //         $complaincategorydetail->category_id = $termination_category_array[$i];

        //         $complaincategorydetail->expiry_date = $expirydate;

        //         $complaincategorydetail->save();
        //     }

        //     $id = $complaint->id;

        //     $termination_insert['complaint_id'] = $complaint->id;
        //     $termination_insert['status'] = 'New';
        //     $termination_insert['sent_from_office'] = $termination_office_id;
        //     $termination_insert['sent_from_office_code'] = $termination_office_code;
        //     $termination_insert['sent_to_office'] = NULL;
        //     $termination_insert['sent_to_office_code'] = NULL;
        //     $termination_insert['action_type'] = 'New';
        //     $termination_insert['remark'] = '';
        //     $termination_insert['show_status'] = 'Ext';
        //     $termination_insert['assigned_lo_id'] = NULL;
        //     $termination_insert['forward_type_id'] = 0;
        //     $termination_insert['user_id'] = 0;

        //     if($request->pref_lang == "SI") {
        //         $termination_insert['status_des'] = 'ඔබගේ පැමිණිල්ල සාර්ථකව ලැබුණා. පැමිණිලි ක්‍රියාවලිය ඉක්මණින් ආරම්භ වනු ඇත';
        //     } else if($request->pref_lang == "TA") {
        //         $termination_insert['status_des'] = 'உங்கள் முறைப்பாடு வெற்றிகரமாக பெறப்பட்டது. முறைப்பாட்டு செயன்முறை விரைவில் ஆரம்பமாகும் ';
        //     } else {
        //         $termination_insert['status_des'] = 'Your Complain received successfully. Complain process will start soon';
        //     }

        //     ComplaintHistory::insert($termination_insert);

        //     $validatedData = $request->validate([
        //         // 'files' => 'required',
        //         'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
        //     ]);


        //     if($request->hasfile('files'))
        //     {
        //         $insert = array();
        //         foreach($request->file('files') as $key => $file)
        //         {
        //             $path = $file->store('public/files');
        //             $name = $file->getClientOriginalName();

        //         $ref_no = $complaint->id;
        //         $insert[$key]['ref_no'] = $ref_no;
        //         $insert[$key]['file_name'] = $path;
        //         $insert[$key]['description'] = $name;
        //         }
        //         //dd($insert);exit();
        //         ComplaintDocument::insert($insert);
        //     }

        //     if($request->comp_type == "U" && $request->union_officer_name != "" && $request->union_officer_name != null) {
        //         $count = count($request->union_officer_name)-1;

        //         for ($i=0; $i < $count; $i++) {

        //         $unionofficerdetail = new UnionOfficerDetail();
        //         $unionofficerdetail->ref_id = $complaint->id;
        //         $unionofficerdetail->officer_name = $request->union_officer_name[$i];
        //         $unionofficerdetail->officer_address = $request->union_officer_address[$i];
        //         $unionofficerdetail->save();
        //         }
        //     }

        //     $regdata = RegisterComplaint::where('id', $id)
        //         ->get();

        //     $officename = LabourOfficeDivision::where('id', $termination_office_id)->first();

        //     if($request->complainant_email != ''){

        //         $mailitem = MailTemplate::where('status', 'Y')
        //                 ->where('is_delete', 0)
        //                 ->where('id', 1)
        //                 ->get();
        //         //dd();exit();
        //         //\App::setLocale($regdata[0]->pref_lang);

        //         if($regdata[0]->pref_lang == 'EN'){
        //             $e_sub = $mailitem[0]->mail_template_name_en;
        //             // $e_body = $mailitem[0]->body_content_en;
        //             $e_name = $mailitem[0]->mail_template_name_en;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$termination_new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

        //             $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

        //             $email_body = 'Dear'.' '.$request->complainant_f_name.', '.$e_body;

        //         } else if($regdata[0]->pref_lang == 'SI'){

        //             $e_sub = $mailitem[0]->mail_template_name_sin;
        //             // $e_body = $mailitem[0]->body_content_sin;
        //             $e_name = $mailitem[0]->mail_template_name_sin;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$termination_new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name,$request->complainant_address];

        //             $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

        //             $email_body = 'හිතවත්'.' '.$request->complainant_f_name.', '.$e_body;

        //         } else if($regdata[0]->pref_lang == 'TA'){

        //             $e_sub = $mailitem[0]->mail_template_name_tam;
        //             // $e_body = $mailitem[0]->body_content_tam;
        //             $e_name = $mailitem[0]->mail_template_name_tam;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$termination_new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name,$request->complainant_address];

        //             $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

        //             $email_body = 'அன்பார்ந்த'.' '.$request->complainant_f_name.', '.$e_body;
        //         }

        //         $mail_body_content = strip_tags($email_body);

        //         \Mail::send('mail.complaint-mail',
        //             array(
        //             'ref_no' => $regdata[0]->external_ref_no,
        //             'date' => $regdata[0]->created_at,
        //             'name' => $request->complainant_f_name,
        //             'subject' => $e_sub,
        //             'body' => $mail_body_content,
        //             ), function($message) use ($e_name, $regdata)
        //         {
        //             $message->from('cms@labourdept.gov.lk');
        //             $message->to($regdata[0]->complainant_email)->subject($e_name);
        //         });
        //     }

        //     if($request->complainant_mobile != ''){

        //         $smsitem = SmsTemplate::where('status', 'Y')
        //             ->where('is_delete', 0)
        //             ->where('id', 1)
        //             ->get();

        //         $complainant_f_name = $request->complainant_f_name;

        //         $catlist = $request->complain_category_id;

        //         $TLMessageEn = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
        //         $TLMessageSi = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
        //         $TLMessageTa = "உங்களது புகார் மீண்டும் பணியமர்த்தல் அல்லது சேவையை நிறுத்துவது தொடர்பாக இழப்பீடு வழங்குவதாக இருந்தால், கடைசி வேலை நாளிலிருந்து 06 மாதங்களுக்குள் சம்பந்தப்பட்ட தொழிலாளர் தீர்ப்பாயத்தில் புகார் அளிக்குமாறும் உங்களுக்குத் தெரிவிக்கிறேன்.";

        //         if($regdata[0]->pref_lang == 'EN'){
        //             $s_sub = $smsitem[0]->sms_template_name_en;
        //             // $s_body = $smsitem[0]->body_content_en;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->current_employer_name,$request->employer_name,$termination_new_complaint_no,$request->current_employer_address,$officename->office_name_en,$request->complainant_f_name,$request->complainant_address];

        //             $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

        //             if(in_array("4", $catlist)) {

        //                 $sms_body = $s_body.' '.$TLMessageEn;

        //             } else {

        //                 $sms_body = $s_body;

        //             }

        //         } else if($regdata[0]->pref_lang == 'SI'){
        //             $s_sub = $smsitem[0]->sms_template_name_sin;
        //             // $s_body = $smsitem[0]->body_content_sin;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address,$request->employer_name,$termination_new_complaint_no,$request->current_employer_address,$officename->office_name_sin,$request->complainant_f_name_si,$request->complainant_address];

        //             $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

        //             if(in_array("4", $catlist)) {

        //                 $sms_body = $s_body.' '.$TLMessageSi;

        //             } else {

        //                 $sms_body = $s_body;

        //             }

        //         } else if($regdata[0]->pref_lang == 'TA'){
        //             $s_sub = $smsitem[0]->sms_template_name_tam;
        //             // $s_body = $smsitem[0]->body_content_tam;

        //             $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

        //             $variableData = [$request->current_employer_name,$request->employer_address_ta,$request->employer_name_ta,$termination_new_complaint_no,$request->current_employer_address,$officename->office_name_tam,$request->complainant_f_name_ta,$request->complainant_address];

        //             $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

        //             if(in_array("4", $catlist)) {

        //                 $sms_body = $s_body.' '.$TLMessageTa;

        //             } else {

        //                 $sms_body = $s_body;

        //             }
        //         }

        //         $mobitelSms = new MobitelSms();
        //         $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
        //         $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($request->complainant_mobile),0);
        //         $mobitelSms->closeSession($session);

        //         \SmsLog::addToLog($request->complainant_f_name, $request->complainant_mobile, $sms_body);
        //     }

        // }

        $new_complaint_no = isset($new_complaint_no) ? $new_complaint_no.' ' : '';
        $epf_new_complaint_no = isset($epf_new_complaint_no) ? $epf_new_complaint_no.' ' : '';
        // $child_new_complaint_no = isset($child_new_complaint_no) ? $child_new_complaint_no.' ' : '';
        // $termination_new_complaint_no = isset($termination_new_complaint_no) ? $termination_new_complaint_no.' ' : '';

        $complaint_num = $new_complaint_no.$epf_new_complaint_no;

        DB::commit();
        return redirect()->route('new-register')
            ->with('success', 'Complaint entered successfully.')->with('info', 'Your Complaint Reference Number/Numbers - '. $complaint_num.'');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    function makeNewRef($office_code,$newRef) {

        $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

        $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

        while($checkDuplicate > 0) {
            $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

            $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

        }

        return $newRef;

    }

    public function complainttracking()
    {

        return view('userpanel.complaint_tracking');
    }

    public function resentotp(Request $request, $id) {

        $data = RegisterComplaint::where('id', '=', $id)->get();

        $randomNumber = random_int(1000, 9999);
        $request->session()->put('otp', $randomNumber);

        $updateOtp = RegisterComplaint::where('id', $id)->update(['otp' => $randomNumber]);

        $num1 = substr($data[0]->complainant_mobile, 0, 2);
        $num2 = substr($data[0]->complainant_mobile, 7, 9);
        $mobile = $num1.'XXXXX'.$num2;

        $mobitelSms = new MobitelSms();
        $session= $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
        $mobitelSms->sendMessagesMultiLang($session,'Labour Dept','Your one time password for complaint tracking is : '.$randomNumber,array($data[0]->complainant_mobile),0);
        $mobitelSms->closeSession($session);

        \SmsLog::addToLog($data[0]->complainant_f_name, $data[0]->complainant_mobile, 'Your one time password for complaint tracking is : '.$randomNumber);

        return redirect()->back()->with('success','Verification code has been resent, please recheck');
    }

    public function searchcomplaint(Request $request)
    {
        $request->validate([
            'complaint_number' => 'required',
            'g-recaptcha-response' => 'recaptcha',
        ]);
        // dd($randomNumber);

        $referenceno = $request->complaint_number;

        $data = RegisterComplaint::where('external_ref_no', '=', $referenceno)->get();
        // $data->complainant_mobile

        if(count($data)>0)
        {
            if(!empty($data[0]->complainant_mobile))
            {

                if($data[0]->otp == '') {

                    $randomNumber = random_int(1000, 9999);
                    $request->session()->put('otp', $randomNumber);

                    $updateOtp = RegisterComplaint::where('external_ref_no', $referenceno)->update(['otp' => $randomNumber]);

                    $num1 = substr($data[0]->complainant_mobile, 0, 2);
                    $num2 = substr($data[0]->complainant_mobile, 7, 9);
                    $mobile = $num1.'XXXXX'.$num2;

                    $mobitelSms = new MobitelSms();
                    $session= $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
                    $mobitelSms->sendMessagesMultiLang($session,'Labour Dept','Your one time password for complaint tracking is : '.$randomNumber,array($data[0]->complainant_mobile),0);
                    $mobitelSms->closeSession($session);

                    \SmsLog::addToLog($data[0]->complainant_f_name, $data[0]->complainant_mobile, 'Your one time password for complaint tracking is : '.$randomNumber);

                    return view('userpanel.verification', ['data' => $data, 'mobile' => $mobile, 'status']);

                } else  {

                    $num1 = substr($data[0]->complainant_mobile, 0, 2);
                    $num2 = substr($data[0]->complainant_mobile, 7, 9);
                    $mobile = $num1.'XXXXX'.$num2;

                    // dd($mobile);

                    // return redirect()->route('verification')->with('error', 'Already sent the otp to your number please check');
                    return view('userpanel.verification', ['data' => $data, 'mobile' => $mobile]);
                    // return redirect()->route('verification')->with('error', 'Already sent the otp to your number please check');
                }

                // return view('userpanel.verification', ['data' => $data, 'mobile' => $mobile, 'success'=>'Verification code has been sent, Please check']);


            } else {

                if($request->pref_lang == "SI") {
                    return redirect()->route('complaint-tracking')->with('error', 'පැමිණිලිකරුගේ ජංගම දුරකථන අංකය නොමැත');
                } else if($request->pref_lang == "TA") {
                    return redirect()->route('complaint-tracking')->with('error', 'முறைப்பாட்டாளர் கையடக்க தொலைபேசி எண் கிடைக்கவில்லை');
                } else {
                    return redirect()->route('complaint-tracking')->with('error', 'Complainant mobile number is not available');
                }
            }

        } else {

            if($request->pref_lang == "SI") {
                return redirect()->route('complaint-tracking')->with('error', 'ඇතුළත් කළ පැමිණිලි අංකය වැරදි');
            } else if($request->pref_lang == "TA") {
                return redirect()->route('complaint-tracking')->with('error', 'உள்ளிட்ட முறைப்பாட்டு எண் தவறானது');
            } else {
                return redirect()->route('complaint-tracking')->with('error', 'Entered complain number is not valid');
            }

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
            if($request->pref_lang == "SI") {
                return redirect()->back()->with('error', 'ඇතුළත් කළ එක් වරක් පමණක් භාවිතා කළ හැකි මුරපදය වැරදී');
            } else if($request->pref_lang == "TA") {
                return redirect()->back()->with('error', 'உள்ளிட்ட ஒரு தடவை மாத்திரம் பயன்படுத்தும் கடவுச்சொல் தவறானது');
            } else {
                return redirect()->back()->with('error', 'Entered OTP is not valid');
            }

        } else {

            $complaintdetails = RegisterComplaint::where('id',$complaint_id)->get();

            $complaintstatus = ComplaintHistory::where('complaint_id',$complaint_id)
                            ->orderBy('created_at','desc')
                            ->get();

            // dd($complaintstatus);
            $updateotp = RegisterComplaint::find($complaint_id);
            $updateotp->otp = "";
            $updateotp->save();

            return view('userpanel.complaint_status', compact('complaintdetails','complaintstatus'));
        }

    }

    // public function complaintstatus(Request $request)
    // {

    //     return view('userpanel.complaint_status');

    // }


}
