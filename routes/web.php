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
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Auth\EmailOtpController;
use App\Http\Controllers\Auth\PhoneOtpController;
use App\Http\Controllers\GuestPhoneOtpController;


Route::get('/', function () {
    $products = Product::where('status', 1)
        ->latest()
        ->take(8)
        ->get();

    return view('frontend.home', compact('products'));
})->name('home');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
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

        Route::post('/orders/{order}/send-invoice', [AdminOrderController::class, 'sendInvoice'])
        ->name('orders.sendInvoice');

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


        Route::middleware(['auth'])->group(function () {

        Route::get('/change-password', function () {
            return view('user.change-password');
        })->name('account.password');

        });


    // ========================================
    // SEO - XML SITEMAP
    // ========================================

    Route::get('/sitemap.xml', function () {

        $products = Product::where('status', 1)
            ->latest('updated_at')
            ->get();

        return response()
            ->view('frontend.sitemap', compact('products'))
            ->header('Content-Type', 'application/xml');

    })->name('sitemap');


// ========================================
// USER / CUSTOMER DASHBOARD
// ========================================

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/my-account', [UserDashboardController::class, 'index'])
        ->name('account.dashboard');

    });



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

             })->name('checkout');


        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');

            // ========================================
            // GUEST CHECKOUT - MOBILE OTP
            // ========================================

            // Show Guest OTP verification page
            Route::get(
                '/guest/phone/verify-otp',
                [GuestPhoneOtpController::class, 'show']
            )->name('guest.phone.otp.show');


            // Send / resend Guest OTP
            Route::post(
                '/guest/phone/send-otp',
                [GuestPhoneOtpController::class, 'send']
            )
                ->middleware('throttle:3,1')
                ->name('guest.phone.otp.send');


            // Verify Guest OTP and create order
            Route::post(
                '/guest/phone/verify-otp',
                [GuestPhoneOtpController::class, 'verify']
            )
                ->middleware('throttle:6,1')
                ->name('guest.phone.otp.verify');


            // Guest Order Success
            Route::get(
                '/guest/order-success/{token}',
                function ($token) {

                    $order = \App\Models\Order::where(
                        'tracking_token',
                        $token
                    )
                        ->where('customer_type', 'guest')
                        ->firstOrFail();

                    /*
                     * Only allow the order that was just completed
                     * in this browser session.
                     */
                    if (
                        session('guest_completed_order_id')
                        != $order->id
                    ) {
                        abort(403);
                    }

                    return view(
                        'frontend.order-success',
                        compact('order')
                    );

                }
            )->name('guest.order.success');

            Route::get('/order-success/{order}', function (\App\Models\Order $order) {
                return view('frontend.order-success', compact('order'));
            })->middleware('auth')->name('order.success');

            Route::middleware('auth')->group(function () {

            Route::get('/my-orders', [OrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/my-orders/{order}', [OrderController::class, 'show'])
                ->name('orders.show');

            });

            // ========================================
            // EMAIL OTP VERIFICATION
            // ========================================

            Route::middleware('auth')->group(function () {

                // Show OTP verification page
                Route::get('/email/verify-otp', [EmailOtpController::class, 'show'])
                    ->name('email.otp.show');

                // Send / resend OTP
                Route::post('/email/send-otp', [EmailOtpController::class, 'send'])
                    ->middleware('throttle:3,1')
                    ->name('email.otp.send');

                // Verify OTP
                Route::post('/email/verify-otp', [EmailOtpController::class, 'verify'])
                    ->middleware('throttle:6,1')
                    ->name('email.otp.verify');

            });

            // ========================================
            // PHONE OTP VERIFICATION
            // ========================================

            Route::middleware('auth')->group(function () {

                // Show phone verification page
                Route::get('/phone/verify-otp', [PhoneOtpController::class, 'show'])
                    ->name('phone.otp.show');

                // Send / resend phone OTP
                Route::post('/phone/send-otp', [PhoneOtpController::class, 'send'])
                ->middleware('throttle:20,1')
                ->name('phone.otp.send');

                // Verify phone OTP
                Route::post('/phone/verify-otp', [PhoneOtpController::class, 'verify'])
                    ->middleware('throttle:6,1')
                    ->name('phone.otp.verify');

            });



require __DIR__.'/auth.php';