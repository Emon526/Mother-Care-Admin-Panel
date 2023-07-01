@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header bg-primary text-white">{{ __('Doctors') }}</div>
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('doctors.create') }}" class="btn btn-primary">Create New Doctor</a>
                <button id="toggleLanguage" class="btn btn-secondary">🇧🇩</button>
            </div>
            <div class="mt-3">
                <h3 style="text-align:center">List of Doctors</h3>
                @if(count($doctors) > 0)

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Location</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // Sort the doctors array by doctorid
                        usort($doctors, function ($a, $b) {
                            return $a['doctorId'] - $b['doctorId'];
                        });
                        ?>

                        @foreach($doctors as $doctor)
                        <tr>
                            <td style="text-align: center;" class="align-middle">{{ $doctor['doctorId']}}</td>
                            <td>
                                <img class="rounded" id="image-preview" width='100' height='100'
                                    src="data:image/png;base64,{{ $doctor['image'] }}" alt="No Image Chosen" />
                            </td>

                            <td class="align-middle language-toggle" data-en="{{ $doctor['doctorname']['en'] }}"
                                data-bn="{{ $doctor['doctorname']['bn'] }}">{{ $doctor['doctorname']['en'] }}</td>

                            <td class="align-middle language-toggle" data-en="{{ $doctor['location']['en'] }}"
                                data-bn="{{ $doctor['location']['bn'] }}">{{ $doctor['location']['en'] }}</td>

                            <td class="align-middle" style="text-align:center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('doctors.edit',['doctor' => $doctor['doctorId']]) }}"
                                        class="btn btn-warning btn-sm me-2">Edit</a>

                                    <form
                                        action="{{ route('doctors.destroy',['doctor' => $doctor['doctorId']]) }}"
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
                <li class="list-group-item" style="text-align:center">No Doctor Added Yet</li>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript function to toggle doctor data language
    function toggleLanguage() {
        const toggleButton = document.getElementById('toggleLanguage');
        const currentLanguage = toggleButton.innerText;
        const languageElements = document.querySelectorAll('.language-toggle');

        if (currentLanguage === '🇧🇩') {
            toggleButton.innerText = '🇺🇸';
            languageElements.forEach(element => {
                const enText = element.getAttribute('data-en');
                const bnText = element.getAttribute('data-bn');
                element.innerText = bnText;
            });
        } else {
            toggleButton.innerText = '🇧🇩';
            languageElements.forEach(element => {
                const enText = element.getAttribute('data-en');
                element.innerText = enText;
            });
        }
    }

    // Add an event listener to the toggle button
    document.getElementById('toggleLanguage').addEventListener('click', toggleLanguage);
</script>

@endsection
