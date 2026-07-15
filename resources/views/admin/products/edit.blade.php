@extends('admin.layouts.app')

@php
    $title = 'Edit Product';
@endphp

@section('content')
<div class="card-box">
    <h2 class="mb-4">Edit Product</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Product Update Form --}}
    <form action="{{ route('admin.products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">
                Category <span class="text-danger">*</span>
            </label>

            <select name="category_id" class="form-select" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                   value="{{ old('name', $product->name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>

            <input type="text"
                   name="slug"
                   class="form-control"
                   value="{{ old('slug', $product->slug) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>

            <input type="text"
                   name="sku"
                   class="form-control"
                   value="{{ old('sku', $product->sku) }}">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Price *</label>

                <input type="number"
                       name="price"
                       step="0.01"
                       min="0"
                       class="form-control"
                       value="{{ old('price', $product->price) }}"
                       required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Sale Price</label>

                <input type="number"
                       name="sale_price"
                       step="0.01"
                       min="0"
                       class="form-control"
                       value="{{ old('sale_price', $product->sale_price) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Stock *</label>

                <input type="number"
                       name="stock"
                       min="0"
                       class="form-control"
                       value="{{ old('stock', $product->stock) }}"
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Short Description</label>

            <textarea name="short_description"
                      rows="3"
                      class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Description</label>

            <textarea name="description"
                      rows="6"
                      class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label>
            <br>

            @if($product->featured_image)
                <img src="{{ asset('uploads/products/' . $product->featured_image) }}"
                     width="120"
                     style="border-radius: 8px;">
            @else
                <span class="text-muted">No image uploaded.</span>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Change Featured Image</label>

            <input type="file"
                   name="featured_image"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.webp">
        </div>

                {{-- Existing Gallery Images --}}
                <div class="mb-3">
                    <label class="form-label">Current Gallery Images</label>

                    <div class="d-flex flex-wrap gap-3">
                        @forelse($product->images as $image)
                            <div class="text-center">
                                <img src="{{ asset('uploads/products/gallery/' . $image->image) }}"
                                     width="100"
                                     height="90"
                                     style="object-fit: cover; border-radius: 8px;">

                                <form action="{{ route('admin.products.gallery.delete', $image->id) }}"
                                      method="POST"
                                      class="mt-1">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this gallery image?')">
                                        ❌
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-muted">No gallery images.</p>
                        @endforelse
                    </div>
                </div>

        {{-- Add More Gallery Images --}}
        <div class="mb-3">
            <label class="form-label">Add Gallery Images</label>

            <input type="file"
                   name="gallery_images[]"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.webp"
                   multiple>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox"
                   name="featured"
                   class="form-check-input"
                   id="featured"
                   {{ old('featured', $product->featured) ? 'checked' : '' }}>

            <label class="form-check-label" for="featured">
                Featured Product
            </label>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox"
                   name="status"
                   class="form-check-input"
                   id="status"
                   {{ old('status', $product->status) ? 'checked' : '' }}>

            <label class="form-check-label" for="status">
                Active
            </label>
        </div>

        <button type="submit" class="btn btn-success">
            Update Product
        </button>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </form>
    {{-- End Main Product Update Form --}}


    {{-- Gallery Delete Forms - OUTSIDE Main Form --}}
    @foreach($product->images as $image)
        <form id="delete-image-{{ $image->id }}"
              action="{{ route('admin.products.gallery.delete', $image->id) }}"
              method="POST"
              style="display: none;">

            @csrf
            @method('DELETE')

        </form>
    @endforeach

</div>
@endsection