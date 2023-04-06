@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Articles') }}</div>
        <div class="card-body">
            <a href="{{route('article.create')}}" class="btn btn-primary">Create New Article</a>
            <div class="mt-3">
                <h3 style="text-align:center">List of Articles</h3>
                @forelse($articles as $article)

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$article->articleId}}</td>
                            <td>{{$article->articleTitle}}</td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{route('article.edit',[$article])}}"
                                        class="btn btn-warning btn-sm me-2">Edit</a>
                                    <form action="{{route('article.destroy',[$article])}}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <button class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>


                    </tbody>
                </table>
                @empty
                <li class="list-group-item" style="text-align:center">No Article Added Yet</li>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection