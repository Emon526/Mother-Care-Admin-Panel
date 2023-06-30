@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Articles') }}</div>
        <div class="card-body">
            <a href="{{route('article.create')}}" class="btn btn-primary">Create New Article</a>
            <div class="mt-3">
                <h3 style="text-align:center">List of Articles</h3>

                @if(count($articles) > 0)
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Sort the articles array by articleId
                        usort($articles, function ($a, $b) {
                        return $a['articleId'] - $b['articleId'];
                        });
                        ?>

                        @foreach($articles as $article)
                        <tr>
                            <td>{{ $article['articleId']}}</td>
                            <td>{{ $article['articleTitle']['en']}} || {{ $article['articleTitle']['bn']}}</td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{route('article.edit',['article' => $article['articleId']])}}"
                                        class="btn btn-warning btn-sm me-2">Edit</a>

                                    <form action="{{route('article.destroy',['article' =>$article['articleId']])}}"
                                        method="post">
                                        @csrf
                                        @method("DELETE")
                                        <button class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="mt-3" style="text-align:center">No Article Added Yet</p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection