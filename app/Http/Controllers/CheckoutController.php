<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    /**
     * Store checkout.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Checkout Information
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'delivery_address_line_1' => [
                'required',
                'string',
                'max:255',
            ],

            'delivery_address_line_2' => [
                'nullable',
                'string',
                'max:255',
            ],

            'delivery_city' => [
                'required',
                'string',
                'max:100',
            ],

            'delivery_state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'delivery_postal_code' => [
                'required',
                'string',
                'max:30',
            ],

            'delivery_country' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Cart
        |--------------------------------------------------------------------------
        */
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        /*
        |--------------------------------------------------------------------------
        | Determine Checkout Type
        |--------------------------------------------------------------------------
        */
        $user = Auth::user();

        $customerType = $user
            ? 'registered'
            : 'guest';

        /*
        |--------------------------------------------------------------------------
        | Guest Checkout
        |--------------------------------------------------------------------------
        | Do NOT create the order yet.
        | First save checkout information temporarily in session.
        | Next step will send/verify mobile OTP.
        |--------------------------------------------------------------------------
        */
        if (!$user) {

            session()->put('guest_checkout', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,

                'delivery_address_line_1' =>
                    $request->delivery_address_line_1,

                'delivery_address_line_2' =>
                    $request->delivery_address_line_2,

                'delivery_city' =>
                    $request->delivery_city,

                'delivery_state' =>
                    $request->delivery_state,

                'delivery_postal_code' =>
                    $request->delivery_postal_code,

                'delivery_country' =>
                    $request->delivery_country,
            ]);

            // Generate Guest Mobile OTP automatically
            $otp = (string) random_int(100000, 999999);

            session([
                'guest_phone_otp' => $otp,
                'guest_phone_otp_expires_at' => now()->addMinutes(10)->timestamp,
            ]);

            // LOCAL TESTING ONLY
            Log::info('Budlume Guest Checkout Phone OTP', [
                'phone' => $request->phone,
                'otp'   => $otp,
            ]);

            return redirect()
                ->route('guest.phone.otp.show')
                ->with(
                    'success',
                    'A 6-digit verification code has been generated for your mobile number.'
                );

            }
        /*
        |--------------------------------------------------------------------------
        | Registered Customer Must Have Verified Mobile
        |--------------------------------------------------------------------------
        */
        if (!$user->phone_verified_at) {

            return redirect()
                ->route('phone.otp.show')
                ->with(
                    'error',
                    'Please verify your mobile number before placing your order.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Subtotal
        |--------------------------------------------------------------------------
        */
        $subtotal = collect($cart)->sum(function ($item) {

            return $item['price']
                * $item['quantity'];
        });

        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        | Admin-controlled discount will be connected in a later step.
        |--------------------------------------------------------------------------
        */
        $discountPercentage = 0;

        $discountAmount =
            ($subtotal * $discountPercentage) / 100;

        $total =
            $subtotal - $discountAmount;

        /*
        |--------------------------------------------------------------------------
        | Create Registered Customer Order
        |--------------------------------------------------------------------------
        */
        $order = DB::transaction(
            function () use (
                $request,
                $cart,
                $user,
                $customerType,
                $subtotal,
                $discountPercentage,
                $discountAmount,
                $total
            ) {

                /*
                 * Keep existing customers table synchronized.
                 */
                $customer = Customer::updateOrCreate(
                    [
                        'email' => $user->email,
                    ],
                    [
                        'name' => $user->name,
                        'phone' => $user->phone,

                        /*
                         * Customer permanent/home address.
                         */
                        'address' => trim(
                            collect([
                                $user->address_line_1,
                                $user->address_line_2,
                                $user->city,
                                $user->state,
                                $user->postal_code,
                                $user->country,
                            ])
                            ->filter()
                            ->implode(', ')
                        ),
                    ]
                );

                /*
                 * Create order with permanent snapshot.
                 */
                $order = Order::create([

                    'customer_id' =>
                        $customer->id,

                    'customer_type' =>
                        $customerType,

                    'order_number' =>
                        'ORD-' .
                        strtoupper(Str::random(10)),

                    /*
                     * Customer snapshot.
                     */
                    'customer_name' =>
                        $request->name,

                    'customer_email' =>
                        $request->email,

                    'customer_phone' =>
                        $request->phone,

                    /*
                     * Delivery address snapshot.
                     */
                    'delivery_address_line_1' =>
                        $request->delivery_address_line_1,

                    'delivery_address_line_2' =>
                        $request->delivery_address_line_2,

                    'delivery_city' =>
                        $request->delivery_city,

                    'delivery_state' =>
                        $request->delivery_state,

                    'delivery_postal_code' =>
                        $request->delivery_postal_code,

                    'delivery_country' =>
                        $request->delivery_country,

                    /*
                     * Pricing snapshot.
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
                     * Phone already verified.
                     */
                    'phone_verified_at' =>
                        now(),

                    /*
                     * Secure order tracking token.
                     */
                    'tracking_token' =>
                        Str::random(64),

                    'status' =>
                        'pending',
                ]);

                /*
                 * Create Order Items.
                 */
                foreach (
                    $cart as $productId => $item
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
| Send Invoice Email
|--------------------------------------------------------------------------
| The order and all order items have already been created successfully.
| Email failure should not cancel or duplicate the customer's order.
|--------------------------------------------------------------------------
*/

try {

    $order->load([
        'items.product',
    ]);

    Mail::to($order->customer_email)
        ->send(
            new OrderInvoiceMail($order)
        );

} catch (\Throwable $e) {

    Log::error(
        'Budlume Order Invoice Email Failed',
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
| Clear Cart Only After Successful Order Creation
|--------------------------------------------------------------------------
*/

session()->forget('cart');

return redirect()
    ->route(
        'order.success',
        $order
    );

     }
}