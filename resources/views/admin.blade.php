@extends('layouts.app')

@section('content')

<!-- <div class="col-md-8">
            <div class="card">
                <div class="card-header  bg-primary text-white">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div> -->
        
<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">{{ __('Menu') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-center">
                                <a href="{{ route('breastcancer') }}" class="text-decoration-none">
                                    <img src="breast-cancer.png" alt="Image 1" class="img-fluid">
                                    <p class="mt-2">Breast Cancer</p>
                                </a>
                            </div>
                            <div class="col text-center">
                                <a href="{{ route('doctors.index') }}" class="text-decoration-none">
                                    <img src="doctor.png" alt="Image 2" class="img-fluid">
                                    <p class="mt-2">Doctors</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection