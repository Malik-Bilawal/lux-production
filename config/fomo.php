<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FOMO Timer Configurations
    |--------------------------------------------------------------------------
    | Har event ke liye unique settings define hain jo timer ka look & feel set karte hain.
    | Tum apne events ke hisaab se inko update kar sakte ho.
    */

    'events' => [

        'azadi_sale' => [
            'title' => '⏳ Azadi Sale Will End In',
            'gradient' => 'linear-gradient(90deg, #006400, #228B22, #006400)', // Green shades
            'border_color' => 'border-green-400/50',
            'text_color' => 'text-green-300',
        ],

        'new_year_sale' => [
            'title' => '⏳ New Year Sale Will End In',
            'gradient' => 'linear-gradient(90deg, #00008B, #1E90FF, #00008B)', // Blue shades
            'border_color' => 'border-blue-400/50',
            'text_color' => 'text-blue-300',
        ],

        'black_friday' => [
            'title' => '🔥 Black Friday Mega Sale Ends In',
            'gradient' => 'linear-gradient(90deg, #000000, #434343, #000000)', // Black shades
            'border_color' => 'border-gray-400/50',
            'text_color' => 'text-gray-300',
        ],

        'valentines_day' => [
            'title' => '💖 Valentine’s Day Offer Ends In',
            'gradient' => 'linear-gradient(90deg, #ff4d6d, #ff1e56, #ff4d6d)', // Romantic pink-red
            'border_color' => 'border-pink-400/50',
            'text_color' => 'text-pink-200',
        ],

        'eid_sale' => [
            'title' => '🌙 Eid Special Offer Ends In',
            'gradient' => 'linear-gradient(90deg, #006d77, #83c5be, #006d77)', // Teal shades
            'border_color' => 'border-teal-400/50',
            'text_color' => 'text-teal-200',
        ],

        'summer_sale' => [
            'title' => '☀️ Summer Sale Will End In',
            'gradient' => 'linear-gradient(90deg, #ff8c00, #ffb347, #ff8c00)', // Warm orange
            'border_color' => 'border-orange-400/50',
            'text_color' => 'text-orange-200',
        ],

        'default' => [
            'title' => '⏳ Limited Time Offer',
            'gradient' => 'linear-gradient(90deg, #8B0000, #B22222, #8B0000)', // Red shades
            'border_color' => 'border-yellow-400/50',
            'text_color' => 'text-yellow-300',
        ],

    ],

];
