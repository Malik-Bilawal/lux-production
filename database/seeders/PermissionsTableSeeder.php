<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'Main Dashboard', 'slug' => 'main_dashboard'],
            ['name' => 'Admin Chatting', 'slug' => 'admin_chatting'],
            ['name' => 'Product Management', 'slug' => 'product_management'],
            ['name' => 'Category Management', 'slug' => 'category_management'],
            ['name' => 'About Us Page Management', 'slug' => 'about_us_management'],
            ['name' => 'Slider Management', 'slug' => 'slider_management'],
            ['name' => 'Referral Management', 'slug' => 'referral_management'],
            ['name' => 'Order Management', 'slug' => 'order_management'],
            ['name' => 'Contact Messages', 'slug' => 'contact_messages'],
            ['name' => 'Sales & Offers', 'slug' => 'sales_offers'],
            ['name' => 'Store Settings', 'slug' => 'store_settings'],
            ['name' => 'Admin Management', 'slug' => 'admin_management'],
            ['name' => 'Analytics', 'slug' => 'analytics'],
            ['name' => 'user', 'slug' => 'users'],
            ['name' => 'newsletter', 'slug' => 'newsleters'],
            ['name' => 'Content Management', 'slug' => 'content_management'],
            ['name' => 'Admin Chatting', 'slug' => 'admin_chatting'],




        ];
        

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['slug' => $perm['slug']], $perm);
        }
    }
}
