<?php

use App\Models\Role;

// ================== ADMIN CONTROLLERS ==================
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

// Products
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProductController;

// Sliders & Banners
use App\Http\Controllers\Admin\AdminChatController;

// Sales & Offers
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;

// About
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\Admins\RoleController;
use App\Http\Controllers\Admin\ActivityLogsController;
use App\Http\Controllers\Admin\Admins\AdminController;
use App\Http\Controllers\Admin\Orders\OrderController;
use App\Http\Controllers\Admin\About\FounderController;

// Contact
use App\Http\Controllers\Admin\Auth\Admin2FAController;
use App\Http\Controllers\Admin\About\OurStoryController;

// Referrals
use App\Http\Controllers\Admin\Auth\AdminAuthController;

// Store Settings
use App\Http\Controllers\Admin\OfferSale\SaleController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\About\AboutPageController;
use App\Http\Controllers\Admin\About\OurValuesController;
use App\Http\Controllers\Admin\OfferSale\OfferController;

// Admins
use App\Http\Controllers\Admin\About\OurJourneyController;
use App\Http\Controllers\Admin\About\TeamMemberController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\ReviewsManagementController;
use App\Http\Controllers\Admin\Chatting\ChatGroupController;
use App\Http\Controllers\Admin\Contact\ContactInfoController;
use App\Http\Controllers\Admin\Chatting\ChatSidebarController;
use App\Http\Controllers\Admin\Products\ShowProductController;
use App\Http\Controllers\Admin\Chatting\AdminChattingController;
use App\Http\Controllers\Admin\Contact\ContactMessagesController;
use App\Http\Controllers\Admin\Products\ProductGalleryController;
use App\Http\Controllers\Admin\StoreSettings\CountriesController;
use App\Http\Controllers\Admin\StoreSettings\PromoCodeController;
use App\Http\Controllers\Admin\StoreSettings\StoreSettingsController;
use App\Http\Controllers\Admin\Referrals\ReferralManagementController;
use App\Http\Controllers\Admin\StoreSettings\PaymentMethodsController;
use App\Http\Controllers\Admin\StoreSettings\ShippingMethodsController;


Route::prefix('admin')->name('admin.')->group(function () {


    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::get('2fa', [Admin2FAController::class, 'showForm'])->name('2fa.form');
    Route::post('2fa', [Admin2FAController::class, 'verify'])->name('2fa.verify');
    Route::get('2fa/resend', [Admin2FAController::class, 'resend'])->name('2fa.resend');


    Route::middleware('auth:admin')->group(function () {


        Route::get('2fa/setup', [Admin2FAController::class, 'showSetupForm'])->name('2fa.setup.form');
        Route::post('2fa/setup', [Admin2FAController::class, 'setup'])->name('2fa.setup.verify');

        // Logout
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Force Logout (Now secure!)
        Route::post('force-logout/{admin}', [AdminController::class, 'forceLogout'])->name('forceLogout');

        // ... Add all your OTHER admin routes here (users, settings, etc.)

    });
});


