<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Resources\ArticleCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    private static $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'body.required' => 'Body no valido'
    ];
    public function index()
    {
        return new ArticleCollection(Article::paginate(25));
    }
    public function show(Article $article)
    {
        return response()->json(new ArticleResource($article),200);
    }
    public function store(Request $request)
    {
        $request->validate([
        'title' => 'required|string|unique:articles|max:255',
        'body' => 'required',
        'category_id' => 'required|exists:categories,id'],self::$messages);
//        $validator = Validator::make($request->all(), [
//            'title' => 'required|string|unique:articles|max:255',
//            'body' => 'required|string'
//        ]);
//        if ($validator->fails()) {
//            return response()->json(['error' => 'data_validation_failed',
//                "error_list"=>$validator->errors()], 400);
//        }

        $article = Article::create($request->all());
        return response()->json($article, 201);
    }
    public function update(Request $request,  Article $article)
    {
        $request->validate([
            'title' => 'required|string|unique:articles,title,'.$article->id.'|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id'],self::$messages);
        $article->update($request->all());
        return response()->json($article, 200);
    }
    public function delete(Request $request,Article $article)
    {
        $article->delete();
        return response()->json(null, 204);
    }
}
