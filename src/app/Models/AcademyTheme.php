<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademyTheme extends Model
{
    use HasFactory;

    protected $table = 'academy_themes';
    public $timestamps = false;
    protected $fillable = [
        'label',
        'alias',
        'icon',
        'ordre'
    ];


    public function getFirstCourse(){
        $course = AcademyCourse::where('theme_id','=',$this->id)
                                ->where('ordre','=',1)
                                ->first();

        if($course){
            return $course;
        }else{
            return null;
        }

        // $childtheme = AcademyTheme::getList(
        //     array(
        //         'where' => array(
        //             'Parent' => $this->get('ID'),
        //             'Ordre' => 1
        //         )
        //     )
        // );

        // if($childtheme){
        //     return $childtheme[0]->getFirstCourse();
        // }else{
        //     return null;
        // }

    }


    public function getLastCourse(){
        $course = AcademyCourse::where('theme_id','=',$this->id)
                                ->orderByDesc('ordre')
                                ->orderByDesc('id')
                                ->first();

        if($course){
            return $course;
        }else{
            return null;
        }


    }

    public function getCourses(){
        $courses = AcademyCourse::where('theme_id','=',$this->id)
                                ->orderBy('ordre')
                                ->get();

        $result = array();
        foreach ($courses as $item) {
            $res = array();
            $res['id'] = 'course_'.$item->id;
            $res['alias'] = $item->alias;
            $res['name'] = $item->label;
            $res['children'] = [];
            $res['type'] = 'course';
            $res['icon'] = false;
            $res['state'] = [
                'icon' => 'mdi-check-circle',
                'color' =>'success' 
            ];
            $res['min'] = $item->min;
            $res['type_course'] = $item->type;
            $result[] = $res;
        }

        return $result;
    }


    public function details()
    {
        $courses = AcademyCourse::where('theme_id',$this->id)->count();
        $total_min = AcademyCourse::where('theme_id',$this->id)->sum('min');

        $result = array();
        $result['count_courses'] = $courses;
        $result['total_minutes'] = $total_min;

        return $result;
    }

}
