@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header  bg-primary text-white">{{ __('Breast Cancer') }}</div>

        <div class="card-body">
            <div class="row">
                <div class="col text-center">
                    <a href="{{ route('article.index') }}" class="text-decoration-none">
                        <img src="articles.png" alt="Image 1" class="img-fluid">
                        <p class="mt-2">Articles</p>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection