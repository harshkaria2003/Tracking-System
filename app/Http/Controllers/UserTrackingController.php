<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTracking;
use Illuminate\Support\Facades\Auth;

class UserTrackingController extends Controller
{
    public function store(Request $request)
    {
        UserTracking::create([
            'user_id'      => Auth::id(),
            'event_type'   => $request->event_type,
            'event_data'   => $request->event_data,
            'url'          => $request->url,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function updateCount(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Cast values to integers
        $clicks = (int) $request->input('click', 0);
        $scrolls = (int) $request->input('scroll', 0);
        $keypresses = (int) $request->input('keypress', 0);

        // Retrieve or create tracking row
        $tracking = UserTracking::firstOrCreate(
            [
                'user_id'    => $user->id,
                'event_type' => 'aggregate',
                'url'        => $request->url,
            ],
            [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

       
        if ($clicks > 0) {
            $tracking->increment('click_count', $clicks);
        }
        if ($scrolls  > 0) {
            $tracking->increment('scroll_count', $scrolls);
        }
        if ($keypresses > 0) {
            $tracking->increment('keypress_count', $keypresses);
        }

        return response()->json(['status' => 'updated']);
    }
}
