<?php
// config/admin_routes.php

return [

    // Friendly label => route name (used for select in role form)
    'available_routes' => [
        'Admin Dashboard'        => 'admin.dashboard',
        'Products Management'    => 'admin.products.index',
        'Categories Management'  => 'admin.categories.index',
        'Sliders'                => 'admin.sliders',
        'About Page'             => 'admin.about',
        'Orders'                 => 'admin.orders',
        'Contact Messages'       => 'admin.contact.index',
        'Sales & Offers'         => 'admin.sales-offers.index',
        'Referral Management'    => 'admin.referral.index',
        'Store Settings'         => 'admin.store-settings',
        'Admins Management'      => 'admin.admins-management',
        'Newsletter System'      => 'admin.news-letter',
        'Content Management'     => 'admin.content-management',
    ],

    // Optional mapping from permission slug => route name (used as fallback)
    'permission_map' => [
        'main_dashboard'    => 'admin.dashboard',
        'product_management'=> 'admin.products.index',
        'category_management'=> 'admin.categories.index',
        'slider_management' => 'admin.sliders',
        'about_us_management'=> 'admin.about',
        'order_management'  => 'admin.orders',
        'contact_messages'  => 'admin.contact.index',
        'sales_offers'      => 'admin.sales-offers.index',
        'referral_management'=> 'admin.referral.index',
        'store_settings'     => 'admin.store-settings',
        'admin_management'   => 'admin.admins-management',
    ],
];
