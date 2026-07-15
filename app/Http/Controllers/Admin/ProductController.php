<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    
public function index()
{
    $products = Product::with('category')->latest()->paginate(10);

    return view('admin.products.index', compact('products'));
}

public function create()
{
    $categories = Category::where('status', 1)
                    ->orderBy('name')
                    ->get();

    return view('admin.products.create', compact('categories'));
}

/* Start from Store */
public function store(Request $request)
{
    $request->validate([
        'category_id'        => 'required|exists:categories,id',
        'name'               => 'required|string|max:255',
        'slug'               => 'nullable|string|max:255|unique:products,slug',
        'sku'                => 'nullable|string|max:100',
        'price'              => 'required|numeric|min:0',
        'sale_price'         => 'nullable|numeric|min:0',
        'stock'              => 'required|integer|min:0',
        'featured_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'short_description'  => 'nullable|string',
        'description'        => 'nullable|string',
        'featured'           => 'nullable',
        'status'             => 'nullable',
        'gallery_images'   => 'nullable|array',
        'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $imageName = null;

    if ($request->hasFile('featured_image')) {

        $image = $request->file('featured_image');

        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('uploads/products'), $imageName);
    }

    $product = Product::create([
        'category_id'       => $request->category_id,
        'name'              => $request->name,
        'slug'              => $request->slug
                                ? Str::slug($request->slug)
                                : Str::slug($request->name),
        'sku'               => $request->sku,
        'price'             => $request->price,
        'sale_price'        => $request->sale_price,
        'stock'             => $request->stock,
        'featured_image'    => $imageName,
        'short_description' => $request->short_description,
        'description'       => $request->description,
        'featured'          => $request->featured ? 1 : 0,
        'status'            => $request->status ? 1 : 0,
    ]);

    // Upload gallery images
if ($request->hasFile('gallery_images')) {
    foreach ($request->file('gallery_images') as $key => $image) {

        $galleryImageName = time() . '_gallery_' . $key . '_' . uniqid() . '.' .
                            $image->getClientOriginalExtension();

        $image->move(
            public_path('uploads/products/gallery'),
            $galleryImageName
        );

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $galleryImageName,
            'sort_order' => $key,
        ]);
    }
}

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Product created successfully.');
}

/* End of Store */

    public function edit(Product $product)
    {
    $categories = Category::where('status', 1)
                    ->orderBy('name')
                    ->get();

    return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'category_id'       => 'required|exists:categories,id',
        'name'              => 'required|string|max:255',
        'slug'              => 'nullable|string|max:255|unique:products,slug,' . $product->id,
        'sku'               => 'nullable|string|max:100',
        'price'             => 'required|numeric|min:0',
        'sale_price'        => 'nullable|numeric|min:0',
        'stock'             => 'required|integer|min:0',
        'featured_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'gallery_images'   => 'nullable|array',
        'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'short_description' => 'nullable|string',
        'description'       => 'nullable|string',
        'featured'          => 'nullable',
        'status'            => 'nullable',
    ]);

    $imageName = $product->featured_image;

    if ($request->hasFile('featured_image')) {

        if ($product->featured_image &&
            file_exists(public_path('uploads/products/' . $product->featured_image))) {

            unlink(public_path('uploads/products/' . $product->featured_image));
        }

        $image = $request->file('featured_image');

        $imageName = time() . '_' . uniqid() . '.' .
                     $image->getClientOriginalExtension();

        $image->move(public_path('uploads/products'), $imageName);
    }

    $product->update([
        'category_id'       => $request->category_id,
        'name'              => $request->name,
        'slug'              => $request->slug
                                ? Str::slug($request->slug)
                                : Str::slug($request->name),
        'sku'               => $request->sku,
        'price'             => $request->price,
        'sale_price'        => $request->sale_price,
        'stock'             => $request->stock,
        'featured_image'    => $imageName,
        'short_description' => $request->short_description,
        'description'       => $request->description,
        'featured'          => $request->featured ? 1 : 0,
        'status'            => $request->status ? 1 : 0,
    ]);


    // Upload new gallery images
if ($request->hasFile('gallery_images')) {
    foreach ($request->file('gallery_images') as $key => $image) {

        $galleryImageName = time() . '_gallery_' . $key . '_' . uniqid() . '.' .
                            $image->getClientOriginalExtension();

        $image->move(
            public_path('uploads/products/gallery'),
            $galleryImageName
        );

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $galleryImageName,
            'sort_order' => $key,
        ]);
    }
}

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Product updated successfully.');
}

public function deleteGalleryImage(ProductImage $image)
{
    $imagePath = public_path('uploads/products/gallery/' . $image->image);

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $image->delete();

    return back()->with('success', 'Gallery image deleted successfully.');
}

public function destroy(Product $product)
{
    // Delete featured image
    if ($product->featured_image &&
        file_exists(public_path('uploads/products/' . $product->featured_image))) {

        unlink(public_path('uploads/products/' . $product->featured_image));
    }

    // Delete product from database
    $product->delete();

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Product deleted successfully.');
}

}