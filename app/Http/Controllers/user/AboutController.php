<?php

namespace App\Http\Controllers\User;

use App\Models\Founder;
use App\Models\OurStory;
use App\Models\OurValue;
use App\Models\OurJourney;
use App\Models\TeamMember;
use App\Models\AboutUsBlock;
use App\Models\AboutUsStats;
use Illuminate\Http\Request;
use App\Models\AboutUsVision;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index(){


        $storyBlocks = AboutUsBlock::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();

        $stats = AboutUsStats::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();

        $vision = AboutUsVision::where('is_active', true)->first();

        return view('user.about', compact('storyBlocks', 'stats', 'vision'));

    }
}
