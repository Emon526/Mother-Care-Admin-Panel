@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Create a New Doctor') }}</div>
        <div class="card-body">

            <form action="{{route('doctors.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">

                    <label for="image">Choose an image:</label>
                    <input type="file"class="form-control mt-3" id="image" accept="image/*"name="image" >
                    <div style="display:flex; justify-content:center;">
                <img id="image-preview"class="form-control mt-3" style="max-width: 20%;" src="#" alt="Image Preview">
                    </div>
                    <input placeholder="Name" type="text" name="doctorname" class="form-control mt-3" id="doctorname">
                    <input placeholder="Degree" type="text" name="degree" class="form-control mt-3" id="degree">
                    <input placeholder="Speciality" type="text" name="speciality" class="form-control mt-3"
                        id="speciality">
                    <input placeholder="Workplace" type="text" name="workplace" class="form-control mt-3"
                        id="workplace">
                    <input placeholder="Biography" type="text" name="biography" class="form-control mt-3"
                        id="biography">
                    <input placeholder="Experience" type="number" name="experience" class="form-control mt-3"
                        id="experience">
                    <input placeholder="Rating" type="number" min="0" max="5" step=0.1 name="rating" class="form-control mt-3" id="rating">

                    <input placeholder="Appointment Number" type="tel" pattern="^\+\d{1,3}\s\d{3}\s\d{3}\s\d{4}$" maxlength="17" name="appointmentNumber"
                        class="form-control mt-3" id="appointmentNumber" >

                    <input placeholder="Location" type="text" name="location" class="form-control mt-3" id="location">
                </div>

                <button class="btn btn-primary mt-3">Save</button>
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


const appointmentNumberField = document.getElementById('appointmentNumber');
const defaultCountryCode = '+880';
// Add default country code when the field is focused
// appointmentNumberField.addEventListener('focus', () => {
//   if (!appointmentNumberField.value.startsWith(defaultCountryCode)) {
//     appointmentNumberField.value = defaultCountryCode + appointmentNumberField.value;
//   }
// });

// Format the input according to the pattern after every input
appointmentNumberField.addEventListener('input', () => {

    let input = appointmentNumberField.value;
  if (!input.startsWith(defaultCountryCode)) {
    input = defaultCountryCode + input;
  }

const formattedNumber = formatPhoneNumber(input);
  appointmentNumberField.value = formattedNumber;
  
});

function formatPhoneNumber(phoneNumber) {
  const countryCode = '+880';
  let formattedNumber = '';

 if(phoneNumber.length === 4){
    formattedNumber = `${phoneNumber}-`;
  }
  else if(phoneNumber.length === 8){
    formattedNumber = `${phoneNumber}/`;
  }
  else if(phoneNumber.length === 12){
    formattedNumber = `${phoneNumber}-`;
  }
  else{
    formattedNumber = phoneNumber;
  }   
  return formattedNumber;

}

</script>
@endsection