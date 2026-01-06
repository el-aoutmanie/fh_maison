<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        return view('frontend.wishlist.index');
    }

    /**
     * Add a product to the wishlist.
     */
    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('Please login to add items to your wishlist.')
            ], 401);
        }

        $product = Product::findOrFail($productId);

        try {
            WishlistItem::firstOrCreate([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Product added to wishlist successfully!')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('This item is already in your wishlist.')
            ], 409);
        }
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove($productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('Please login to manage your wishlist.')
            ], 401);
        }

        $deleted = WishlistItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => __('Product removed from wishlist successfully!')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Product not found in wishlist.')
        ], 404);
    }

    /**
     * Toggle a product in the wishlist.
     */
    public function toggle($productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('Please login to manage your wishlist.')
            ], 401);
        }

        $wishlistItem = WishlistItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json([
                'success' => true,
                'inWishlist' => false,
                'message' => __('Product removed from wishlist.')
            ]);
        } else {
            WishlistItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
            return response()->json([
                'success' => true,
                'inWishlist' => true,
                'message' => __('Product added to wishlist.')
            ]);
        }
    }

    /**
     * Clear all items from the wishlist.
     */
    public function clear()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('Please login to manage your wishlist.')
            ], 401);
        }

        WishlistItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => __('Wishlist cleared successfully!')
        ]);
    }
}