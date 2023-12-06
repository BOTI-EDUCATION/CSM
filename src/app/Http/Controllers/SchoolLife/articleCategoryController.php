<?php

namespace App\Http\Controllers\SchoolLife;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\articleCategory;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class articleCategoryController extends Controller
{
    public function insertCategory(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            "label" => ["required", "string"],
            "icon" => ['mimes:ico,png'],
            "hasVideo" => ["required"],
            "specific_date" => ["required"]
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag(),
            ], 400);
        }

        // dd(intval($request->hasVideo), intval($request->specific_date));

        $articleCategory = articleCategory::create([
            'label' => $request->label,
            'hasVideo' => $request->hasVideo == "true" ? 1 : 0,
            'specific_date' => $request->specific_date == "true" ? 1 : 0
        ]);

        $alias = Helpers::getAlias($request->label);
        $articleCategory->update(['alias' => $alias]);

        if ($request->hasFile('icon')) {
            $file = $request->file("icon");
            $filename = "image_" . $articleCategory->id . '.' . $file->extension();
            $path = $file->storeAs('categories_icon', $filename, 'public');
            $articleCategory->update(['icon' => $path]);
        }

        return response()->json([
            "categories" => articleCategory::all(['id', 'label', 'icon']),
        ], 200);
    }

    public function updateCategory(Request $request, $id)
    {
        $validator =  Validator::make($request->all(), [
            "label" => ["required", "string"],
            "icon" => ["sometimes", 'mimes:ico,png,jpg,jpeg'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $request,
            ], 400);
        }

        $articleCategory = articleCategory::findOrFail($id);

        if ($request->hasFile('icon')) {
            $file = $request->file("icon");
            $filename = "image_" . $articleCategory->id . '.' . $file->extension();
            $path = $file->storeAs('categories_icon', $filename, 'public');
            $articleCategory->icon = $path;
        }

        $articleCategory->label = $request->label;
        $alias = Helpers::getAlias($request->label);
        $articleCategory->alias = $alias;
        $articleCategory->save();


        return response()->json([
            "categories" => articleCategory::all(['id', 'label', 'icon']),
        ], 200);
    }

    public function getAllcategories()
    {
        return response()->json([
            "categories" => articleCategory::all(),
        ], 200);
    }

    public function deleteCat($id)
    {
        $category = articleCategory::findOrFail($id);
        if (Storage::delete('public/' . $category->icon)) {
            $category->delete();
        }
        return response()->json([
            "categories" => articleCategory::all(['id', 'label', 'icon']),
        ], 200);
    }


    public function deleteArt($id)
    {
        $article = Article::findOrFail($id);
        // return($article);

        $article->delete();
        if (Storage::delete('public/' . $article->image)) {
        }
        return response()->json([
            "articles" => Article::all(['id', 'label', 'image']),
        ], 200);
    }
}
