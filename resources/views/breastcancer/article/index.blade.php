@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header bg-primary text-white">{{ __('Articles') }}</div>
        <div class="card-body">
        <div class="d-flex justify-content-between">
            <a href="{{ route('article.create') }}" class="btn btn-primary">Create New Article</a>
            <button id="toggleLanguage" class="btn btn-secondary">🇧🇩</button> <!-- New button -->
            </div>
            <div class="mt-3">
                <h3 style="text-align:center">List of Articles</h3>

                @if(count($articles) > 0)
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ID</th>
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
                            <td style="text-align: center;">{{ $article['articleId'] }}</td>
                            <td class="article-title" data-article-id="{{ $article['articleId'] }}">{{ $article['articleTitle']['en'] }}</td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('article.edit',['article' => $article['articleId']]) }}"
                                        class="btn btn-warning btn-sm me-2">Edit</a>

                                    <form action="{{ route('article.destroy',['article' => $article['articleId']]) }}"
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

<script>
    var toggleBtn = document.getElementById('toggleLanguage');
    var currentLanguage = 'en'; // Initial language

    toggleBtn.addEventListener('click', function () {
        var buttonText = toggleBtn.innerText;

        if (buttonText === '🇧🇩') {
            toggleBtn.innerText = '🇺🇸';
            currentLanguage = 'bn';
        } else {
            toggleBtn.innerText = '🇧🇩';
            currentLanguage = 'en';
        }

        updateArticleLanguage(currentLanguage);
    });

    function updateArticleLanguage(language) {
        var articleTitles = document.querySelectorAll('.article-title');

        articleTitles.forEach(function (title) {
            var articleId = title.dataset.articleId;
            var articleData = getArticleDataById(articleId);

            if (articleData) {
                title.innerText = articleData['articleTitle'][language];
            }
        });
    }

    function getArticleDataById(articleId) {
        var articles = @json($articles);

        for (var i = 0; i < articles.length; i++) {
            if (articles[i]['articleId'] === articleId) {
                return articles[i];
            }
        }

        return null;
    }
</script>
@endsection
