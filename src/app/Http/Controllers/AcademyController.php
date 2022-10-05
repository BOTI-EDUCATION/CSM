<?php

namespace App\Http\Controllers;

use App\Models\AcademyCourse;
use App\Models\AcademyTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AcademyController extends Controller
{
    //


    function getAcademyTree(){

        $result = array();
        
        foreach (AcademyTheme::orderBy('ordre')->get() as $key => $item) {
            // $states[$item->alias];

            $res = array();
            $res['id'] = 'theme_'.$item->id;
            $res['alias'] = $item->alias;
            $res['name'] = $item->label;
            $res['children'] = $item->getCourses();
            $res['type'] = 'theme';
            $res['icon'] = false;
            $res['state'] = [
                'icon' => 'mdi-clock-time-four',
                'color' => 'warning'
            ];
            $result[] = $res;
        }
        
        return response()->json($result) ;
    }

    function getCourse($id){

        $course = AcademyCourse::find($id);
        $result = [
            'id' => $course->id,
            'label' => $course->label,
            'alias' => $course->alias,
            'description' => $course->description,
            'type' => $course->type,
            'video' => $course->video,
            'content' => $course->content,
            'theme_alias' => $course->theme->alias,
            'has_quiz' => ($course->quiz()->count()>0?true:false),
        ];

        return response()->json($result) ;
    }
   
    function getNextCourse($id){

        $curr_course = AcademyCourse::find($id);

        $course = $curr_course->getNext();

        if($course){
            $result = [
                'id' => $course->id,
                'label' => $course->label,
                'description' => $course->description,
                'type' => $course->type,
                'video' => $course->video,
                'content' => $course->content,
            ];
        }else{
            $result = [
                'done' => true
            ];
        }

        return response()->json($result) ;
    }
    
    function getPrevCourse($id){

        $curr_course = AcademyCourse::find($id);

        $course = $curr_course->getPrev();

        if($course){
            $result = [
                'id' => $course->id,
                'label' => $course->label,
                'description' => $course->description,
                'type' => $course->type,
                'video' => $course->video,
                'content' => $course->content,
            ];
        }else{
            $result = [
                'done' => true
            ];
        }

        return response()->json($result) ;
    }

    function getQuiz($idCourse){
        $courseObj = AcademyCourse::find($idCourse);

        $course = [
            'id' => $courseObj->id,
            'label' => $courseObj->label,
            'alias' => $courseObj->alias,
            'theme_alias' => $courseObj->theme->alias,
        ];

        $quizObj = $courseObj->quiz;

        $quiz = [
            'id' => $quizObj->id,
            'alias' => $quizObj->alias,
            'label' => $quizObj->label,
            'description' => $quizObj->label,
            'questions' => []
        ];

        foreach ($quizObj->questions()->orderBy('ordre')->get() as $question) {
            $quiz['questions'][] = [
                'id' => $question->id,
                'label'=>$question->titre,
                'description'=>$question->description,
                'img'=>$question->getImage(),
                'alias'=>$question->alias,
                'type'=>$question->type,
                'options'=>json_decode($question->options),
                'correct_answer'=>json_decode($question->options),
            ];
        }

        $result= [
            'course' => $course,
            'quiz' => $quiz
        ];

        return response()->json($result);

    }

}
