<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Workshops;
use App\Models\State;
use DataTables;
use App\Models\City;
use App\Models\User;
use App\Models\Address;
use App\Models\Michanics;
use App\Models\Exam;
use App\Models\Trainning;
use App\Models\RedeemPoints;
use App\Models\TrainingAllocated;
use App\Models\Warrent_incident;
use App\Models\EnrollExams;
use App\Models\StructuredQuestions;

use App\Models\Quiz;
use App\Models\ExamQuestions;
use App\Models\QuizAnswers;
use App\Models\StructuredqAnswer;
use App\Models\QuizAttemptAnswers;

use DateTime;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class WorkshopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('workshop.micheanics_list');
    }
    public function workshop_register(Request $request)
    {
        $state =State::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        return view('workshop.register')->with('state',$state)->with('city',$city);
    }
    public function register(Request $request)
    {
        
        $request->validate([
            'business_name' => 'required|max:50|unique:workshops,business_name',
            'email' => 'required|max:50|email',
            'phone' => 'required|max:20|min:10',
            'vContactperson' => 'required|max:50',
            'vABN' => 'required|max:50',
            'vAddressline1' => 'required|max:50',
            'vAddressline2' => 'required|max:50',
            'stateID' => 'required',
            'cityID' => 'required',
            'postcode' => 'required',
        ]);

        $existing_user = User::where('email', $request->email)->first();
        if(isset($existing_user))
        {
            return redirect('workshop/register')->with('danger', 'User already exist.');
        }

        $data_arry = array();
        $data_arry['business_name'] = $request->business_name;
        $data_arry['email'] = $request->email;
        $data_arry['phone'] = $request->phone;
        $data_arry['Contact_person'] = $request->vContactperson;
        $data_arry['ABN'] = $request->vABN;
        $data_arry['branchID'] = $request->branchID;
        $data_arry['status'] = '1';
        //$data_arry['status'] = $request->status;
        
        $addresses_arry = array(); 
            // address details
        $addresses_arry['vAddressline1'] =$request->vAddressline1;
        $addresses_arry['vAddressline2'] =$request->vAddressline2;
        $addresses_arry['stateID'] =$request->stateID;
        $addresses_arry['cityID'] =$request->cityID;
        $addresses_arry['postcode'] =$request->postcode;

        $addressID = Address::create($addresses_arry);
        $data_arry['addressID'] = $addressID->id;
        $workshop= Workshops::create($data_arry);

        $input = array(); 

        $input['name'] = $request->vContactperson;
        $input['email'] = $request->email;
        $input['mobile_no'] = $request->phone;
        $input['type'] = 'W';
        $input['workshopid'] = $workshop->id;
        $input['status'] = 'Y';
        $input['password'] = Hash::make($request->password);
        $user = User::create($input);

         \LogActivity::addToLog('New workshop'.$request->business_name.' added('.$workshop->id.').');
        return redirect('workshop/register')->with('success', 'New workshop created successfully');

    }
    public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $workshopid = Auth::user()->workshopid;
            $data = Michanics::select('*')
            ->where('workshop_id', $workshopid)
            ->orderBy('michanics.id','ASC')
            ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            }) 
            ->addColumn('edit', function($row){
               
                $btn = 'Edit';
               
                 return $btn;
            })    
            ->addColumn('edit', 'workshop.actionsEdit')
            ->addColumn('activation', 'workshop.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.workshop.list');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $savestatus = 'A';
        $workshopid = Auth::user()->workshopid;
        $michanics =Michanics::select('*')->where('workshop_id', $workshopid)->get();
        return view('workshop.michanic_add')->with('michanics',$michanics)->with('savestatus',$savestatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->savestatus == 'A') {
            $request->validate([   
                'michanicstitle' => 'required',
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);
        } else {
            $request->validate([
                'michanicstitle' => 'required',
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);
        }
        //dd($request->michanicstitle);
        $data_arry = array();
        $data_arry['title'] = $request->michanicstitle;
        $data_arry['name'] = $request->name;
        $data_arry['email'] = $request->email;
        $data_arry['phone'] = $request->phone;
        $data_arry['workshop_id'] = Auth::user()->workshopid;
        if($request->savestatus == 'A'){
            $data_arry['status'] = '1';
            $michanicsdata = Michanics::create($data_arry);
           
             \LogActivity::addToLog('New Mechanics '.$request->name.' added('.$michanicsdata->id.').');
            return redirect('workshop/add-michanics')->with('success', 'New michanic added successfully');
        }else{
            
            $recid = $request->id;
            
           
            Michanics::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('Michanic ' . $request->name . ' updated(' . decrypt($recid) . ').');
            return redirect('/workshop/edit-michanics/'.$recid.'')->with('success', 'Michanic updated successfully');
        }
    }

    public function activation(Request $request)
    { 
        $id = decrypt($request->id);
       
        $data =  Michanics::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('mechanic record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('workshop.michanics')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('mechanic record '.$data->name.' activated('.$id.')');

            return redirect()->route('workshop.michanics')
            ->with('success', 'Record activate successfully.');
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_calander(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $exam_sessions =Trainning::select('*')->where('dStartDate', '>', $date)->orderby('id', 'ASC')->get();
        $traning_session = \DB::table('training_allocated')
            ->join('trainnings', 'trainnings.id', '=', 'training_allocated.training_id')
            ->select('trainnings.dStartDate')
            ->where('training_allocated.workshop_id', $workshopid)
            ->where('trainnings.dStartDate', '>', $date)
            ->get();

       
        return view('workshop.session_calendar')->with('exam_sessions',$exam_sessions)->with('traning_session',$traning_session);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $savestatus = 'E';
        $ID = decrypt($id);
        //$workshopid = Auth::user()->workshopid;
        $michanics =Michanics::select('*')->where('id', $ID)->get();
        return view('workshop.michanic_add')->with('michanics',$michanics)->with('savestatus',$savestatus);
    }

    public function enroll_session(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
       
        $data_arry = array();
        $data_arry['training_id'] = base64_decode($request->tsession_id);
        $data_arry['workshop_id'] = $workshopid;
        $data_arry['enrole_status'] = '1';

        $previous_enroll = TrainingAllocated::select('*')->where('workshop_id', $workshopid)->where('training_id', base64_decode($request->tsession_id))->get();
        if(!count($previous_enroll)>0)
        {
            $enroll_training = TrainingAllocated::create($data_arry);
            return true;
        }else{
            return response()->json(['error' => 'Already enrolled to this session'], 403);
        }
         
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function past_trainings(Request $request)
    {
        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $workshopid = Auth::user()->workshopid;
        //$data = TrainingAllocated::select('*')->where('workshop_id', $workshopid)->paginate(5);

        $data = \DB::table('training_allocated')
            ->join('trainnings', 'trainnings.id', '=', 'training_allocated.training_id')
            //->join('trainingmeterials', 'trainnings.id', '=', 'trainingmeterials.iTrainingID')
            ->select('trainnings.*')
            ->where('training_allocated.workshop_id', $workshopid)
            ->where('trainnings.dStartDate', '<=', $date)
            ->paginate(20);

       
        return view('workshop.past_trainings',compact('data'));

       
    }

    public function future_trainings(Request $request)
    {
        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $workshopid = Auth::user()->workshopid;
        //$data = TrainingAllocated::select('*')->where('workshop_id', $workshopid)->paginate(5);

        $data = \DB::table('training_allocated')
            ->join('trainnings', 'trainnings.id', '=', 'training_allocated.training_id')
            //->join('trainingmeterials', 'trainnings.id', '=', 'trainingmeterials.iTrainingID')
            ->select('trainnings.*')
            ->where('training_allocated.workshop_id', $workshopid)
            ->where('trainnings.dStartDate', '>', $date)
            ->paginate(20);

        //$data = User::paginate(5);
        return view('workshop.future_tranings',compact('data'));
        //return view('workshop.past_trainings');
    }
    public function scan_for_points(Request $request)
    {
        return view('workshop.scan_forpoints');
    }

    public function take_picture(Request $request)
    {
        return view('workshop.take_picture');
    }

    public function redeem_points(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
        $qr_code = base64_decode($request->qr_value);
        $qr_array = explode("|",$qr_code);
        $data_arry = array();
        $data_arry['product_code'] = $qr_code;
        $data_arry['workshop_id'] = $workshopid;
        $data_arry['points'] = $qr_array[2];
        $data_arry['product_id'] = $qr_array[0];
        
        $enroll_training = RedeemPoints::create($data_arry);
        return true;
    }

    public function redeemlist(Request $request)
    {
       
        if ($request->ajax()) {
            $workshopid = Auth::user()->workshopid;
            $data = RedeemPoints::join('product_points', 'product_points.part_number', '=', 'points_redeem.product_id')
            //->join('trainingmeterials', 'trainnings.id', '=', 'trainingmeterials.iTrainingID')
            ->select('product_points.id','product_points.part_number','product_points.part_discription','points_redeem.created_at','points_redeem.points')
            ->where('workshop_id', $workshopid)
            ->orderBy('points_redeem.id','DESC')
            ->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                if($row->status == 1){
                $btn = 'Active';}
                else{  $btn = 'Inactive';}
                 return $btn;
            }) 
           
                ->make(true);
        }

        return view('workshop.points_redeem');
    }

    public function report_incedent(Request $request)
    {
        $savestatus = 'A';
        return view('workshop.report_incedent')->with('savestatus',$savestatus);
    }

    public function store_incedent(Request $request)
    {
        $request->validate([
            'product_id' => 'required|max:50',
            'vMake' => 'required|max:50',
            'vModel' => 'required',
            'vYOM' => 'required',
            'vVINNo' => 'required',
            'purchased_date' => 'required',
            'vKMFitted' => 'required',
            'vODOmeter' => 'required',
            
        ]);
        $workshopid = Auth::user()->workshopid;
        $data_arry = array();
        $data_arry['product_id'] = $request->product_id;
        $data_arry['vMake'] = $request->vMake;
        $data_arry['vModel'] = $request->vModel;
        $data_arry['vYOM'] = $request->vYOM;
        $data_arry['vVINNo'] = $request->vVINNo;
        $data_arry['purchased_date'] = $request->purchased_date;
        $data_arry['vKMFitted'] = $request->vKMFitted;
        $data_arry['vODOmeter'] = $request->vODOmeter;
        $data_arry['cCityuse'] = $request->cCityuse;
        $data_arry['cHightWayUse'] = $request->cHightWayUse;
        $data_arry['cOffRoadUse'] = $request->cOffRoadUse;
        $data_arry['cTowingUse'] = $request->cTowingUse;
        $data_arry['cMountainUse'] = $request->cMountainUse;
        $data_arry['cOtherUse'] = $request->cOtherUse;
        $data_arry['vOtherUseReason'] = $request->vOtherUseReason;

        $data_arry['cNewDrums'] = $request->cNewDrums;
        $data_arry['cDiskMachined'] = $request->cDiskMachined;
        $data_arry['cNewPads'] = $request->cNewPads;
        $data_arry['cSlideGreased'] = $request->cSlideGreased;

        $data_arry['vWheelTorque'] = $request->vWheelTorque;
        $data_arry['vAntiSequel'] = $request->vAntiSequel;
        $data_arry['vDiskClean'] = $request->vDiskClean;
        $data_arry['description'] = $request->description;
        $data_arry['workshop_id'] = $workshopid;
        $data_arry['warrenty_status'] = 'P';
        
        if($request->save_status == 'A')
        {
            $warenty_incedent = Warrent_incident::create($data_arry);
            \LogActivity::addToLog('New Incedent recorded by workshop'.$workshopid.' incedent'.$warenty_incedent->id);
    
                return redirect()->route('workshop.report-incedent')
                ->with('success', 'Incedent Recorded successfully.');
            
        }else if($request->save_status == 'E'){
            
            $warenty_incedent = Warrent_incident::where(['id' =>  $request->incedent_id])->update($data_arry);
            \LogActivity::addToLog('Incedent Update by workshop'.$workshopid.' incedent'.$request->incedent_id);
    
                return redirect()->route('workshop.report-incedent')
                ->with('success', 'Incedent Recorded successfully.');
        }
       
    }

    public function incedent_list(Request $request)
    {
        return view('workshop.warrentyincedent_list');
    }

    public function incedent_datalist(Request $request)
    {
        if ($request->ajax()) {
            $workshopid = Auth::user()->workshopid;
            $data = Warrent_incident::select('*')
            ->where('workshop_id', $workshopid)
            ->orderBy('warenty_incidents.id','ASC')
            ->get();
           
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('warrenty_status', function($row){
                if($row->warrenty_status == 'P'){
                    $btn = 'progress';
                }else if($row->warrenty_status == 'A')
                {
                    $btn = 'active';
                }else if($row->warrenty_status == 'C')
                {  $btn = 'complete';}
                 return $btn;
            }) 
            ->addColumn('edit', function($row){
               
                $btn = 'Edit';
               
                 return $btn;
            })    
            ->addColumn('edit', 'workshop.incedentactionsEdit')
            ->addColumn('activation', 'workshop.incedentactionsStatus')
                ->rawColumns(['edit','activation'])
                ->make(true);
        }

        return view('workshop.warrentyincedent_list');
    }
    public function edit_incedent($id)
    {
        $savestatus = 'E';
        $ID = decrypt($id); 
        $info = Warrent_incident::select('*')
        ->where('id', $ID)
        ->orderBy('warenty_incidents.id','ASC')
        ->get();
        //$workshopid = Auth::user()->workshopid;
        return view('workshop.report_incedent')->with('info',$info)->with('savestatus',$savestatus);
        
    }

    public function exam_sessions(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $exam_sessions =Exam::select('*')->where('starting_date', '>', $date)->orderby('id', 'ASC')->get();
        $enroll_exams = \DB::table('enroll_exams')
            ->join('exam', 'exam.id', '=', 'enroll_exams.exam_id')
            ->select('exam.starting_date','exam.starting_time')
            ->where('enroll_exams.workshop_id', $workshopid)
            ->where('exam.starting_date', '>', $date)
            ->get();

       
        return view('workshop.exam_calendar')->with('exam_sessions',$exam_sessions)->with('enroll_exams',$enroll_exams);
    }

    public function enroll_exam(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
        $user_name = Auth::user()->name;
        
        
        $data_arry = array();
        $data_arry['exam_id'] = base64_decode($request->exam_id);
        $data_arry['workshop_id'] = $workshopid;
        $data_arry['exam_status'] = 'P';

        $previous_enroll = EnrollExams::select('*')->where('workshop_id', $workshopid)->where('exam_id', base64_decode($request->exam_id))->get();
        if(!count($previous_enroll)>0)
        {
            $enroll_training = EnrollExams::create($data_arry);
            \Mail::send('mail.enroll_exam_mail', ['workshop_id' => $workshopid, 'name' => $user_name ], function($message){
                $user_email = Auth::user()->email;
                $message->to($user_email);
                $message->subject('Enroll with Exam');
            });
            return true;
        }else{
            return response()->json(['error' => 'Already enrolled to this Exam'], 403);
        }
    }

    public function start_exam(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
        //$enroll_exam_data = EnrollExams::select('*')->where('workshop_id', $workshopid)->where('exam_status','P')->get();

        $enroll_exams = \DB::table('enroll_exams')
            ->join('exam', 'exam.id', '=', 'enroll_exams.exam_id')
            ->select('exam.*','enroll_exams.created_at AS enrolled_date')
            ->where('enroll_exams.workshop_id', $workshopid)
            ->where('enroll_exams.exam_status', '=' ,'P')
            ->get();
        if($enroll_exams->count()>0)
        {
            return view('workshop.enroll_exams_feature')->with('enroll_exams',$enroll_exams);
        }else{
            return view('workshop.exam_expired');
        }
        
    }

    public function load_exam_paper($id)
    {
        $workshopid = Auth::user()->workshopid;
        // //$enroll_exam_data = EnrollExams::select('*')->where('workshop_id', $workshopid)->where('exam_status','P')->get();
        // $enroll_exams = \DB::table('enroll_exams')
        //     ->join('exam', 'exam.id', '=', 'enroll_exams.exam_id')
        //     ->select('exam.*','enroll_exams.created_at AS enrolled_date')
        //     ->where('enroll_exams.workshop_id', $workshopid)
        //     ->where('enroll_exams.exam_status', '=' ,'P')
        //     ->get();
        $exam_id = decrypt($id);
        //$exam_data = Exam::select('*')->where('id', $exam_id)->get();
        //$exam_qestions = ExamQuestions::
        $mcq_questions = \DB::table('exam_questions')
            ->join('quizzes', 'quizzes.id', '=', 'exam_questions.question_id')
            ->select('quizzes.*')
            ->where('exam_questions.quiz_type','MCQ')
            ->where('exam_questions.exam_id', '=' ,$exam_id)
            ->orderBy('exam_questions.id','ASC')
            ->get();
        $structured_exam = \DB::table('exam_questions')
            ->join('structured_questions', 'structured_questions.id', '=', 'exam_questions.question_id')
            ->select('structured_questions.*')
            ->where('exam_questions.quiz_type','STRU')
            ->where('exam_questions.exam_id', '=' ,$exam_id)
            ->orderBy('exam_questions.id','ASC')
            ->get();
        //var_dump($mcq_questions);die();
        return view('workshop.exam_paper')->with('mcq_quiz',$mcq_questions)->with('struct_quiz',$structured_exam)->with('exam_id',$exam_id);
    }

    public function submit_exampaper(Request $request)
    {
        //dd(decrypt($request->exam_id));
        $workshopid = Auth::user()->workshopid;
        $exam_id = decrypt($request->exam_id);
        $mcq_questions = \DB::table('exam_questions')
            ->join('quizzes', 'quizzes.id', '=', 'exam_questions.question_id')
            ->select('quizzes.*')
            ->where('exam_questions.quiz_type','MCQ')
            ->where('exam_questions.exam_id', '=' ,$exam_id)
            ->orderBy('exam_questions.id','ASC')
            ->get();
        foreach($mcq_questions as $mcqs)
        {
            $mcq_id = $mcqs->id;
            $mcq_ansrs = QuizAnswers::select('id','answer','marks')
            ->where('quiz_id', $mcq_id)
            ->orderBy('id', 'desc')
            ->get();
            foreach($mcq_ansrs as $mcq_ans)
            {
                $mcq_ans_id = $mcq_ans->id;
                $answer_name = 'ANS_'.$mcq_id.'_'.$mcq_ans_id;
                $answer =  $request->$answer_name;

                $ans_array = array();
                $ans_array['quiz_attempt_id'] = $exam_id;
                $ans_array['quiz_id'] = $mcq_id;
                $ans_array['workshop_id'] = $workshopid;
                $ans_array['answer'] = $answer;

                QuizAttemptAnswers::create($ans_array);
                  
            }
            
        }
        $structured_exam = \DB::table('exam_questions')
            ->join('structured_questions', 'structured_questions.id', '=', 'exam_questions.question_id')
            ->select('structured_questions.*')
            ->where('exam_questions.quiz_type','STRU')
            ->where('exam_questions.exam_id', '=' ,$exam_id)
            ->orderBy('exam_questions.id','ASC')
            ->get();
        foreach($structured_exam as $mcqs)
        {
            $structured_id = $mcqs->id;

            $answer_name = 'STRUC_'.$structured_id;
            $answer =  $request->$answer_name;

            $stru_array = array();
            $stru_array['exam_id'] = $exam_id;
            $stru_array['structured_id'] = $structured_id;
            $stru_array['workshop_id'] = $workshopid;
            $stru_array['quest_answer'] = $answer;
            //var_dump( $ans_array);die();
            StructuredqAnswer::create($stru_array);
            
        }
        EnrollExams::where(['workshop_id' =>  $workshopid, 'exam_status' => 'P'])->update(['exam_status' => 'C']);
        return redirect()->route('workshop.exam-complete')
        ->with('success', 'Incedent Recorded successfully.');
    }
    
    public function complete_exam(Request $request)
    {
        return view('workshop.exam_complete');
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
