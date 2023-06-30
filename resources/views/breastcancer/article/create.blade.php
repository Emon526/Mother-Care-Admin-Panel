@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Create a New Article') }}</div>
        <div class="card-body">

            <form action="{{route('article.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">

                    <label for="articleImage">Choose Article image:</label>
                    <input type="file" class="form-control mt-3" id="articleImage" accept="image/*" name="articleImage">
                    <div style="display:flex; justify-content:center;">
                        <img id="image-preview" class="form-control mt-3" style="max-width: 20%;" src="#"
                            alt="Image Preview">
                    </div>

                    <input placeholder="Article ID" type="number" name="articleId" class="form-control mt-3"
                        id="articleId" value="{{$articlesLength}}">

                    <div class="btn-group mt-3" role="group" aria-label="Language Toggle">
                        <button type="button" class="btn btn-primary" id="toggleLanguage">Toggle Language</button>
                    </div>

                    <div id="banglaFields" style="display: none;">
                        <input placeholder="Bangali Title" type="text" name="banglaTitle" class="form-control mt-3"
                            id="banglaTitle">
                        <textarea placeholder="Bangali Description" name="banglaDescription"
                            class="form-control mt-3" id="banglaDescription" rows="6"></textarea>                      
                    </div>

                    <div id="englishFields">
                        <input placeholder="English Title" type="text" name="englishTitle" class="form-control mt-3"
                            id="englishTitle">
                        <textarea placeholder="English Description" name="englishDescription"
                            class="form-control mt-3" id="englishDescription" rows="6"></textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-3">Save</button>
                <div class="d-flex justify-content-center mt-3">
                    <div class="alert alert-info" role="alert">
                        <h5 class="alert-heading">Hint:</h5>
                        <p>Try using <strong>**</strong> to make text bold. For example, <strong>**Text**</strong>.</p>
                        <p>For bullet points, use the <strong>&bull;</strong> character at the beginning of a sentence.
                        </p>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
const imagePicker = document.getElementById('articleImage');
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

</script>
@endsection