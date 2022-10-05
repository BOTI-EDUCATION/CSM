<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'youtube_id',
        'title',
        'presentation',
        'creation',
        'edit',
        'history',
        'roles',
        'order',
        'keywords'
    ];

    public $timestamps = false;

    public function themes(){
        return $this->belongsToMany(Theme::class,'theme_videos','video_id','theme_id');
    }

    public function getImage(){
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }
    public function getLink(){
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }
}
