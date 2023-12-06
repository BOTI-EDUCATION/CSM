<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\AcademyCourse;
use App\Models\AcademyTheme;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Http;

class AcademyController extends Controller
{
    //


    function getAcademyTree()
    {

        $result = array();

        foreach (AcademyTheme::orderBy('ordre')->get() as $key => $item) {
            // $states[$item->alias];

            $res = array();
            $res['id'] = 'theme_' . $item->id;
            $res['alias'] = $item->alias;
            $res['name'] = $item->label;
            $res['children'] = $item->getCourses();
            $res['type'] = 'theme';
            $res['icon'] = false;
            $res['state'] = [
                'icon' => 'mdi-clock-time-four',
                'color' => 'warning'
            ];
            $res['details'] = $item->details();
            $result[] = $res;
        }
        
        return response()->json($result);
    }

    // ! --------------------------------- ABOUT THEMES
    public function themes()
    {
        $themes = AcademyTheme::orderBY('ordre')->get();
        return response()->json($themes);
    }


    public function newTheme(Request $request)
    {
        $theme_id  = $request->theme_id;
        $theme_id ? $newTheme = AcademyTheme::find($theme_id) : $newTheme = new AcademyTheme();
        $newTheme->label = $request->label;
        $newTheme->ordre = $request->order;
        $newTheme->alias = Helpers::getAlias($request->label);
        $newTheme->save();

        return response()->json($newTheme, 201);
    }

    public function deleteTheme($id)
    {
        AcademyTheme::find($id)->delete();
        return response()->json('deleted');
    }

    public function updateOrder(Request $request)
    {
        $ids = json_decode($request->ids);
        foreach ($ids as $key => $value) {
            $theme = AcademyTheme::find($value);
            $theme->ordre = $key + 1 ;
            $theme->save();
        };
    }
 

    // ! --------------------------------- ABOUT COURSES

    public function courses()
    {
        $courses = AcademyCourse::orderBy('ordre')->get();
        $themes  = AcademyTheme::orderBY('ordre')->select('label','id')->get();
        $datas   = [];

        foreach ($courses as  $course) {
            $datas [] = [
                'id' => $course->id,
                'label' => $course->label,
                'alias' => $course->alias,
                'description' => $course->description,
                'type' => $course->type,
                'video' => $course->video,
                'content' => $course->content,
                'ordre' => $course->ordre,
                'theme' => $course->theme->label,
                'min' => $course->min,
            ];
        }
        return response()->json([
            'themes' => $themes,
            'datas'  => $datas
        ]);
    }

    public function newCourse(Request $request)
    {
        $course_id = $request->course_id;
        $course_id ? $course = AcademyCourse::find($course_id) : $course = new AcademyCourse();
        $course->label       = $request->label;
        $course->description = $request->desc;
        $course->alias       = Helpers::getAlias($request->label);
        $course->type        = $request->type;
        $course->video       = $request->link;
        $course->content     = $request->content;
        $course->ordre       = $request->ordre;
        $course->theme_id    = $request->theme;
        $course->min         = $request->min;
        $course->save();

        $data = [
            'id' => $course->id,
            'label' => $course->label,
            'description' => $course->description,
            'alias' => $course->alias,
            'type' => $course->type,
            'video' => $course->video,
            'content' => $course->content,
            'ordre' => $course->ordre,
            'theme' => $course->theme->label,
            'min' => $course->min,
        ];

        return response()->json($data,201);
    }

    public function deleteCourse($id)
    {
        AcademyCourse::find($id)->delete();
        return response()->json("course deleted");
    }

    public function updateCoursesOrdres(Request $request)
    {
        $ids = json_decode($request->ids);
        foreach ($ids as $key => $value) {
            $theme = AcademyCourse::find($value);
            $theme->ordre = $key + 1 ;
            $theme->save();
        };
    }

    function getCourse($id)
    {

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
            'has_quiz' => ($course->quiz()->count() > 0 ? true : false),
            'theme_label' => $course->theme->label,
            'min' => $course->min
        ];

        return response()->json($result);
    }

    function getNextCourse($id)
    {

        $curr_course = AcademyCourse::find($id);

        $course = $curr_course->getNext();

        if ($course) {
            $result = [
                'id' => $course->id,
                'label' => $course->label,
                'description' => $course->description,
                'type' => $course->type,
                'video' => $course->video,
                'content' => $course->content,
            ];
        } else {
            $result = [
                'done' => true
            ];
        }

        return response()->json($result);
    }

    function getPrevCourse($id)
    {

        $curr_course = AcademyCourse::find($id);

        $course = $curr_course->getPrev();

        if ($course) {
            $result = [
                'id' => $course->id,
                'label' => $course->label,
                'description' => $course->description,
                'type' => $course->type,
                'video' => $course->video,
                'content' => $course->content,
            ];
        } else {
            $result = [
                'done' => true
            ];
        }

        return response()->json($result);
    }

    function getQuiz($idCourse)
    {
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
                'label' => $question->titre,
                'description' => $question->description,
                'img' => $question->getImage(),
                'alias' => $question->alias,
                'type' => $question->type,
                'options' => json_decode($question->options),
                'correct_answer' => json_decode($question->options),
            ];
        }

        $result = [
            'course' => $course,
            'quiz' => $quiz
        ];

        return response()->json($result);
    }
}
