<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function getpostsList(){

        $data = array();

        $posts = Post::orderBy('created_at','desc')->get();

        foreach($posts as $post){
            $data[] = [
                'id'=> $post->id,
                'label'=> $post->label,
                'alias'=> $post->alias,
                'details'=> $post->details,
                'image'=> $post->getImage(),
                'date'=> $post->date_publication,
                'enabled'=> $post->enabled,
                'views'=> $post->views()->count(),
            ];
        }

        return response()->json($data);
        
    }



    public function savePost(Request $request){
        

        if($request->post){
            $post = Post::find($request->post);
        }else{
            $post = new Post();
        }


        $post->label = $request->label;
        $post->details = $request->details;
        $post->date_publication = $request->date;
        $post->enabled = $request->enabled;
        $post->save();

        $post->update(['alias'=>Helpers::getAlias($post->id.' '.$request->label)]);

        if($request->hasFile('image')){
            $filename = $post->id.'-'.Helpers::getAlias($post->label).'.'.$request->image->extension();
            $request->image->storeAs('posts',$filename,'public');
            $post->update(['image'=>$filename]);
        }

        return response()->json('ok');
    }

    public function getPostFormInfo($id){
        $post = Post::find($id);
        $data = [
            'id'=> $post->id,
            'label'=> $post->label,
            'alias'=> $post->alias,
            'details'=> $post->details,
            'date'=> $post->date_publication,
            'image'=> $post->getImage(),
            'date'=> $post->date_publication,
            'enabled'=> $post->enabled,
            'views'=> $post->views()->count(),
        ];
        return response()->json($data);
    }

    public function deletePost($id){
        $post = Post::find($id);
        
        $post->delete();

        return $this->postsList();
    }

    public function changePostVisibility(Request $request , $id){
        $post = Post::find($id);

        $post->enabled = $request->visibility;

        $post->save();

        return $this->getPostFormInfo($id);
    }
}
