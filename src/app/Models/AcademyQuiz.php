<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademyQuiz extends Model
{
    use HasFactory;

    protected $table = 'academy_quiz';

    public function questions(){
        return $this->hasMany(AcademyQuizQuestion::class,'quiz_id','id');
    }
}
