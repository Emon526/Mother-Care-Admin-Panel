@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Edit Doctor') }}</div>
        <div class="card-body">

            <form action="{{route('doctors.update',[$doctor])}}" method="post" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="form-group">

                    <label for="image">Choose an image:</label>
                    <input type="file"class="form-control mt-3" id="image" accept="image/*"name="image" >
                    <div style="display:flex; justify-content:center;">
                <img id="image-preview"class="form-control mt-3" style="max-width: 20%;" src="data:image/png;base64,{{ $doctor->image }}" alt="Image Preview">
                    </div>

                    <input placeholder="Name" type="text" name="doctorname" class="form-control mt-3" id="doctorname"
                        value="{{$doctor->doctorname}}">
                    <input placeholder="Degree" type="text" name="degree" class="form-control mt-3" id="degree"
                        value="{{$doctor->degree}}">
                    <input placeholder="Speciality" type="text" name="speciality" class="form-control mt-3"
                        id="speciality" value="{{$doctor->speciality}}">
                    <input placeholder="Workplace" type="text" name="workplace" class="form-control mt-3" id="workplace"
                        value="{{$doctor->workplace}}">
                    <input placeholder="Biography" type="text" name="biography" class="form-control mt-3" id="biography"
                        value="{{$doctor->biography}}">
                    <input placeholder="Experience" type="number" name="experience" class="form-control mt-3"
                        id="experience" value="{{$doctor->experience}}">
                    <input placeholder="Rating" type="number" min="0" max="5" step=0.1 name="rating" class="form-control mt-3" id="rating"
                        value="{{$doctor->rating}}">

                    <input placeholder="Appointment Number" type="tel"  pattern="^\+\d{1,3}\s\d{3}\s\d{3}\s\d{4}$" maxlength="17" name="appointmentNumber"
                        class="form-control mt-3" id="appointmentNumber" value="{{$doctor->appointmentNumber}}">

                    <input placeholder="Location" type="text" name="location" class="form-control mt-3" id="location"
                        value="{{$doctor->location}}">
                </div>
                <button class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
<script>
const imagePicker = document.getElementById('image');
const imagePreview = document.getElementById('image-preview');

imagePicker.addEventListener('change', (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        imagePreview.src = reader.result;
    };
    
});

const input = document.querySelector('#review');
input.addEventListener('change', e => {
  e.currentTarget.value = parseFloat(e.currentTarget.value).toFixed(1)
});

</script>
@endsection