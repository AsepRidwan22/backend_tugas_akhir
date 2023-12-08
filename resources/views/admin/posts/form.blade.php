@php
$categories = App\Models\Category::all();
@endphp
<div class="input-group mb-3">
    <span class="input-group-text" for="title">Title</span>
    <input type="text" class="form-control" name="title" id="title" placeholder="Masukan Title Postingan">
    <div class="error_text title_error"></div>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" for="slug">Slug</span>
    <input type="text" class="form-control" name="slug" id="slug" placeholder="Masukan slug">
    <div class="error_text slug_error"></div>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" for="id_category">Category</span>
    <select id="id_category" name="id_category" class="form-select">
        @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <div class="error_text id_category_error"></div>
</div>
<div class="input-group mb-3">
    <input id="body" type="hidden" name="body">
    <trix-editor id="trix-editor" class="trix-content" input="body"></trix-editor>
    <div class="error_text body_error"></div>
</div>
<div class="input-group mb-3">
    <input type="file" class="image-file form-control-file" name="image" id="image">
    <div class="error_text image_error"></div>
</div>
<div class="col-md-12 mb-3 text-center">
    <img id="preview-image" src="{{ asset('template/src/assets/img/preview.png') }}" alt="preview image"
        style="max-height: 250px;">
</div>
