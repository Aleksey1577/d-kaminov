<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Stevebauman\Location\Facades\Location;
use Jenssegers\Agent\Agent;

class AnalyticsController extends Controller
{
    public function index()
    {
        $visits = Visit::with('user')
            ->latest()
            ->paginate(100);

        return view('admin.analytics.index', compact('visits'));
    }
}
