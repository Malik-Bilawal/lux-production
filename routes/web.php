<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// MODELS & MIDDLEWARE
use App\Models\User;
use App\Http\Middleware\VerifyCsrfToken;

// CONTROLLERS
use App\Http\Controllers\User\{
    HomeController,
    AboutController,
    ObjectController,
    ContactController,
    GalleryController,
    WatchesController,
    CategoryController,
    NewDropsController,
    OrderHistoryController
};
use App\Http\Controllers\User\Auth\{
    LoginController,
    RegisterController,
    VerifyController,
    GoogleLoginController,
    ResetPasswordController,
    ForgotPasswordController
};
use App\Http\Controllers\User\Checkouts\{
    CartController,
    CheckoutController,
    OrderPlacedController
};
use App\Http\Controllers\User\Products\{
    ProductsController,
    ProductsDetailController,
    ReviewController
};
use App\Http\Controllers\User\CustomerSupport\{
    OrderTrackingController,
    CustomerSupportController
};
use App\Http\Controllers\User\Partial\NavbarController;

/*
|--------------------------------------------------------------------------
| PUBLIC / GENERAL ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('user.welcome');
Route::get('/about', [AboutController::class, 'index'])->name('user.about');
Route::get('/contact', [ContactController::class, 'index'])->name('user.contact');
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

// Newsletter
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [HomeController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Search & Navigation
Route::get('/search-products', [NavbarController::class, 'search']);

/*
|--------------------------------------------------------------------------
| SHOPPING & CATEGORIES
|--------------------------------------------------------------------------
*/
Route::get('/watches', [WatchesController::class, 'index'])->name('user.watches');
Route::get('/object', [ObjectController::class, 'index'])->name('user.object');
Route::get('/new-drops', [NewDropsController::class, 'index'])->name('user.new-drops');
Route::get('category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/home/products/fetch', [HomeController::class, 'fetchCategory'])->name('home.products.fetch');

/*
|--------------------------------------------------------------------------
| PRODUCT DETAIL & REVIEWS
|--------------------------------------------------------------------------
*/
Route::get('/products/{slug}', [ProductsDetailController::class, 'show'])->name('products.show');
Route::post('/products/{product}/notify', [ProductsDetailController::class, 'storeNotification'])->name('products.notify');

// Reviews (Note: Duplicate logic below in middleware)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
// Registration
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/resend-verification', [RegisterController::class, 'resend'])->name('resend.verification');
Route::post('/update-email', [RegisterController::class, 'updateEmail'])->name('update.email');
Route::get('/check-email', fn() => view('user.auth.check-email'))->name('check.email.page');
Route::get('/verify-email/{token}/{email}', [VerifyController::class, 'verify'])->name('custom.verify');

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');
Route::post('/google-login', [GoogleLoginController::class, 'handleGoogleLogin']);

// Password Recovery
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| CART & CHECKOUT
|--------------------------------------------------------------------------
*/
// Cart Management
Route::post('/add-to-cart', [ProductsDetailController::class, 'addToCart'])->name('cart.add');
Route::post('/buy-now', [ProductsDetailController::class, 'buyNow'])->name('cart.buyNow');
Route::get('/cart', [CartController::class, 'index'])->name('user.cart');
Route::get('/cart/data', [CartController::class, 'getCart'])->name('cart.data');
Route::post('/cart-update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart-remove', [CartController::class, 'removeCart'])->name('cart.remove');;

// Checkout Process
Route::get('/checkout/user', [CheckoutController::class, 'checkoutPage'])->name('user.checkout');
Route::get('/user/cart/summary', [CheckoutController::class, 'cartSummary'])->name('user.checkout.cartSummary');
Route::post('/apply-promo', [CheckoutController::class, 'applyPromo'])->name('user.checkout.applyPromo');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('user.checkout.placeOrder');
Route::get('/checkout/success/{order_code}', [CheckoutController::class, 'success'])->name('user.checkout.success');
Route::get('/shipping-methods', [CheckoutController::class, 'getShippingMethodsForCountry'])->name('user.checkout.getShippingMethods');
Route::get('/order-confirmation/{order_code}', [CheckoutController::class, 'orderConfirmation'])->name('user.order.confirmation');
/*
|--------------------------------------------------------------------------
| CUSTOMER SUPPORT & TRACKING
|--------------------------------------------------------------------------
*/
Route::get('/track-order', [OrderTrackingController::class, 'orderTracking'])->name('order.tracking.form');
Route::post('/track-order', [OrderTrackingController::class, 'trackOrder'])->name('track-order');
Route::get('/invoice/{order}', [OrderTrackingController::class, 'downloadInvoice'])->name('invoice.download');
Route::post('/reorder/{order_code}', [OrderTrackingController::class, 'reorder'])->name('order.reorder');
Route::post('/order/cancellation', [OrderTrackingController::class, 'cancel'])->name('order.cancellation');

// CMS / Static Pages (Catch-all slug should be towards the bottom)
Route::get('policies/{slug}', [CustomerSupportController::class, 'show'])->name('pages.show');



/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Duplicate Review route inside auth
    Route::post('/product/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Order History
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('user.order-history');
    Route::get('/orders/{order_code}/invoice', [OrderHistoryController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/cancel', [OrderHistoryController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/reorder/{order_code}', [OrderTrackingController::class, 'reorder'])->name('orders.reorder');
});
