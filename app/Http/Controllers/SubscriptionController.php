<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $currentSubscription = Auth::user()->activeSubscription()->with('plan')->first();

        return Inertia::render('Subscription/Index', [
            'plans' => $plans,
            'currentSubscription' => $currentSubscription,
            'onTrial' => Auth::user()->onTrial(),
            'trialEndsAt' => Auth::user()->trial_ends_at
        ]);
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'phone' => 'required|string|regex:/^254[0-9]{9}$/'
        ]);

        return Inertia::render('Subscription/Checkout', [
            'plan' => $plan,
            'phone' => $validated['phone']
        ]);
    }

    public function cancel()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        if ($subscription) {
            $subscription->update(['auto_renew' => false]);
            return back()->with('success', 'Subscription will not auto-renew.');
        }

        return back()->with('error', 'No active subscription found.');
    }
}
