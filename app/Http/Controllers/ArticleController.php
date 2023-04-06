<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $articles = Article::all();
        return view('breastcancer.article.index',compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('breastcancer.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'articleImage'=>'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'articleId'=>'required',
            'articleTitle'=>'required',
            'articleDescription'=>'required',
        ]);
        $imageBase64 = $request->articleImage; 
        if ($request->hasFile('articleImage')) {

            $file = $request->file('articleImage');
            $imageData = file_get_contents($file);
            $imageBase64 = base64_encode($imageData);
        }

        Article::create([
            'articleImage'=> $imageBase64,
            'articleId'=>$request->articleId,
            'articleTitle'=>$request->articleTitle,
            'articleDescription'=>$request->articleDescription,
        ]);
        return redirect()->route('article.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        // 
        return view('breastcancer.article.edit',compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
        $request->validate([

            'articleImage'=>'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'articleId'=>'required',
            'articleTitle'=>'required',
            'articleDescription'=>'required',
        ]);
        
        $imageBase64 = $article->articleImage; // default to existing value
        
        if ($request->hasFile('articleImage')) {

            $file = $request->file('articleImage');
            $imageData = file_get_contents($file);
            $imageBase64 = base64_encode($imageData);
        }
  
        $article->update([
       
            'articleImage'=> $imageBase64,
            'articleId'=>$request->articleId,
            'articleTitle'=>$request->articleTitle,
            'articleDescription'=>$request->articleDescription,
        ]);
     
        return redirect()->route('article.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
        $article->delete();
        return redirect()->route('article.index');
    }
}
