<?php

namespace App\Http\Controllers\SchoolLife;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\SL_News;
use App\Models\SL_Quote;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    //

    function insertArticle(Request $request)
    {
        // dd($request->allFiles());
        // exit;
         $article = new Article();
         $article->label = $request->label;
         $article->details = $request->details;
         $article->introduction = $request->introduction;
            $blogs = [];
        $tmp=$request->allFiles();
         foreach($request->blogs as $blogItem){
            $blog_images = [];
            $blog = json_decode($blogItem);
            if(isset($tmp['images_'.$blog->order])&&$tmp['images_'.$blog->order]){
                $index = 0;
                foreach ($tmp['images_'.$blog->order] as $image) {
                   
                    $filename = "image_".$article->id.'_blog_'.strval($blog->order).'_'.strval($blog->order+$index).'.'.$image->extension();
                    $index++;
                    $image->storeAs('schoolLife_articles',$filename,'public');
                    $blog_images[] =  $filename;

                }
            }
            $blog->images = $blog_images;
            $blogs[] = $blog;
         }
         $article->blogs = json_encode($blogs);
         $article->keywords = json_encode($request->tags);
         $article->keys = json_encode($request->keys);

         $article->save();

         if($request->hasFile('image')){
            $file=$request->file("image");
            $filename = "image_".$article->id.'.'.$file->extension();
            $file->storeAs('schoolLife_articles',$filename,'public');
            $article->update(['image'=>$filename]);  
        }


        // return response()->json($request->all());
    }

    function getAllArticles(){
        $articles = Article::all();
        $data = array();

        foreach ($articles as $article) {
            
            $data[] = [
                'id' => $article->id,
                'label' => $article->label,
                'introduction'=>$article->introduction,
                'details' => $article->details,
                'image' => $article->getPicture(),
                'Keys' => json_decode($article->keys),
                'blogs' => json_decode($article->blogs),
                'keywords' => json_decode($article->keywords),
            ];
        }
        return response()->json($data);
    }

    public function getArticleDetails(Request $request , $id){
        $article = Article::find($id);
        $data = [
            'id' => $article->id,
                'label' => $article->label,
                'introduction'=>$article->introduction,
                'details' => $article->details,
                'image' => $article->getPicture(),
                'Keys' => json_decode($article->keys),
                'blogs' => json_decode($article->blogs),
                'keywords' => json_decode($article->keywords),
        ];
        return response()->json($data);
    }
    function InsertNewQuote(Request $request){
        $quote= new SL_Quote();
        $quote->text = $request->texte;
        $quote->image = $request->image;
        $quote->Author = $request->author;
        $quote->function = $request->function;
        $quote->save();
        if($request->hasFile('image')){
            $file=$request->file("image");
            $filename = "image_".$quote->id.'.'.$file->extension();
            $file->storeAs('schoolLife_quotes',$filename,'public');
            $quote->update(['image'=>$filename]);  
        }
    }

    function InsertNews(Request $request){

        $news= new SL_News();
        $news->label = $request->label;
        $news->image = $request->image;
        $news->intro = $request->introduction;
        $news->text = $request->texte;
        $news->link = $request->link;
        $news->save();
        if($request->hasFile('image')){
            $file=$request->file("image");
            $filename = "image_".$news->id.'.'.$file->extension();
            $file->storeAs('schoolLife_news',$filename,'public');
            $news->update(['image'=>$filename]);  
        }
    }

    function getAllNews(){
        $news = SL_News::all();
        $data = array();

        foreach ($news as $new) {
            
            $data[] = [
                'id' => $new->id,
                'label' => $new->label,
                'image' => $new->getPicture(),
                'introduction'=>$new->intro,
                'text' => $new->text,
                'link' => $new->link,
                'date' => $new->created_at,
            ];
        }
        return response()->json($data);
    }

    function getAllQuotes(){
        $quotes = SL_Quote::all();
        $data = array();

        foreach ($quotes as $quote) {
            
            $data[] = [
                'id' => $quote->id,
                'text' => $quote->text,
                'image' => $quote->getPicture(),
                'Author'=>$quote->Author,
                'function' => $quote->function,
                'date' => $quote->created_at,
            ];
        }
        return response()->json($data);
    }
    public function getNewsDetails(Request $request , $id){
        $new = SL_News::find($id);
        $data = [
            'id' => $new->id,
            'label' => $new->label,
            'image' => $new->getPicture(),
                'introduction'=>$new->intro,
                'text' => $new->text,
                'link' => $new->link,
                'date' => $new->created_at,
        ];
        return response()->json($data);
    }

    public function getQuoteDetails(Request $request , $id){
        $quote = SL_Quote::find($id);
        $data = [
            'id' => $quote->id,
                'text' => $quote->text,
                'image' => $quote->getPicture(),
                'Author'=>$quote->Author,
                'function' => $quote->function,
                'date' => $quote->created_at,
        ];
        return response()->json($data);
    }

}
