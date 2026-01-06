<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $tax = Cart::tax();
        $total = Cart::total();
        
        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add item to cart by product ID (uses first variant)
     * Used for wishlist add-to-cart functionality
     */
    public function addByProduct(Request $request, Product $product)
    {
        // Get the first available variant
        $variant = $product->variants()->first();
        
        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => __('This product has no available variants')
            ], 422);
        }

        // Use quantity as primary stock field, fallback to stock
        $availableStock = $variant->quantity ?? $variant->stock ?? 0;
        
        // Check if variant has stock
        if ($availableStock <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('This product is out of stock')
            ], 422);
        }

        $quantity = $request->input('quantity', 1);
        
        if ($quantity > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => __('Only :stock items available in stock', ['stock' => $availableStock])
            ], 422);
        }
        
        // Get the first image from the product's images relationship
        $productImage = $product->images()->first()?->url ?? null;
        
        Cart::add([
            'id' => $variant->id,
            'name' => $product->name[app()->getLocale()] ?? $product->name['en'],
            'qty' => $quantity,
            'price' => $variant->price,
            'weight' => 0,
            'options' => [
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'image' => $productImage,
                'variant_name' => $variant->name[app()->getLocale()] ?? $variant->name['en'] ?? null,
                'stock' => $availableStock,
                'category' => $product->category?->name[app()->getLocale()] ?? $product->category?->name['en'] ?? 'Handcrafted',
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Product added to cart successfully!'),
            'cart_count' => Cart::count(),
            'cart_total' => Cart::total()
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, Variant $variant)
    {
        // Use quantity as primary stock field, fallback to stock
        $availableStock = $variant->quantity ?? $variant->stock ?? 0;
        
        // Check if variant has stock
        if ($availableStock <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('This product is out of stock')
            ], 422);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $availableStock
        ]);

        $product = $variant->product;
        
        // Get the first image from the product's images relationship
        $productImage = $product->images()->first()?->url ?? null;
        
        Cart::add([
            'id' => $variant->id,
            'name' => $product->name[app()->getLocale()] ?? $product->name['en'],
            'qty' => $request->quantity,
            'price' => $variant->price,
            'weight' => 0,
            'options' => [
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'image' => $productImage,
                'variant_name' => $variant->name[app()->getLocale()] ?? $variant->name['en'] ?? null,
                'stock' => $availableStock,
                'category' => $product->category?->name[app()->getLocale()] ?? $product->category?->name['en'] ?? 'Handcrafted',
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Product added to cart successfully!'),
            'cart_count' => Cart::count(),
            'cart_total' => Cart::total()
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $rowId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::get($rowId);
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => __('Item not found in cart')
            ], 404);
        }

        // Check stock availability
        if ($request->quantity > $cartItem->options->stock) {
            return response()->json([
                'success' => false,
                'message' => __('Only :stock items available in stock', ['stock' => $cartItem->options->stock])
            ], 422);
        }

        Cart::update($rowId, $request->quantity);

        return response()->json([
            'success' => true,
            'message' => __('Cart updated successfully!'),
            'cart_count' => Cart::count(),
            'cart_subtotal' => Cart::subtotal(),
            'cart_total' => Cart::total(),
            'item_subtotal' => number_format($cartItem->price * $request->quantity, 2)
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($rowId)
    {
        Cart::remove($rowId);

        return response()->json([
            'success' => true,
            'message' => __('Item removed from cart'),
            'cart_count' => Cart::count(),
            'cart_subtotal' => Cart::subtotal(),
            'cart_total' => Cart::total()
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        Cart::destroy();

        return response()->json([
            'success' => true,
            'message' => __('Cart cleared successfully')
        ]);
    }

    /**
     * Get cart data (for AJAX requests)
     */
    public function getCart()
    {
        return response()->json([
            'items' => Cart::content(),
            'count' => Cart::count(),
            'subtotal' => Cart::subtotal(),
            'tax' => Cart::tax(),
            'total' => Cart::total()
        ]);
    }

    /**
     * Update cart item variant
     */
    public function updateVariant(Request $request, $rowId)
    {
        $request->validate([
            'variant_id' => 'required|exists:variants,id'
        ]);

        $cartItem = Cart::get($rowId);
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => __('Item not found in cart')
            ], 404);
        }

        $newVariant = Variant::with('product.images', 'product.category')->find($request->variant_id);
        
        // Use quantity as primary stock field, fallback to stock
        $availableStock = $newVariant->quantity ?? $newVariant->stock ?? 0;
        
        // Check if new variant has stock
        if ($availableStock <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('This variant is out of stock')
            ], 422);
        }

        // Check if requested quantity is available
        $quantity = min($cartItem->qty, $availableStock);
        
        // Remove old item
        Cart::remove($rowId);
        
        $product = $newVariant->product;
        $productImage = $product->images()->first()?->url ?? null;
        
        // Add new item with updated variant
        $newItem = Cart::add([
            'id' => $newVariant->id,
            'name' => $product->name[app()->getLocale()] ?? $product->name['en'],
            'qty' => $quantity,
            'price' => $newVariant->price,
            'weight' => 0,
            'options' => [
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'image' => $productImage,
                'variant_name' => $newVariant->name[app()->getLocale()] ?? $newVariant->name['en'] ?? null,
                'stock' => $availableStock,
                'category' => $product->category?->name[app()->getLocale()] ?? $product->category?->name['en'] ?? 'Handcrafted',
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Variant updated successfully!'),
            'cart_count' => Cart::count(),
            'cart_subtotal' => Cart::subtotal(),
            'cart_total' => Cart::total(),
            'item' => [
                'rowId' => $newItem->rowId,
                'name' => $newItem->name,
                'price' => number_format($newItem->price, 2),
                'qty' => $newItem->qty,
                'subtotal' => number_format($newItem->subtotal, 2),
                'variant_name' => $newItem->options->variant_name,
                'stock' => $newItem->options->stock,
            ]
        ]);
    }

    /**
     * Get product variants for cart item
     */
    public function getProductVariants($productId)
    {
        $product = Product::with(['variants' => function($query) {
            $query->where('is_active', true);
        }])->find($productId);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => __('Product not found')
            ], 404);
        }

        $variants = $product->variants->map(function($variant) {
            $stock = $variant->quantity ?? $variant->stock ?? 0;
            return [
                'id' => $variant->id,
                'name' => $variant->name[app()->getLocale()] ?? $variant->name['en'] ?? 'Default',
                'price' => number_format($variant->price, 2),
                'stock' => $stock,
                'in_stock' => $stock > 0,
            ];
        });

        return response()->json([
            'success' => true,
            'variants' => $variants
        ]);
    }
}
