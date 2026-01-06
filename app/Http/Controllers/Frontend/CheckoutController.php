<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        // Restore test cart if current cart is empty (for testing payment methods)
        if (Cart::count() === 0) {
            try {
                Cart::restore('test_user_cart');
            } catch (\Exception $e) {
                // Silently continue if cart restore fails
            }
        }
        
        if (Cart::count() == 0) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty'));
        }

        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $tax = Cart::tax();
        $total = Cart::total();
        
        $customer = null;
        if (Auth::check()) {
            $customer = Customer::where('user_id', Auth::id())->first();
        }
        
        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'tax', 'total', 'customer'));
    }

    /**
     * Process checkout and create order
     */
    public function store(Request $request)
    {
        if (Cart::count() == 0) {
            return response()->json([
                'success' => false,
                'message' => __('Your cart is empty')
            ], 422);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,stripe',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        
        try {
            // Create or update customer
            $customer = null;
            if (Auth::check()) {
                $customer = Customer::updateOrCreate(
                    ['user_id' => Auth::id()],
                    [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone_number' => $request->phone,
                        'address_line1' => $request->address,
                        'address_line2' => $request->state, // Store state in address_line2
                        'city' => $request->city,
                        'country' => $request->country,
                    ]
                );
            } else {
                $customer = Customer::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone,
                    'address_line1' => $request->address,
                    'address_line2' => $request->state, // Store state in address_line2
                    'city' => $request->city,
                    'country' => $request->country,
                ]);
            }

            // Calculate amounts
            $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
            $tax = floatval(str_replace(',', '', Cart::tax()));
            $total = floatval(str_replace(',', '', Cart::total()));
            
            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'code' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'total_amount' => $total,
                'shipping_amount' => 0, // Free shipping
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_state' => $request->state,
                'shipping_zip' => $request->postal_code,
                'shipping_country' => $request->country,
                'shipping_name' => $request->first_name . ' ' . $request->last_name,
                'shipping_phone' => $request->phone,
            ]);

            // Create order items
            foreach (Cart::content() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->options->product_id ?? null,
                    'variant_id' => $item->id,
                    'product_name' => $item->name,
                    'variant_name' => $item->options->variant_name ?? null,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            DB::commit();
            
            // Handle payment method
            if ($request->payment_method === 'stripe') {
                // Check if Stripe is configured
                $stripeSecret = config('services.stripe.secret');
                if (empty($stripeSecret)) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Stripe payment is not configured. Please contact the administrator or use Cash on Delivery.')
                    ], 500);
                }
                
                // Create Stripe checkout session
                try {
                    Stripe::setApiKey($stripeSecret);
                    
                    $lineItems = [];
                    foreach (Cart::content() as $item) {
                        $lineItems[] = [
                            'price_data' => [
                                'currency' => 'usd',
                                'product_data' => [
                                    'name' => $item->name,
                                    'description' => $item->options->variant_name ?? '',
                                ],
                                'unit_amount' => intval($item->price * 100), // Convert to cents
                            ],
                            'quantity' => $item->qty,
                        ];
                    }
                    
                    $session = StripeSession::create([
                        'payment_method_types' => ['card'],
                        'line_items' => $lineItems,
                        'mode' => 'payment',
                        'success_url' => route('checkout.stripe.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => route('checkout.stripe.cancel', ['order' => $order->id]),
                        'customer_email' => $customer->email,
                        'metadata' => [
                            'order_id' => $order->id,
                            'order_code' => $order->code,
                        ],
                    ]);
                    
                    // Store session ID in order for verification
                    $order->update(['payment_status' => 'pending_stripe']);
                    
                    return response()->json([
                        'success' => true,
                        'payment_method' => 'stripe',
                        'stripe_session_id' => $session->id,
                        'redirect' => $session->url
                    ]);
                    
                } catch (ApiErrorException $e) {
                    // Don't rollback - the order is already created, just payment failed
                    $order->update(['payment_status' => 'failed']);
                    
                    return response()->json([
                        'success' => false,
                        'message' => __('Payment processing failed: ') . $e->getMessage()
                    ], 500);
                }
            } else {
                // Cash on Delivery
                Cart::destroy();
                
                return response()->json([
                    'success' => true,
                    'message' => __('Order placed successfully!'),
                    'payment_method' => 'cod',
                    'order_id' => $order->id,
                    'redirect' => route('checkout.success', $order->id)
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while processing your order. Please try again.')
            ], 500);
        }
    }

    /**
     * Display order success page
     */
    public function success(Order $order)
    {
        // Load customer relationship and nested relationships for items
        $order->load(['customer', 'items.variant.product.images']);

        return view('frontend.checkout.success', compact('order'));
    }
    
    /**
     * Handle successful Stripe payment
     */
    public function stripeSuccess(Request $request, Order $order)
    {
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('cart.index')->with('error', __('Invalid payment session'));
        }
        
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);
            
            if ($session->payment_status === 'paid' && $session->metadata->order_id == $order->id) {
                // Update order payment status
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => 'processing'
                ]);
                
                // Clear cart
                Cart::destroy();
                
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', __('Payment successful! Your order is being processed.'));
            }
            
            return redirect()->route('cart.index')->with('error', __('Payment verification failed'));
            
        } catch (ApiErrorException $e) {
            return redirect()->route('cart.index')->with('error', __('Payment verification failed'));
        }
    }
    
    /**
     * Handle cancelled Stripe payment
     */
    public function stripeCancel(Order $order)
    {
        // Update order status
        $order->update([
            'payment_status' => 'cancelled',
            'status' => 'cancelled'
        ]);
        
        return redirect()->route('cart.index')
            ->with('warning', __('Payment was cancelled. Your order has been cancelled.'));
    }
}

