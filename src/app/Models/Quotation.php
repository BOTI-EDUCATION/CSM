<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = [
        'type',
        'niveaux',
        'nombre',
        'pack',
        'ville',
        'tel',
        'email',
        'nom',
        'deleted_at'
    ];
    public function files(){
        return $this->hasMany(QuotationFile::class,'quotation_id','id');
    }

    public function getArrayFile(){
        $files = array();

        foreach ($this->files()->get() as $file) {
            $files[] = [
                'label' => $file->titre,
                'path' => $file->file
            ];
        }

        return $files;
    }
}
