@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Doctors') }}</div>
        <div class="card-body">
            <a href="{{route('doctors.create')}}" class="btn btn-primary">Create New Doctor</a>
            <div class="mt-3">
                <h3 style="text-align:center">List of Doctors</h3>
                @forelse($doctors as $doctor)
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>
                                <img class="rounded" id="image-preview" width='100' height='100'
                                    src="data:image/png;base64,{{ $doctor->image }}" alt="No Image Chosen" />
                            </td>

                            <td class="align-middle"> {{$doctor->doctorname}}</td>

                            <td class="align-middle"> {{$doctor->location}}</td>

                            <td class="align-middle" style="text-align:center">
                            <div class="d-flex justify-content-center">
                                <a href="{{route('doctors.edit',[$doctor])}}"
                                    class="btn btn-warning btn-sm me-2">Edit</a>

                                <form action="{{route('doctors.destroy',[$doctor])}}" method="post">
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
                <li class="list-group-item" style="text-align:center">No Doctor Added Yet</li>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection