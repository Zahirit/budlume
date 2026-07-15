<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('frontend.cart', compact('cart'));
    }

    public function add(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
           $cart[$product->id]['quantity'] += max(1, (int) request('quantity', 1));
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->sale_price ?: $product->price,
                'image' => $product->featured_image,
                'quantity' => max(1, (int) request('quantity', 1)),
            ];
        }

        session()->put('cart', $cart);

        return redirect()
            ->route('shop')
            ->with('success', 'Product added to cart.');
    }

    public function update(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $quantity = max(1, (int) request('quantity'));

            $cart[$product->id]['quantity'] = $quantity;

            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);

            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart.');
    }
}