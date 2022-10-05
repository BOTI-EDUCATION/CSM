<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademyCourse extends Model
{
    use HasFactory;

    protected $table = 'academy_courses';

    protected $fillable = [
        'label',
        'alias',
        'description',
        'type',
        'video',
        'content',
        'ordre'
    ];

    public function theme(){
        return $this->belongsTo(AcademyTheme::class,'theme_id','id');
    }

    public function quiz(){
        return $this->hasOne(AcademyQuiz::class,'course_id','id');
    }


    public function getNext(){

        $course = AcademyCourse::where('id','<>',$this->id)
                                ->where('theme_id','=',$this->theme->id)
                                ->where('ordre','=',($this->ordre?(intval($this->ordre)+1):2))
                                ->first();

        if($course){
            return $course;
        }

        // $siblingtheme = \models\ADM\AcademyTheme::getList(
        //     array(
        //         'where' => array(
        //             'Parent' => $this->get('Theme'),
        //             'Ordre' => ($this->get('Ordre')?(intval($this->get('Ordre'))+1):2)
        //         )
        //     )
        // );

        // if($siblingtheme){

        //     return $siblingtheme[0]->getFirstCourse();
        // }


        $parent_theme =  $this->theme;

        $nextTheme = AcademyTheme::where('id','<>',$parent_theme->id)
                                ->where('ordre','=',($parent_theme->ordre?(intval($parent_theme->ordre)+1):2))
                                ->first();

        if($nextTheme){
            return $nextTheme->getFirstCourse();
        }else{
            return null;
        }

    }

    public function getPrev(){

        $course = AcademyCourse::where('id','<>',$this->id)
                                ->where('theme_id','=',$this->theme->id)
                                ->where('ordre','=',($this->ordre&&$this->ordre!=1?(intval($this->ordre)-1):1))
                                ->first();

        if($course){
            return $course;
        }

        // $siblingtheme = \models\ADM\AcademyTheme::getList(
        //     array(
        //         'where' => array(
        //             'Parent' => $this->get('Theme'),
        //             'Ordre' => ($this->ordre?(intval($this->ordre)+1):2)
        //         )
        //     )
        // );

        // if($siblingtheme){

        //     return $siblingtheme[0]->getFirstCourse();
        // }


        $parent_theme =  $this->theme;

        $nextTheme = AcademyTheme::where('id','<>',$parent_theme->id)
                                ->where('ordre','=',($parent_theme->ordre&&$parent_theme->ordre!=1?(intval($parent_theme->ordre)-1):1))
                                ->first();

        if($nextTheme){
            return $nextTheme->getLastCourse();
        }else{
            return null;
        }

    }
	
    

}
