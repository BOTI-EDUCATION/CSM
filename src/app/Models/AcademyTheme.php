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

    public function getCourses(){
        $courses = AcademyCourse::where('theme_id','=',$this->id)
                                ->orderBy('ordre')
                                ->get();
        
        // getList(
        //     array(
        //         'where' => array(
        //             'Theme' => $this->get('ID'),
        //         ),
        //         'order' => array(
        //             'Ordre' => true
        //         )
        //     )
        // );

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
            $result[] = $res;
        }

        return $result;
    }

}
