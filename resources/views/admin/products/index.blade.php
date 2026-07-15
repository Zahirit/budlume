@extends('admin.layouts.app')

@php
    $title = 'Products';
@endphp

@section('content')
<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Product List</h2>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>

                        <td>
                            @if($product->featured_image)
                                <img
                                    src="{{ asset('uploads/products/' . $product->featured_image) }}"
                                    width="70"
                                    height="60"
                                    style="object-fit: cover;"
                                    alt="{{ $product->name }}">
                            @else
                                No Image
                            @endif
                        </td>

                        <td>{{ $product->name }}</td>

                        <td>{{ $product->category?->name ?? 'N/A' }}</td>

                        <td>{{ $product->sku ?? '-' }}</td>

                        <td>
                            @if($product->sale_price)
                                <del>{{ number_format($product->price, 2) }}</del>
                                <br>
                                {{ number_format($product->sale_price, 2) }}
                            @else
                                {{ number_format($product->price, 2) }}
                            @endif
                        </td>

                        <td>{{ $product->stock }}</td>

                        <td>
                            @if($product->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.products.destroy', $product) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection