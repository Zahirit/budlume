@extends('admin.layouts.app')

@php
    $title = 'Add Product';
@endphp

@section('content')
<div class="card-box">
    <h2 class="mb-4">Add Product</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">
                Category <span class="text-danger">*</span>
            </label>

            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Product Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text"
                   name="slug"
                   class="form-control"
                   value="{{ old('slug') }}">

            <small class="text-muted">
                Leave empty to auto-generate from product name.
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text"
                   name="sku"
                   class="form-control"
                   value="{{ old('sku') }}">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Price <span class="text-danger">*</span>
                </label>
                <input type="number"
                       name="price"
                       step="0.01"
                       min="0"
                       class="form-control"
                       value="{{ old('price') }}"
                       required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Sale Price</label>
                <input type="number"
                       name="sale_price"
                       step="0.01"
                       min="0"
                       class="form-control"
                       value="{{ old('sale_price') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Stock <span class="text-danger">*</span>
                </label>
                <input type="number"
                       name="stock"
                       min="0"
                       class="form-control"
                       value="{{ old('stock', 0) }}"
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="short_description"
                      rows="3"
                      class="form-control">{{ old('short_description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Description</label>
            <textarea name="description"
                      rows="6"
                      class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Featured Image</label>
            <input type="file"
                   name="featured_image"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="mb-3">
            <label class="form-label">Gallery Images</label>
            <input type="file"
                   name="gallery_images[]"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.webp"
                   multiple>

            <small class="text-muted">
                You can select multiple images.
            </small>
            </div>

        <div class="mb-3 form-check">
            <input type="checkbox"
                   name="featured"
                   class="form-check-input"
                   id="featured"
                   {{ old('featured') ? 'checked' : '' }}>

            <label class="form-check-label" for="featured">
                Featured Product
            </label>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox"
                   name="status"
                   class="form-check-input"
                   id="status"
                   {{ old('status', 1) ? 'checked' : '' }}>

            <label class="form-check-label" for="status">
                Active
            </label>
        </div>

        <button type="submit" class="btn btn-success">
            Save Product
        </button>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection