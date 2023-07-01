@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header  bg-primary text-white">{{ __('Create a New Doctor') }}</div>
        <div class="card-body">

            <form action="{{route('doctors.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">

                    <label for="image">Choose an image:</label>
                    <input type="file"class="form-control mt-3" id="image" accept="image/*"name="image" >
                    <div style="display:flex; justify-content:center;">
                <img id="image-preview"class="form-control mt-3" style="max-width: 20%;" src="#" alt="Image Preview">
                    </div>

                    <input placeholder="Doctor ID" type="number" name="doctorId" class="form-control mt-3"
                        id="doctorId"  value="{{$doctorsLength}}">
                        <input placeholder="Experience" type="number" name="experience" class="form-control mt-3"
                        id="experience">
                    <input placeholder="Rating" type="number" min="0" max="5" step=0.1 name="rating" class="form-control mt-3" id="rating">

                    <input placeholder="Appointment Number" type="tel" maxlength="14" name="appointmentNumber"
                        class="form-control mt-3" id="appointmentNumber" >
                        <div class="btn-group mt-3" role="group" aria-label="Language Toggle">
                        <button type="button" class="btn btn-primary" id="toggleLanguage">Toggle Language</button>
                    </div>

                    <div id="banglaFields" style="display: none;">
                    <input placeholder="Name in Bangla" type="text" name="bangladoctorname" class="form-control mt-3" id="bangladoctorname">
                    <input placeholder="Degree in Bangla" type="text" name="bangladegree" class="form-control mt-3" id="bangladegree">
                    <input placeholder="Speciality in Bangla" type="text" name="banglaspeciality" class="form-control mt-3"
                        id="banglaspeciality">
                        <input placeholder="Workplace in Bangla" type="text" name="banglaworkplace" class="form-control mt-3"
                        id="banglaworkplace">
                        <input placeholder="Location in Bangla" type="text" name="banglalocation" class="form-control mt-3" id="banglalocation">
                        <textarea placeholder="Biography in Bangla" name="banglabiography" class="form-control mt-3"
                        id="banglabiography" rows="6"></textarea>

                    </div>

                    <div id="englishFields">
                    <input placeholder="Name in English" type="text" name="englishdoctorname" class="form-control mt-3" id="englishdoctorname">
                    <input placeholder="Degree in English" type="text" name="englishdegree" class="form-control mt-3" id="englishdegree">
                        <input placeholder="Speciality in English" type="text" name="englishspeciality" class="form-control mt-3"
                        id="englishspeciality">
                        <input placeholder="Workplace in English" type="text" name="englishworkplace" class="form-control mt-3"
                        id="englishworkplace">
                        <input placeholder="Location in English" type="text" name="englishlocation" class="form-control mt-3" id="englishlocation">
                        <textarea placeholder="Biography in English" name="englishbiography" class="form-control mt-3"
                        id="englishbiography" rows="6"></textarea>

                    </div>

                </div>

                <button class="btn btn-primary mt-3">Save</button> 
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