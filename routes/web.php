<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ServiceController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Admin\ImageController;

// Root redirect to default locale (MUST BE BEFORE THE LOCALIZED GROUP)
Route::get('/', function () {
    return redirect(LaravelLocalization::getLocalizedURL(app()->getLocale()));
});

// Localized routes
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    
    // Frontend Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/categories/{slug}', [ProductController::class, 'category'])->name('categories.show');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{variant}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/add-product/{product}', [CartController::class, 'addByProduct'])->name('cart.add.product');
    Route::post('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/update-variant/{rowId}', [CartController::class, 'updateVariant'])->name('cart.update.variant');
    Route::get('/cart/product-variants/{product}', [CartController::class, 'getProductVariants'])->name('cart.product.variants');
    Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/data', [CartController::class, 'getCart'])->name('cart.data');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/stripe/success/{order}', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe.success');
    Route::get('/checkout/stripe/cancel/{order}', [CheckoutController::class, 'stripeCancel'])->name('checkout.stripe.cancel');
    
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/services/{service}/book', [ServiceController::class, 'book'])->name('services.book');
    
    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    
    // About
    Route::get('/about', function () { 
        return view('frontend.about.index'); 
    })->name('about');
    
    // Alert Dialog Demo
    Route::get('/alert-dialog-demo', function () {
        return view('frontend.alert-dialog-demo');
    })->name('alert-dialog-demo');
    
    // Profile & Orders (require auth)
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        Route::get('/orders', function () { return view('frontend.orders.index'); })->name('orders.index');
        
        // Wishlist routes
        Route::get('/wishlist', [App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/add/{product}', [App\Http\Controllers\Frontend\WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/wishlist/remove/{product}', [App\Http\Controllers\Frontend\WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::post('/wishlist/toggle/{product}', [App\Http\Controllers\Frontend\WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/clear', [App\Http\Controllers\Frontend\WishlistController::class, 'clear'])->name('wishlist.clear');
    });
    
    // Admin Routes
    Route::middleware(['auth', 'role:admin|manager'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Products
        Route::resource('products', AdminProductController::class);
        Route::delete('images/{image}', [ImageController::class, 'delete'])->name('images.destroy');

        
        // Orders
        Route::resource('orders', OrderController::class);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Services
        Route::resource('services', AdminServiceController::class);
        Route::delete('services/images/{image}', [AdminServiceController::class, 'deleteImage'])->name('services.images.destroy');
        Route::get('meetings', [AdminServiceController::class, 'meetings'])->name('meetings.index');
        Route::patch('meetings/{meeting}/status', [AdminServiceController::class, 'updateMeetingStatus'])->name('meetings.update-status');
        
        // Contacts
        Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        
        // Users Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // Auth Routes
    require __DIR__.'/auth.php';
});
