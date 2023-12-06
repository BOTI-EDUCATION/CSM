<?php

namespace App\Http\Controllers\SchoolLife;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\articleCategory;
use App\Models\SL_News;
use App\Models\SL_Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogsController extends Controller
{
    //

    
    function insertArticle(Request $request)
    {

        $role = [
            "label" => "required|string",
            "image" => 'mimes:jpeg,png,jpg,gif',
            "details" => 'required|string',
            "introduction" => "required",
        ];
        if($request->hasVideo) {
            $role = [...$role, "video" => "mimes:mp4"];
        }
        // dd($request->all());
        if($request->haseDate) {
            $date = date('Y-m-d');
            $role = [...$role, "time_event" => "required", "date_event" => "required|after:$date"];
        }

        $validator =  Validator::make($request->all(), $role);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag(),
            ], 400);
        }


        $article = new Article();
        $article->label = $request->label;
        $article->details = $request->details;
        $article->introduction = $request->introduction;
      
        $article->keywords = json_encode($request->tags);
        $article->keys = json_encode($request->keys);
        $article->user_id = Auth::id();
        $article->category_id = $request->category;
        // dd(date('Y-m-d H:i:s', strtotime("$request->date_event $request->time_event")));
        if($request->haseDate) {
            $article->date_evenement = date('Y-m-d H:i:s', strtotime("$request->date_event $request->time_event"));
        }
    
        $article->visible = 0;
        if($request->visible == "true") {
            $article->visible = 1; 
            $article->date_publication = date('Y-m-d H:i:s');
        }

        // $category_id = articleCategory::where('label', $request->category);

        $article->save();

        if($request->hasFile('image')){
            $file=$request->file("image");
            $filename = "image_".$article->id.'.'.$file->extension();
            $file->storeAs('schoolLife_articles',$filename,'public');
            $article->update(['image' => $filename]);  
        }
        if($request->hasFile('video')){
            $file=$request->file("video");
            $filename = "video_".$article->id.'.'.$file->extension();
            $file->storeAs('podcast_video',$filename,'public');
            $article->update(['video' => $filename]);  
        }
    }
    function updateArticle(Request $request, $id)
    {
        // dd($request->date_event);
        $role = [
            "label" => "required|string",
            "details" => 'required|string',
            "introduction" => "required",
        ];
        
        if($request->hasFile('image')) {
            $role = [...$role, "image" => 'mimes:jpeg,png,jpg,gif',];
        }

        if($request->hasVideo) {
            $role = [...$role, "video" => "mimes:mp4"];
        }
        // dd($request->all());
        if($request->hasDate) {
            $date = date('Y-m-d');
            $role = [...$role, "time_event" => "required", "date_event" => "required|after:$date"];
        }

        $validator =  Validator::make($request->all(), $role);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag(),
            ], 400);
        }


        $article = Article::findOrfail($id);
        if($article) {
            $article->label = $request->label;
            $article->details = $request->details;
            $article->introduction = $request->introduction;
            $article->keywords = $request->keywords;
            $article->category_id = $request->category;

            if($request->hasDate) {
                $article->date_evenement = date('Y-m-d H:i:s', strtotime("$request->date_event $request->time_event"));
            } else {
                $article->date_evenement = null;
            }

            $article->visible = 0;
            if($request->visible == "true") {
                $article->visible = 1; 
                $article->date_publication = date('Y-m-d H:i:s');
            }

            if($request->hasFile('image')){
                if(Storage::delete('public/schoolLife_articles/' . $article->image)) {
                    $file=$request->file("image");
                    $filename = "image_".time().'.'.$file->extension();
                    $file->storeAs('schoolLife_articles',$filename,'public');
                    $article->image = $filename;  
                }
            }

            if($request->hasVideo) {
                if($request->hasFile('video')){
                    if(Storage::delete('public/schoolLife_articles/' . $article->video)) {
                        $file=$request->file("video");
                        $filename = "video_".time().'.'.$file->extension();
                        $file->storeAs('podcast_video',$filename,'public');
                        $article->video = $filename;  
                    }
                }
            } else {
                $article->video = null;  
            }
        }

        $article->save();

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


    function getLastArticles(){
        
        $educationCat = articleCategory::where('alias', 'education')->get(['id'])[0];
      
        $articles = Article::where('visible', 1)
                            ->where('category_id', $educationCat->id)
                            ->orderByDesc('date_publication')
                            ->limit(3)
                            ->get();
        $lastArticle = [];
        foreach ($articles as $value) {
            $lastArticle[] =  [
                'id' => $value->id,
                'introduction' => $value->label,
                'public' => date('Y-F-d H:i:s',strtotime( $value->date_publication)) ,
                'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
                'user' => [
                    'img' => $value->users->getPicture(),
                    'name' => $value->users->getNomComplet()
                ],
                'category' => $value->category->label,
            ];
        }

        $topArticleData =  Article::where('visible', 1)
                        ->where('category_id', $educationCat->id)
                        ->orderByDesc('likes')
                        ->limit(3)
                        ->get();

        $topArticle = [];
        foreach ($topArticleData as $value) {
            $topArticle[] =  [
                'id' => $value->id,
                'introduction' => $value->label,
                'public' => date('Y-F-d H:i:s',strtotime( $value->date_publication)) ,
                'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
                'user' => [
                    'name' => $value->users->getNomComplet()
                ],
                'category' => $value->category->label
            ];
        }
        
        $podcastCat = articleCategory::where('alias', 'podcast')->get(['id'])[0];
        $podcast =  Article::where('visible', 1)
                        ->where('category_id', $podcastCat->id)
                        ->orderByDesc('date_publication')
                        ->limit(6)
                        ->get();

        $podcastArt = [];
        foreach ($podcast as $value) {
            $podcastArt[] =  [
                'id' => $value->id,
                'label' => $value->label,
                'introduction' => $value->introduction,
                'public' => date('Y-F-d H:i:s',strtotime( $value->date_publication)) ,
                'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
                'category' => $value->category->label
            ];
        }
        $eventCat = articleCategory::where('alias', 'evenement')->get(['id'])[0];
        $event =  Article::where('visible', 1)
                        ->where('category_id', $eventCat->id)
                        ->orderByDesc('date_evenement')
                        ->limit(6)
                        ->get();

        $eventArt = [];
        foreach ($event as $value) {
            $eventArt[] =  [
                'id' => $value->id,
                'label' => $value->label,
                'introduction' => $value->introduction,
                'public' => date('Y-F-d H:i',strtotime( $value->date_evenement)) ,
                'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
            ];
        }

        $schoolLifeCat = articleCategory::where('alias', 'school-life')->get(['id'])[0];
        $schoolLife =  Article::where('visible', 1)
                        ->where('category_id', $schoolLifeCat->id)
                        ->orderByDesc('date_publication')
                        ->limit(5)
                        ->get();
        $schoolLifeArt = [];
        foreach ($schoolLife as $value) {
            $schoolLifeArt[] =  [
                'id' => $value->id,
                'label' => $value->label,
                'introduction' => $value->introduction,
                'details' => $value->details,
                'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
            ];
        }

        return response()->json(['lastArticle' => $lastArticle, 'topArticle' => $topArticle, 'podcast' => $podcastArt, 'event' => $eventArt, 'schoolLife' => $schoolLifeArt]);        
    }



    public function getArticleDetails($id){
        $article = Article::find($id);
        $cate = articleCategory::find($article->category_id);
        $hasVideo = $cate->hasVideo;
        $hasDate = $cate->specific_date;
        $data = [
            'id' => $article->id,
            'category' => $article->category_id,
            'label' => $article->label,
            'introduction'=>$article->introduction,
            'details' => $article->details,
            'image' => $article->getPicture(),
            'keywords' => json_decode($article->keywords),
            'visible' => $article->visible == 1 ? true : false,
            'hasDate' => $hasDate == 1 ? true : false,
            'hasVideo' => $hasVideo == 1 ?true : false,
        ];
        
        if($hasDate) {
            $data = [
                ...$data,
                'date_event' => date('Y-m-d', strtotime($article->date_evenement)),
                'time_event' => date('H:i:s', strtotime($article->date_evenement))
            ];
        }
        if($hasVideo) {
            $data = [
                ...$data,
                'video' =>  asset('src/public/podcast_video') .'/'.$article->video,
            ];
        }

        return response()->json($data);
    }

    public function getSchoolLifeDetail($id) {
        $article = Article::findOrfail($id);
        $articleDetails = [
            'label' => $article->label,
            'introduction' => $article->introduction,
            'details' => $article->details,
            'tags' => $article->keywords,
            'user' => [
                'img' => $article->users->getPicture(),
                'name' => $article->users->getNomComplet()
            ],
            'public' => date("Y M, d H:i",  strtotime($article->date_publication)),
            'img' => asset('src/storage/app/public/schoolLife_articles/' . $article->image),
        ]; 
        if($article->video != null) {
            $articleDetails = [...$articleDetails, 'video' => asset('src/storage/app/public/podcast_video/' . $article->video)];
        }

        $topArticleData =  Article::where('visible', 1)
            ->where('category_id', $article->category_id)
            ->orderByDesc('likes')
            ->limit(3)
            ->get();


        $topArticle = [];
        foreach ($topArticleData as $value) {
            $topArticle[] =  [
                'id' => $value->id,
                'introduction' => $value->label,
                'public' => date('Y-F-d H:i:s',strtotime( $value->date_publication)) ,
                'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
                'user' => [
                    'img' => $value->users->getPicture(),
                    'name' => $value->users->getNomComplet()
                ],
                'category' => $value->category->label
            ];
        }

        $articles = Article::where('visible', 1)
                    ->where('category_id', $article->category_id)
                    ->where('id', '<>', $article->id)
                    ->orderByDesc('date_publication')
                    ->limit(3)
                    ->get();
        $lastArticle = [];
        foreach ($articles as $value) {
        $lastArticle[] =  [
            'id' => $value->id,
            'label' => $value->label,
            'public' => date('Y-F-d H:i:s',strtotime( $value->date_publication)) ,
            'img' => asset('src/storage/app/public/schoolLife_articles/' . $value->image),
            'user' => [
                'img' => $value->users->getPicture(),
                'name' => $value->users->getNomComplet()
            ],
            'category' => $value->category->label,
            ];
        }



        return response()->json(['lastArticle' => $lastArticle, 'topArticle' => $topArticle, 'articleDetails' => $articleDetails]);
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