// ================== PROTECTED ADMIN ROUTES ==================
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    // ================== DASHBOARD ==================
    Route::middleware('checkPermission:main_dashboard')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/admin/chart-data', [DashboardController::class, 'chartData'])->name('chartData');
    });

    Route::middleware('checkPermission:admin_management', 'superadmin')->group(function () {
        // Activity Logs Routes
        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [ActivityLogsController::class, 'index'])->name('index');
            Route::get('/{id}', [ActivityLogsController::class, 'show'])->name('show');
            Route::delete('/{id}', [ActivityLogsController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [ActivityLogsController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/export', [ActivityLogsController::class, 'export'])->name('export');
            Route::post('/clear-old', [ActivityLogsController::class, 'clearOldLogs'])->name('clear-old');
            Route::get('/stats', [ActivityLogsController::class, 'getStats'])->name('stats');
        });
    });


    // ================== PRODUCTS ==================
    Route::name('products.')->middleware('checkPermission:product_management')->group(function () {

        // --- 1. AJAX/SPECIFIC ROUTES (Must come first!) ---
        // These use specific keywords that must be matched exactly before the {id} wildcard is checked.
        Route::get('products/data', [ProductController::class, 'getProducts'])->name('data');
        Route::get('products/categories', [ProductController::class, 'getCategories'])->name('categories');
        Route::get('products/analytics', [ProductController::class, 'getAnalytics'])->name('analytics');

        // --- 2. Standard Resource Routes ---
        Route::get('products/', [ProductController::class, 'index'])->name('index');
        Route::post('products/store', [ProductController::class, 'store'])->name('store');


        Route::put('products/{id}/sort-order', [ProductController::class, 'updateSortOrder'])->name('update.sort-order');
        Route::post('products/{id}/image-sort-order', [ProductController::class, 'updateImageSortOrder'])->name('update.image-sort-order');

        Route::get('products/{id}', [ProductController::class, 'show'])->name('show');
        Route::put('products/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::resource('categories', CategoryController::class)
        ->middleware('checkPermission:category_management');




    // ================== SLIDERS & BANNERS ==================
    Route::middleware('checkPermission:slider_management')->group(function () {
        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders');

        Route::post('/page-hero/store', [SliderController::class, 'storePageHero'])->name('page-hero.store');
        Route::delete('/page-hero/delete/{id}', [SliderController::class, 'destroyPageHero'])->name('page-hero.delete');
        Route::post('/page-hero/update/{id}', [SliderController::class, 'updatePageHero'])->name('page-hero.update');

        Route::get('/sliders/video', [SliderController::class, 'index'])->name('home-video.index');
        Route::post('/sliders/video', [SliderController::class, 'storeHomevideo'])->name('home-video.store');

        Route::get('/sliders/watch-banner', [SliderController::class, 'index'])->name('watch-banner.index');
        Route::post('/sliders/watch-banner', [SliderController::class, 'updateWatchBanner'])->name('watch-banner.update');

        Route::get('/sliders/neck-wrist-banner', [SliderController::class, 'index'])->name('neck-wrist-banner.index');
        Route::post('/sliders/neck-wrist-banner', [SliderController::class, 'updateNeckWristBanner'])->name('neckwrist-banner.update');

        Route::get('/sliders/newArrival-banner', [SliderController::class, 'index'])->name('new-arrival-banner.index');
        Route::post('/sliders/newArrivalbanner', [SliderController::class, 'updateNewArrivalBanner'])->name('new-arrival-banner.update');
    });


    // ================== SALES & OFFERS ==================
    Route::middleware('checkPermission:sales_offers')->group(function () {
        Route::get('sales-offer/index', [OfferController::class, 'index'])->name('sales-offers.index');
        Route::get('sales-offer/create', [OfferController::class, 'create'])->name('offers.create');
        Route::get('/admin/products/{categoryId}', [OfferController::class, 'getProductsByCategory'])->name('products.byCategory');
        Route::post('sales-offer/store', [OfferController::class, 'store'])->name('offers.store');
        Route::get('admin/sales-offers/{id}/edit', [OfferController::class, 'edit'])->name('offers.edit');
        Route::put('admin/sales-offers/{id}', [OfferController::class, 'update'])->name('offers.update');
        Route::delete('admin/sales-offers/{id}', [OfferController::class, 'destroy'])->name('offers.destroy');

        Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('sales/store', [SaleController::class, 'store'])->name('sales.store');
        Route::get('sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit');
        Route::put('sales/{id}', [SaleController::class, 'update'])->name('sales.update');
        Route::delete('sales/{id}destroy', [SaleController::class, 'destroy'])->name('sales.destroy');
    });


    // ================== ABOUT ==================
    Route::middleware('checkPermission:about_us_management')->group(function () {
        Route::get('about', [AboutPageController::class, 'index'])->name('about');

        Route::post('about-block/store', [AboutPageController::class, 'storAboutBlock'])->name('about-block.store');
        Route::put('about-block/{id}', [AboutPageController::class, 'updateAboutBlock'])->name('about-block.update');
        Route::delete('about-block/{id}', [AboutPageController::class, 'destroyAboutBlock'])->name('about-block.delete');


        Route::post('about-stats/store', [AboutPageController::class, 'storeAboutStats'])->name('about-stats.store');
        Route::put('about-stats/{id}', [AboutPageController::class, 'updateAboutStats'])->name('about-stats.update');
        Route::delete('about-stats/{id}', [AboutPageController::class, 'destroyAboutStats'])->name('about-stats.delete');

        Route::post('vision/store', [AboutPageController::class, 'storeVision'])->name('vision.store');
        Route::put('vision/{id}/update', [AboutPageController::class, 'updateVision'])->name('vision.update');
        Route::delete('vision/{id}/destroy', [AboutPageController::class, 'destroyVision'])->name('vision.delete');
    });


    // ================== CONTACT ==================
    Route::middleware('checkPermission:contact_messages')->group(function () {
        Route::get('/contact', [ContactMessagesController::class, 'index'])->name('contact.index');
        Route::post('/admin/contact/reply', [ContactMessagesController::class, 'sndReply'])->name('contact.index.reply');
        Route::get('/contact/avg-repsonce-time', [ContactMessagesController::class, 'getAvgResponseTime'])->name('contact.index.avg-response-time');
        Route::delete('/contact/{message}/destroy', [ContactMessagesController::class, 'destroy'])->name('contact.index.destroy');
        Route::patch('/messages/{id}/mark-read', [ContactMessagesController::class, 'markAsRead'])->name('contact.messages.markRead');

        Route::get('contact-info', [ContactInfoController::class, 'index'])->name('contact.contact-info');
        Route::post('contact-info', [ContactInfoController::class, 'store'])->name('contact.contact-info.store');
        Route::put('contact-info/{id}', [ContactInfoController::class, 'update'])->name('contact.contact-info.update');
        Route::delete('contact-info/{id}', [ContactInfoController::class, 'destroy'])->name('contact.contact-info.destroy');
    });


    // ================== REFERRALS ==================
    Route::middleware('checkPermission:referral_management')->group(function () {
        Route::get('referral', [ReferralManagementController::class, 'index'])->name('referral.index');
        Route::post('/referrals/{referral}/updateStatus', [ReferralManagementController::class, 'updateStatus'])->name('referrals.updateStatus');
    });


    // ================== STORE SETTINGS ==================
    Route::middleware('checkPermission:store_settings')->group(function () {
        Route::get('/store-settings', [StoreSettingsController::class, 'index'])->name('store-settings');

        Route::post('promos/store', [PromoCodeController::class, 'store'])->name('promos.store');
        Route::post('promos/update/{id}', [PromoCodeController::class, 'update'])->name('promo.update');
        Route::delete('promos/delete/{id}', [PromoCodeController::class, 'destroy'])->name('promo.delete');

        Route::post('shipping-countries/store', [CountriesController::class, 'store'])->name('shipping-countries.store');
        Route::put('/shipping-countries/update/{id}', [CountriesController::class, 'update'])->name('admin.shipping-countries.update');
        Route::delete('shipping-countries/delete/{id}', [CountriesController::class, 'destroy'])->name('shipping-countries.delete');

        Route::post('payment-methods/store', [PaymentMethodsController::class, 'store'])->name('payment-methods.store');
        Route::put('payment-methods/update/{id}', [PaymentMethodsController::class, 'update'])->name('payment-methods.update');
        Route::delete('payment-methods/delete/{id}', [PaymentMethodsController::class, 'destroy'])->name('payment-methods.destroy');

        Route::post('shipping-methods/store', [ShippingMethodsController::class, 'store'])->name('shipping-methods.store');
        Route::get('get/shipping-countries', [ShippingMethodsController::class, 'index']);

        Route::put('shipping-methods/update/{id}', [ShippingMethodsController::class, 'update'])->name('shipping-methods.update');
        Route::delete('shipping-methods/delete/{id}', [ShippingMethodsController::class, 'destroy'])->name('shipping-methods.destroy');
    });


    // ================== ADMINS ==================
    Route::middleware('checkPermission:admin_management')->group(function () {
        Route::get('/admin-managment', [AdminController::class, 'index'])->name('admins-management');
    });

    Route::middleware('checkPermission:admin_management', 'superadmin')->group(function () {

        Route::get('/admin-role', [RoleController::class, 'index'])->name('admins-role-management');
        Route::post('/add-admin-role', [RoleController::class, 'store'])->name('admins-roles.store');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('admins-roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('admins-roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('admins-roles.destroy');
        Route::post('/add-admins', [AdminController::class, 'store'])->name('admins.store');
        Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
        Route::post('/admins/{admin}/force-logout', [AdminController::class, 'forceLogout'])
            ->name('force.logout');
    });



    // ================== ORDERS (COMING SOON) ==================
    Route::middleware('checkPermission:order_management')->group(function () {

        Route::get('/orders', [OrderController::class, 'index'])->name('orders');

        Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/admin/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
        Route::post('orders/{order}/update-estimated-delivery', [OrderController::class, 'updateEstimatedDelivery'])->name('orders.updateEstimatedDelivery');
    });


    Route::middleware('checkPermission:analytics')->group(function () {

        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });

    Route::middleware('checkPermission:users')->group(function () {
        Route::resource('user-management', UserManagementController::class);

        // Additional routes
        Route::post('users/bulk-action', [UserManagementController::class, 'bulkAction'])
            ->name('users.bulkAction');

        Route::get('users/export', [UserManagementController::class, 'export'])
            ->name('users.export');

        Route::get('users/activity-stats', [UserManagementController::class, 'getActivityStats'])
            ->name('users.activityStats');

        Route::post('users/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])
            ->name('user.toggleBlock');

        Route::post('users/{user}/send-reset-link', [UserManagementController::class, 'sendResetLink'])
            ->name('user.sendResetLink');

        Route::post('users/{user}/impersonate', [UserManagementController::class, 'impersonate'])
            ->name('user.impersonate');
    });



    Route::middleware('checkPermission:newsletters')->prefix('/newsletter')->name('newsletter.')->group(function () {
        // Main dashboard
        Route::get('/', [NewsletterController::class, 'index'])->name('index');

        // Campaign CRUD
        Route::post('/campaigns', [NewsletterController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/{campaign}/edit', [NewsletterController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campaigns/{campaign}', [NewsletterController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', [NewsletterController::class, 'destroy'])->name('campaigns.destroy');
        Route::post('/campaigns/{campaign}/send', [NewsletterController::class, 'send'])->name('campaigns.send');
        Route::post('/campaigns/{campaign}/schedule', [NewsletterController::class, 'schedule'])->name('campaigns.schedule');
        Route::post('/campaigns/{campaign}/cancel', [NewsletterController::class, 'cancel'])->name('campaigns.cancel');
        Route::post('/campaigns/{campaign}/duplicate', [NewsletterController::class, 'duplicate'])->name('campaigns.duplicate');
        Route::get('/campaigns/{campaign}/report', [NewsletterController::class, 'campaignReport'])->name('campaigns.report');
        Route::get('/subscribers', [NewsletterController::class, 'subscribers'])->name('subscribers');
        Route::post('/subscribers/{subscriber}/toggle-unsubscribe', [NewsletterController::class, 'toggleUnsubscribe'])->name('subscribers.toggle-unsubscribe');
        Route::post('/campaigns/{campaign}/export', [NewsletterController::class, 'exportReport'])->name('campaigns.export');
        Route::get('/refresh', [NewsletterController::class, 'refresh'])->name('refresh');

        // Public tracking routes

    });


    Route::middleware('checkPermission:content_management')->group(function () {
        Route::prefix('cms')->name('cms.')->group(function () {
            // Admin interface
            Route::get('/', [CMSController::class, 'adminIndex'])->name('index');

            // Data endpoints
            Route::get('/get-data', [CMSController::class, 'getData'])->name('getData');
            Route::get('/preview/{page}', [CMSController::class, 'preview'])->name('preview');

            // Pages
            Route::post('/pages', [CMSController::class, 'pageStore'])->name('pages.store');
            Route::get('/pages/{page}', [CMSController::class, 'getPage'])->name('pages.get');
            Route::put('/pages/{page}', [CMSController::class, 'pageUpdate'])->name('pages.update');
            Route::delete('/pages/{page}', [CMSController::class, 'pageDestroy'])->name('pages.destroy');
            Route::post('/pages/order', [CMSController::class, 'updatePageOrder'])->name('pages.order');

            // Sections
            Route::get('/pages/{page}/sections', [CMSController::class, 'getSectionsByPage'])->name('sections.list');
            Route::post('/sections', [CMSController::class, 'sectionStore'])->name('sections.store');
            Route::get('/sections/{section}', [CMSController::class, 'getSection'])->name('sections.get');
            Route::put('/sections/{section}', [CMSController::class, 'sectionUpdate'])->name('sections.update');
            Route::delete('/sections/{section}', [CMSController::class, 'sectionDestroy'])->name('sections.destroy');
            Route::post('/sections/order', [CMSController::class, 'updateSectionOrder'])->name('sections.order');

            // Items
            Route::get('/sections/{section}/items', [CMSController::class, 'getItemsBySection'])->name('items.list');
            Route::post('/items', [CMSController::class, 'itemStore'])->name('items.store');
            Route::get('/items/{item}', [CMSController::class, 'getItem'])->name('items.get');
            Route::put('/items/{item}', [CMSController::class, 'itemUpdate'])->name('items.update');
            Route::delete('/items/{item}', [CMSController::class, 'itemDestroy'])->name('items.destroy');
            Route::post('/items/order', [CMSController::class, 'updateItemOrder'])->name('items.order');

            // Media upload
            Route::post('/media/upload', [CMSController::class, 'uploadMedia'])->name('media.upload');
        });

        // Frontend route (outside admin middleware)
        Route::get('/pages/{slug}', [CMSController::class, 'show'])->name(name: 'pages.show');
    });
});





Route::middleware('checkPermission:admin_chatting')->group(function () {

    Route::get('/chatting', [AdminChatController::class, 'index'])->name('admin.chatting.index');

    Route::get('/admin-chat/messages', [AdminChatController::class, 'getMessages']);
    Route::post('/admin-chat/send', [AdminChatController::class, 'sendMessage']);
});


Route::middleware('checkPermission:admin_chatting', 'superadmin')->group(function () {


    Route::post('/admin-chat/block/{admin}', [AdminChatController::class, 'block'])->name('admin-chat.block');
    Route::post('/admin-chat/unblock/{admin}', [AdminChatController::class, 'unblock'])->name('admin-chat.unblock');

    Route::post('/admin-chat/clear', [AdminChatController::class, 'clearHistory'])->name('admin-chat.clear');
});

Route::prefix('admin')->group(function () {


    Route::get('/reviews', [ReviewsManagementController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{id}/approve', [ReviewsManagementController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/reviews/{id}/reject', [ReviewsManagementController::class, 'reject'])->name('admin.reviews.reject');
    Route::post('/admin/reviews/reply', [ReviewsManagementController::class, 'reply'])->name('admin.reviews.reply');
    Route::delete('admin/reviews/{id}', [ReviewsManagementController::class, 'destroy'])->name('admin.reviews.delete');
});


// Admin Chat Routes
Route::prefix('admin/chat')->name('admin.chat.')->group(function () {
    Route::get('/', [AdminChattingController::class, 'index'])->name('index');

    // Message Routes
    Route::post('/send', [AdminChattingController::class, 'sendMessage'])->name('send');
    Route::post('/send-media', [AdminChattingController::class, 'sendMedia'])->name('send.media');
    Route::get('/messages/{chatId}', [AdminChattingController::class, 'getMessages'])->name('messages');
    Route::post('/mark-read/{chatRoomId}', [AdminChattingController::class, 'markMessagesAsRead'])->name('mark.read');
    Route::post('/message/{messageId}/read', [AdminChattingController::class, 'markMessageAsRead'])->name('message.read');

    // Group Management Routes
    Route::get('/sidebar', [AdminChattingController::class, 'getSidebarData'])->name('sidebar');
    Route::get('/members', [AdminChattingController::class, 'getAdminMembers'])->name('members');
    Route::post('/create-group', [AdminChattingController::class, 'createGroup'])->name('create.group');
    Route::get('/group/{groupId}', [AdminChattingController::class, 'getGroup'])->name('get.group');
    Route::post('/group/{groupId}/update', [AdminChattingController::class, 'updateGroup'])->name('update.group');
    Route::get('/group/{groupId}/info', [AdminChattingController::class, 'getGroupInfo'])->name('group.info');
    Route::get('/group/{groupId}/members', [AdminChattingController::class, 'getGroupMembers'])->name('group.members');
    Route::post('/group/{groupId}/add-members', [AdminChattingController::class, 'addMembersToGroup'])->name('group.add.members');
    Route::get('/group/{groupId}/available-admins', [AdminChattingController::class, 'getAvailableAdmins'])->name('group.available.admins');
    Route::post('/group/{groupId}/kick/{memberId}', [AdminChattingController::class, 'kickMember'])->name('group.kick.member');
    Route::post('/group/{groupId}/leave', [AdminChattingController::class, 'leaveGroup'])->name('group.leave');
    Route::delete('/group/{groupId}/delete', [AdminChattingController::class, 'deleteGroup'])->name('group.delete');

    // Chat Management
    Route::post('/clear/{chatRoomId}', [AdminChattingController::class, 'clearChat'])->name('clear');

    // Pusher Authentication
    Route::post('/auth', function (\Illuminate\Http\Request $request) {
        $pusher = new Pusher\Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true
            ]
        );

        return $pusher->socket_auth($request->channel_name, $request->socket_id);
    })->name('auth');
});


Route::get('admin/products/test', function () {
    return 'works';
});

Route::get('user-management', [UserManagementController::class, 'index'])->name('admin.user-management');



Route::get('/newsletter/track/open/{log}', [NewsletterController::class, 'trackOpen'])->name('newsletter.track.open');
Route::get('/newsletter/track/click/{log}', [NewsletterController::class, 'trackClick'])->name('newsletter.track.click');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/debug-pixel/{log}', function($logId){
    \Log::info("Pixel hit for log {$logId}");
    return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'))
        ->header('Content-Type', 'image/gif');
});
