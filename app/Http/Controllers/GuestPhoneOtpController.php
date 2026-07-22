<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GuestPhoneOtpController extends Controller
{
    /**
     * Show Guest Mobile OTP verification page.
     */
    public function show()
    {
        if (!session()->has('guest_checkout')) {
            return redirect()
                ->route('checkout')
                ->with(
                    'error',
                    'Please enter your checkout details first.'
                );
        }

        return view('frontend.guest-phone-otp');
    }

    /**
     * Generate / resend Guest Mobile OTP.
     */
    public function send(Request $request)
    {
        $checkout = session('guest_checkout');

        if (!$checkout) {
            return redirect()
                ->route('checkout')
                ->with(
                    'error',
                    'Please enter your checkout details first.'
                );
        }

        $otp = (string) random_int(
            100000,
            999999
        );

        session()->put(
            'guest_phone_otp',
            $otp
        );

        session()->put(
            'guest_phone_otp_expires_at',
            now()->addMinutes(10)->timestamp
        );

        /*
        |--------------------------------------------------------------------------
        | LOCAL TESTING ONLY
        |--------------------------------------------------------------------------
        | OTP will appear in:
        | storage/logs/laravel.log
        |
        | Later this can be replaced with a real SMS provider.
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Budlume Guest Checkout Phone OTP',
            [
                'phone' =>
                    $checkout['phone'],

                'otp' =>
                    $otp,
            ]
        );

        return redirect()
            ->route('guest.phone.otp.show')
            ->with(
                'success',
                'A 6-digit verification code has been generated for your mobile number.'
            );
    }

    /**
     * Verify Guest OTP and create the order.
     */
    public function verify(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate OTP
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Guest Checkout + Cart
        |--------------------------------------------------------------------------
        */

        $checkout = session(
            'guest_checkout'
        );

        $cart = session(
            'cart',
            []
        );

        if (!$checkout) {
            return redirect()
                ->route('checkout')
                ->with(
                    'error',
                    'Your checkout session has expired.'
                );
        }

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Saved OTP
        |--------------------------------------------------------------------------
        */

        $savedOtp = session(
            'guest_phone_otp'
        );

        $expiresAt = session(
            'guest_phone_otp_expires_at'
        );

        if (!$savedOtp || !$expiresAt) {
            return back()
                ->withErrors([
                    'otp' =>
                        'Please request a verification code first.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check OTP Expiration
        |--------------------------------------------------------------------------
        */

        if (
            now()->timestamp
            > $expiresAt
        ) {

            session()->forget([
                'guest_phone_otp',
                'guest_phone_otp_expires_at',
            ]);

            return back()
                ->withErrors([
                    'otp' =>
                        'The verification code has expired. Please request a new code.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify OTP
        |--------------------------------------------------------------------------
        */

        if (
            !hash_equals(
                (string) $savedOtp,
                (string) $request->otp
            )
        ) {

            return back()
                ->withErrors([
                    'otp' =>
                        'The verification code is incorrect.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Guest Order Total
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)
            ->sum(
                function ($item) {

                    return $item['price']
                        * $item['quantity'];
                }
            );

        /*
         * Guests currently receive no
         * registered-customer discount.
         */

        $discountPercentage = 0;

        $discountAmount = 0;

        $total = $subtotal;

        /*
        |--------------------------------------------------------------------------
        | Create Guest Order + Order Items
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(
            function () use (
                $checkout,
                $cart,
                $subtotal,
                $discountPercentage,
                $discountAmount,
                $total
            ) {

                /*
                |--------------------------------------------------------------------------
                | Create Order
                |--------------------------------------------------------------------------
                */

                $order = Order::create([

                    /*
                     * Guest has no permanent
                     * customer record.
                     */

                    'customer_id' =>
                        null,

                    'customer_type' =>
                        'guest',

                    /*
                     * Order Number
                     */

                    'order_number' =>
                        'ORD-' . strtoupper(
                            Str::random(10)
                        ),

                    /*
                     * Guest Snapshot
                     */

                    'customer_name' =>
                        $checkout['name'],

                    'customer_email' =>
                        $checkout['email'],

                    'customer_phone' =>
                        $checkout['phone'],

                    /*
                     * Delivery Address Snapshot
                     */

                    'delivery_address_line_1' =>
                        $checkout[
                            'delivery_address_line_1'
                        ],

                    'delivery_address_line_2' =>
                        $checkout[
                            'delivery_address_line_2'
                        ] ?? null,

                    'delivery_city' =>
                        $checkout[
                            'delivery_city'
                        ],

                    'delivery_state' =>
                        $checkout[
                            'delivery_state'
                        ] ?? null,

                    'delivery_postal_code' =>
                        $checkout[
                            'delivery_postal_code'
                        ],

                    'delivery_country' =>
                        $checkout[
                            'delivery_country'
                        ],

                    /*
                     * Pricing
                     */

                    'subtotal' =>
                        $subtotal,

                    'discount_percentage' =>
                        $discountPercentage,

                    'discount_amount' =>
                        $discountAmount,

                    'total_amount' =>
                        $total,

                    /*
                     * Guest mobile was successfully
                     * verified before order creation.
                     */

                    'phone_verified_at' =>
                        now(),

                    /*
                     * Secure Guest Order Tracking
                     */

                    'tracking_token' =>
                        Str::random(64),

                    /*
                     * Initial Order Status
                     */

                    'status' =>
                        'pending',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create Order Items
                |--------------------------------------------------------------------------
                */

                foreach (
                    $cart
                    as $productId => $item
                ) {

                    OrderItem::create([

                        'order_id' =>
                            $order->id,

                        'product_id' =>
                            $productId,

                        'quantity' =>
                            $item['quantity'],

                        'price' =>
                            $item['price'],

                        'subtotal' =>
                            $item['price']
                            * $item['quantity'],
                    ]);
                }

                return $order;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Send Guest Order Invoice Email
        |--------------------------------------------------------------------------
        |
        | At this point the database transaction has completed successfully.
        | Therefore the Order and OrderItems already exist.
        |
        | Email sending stays OUTSIDE the database transaction.
        | If SMTP/email fails, the valid order will NOT be rolled back.
        |
        */

        try {

            /*
             * Load products belonging to
             * each order item for invoice.
             */

            $order->load([
                'items.product',
            ]);

            /*
             * Send invoice to the email
             * entered by the Guest at checkout.
             */

            Mail::to(
                $order->customer_email
            )->send(
                new OrderInvoiceMail(
                    $order
                )
            );

            /*
             * Helpful confirmation in local log.
             */

            Log::info(
                'Budlume Guest Order Invoice Email Sent',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'email' =>
                        $order->customer_email,
                ]
            );

        } catch (\Throwable $e) {

            /*
             * Do not cancel the successfully
             * created order if email fails.
             */

            Log::error(
                'Budlume Guest Order Invoice Email Failed',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'email' =>
                        $order->customer_email,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Guest Checkout Session
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'cart',
            'guest_checkout',
            'guest_phone_otp',
            'guest_phone_otp_expires_at',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Completed Guest Order ID Temporarily
        |--------------------------------------------------------------------------
        |
        | This allows the next success page
        | to display the newly-created order safely.
        |
        */

        session()->put(
            'guest_completed_order_id',
            $order->id
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect to Guest Order Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'guest.order.success',
                [
                    'token' =>
                        $order->tracking_token,
                ]
            );
    }
}