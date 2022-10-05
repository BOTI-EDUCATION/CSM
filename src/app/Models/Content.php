<?php

namespace App\Models;

use App\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'label',
        'image',
        'details',
        'visible'
    ];

    public function getImage(){
        if(!$this->image){
            $image = null;
            preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $this->details, $image);
            if(isset($image['src'])&&$image['src']){
                $url = $image['src'];
                $info = pathinfo($url);
                $contents = file_get_contents($url);
                $file = '/tmp/' . $info['basename'];
                file_put_contents($file, $contents);
                $uploaded_file = new UploadedFile($file, $info['basename']);
                $filename = $this->id.'-'.Helpers::getAlias($this->label).'.'.$uploaded_file->extension();
                $uploaded_file->storeAs('contents',$filename,'public');
                $this->update(['image'=>$filename]);
            }else{
                return null;
            }
        }

        return (asset('src/public/contents/'.$this->image));
    }
}
