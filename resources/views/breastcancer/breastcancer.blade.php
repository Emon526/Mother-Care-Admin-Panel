@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header  bg-primary text-white">{{ __('Breast Cancer') }}</div>

        <div class="card-body">
            <ui class="list-item">
                <li class="list-group-item">
                    <a href="{{route('article.index')}}"class="text-decoration-none">Articles</a>
                </li>
            </ui>
        </div>
    </div>
</div>
@endsection