<?php
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use App\Http\Controllers\CartController;


Route::get('/', function () {
    $products = Product::where('status', 1)
        ->latest()
        ->take(8)
        ->get();

    return view('frontend.home', compact('products'));
})->name('home');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);

        Route::delete('/product-gallery/{image}',
            [ProductController::class, 'deleteGalleryImage'])
            ->name('products.gallery.delete');

        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'update'])
    ->name('orders.updateStatus');

    Route::resource('contact-messages', ContactMessageController::class)
    ->only(['index', 'show']);

        Route::resource('orders', AdminOrderController::class)
    ->only(['index', 'create', 'store', 'show', 'update']);

    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])
    ->name('orders.invoice');

        Route::get('/settings', [SettingController::class, 'index'])
         ->name('settings.index');

        Route::put('/settings', [SettingController::class, 'update'])
    ->name('settings.update');

        Route::resource('customers', CustomerController::class)
    ->only(['index', 'show']);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

        Route::get('/shop', function () {
            $products = Product::where('status', 1)
                ->latest()
                ->paginate(12);

            return view('frontend.shop', compact('products'));
        })->name('shop');

        Route::get('/product/{product}', function (Product $product) {
        return view('frontend.product-show', compact('product'));
    })->name('product.show');

        Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

        Route::post('/cart/add/{product}', [CartController::class, 'add'])
        ->name('cart.add');

        Route::patch('/cart/update/{product}', [CartController::class, 'update'])
        ->name('cart.update');

        Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])
        ->name('cart.remove');

        Route::get('/checkout', function () {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return redirect()->route('cart.index');
            }

            return view('frontend.checkout', compact('cart'));
        })->middleware('auth')->name('checkout');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->middleware('auth')
            ->name('checkout.store');

            Route::get('/order-success/{order}', function (\App\Models\Order $order) {
                return view('frontend.order-success', compact('order'));
            })->middleware('auth')->name('order.success');

            Route::middleware('auth')->group(function () {

            Route::get('/my-orders', [OrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/my-orders/{order}', [OrderController::class, 'show'])
                ->name('orders.show');

            });



require __DIR__.'/auth.php';