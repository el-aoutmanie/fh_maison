<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    /**
     * Remove the specified image from storage.
     */
    public function delete(Image $image)
    {
        // Verify the image belongs to a product (since images are now product-specific)
        if (!$image->product_id) {
            return response()->json(['success' => false, 'message' => __('Image not found or not associated with a product')], 404);
        }

        // Extract path from full URL (remove domain and /storage/ prefix)
        $path = str_replace(url('storage/'), '', $image->url);

        // Delete the file from storage
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Delete the database record
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => __('Image deleted successfully')
        ]);
    }
}
