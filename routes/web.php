<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\product\CartController;
use App\Http\Controllers\product\CheckoutController;
use App\Http\Controllers\product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\superAdmin\PaymentAccountController;
use Illuminate\Support\Facades\Route;


/*********** Public Routes *************/
Route::get('/', [HomeController::class, 'indexHome'])->name('home');
Route::get('/about-us', [HomeController::class, 'indexAboutUs'])->name('about-us');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/category/{categorySlug}', [ProductController::class, 'indexCategory'])->name('products.category');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');


/**
 * Auth Routes
 *
 */
require __DIR__ . '/auth.php';


/**
 *  Super Admin routes (hanya super_admin)
 *
 */
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/payment-accounts', [PaymentAccountController::class, 'index'])->name('payment-accounts.index');
        Route::post('/payment-accounts', [PaymentAccountController::class, 'store'])->name('payment-accounts.store');
        Route::put('/payment-accounts/{id}', [PaymentAccountController::class, 'update'])->name('payment-accounts.update');
        Route::delete('/payment-accounts/{id}', [PaymentAccountController::class, 'destroy'])->name('payment-accounts.destroy');
        Route::get('/manage-users', [ManageUserController::class, 'index'])->name('manage-users.index');
        Route::post('/manage-users', [ManageUserController::class, 'store'])->name('manage-users.store');
        Route::put('/manage-users/{id}', [ManageUserController::class, 'update'])->name('manage-users.update');
        Route::delete('/manage-users/{id}', [ManageUserController::class, 'destroy'])->name('manage-users.destroy');

        // Financial Report routes (Super Admin Only)
        Route::get('/financial-report', [\App\Http\Controllers\Admin\FinancialReportController::class, 'index'])->name('financial-report');

    });

/**
 * Admin routes (admin dan super_admin)
 *
 */
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/category', [AdminController::class, 'category'])->name('category');
        Route::post('/category', [AdminController::class, 'categoryStore'])->name('category.store');
        Route::put('/category/{id}', [AdminController::class, 'categoryUpdate'])->name('category.update');
        Route::delete('/category/{id}', [AdminController::class, 'categoryDelete'])->name('category.delete');

        Route::get('/product', [AdminController::class, 'product'])->name('products');
        Route::post('/product', [AdminController::class, 'productStore'])->name('products.store');
        Route::put('/product/{id}', [AdminController::class, 'productUpdate'])->name('products.update');
        Route::delete('/product/{id}', [AdminController::class, 'productDelete'])->name('products.delete');
        Route::patch('/product/{id}/toggle-status', [AdminController::class, 'productToggleStatus'])->name('products.toggle-status');

        Route::get('/order', [AdminController::class, 'order'])->name('orders');
        Route::post('/order/send/{id}', [AdminController::class, 'orderSend'])->name('order.send');
        Route::post('/order/approve/{id}', [AdminController::class, 'approvePayment'])->name('order.approve');
        Route::post('/order/reject/{id}', [AdminController::class, 'rejectPayment'])->name('order.reject');

        Route::post('/return/rejected/{id}', [AdminController::class, 'returnRejected'])->name('return.rejected');
        Route::post('/return/approved/{id}', [AdminController::class, 'returnApproved'])->name('return.approved');

        // Fitur pyment return shipping
        Route::get('/payment', [AdminController::class, 'payment'])->name('payments');
        Route::get('/return', [AdminController::class, 'return'])->name('return');
        Route::get('/shipping', [AdminController::class, 'shipping'])->name('shippings');

        // Carousel management routes
        Route::get('/carousel', [\App\Http\Controllers\Admin\CarouselController::class, 'index'])->name('carousel.index');
        Route::post('/carousel', [\App\Http\Controllers\Admin\CarouselController::class, 'store'])->name('carousel.store');
        Route::patch('/carousel/{id}/toggle', [\App\Http\Controllers\Admin\CarouselController::class, 'toggleActive'])->name('carousel.toggle');
        Route::post('/carousel/update-order', [\App\Http\Controllers\Admin\CarouselController::class, 'updateOrder'])->name('carousel.update-order');
        Route::delete('/carousel/{id}', [\App\Http\Controllers\Admin\CarouselController::class, 'destroy'])->name('carousel.destroy');
    });


// Customer routes
Route::middleware(['auth', 'role:customer'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/profile/address', [ProfileController::class, 'address'])->name('profile.address');
        Route::post('/profile/address', [ProfileController::class, 'addAddress'])->name('profile.addAddress');
        Route::post('/profile/address/{id}', [ProfileController::class, 'updateAddress'])->name('profile.updateAddress');
        Route::delete('/profile/address/{id}', [ProfileController::class, 'delAddress'])->name('profile.deleteAddress');

        Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
        Route::post('/profile/orders/{id}', [ProfileController::class, 'completeOrder'])->name('profile.completeOrder');
        Route::post('/profile/orders/return/{id}', [ProfileController::class, 'returnOrder'])->name('profile.returnOrder');

    });

// Customer router without prefix 'user'
Route::middleware(['auth', 'role:customer'])->group(function () {

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');


    // Checkout
    Route::get('/checkout/cart', [CheckoutController::class, 'checkoutCart'])->name('checkout.cart');
    Route::get('/checkout', function() {
        return redirect()->route('cart.index');
    });
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'checkoutProcess'])->name('checkout.process');
});


