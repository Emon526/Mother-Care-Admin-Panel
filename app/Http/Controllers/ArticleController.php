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
        $responsearticles = Article::all();

        $articles = [];
        foreach ($responsearticles as $article) {
            $articles[] = [
                'articleImage' => $article->articleImage,
                'articleId' => $article->articleId,
                'articleTitle' => json_decode($article->articleTitle, true),
                'articleDescription' => json_decode($article->articleDescription, true),
            ];
        }
        return view('breastcancer.article.index',compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    // Get the list of articles
    $articles = Article::all();

    // Calculate the number of articles
    $articlesLength = $articles->count();

    // Pass the list of articles and the length to the view
    return view('breastcancer.article.create', compact('articlesLength'));
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
            'banglaTitle'=>'required',
            'banglaDescription'=>'required',
            'englishTitle'=>'required',
            'englishDescription'=>'required',
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
            'articleTitle'=>json_encode([
                'bn'=>$request->banglaTitle,
                'en'=>$request->englishTitle,
            ]),
            'articleDescription'=>json_encode([
                'bn'=>$request->banglaDescription,
                'en'=>$request->englishDescription,
            ]),
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
    public function edit($article)
    {

        $article = Article::where('articleId', $article)->firstOrFail();
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
            'banglaTitle'=>'required',
            'banglaDescription'=>'required',
            'englishTitle'=>'required',
            'englishDescription'=>'required',
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
            'articleTitle'=>json_encode([
                'bn'=>$request->banglaTitle,
                'en'=>$request->englishTitle,
            ]),
            'articleDescription'=>json_encode([
                'bn'=>$request->banglaDescription,
                'en'=>$request->englishDescription,
            ]),
        ]);
     
        return redirect()->route('article.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($article)
    {
    Article::where('articleId',$article)->delete();
        return redirect()->route('article.index');
    }
}