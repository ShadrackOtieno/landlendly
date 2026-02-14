<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\PropertyInquiry;
use Illuminate\Support\Facades\Auth;

class LandlordController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_properties' => $user->properties()->count(),
            'available_properties' => $user->properties()->where('status', 'available')->count(),
            'rented_properties' => $user->properties()->where('status', 'rented')->count(),
            'total_views' => $user->properties()->sum('views'),
            'unread_messages' => $user->receivedMessages()->where('is_read', false)->count(),
            'pending_inquiries' => PropertyInquiry::whereIn('property_id', $user->properties()->pluck('id'))
                ->where('status', 'pending')
                ->count()
        ];

        $recentProperties = $user->properties()
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = $user->receivedMessages()
            ->with('sender', 'property')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Landlord/Dashboard', [
            'stats' => $stats,
            'recentProperties' => $recentProperties,
            'recentMessages' => $recentMessages,
            'subscription' => $user->activeSubscription()->with('plan')->first(),
            'onTrial' => $user->onTrial(),
            'trialEndsAt' => $user->trial_ends_at
        ]);
    }

    public function properties()
    {
        $user = Auth::user();

        $properties = $user->properties()
            ->withCount('reviews', 'inquiries')
            ->latest()
            ->paginate(10);

        return Inertia::render('Landlord/Properties/Index', [
            'properties' => $properties
        ]);
    }

    public function inquiries()
    {
        $user = Auth::user();

        $inquiries = PropertyInquiry::whereIn('property_id', $user->properties()->pluck('id'))
            ->with('user', 'property')
            ->latest()
            ->paginate(15);

        return Inertia::render('Landlord/Inquiries', [
            'inquiries' => $inquiries
        ]);
    }
}
