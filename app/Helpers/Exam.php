<?php


namespace App\Helpers;
use Request;
use App\Models\LogActivity as LogActivityModel;
use App\Models\QuizAnswers;
use App\Models\RedeemPoints;

class Exam
{


    public static function get_quiz_answers($id) {
        $user_id = auth()->user()->id;
        $sql = QuizAnswers::select('id','answer','marks')
            ->where('quiz_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        if (!empty($sql)) {
            return $sql;
        } else {
            return NULL;
        }
    }
    public static function get_all_points()
    {
        $workshop_id = auth()->user()->workshopid;
        $total_points = RedeemPoints::where('workshop_id',$workshop_id)->sum('points');
        
        return $total_points;
    }

}