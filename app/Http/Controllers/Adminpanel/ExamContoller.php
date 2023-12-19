<?php

namespace App\Http\Controllers\adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Workshops;
use App\Models\TrainingMeterial;
use App\Models\Trainning;
use App\Models\Quiz;
use App\Models\Exam;
use App\Models\ExamQuestions;
use App\Models\QuizAnswers;
use App\Models\StructuredQuestions;
use App\Models\State;
use App\Models\City;
use App\Models\Address;
use DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Storage;

class ExamContoller extends Controller
{
    function __construct()
    {

        $this->middleware('permission:exam-list|exam-create|exam-edit|exam-delete', ['only' => ['list']]);
        $this->middleware('permission:exam-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:exam-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:exam-list', ['only' => ['list']]);
        $this->middleware('permission:exam-list|exam-create|exam-edit|exam-delete', ['only' => ['list']]);

        $this->middleware('permission:exam-questions-list|exam-questions-create|exam-questions-edit|exam-questions-delete', ['only' => ['list']]);
        $this->middleware('permission:exam-questions-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:exam-questions-edit', ['only' => ['edit', 'update','activation']]);
        $this->middleware('permission:exam-questions-list', ['only' => ['list']]);
        $this->middleware('permission:exam-questions-list|exam-questions-create|exam-questions-edit|exam-questions-delete', ['only' => ['list']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savestatus= 'A';
        $quizes = Quiz::where('status', '=', '1')->get();
        $struct_quiz = StructuredQuestions::where('status', '=', '1')->get();
        return view('adminpanel.exam.index')->with('savestatus',$savestatus)->with('struct_quiz',$struct_quiz)->with('quizes',$quizes);
    }

     public function datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Exam::select('exam.*')
                    ->orderBy('exam.id','DESC')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.exam.actionsEdit')
                ->addColumn('activation', 'adminpanel.exam.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.exam.list');
    }

    /**
     * Store a newly created resource in storage.
     * update existing resources 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
            $request->validate([
                'vName' => 'required|max:50',
                'starting_date' => 'required',
                'duration' => 'required',
                'description' => 'required|max:2500',
                'clockpicker' => 'required',
                'mcq_quez' => 'required',
                'structured_quiz' => 'required',
            ]);
       
      
        $data_arry = array();
        $data_arry['name'] = $request->vName;
        $data_arry['duration'] = $request->duration;
        $data_arry['description'] = $request->description;
        $data_arry['starting_date'] = $request->starting_date;
        $data_arry['starting_time'] = $request->clockpicker;
        $data_arry['mcq_quez'] = $request->mcq_quez;
        $data_arry['structured_quiz'] = $request->structured_quiz;
        $data_arry['status'] = $request->status;
        $mcq_arry = array();
       
        if($request->savestatus == 'A'){
            
            $id= Exam::create($data_arry);
            foreach($request->mcq_quiz as $key => $mcq_list)
            {
                $mcq_array = explode('_', $mcq_list);
                //dd( $key);
                $mcq_arry[$key]['question_id']=$mcq_array[1];
                $mcq_arry[$key]['quiz_type']=$mcq_array[0];
                $mcq_arry[$key]['exam_id']= $id->id;
            }
            ExamQuestions::insert($mcq_arry);
               
             \LogActivity::addToLog('New Exam added('.$id->id.').');
            return redirect('new-exam')->with('success', 'New Exam created successfully');
        }else{
            
            $recid = $request->id;
            ExamQuestions::where('exam_id',decrypt($recid))->delete();
            //dd($request->mcq_quiz);
            foreach($request->mcq_quiz as $key => $mcq_list)
            {
                $mcq_array = explode('_', $mcq_list);
                
                $mcq_arry[$key]['question_id']=$mcq_array[1];
                $mcq_arry[$key]['quiz_type']=$mcq_array[0];
                $mcq_arry[$key]['exam_id']=decrypt($recid);
                
            }
            //dd($mcq_arry);
            ExamQuestions::insert($mcq_arry);
            //die();
            Exam::where('id', decrypt($recid))->update($data_arry);
             
            \LogActivity::addToLog('training ' . $request->business_name . ' updated(' . decrypt($recid) . ').');
            return redirect('/edit-exam/'.$recid.'')->with('success', 'Exam updated successfully');
        }

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
        $info = Exam::where('id', '=', $ID)->get();
        $quizes = Quiz::where('status', '=', '1')->get();
        $struct_quiz = StructuredQuestions::where('status', '=', '1')->get();
        $exam_quest = ExamQuestions::where('exam_id', '=', $ID)->get();
        
        $savestatus = 'E';
        return view('adminpanel.exam.index')->with('info',$info)->with('savestatus',$savestatus)->with('quizes',$quizes)->with('struct_quiz',$struct_quiz)->with('exam_quest',$exam_quest);
       
    }
    
    
    public function activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Workshops::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('workshop record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('workshop-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('workshop record '.$data->name.' activated('.$id.')');

            return redirect()->route('workshop-list')
            ->with('success', 'Record activate successfully.');
        }

    }
    
     public function get_state_cities(Request $request)
    {
        $cityID =  $request->stateID;
        $city['data'] = City::select('*')->where("state_id", $cityID)->orderBy('name','ASC')->get();
        return response()->json($city);
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
    public function deletemeterial($id)
    {
       
        $training_meterials = TrainingMeterial::select('fImage')->where('id', decrypt($id))->first();
       
        if(Storage::exists($training_meterials->fImage)){
            Storage::delete($training_meterials->fImage);
            $res=TrainingMeterial::where('id',decrypt($id))->delete();
            return redirect()->back()->with('success',"Training meterial removed");
        }else{
            return redirect()->back()->with('error',"There is s problem in meterial removal.Please try again.");
        }
    }

    /////Questions 
    public function questions_landing()
    {
        $savestatus= 'A';
        $state =State::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        return view('adminpanel.quiz.index')->with('state',$state)->with('savestatus',$savestatus)->with('city',$city);
    }

     public function questions_datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = Quiz::select('quizzes.*')
                    ->orderBy('quizzes.id','DESC')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.quiz.actionsEdit')
                ->addColumn('activation', 'adminpanel.quiz.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.quiz.list');
    }
    public function store_quiz(Request $request)
    {
        if ($request->savestatus == 'A') {
            $request->validate([
                'vName' => 'required|max:50',
                'description' => 'required',
            ]);
        } else {
            $request->validate([
                'vName' => 'required|max:50',
                'description' => 'required',
            ]);
        }
      
        $data_arry = array();
        $answer_arry = array();
        $data_arry['name'] = $request->vName;
        $data_arry['description'] = $request->description;
        $data_arry['status'] = $request->status;
        $answer_arry = $request->quiz_answer;
        //var_dump(count($answer_arry));die();
       
        if($request->savestatus == 'A'){
            
            $id= Quiz::create($data_arry);
                //file upload 
               foreach($answer_arry as  $key => $ans_arr)
               {
                if(count($answer_arry)>$key){
                    $varibl = 'correct_answer_checkbox'.$key;
                    $insert[$key]['answer']=$ans_arr;
                    $insert[$key]['marks']= $request->$varibl;
                    $insert[$key]['quiz_id'] = $id->id;
                   
                }
                    
               }
               QuizAnswers::insert($insert);
              
             \LogActivity::addToLog('New MCQ added('.$id->id.').');
            return redirect('new-examquestion-quiz')->with('success', 'New MCQ created successfully');
        }else{
            
            $recid = $request->id;
            //$addressid = $request->adddressid;
            //Address::where('id', decrypt($addressid))->update($addresses_arry);
            Quiz::where('id', decrypt($recid))->update($data_arry);
             //file upload 
             QuizAnswers::where('quiz_id',decrypt($recid))->delete();
             foreach($answer_arry as  $key => $ans_arr)
               {
                if(count($answer_arry)>$key){
                    $varibl = 'correct_answer_checkbox'.$key;
                    $insert[$key]['answer']=$ans_arr;
                    $insert[$key]['marks']= $request->$varibl;
                    $insert[$key]['quiz_id'] = decrypt($recid);
                   
                }
                    
               }
               QuizAnswers::insert($insert);

            \LogActivity::addToLog('MCQ updated(' . decrypt($recid) . ').');
            return redirect('/edit-exam-quiz/'.$recid.'')->with('success', 'MCQ updated successfully');
        }

    }
    public function edit_question($id)
    {
        $ID = decrypt($id);
        $info = Quiz::where('id', '=', $ID)->get();
        $quizanswers =QuizAnswers::select('*')->where('quiz_id', $ID)->orderBy('id','ASC')->get();
        // $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        // $addressID = $info[0]->addressID; 
        // $addressinfo = Address::where('id','=',$addressID)->get();
        $savestatus = 'E';
        return view('adminpanel.quiz.index')->with('info',$info)->with('savestatus',$savestatus)->with('quizanswers',$quizanswers);
        //return view('adminpanel.masterdata.complain_category.edit', ['data' => $data]);
        //return view('adminpanel.masterdata.complain_category.edit');
    }
    public function quiz_activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  Quiz::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('MCQ record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('exam-questions-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('MCQ record '.$data->name.' activated('.$id.')');

            return redirect()->route('exam-questions-list')
            ->with('success', 'Record activate successfully.');
        }

    }
    //Structurd Questions
    public function structured_landing()
    {
        $savestatus= 'A';
        $state =State::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        return view('adminpanel.structured_question.index')->with('state',$state)->with('savestatus',$savestatus)->with('city',$city);
    }

     public function structured_datalist(Request $request)
    {
        if ($request->ajax()) {
            $data = StructuredQuestions::select('structured_questions.*')
                    ->orderBy('structured_questions.id','DESC')
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'adminpanel.structured_question.actionsEdit')
                ->addColumn('activation', 'adminpanel.structured_question.actionsStatus')
                ->rawColumns(['edit', 'activation'])
                ->make(true);
        }

        return view('adminpanel.structured_question.list');
    }
    public function store_structured(Request $request)
    {
        if ($request->savestatus == 'A') {
            $request->validate([
                'vName' => 'required|max:50',
                'description' => 'required',
            ]);
        } else {
            $request->validate([
                'vName' => 'required|max:50',
                'description' => 'required',
            ]);
        }
      
        $data_arry = array();
        $answer_arry = array();
        $data_arry['name'] = $request->vName;
        $data_arry['description'] = $request->description;
        $data_arry['status'] = $request->status;
       
       
        if($request->savestatus == 'A'){
            
            $id= StructuredQuestions::create($data_arry);
                         
             \LogActivity::addToLog('New Structured Question added('.$id->id.').');
            return redirect('new-structured-question')->with('success', 'New Structured Question created successfully');
        }else{
            
            $recid = $request->id;
           
            StructuredQuestions::where('id', decrypt($recid))->update($data_arry);
             //file upload 
            
            \LogActivity::addToLog('Structured Question updated(' . decrypt($recid) . ').');
            return redirect('/edit-structured-quiz/'.$recid.'')->with('success', 'Structured Question updated successfully');
        }

    }
    public function edit_structured_question($id)
    {
        $ID = decrypt($id);
        $info = StructuredQuestions::where('id', '=', $ID)->get();
       
       
        $savestatus = 'E';
        return view('adminpanel.structured_question.index')->with('info',$info)->with('savestatus',$savestatus);
       
    }
    public function structured_activation(Request $request)
    {
        $id = decrypt($request->id);

        $data =  StructuredQuestions::find($id);

        if ( $data->status == "1" ) {

            $data->status = '0';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('MCQ record '.$data->name.' deactivated('.$id.')');

            return redirect()->route('structured-questions-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "1";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('MCQ record '.$data->name.' activated('.$id.')');

            return redirect()->route('structured-questions-list')
            ->with('success', 'Record activate successfully.');
        }

    }
}
