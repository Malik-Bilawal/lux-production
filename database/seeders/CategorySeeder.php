<?php

namespace Database\Seeders;

// database/seeders/CategorySeeder.php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Watches', 'Neck & Wrist Collection']; 

        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}
