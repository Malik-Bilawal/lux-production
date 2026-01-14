<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\ShippingMethod::query()->delete();

        \App\Models\ShippingMethod::insert([
            ['name'=>'Standard Delivery','description'=>'5–7 business days','price'=>0,'status'=>'active','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Express Shipping','description'=>'2–3 business days','price'=>250,'status'=>'active','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'One-Day Delivery','description'=>'1 business day','price'=>400,'status'=>'active','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}

