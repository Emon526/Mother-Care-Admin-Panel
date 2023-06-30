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
                    <input type="file" class="form-control mt-3" id="image" accept="image/*" name="image">
                    <div style="display:flex; justify-content:center;">
                        <img id="image-preview" class="form-control mt-3" style="max-width: 20%;"
                            src="data:image/png;base64,{{ $doctor->image }}" alt="Image Preview">
                    </div>

                    <input placeholder="Doctor ID" type="number" name="doctorId" class="form-control mt-3" id="doctorId"
                        value="{{$doctor->doctorId}}">
                        <input placeholder="Experience" type="number" name="experience" class="form-control mt-3"
                        id="experience" value="{{$doctor->experience}}">
                    <input placeholder="Rating" type="number" min="0" max="5" step=0.1 name="rating"
                        class="form-control mt-3" id="rating" value="{{$doctor->rating}}">
                    <input placeholder="Appointment Number" type="tel" name="appointmentNumber" maxlength="14"
                        class="form-control mt-3" id="appointmentNumber" value="{{$doctor->appointmentNumber}}">
                        <div class="btn-group mt-3" role="group" aria-label="Language Toggle">
                        <button type="button" class="btn btn-primary" id="toggleLanguage">Toggle Language</button>
                    </div>
                    <div id="banglaFields" style="display: none;">
                    <input placeholder="Name in Bangla" type="text" name="bangladoctorname" class="form-control mt-3"
                        id="bangladoctorname" value="{{json_decode($doctor->doctorname)->bn}}">
                        <input placeholder="Degree in Bangla" type="text" name="bangladegree" class="form-control mt-3"
                        id="bangladegree" value="{{json_decode($doctor->degree)->bn}}">
                        <input placeholder="Speciality in Bangla" type="text" name="banglaspeciality"
                        class="form-control mt-3" id="banglaspeciality"
                        value="{{json_decode($doctor->speciality)->bn}}">
                        <input placeholder="Workplace in Bangla" type="text" name="banglaworkplace"
                        class="form-control mt-3" id="banglaworkplace" value="{{json_decode($doctor->workplace)->bn}}">
                        <input placeholder="Location in Bangla" type="text" name="banglalocation" class="form-control mt-3"
                        id="banglalocation" value="{{json_decode($doctor->location)->bn}}">
                        <textarea placeholder="Biography in Bangla" name="banglabiography" class="form-control mt-3"
                        id="banglabiography" rows="6">{{json_decode($doctor->biography)->bn}}</textarea>
                        </div>
                        <div id="englishFields">
                    <input placeholder="Name in English" type="text" name="englishdoctorname" class="form-control mt-3"
                        id="englishdoctorname" value="{{json_decode($doctor->doctorname)->en}}">
                    <input placeholder="Degree in English" type="text" name="englishdegree" class="form-control mt-3"
                        id="englishdegree" value="{{json_decode($doctor->degree)->en}}">
                    <input placeholder="Speciality in English" type="text" name="englishspeciality"
                        class="form-control mt-3" id="englishspeciality"
                        value="{{json_decode($doctor->speciality)->en}}">
                    <input placeholder="Workplace in English" type="text" name="englishworkplace"
                        class="form-control mt-3" id="englishworkplace" value="{{json_decode($doctor->workplace)->en}}">
                        <input placeholder="Location in English" type="text" name="englishlocation"
                        class="form-control mt-3" id="englishlocation" value="{{json_decode($doctor->location)->en}}">
                        <textarea placeholder="Biography in English" name="englishbiography" class="form-control mt-3"
                        id="englishbiography" rows="6">{{json_decode($doctor->biography)->en}}</textarea>

                        </div>
                </div>
                <button class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
<script>
const imagePicker = document.getElementById('image');
const imagePreview = document.getElementById('image-preview');
const toggleButton = document.getElementById('toggleLanguage');
        const banglaFields = document.getElementById('banglaFields');
        const englishFields = document.getElementById('englishFields');

imagePicker.addEventListener('change', (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        imagePreview.src = reader.result;
    };

});

toggleButton.addEventListener('click', () => {
            if (banglaFields.style.display === 'none') {
                banglaFields.style.display = 'block';
                englishFields.style.display = 'none';
            } else {
                banglaFields.style.display = 'none';
                englishFields.style.display = 'block';
            }
        });

const input = document.querySelector('#rating');
input.addEventListener('change', e => {
    e.currentTarget.value = parseFloat(e.currentTarget.value).toFixed(1)
});

const appointmentNumberField = document.getElementById('appointmentNumber');
const defaultCountryCode = "+880";

appointmentNumberField.addEventListener('focus', () => {
    if (!appointmentNumberField.value.startsWith(defaultCountryCode)) {
        appointmentNumberField.value = defaultCountryCode + appointmentNumberField.value;
    }
});

appointmentNumberField.addEventListener('input', () => {
    const input = appointmentNumberField.value;
    appointmentNumberField.value = input;
});
</script>
@endsection