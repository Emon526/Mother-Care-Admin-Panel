@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="card-header">{{ __('Create a New Article') }}</div>
        <div class="card-body">

            <form action="{{route('article.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">

                    <label for="articleImage">Choose Article image:</label>
                    <input type="file"class="form-control mt-3" id="articleImage" accept="image/*"name="articleImage" >
                    <div style="display:flex; justify-content:center;">
                <img id="image-preview"class="form-control mt-3" style="max-width: 20%;" src="#" alt="Image Preview">
                    </div>

                    <input placeholder="Article ID" type="number" name="articleId" class="form-control mt-3"
                        id="articleId">
                        <input placeholder="Article Title" type="text" name="articleTitle" class="form-control mt-3"
                        id="articleTitle">
                    <input placeholder="Aticle Description" type="text" name="articleDescription" class="form-control mt-3"
                        id="articleDescription">
                </div>

                <button class="btn btn-primary mt-3">Save</button>
            </form>

        </div>
    </div>
</div>
<script>
const imagePicker = document.getElementById('articleImage');
const imagePreview = document.getElementById('image-preview');

imagePicker.addEventListener('change', (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        imagePreview.src = reader.result;
    };
});


</script>
@endsection