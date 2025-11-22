<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Jenssegers\Agent\Agent;

class LogVisit
{
    public function handle(Request $request, Closure $next)
    {
        if (!str_starts_with($request->path(), 'admin')) {
            $agent = new Agent();
            $location = Location::get($request->ip());

            \App\Models\Visit::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'country' => $location ? $location->countryName : null,
                'country_code' => $location ? $location->countryCode : null,
                'device_type' => $agent->isDesktop() ? 'desktop' : ($agent->isMobile() ? 'mobile' : 'tablet'),
                'url' => $request->fullUrl(),
            ]);
        }

        return $next($request);
    }
}