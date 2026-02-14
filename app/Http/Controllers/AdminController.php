<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Property;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_landlords' => User::where('role', 'landlord')->count(),
            'total_tenants' => User::where('role', 'tenant')->count(),
            'total_properties' => Property::count(),
            'available_properties' => Property::where('status', 'available')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentProperties = Property::with('user')->latest()->take(5)->get();
        $recentPayments = Payment::with('user')->latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentProperties' => $recentProperties,
            'recentPayments' => $recentPayments
        ]);
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role'])
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,landlord,tenant',
            'is_active' => 'boolean'
        ]);

        $user->update($validated);

        return back()->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    public function properties(Request $request)
    {
        $query = Property::with('user');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $properties = $query->latest()->paginate(15);

        return Inertia::render('Admin/Properties/Index', [
            'properties' => $properties,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function updateProperty(Request $request, Property $property)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,rented,maintenance',
            'is_featured' => 'boolean'
        ]);

        $property->update($validated);

        return back()->with('success', 'Property updated successfully!');
    }

    public function deleteProperty(Property $property)
    {
        $property->delete();

        return back()->with('success', 'Property deleted successfully!');
    }

    public function payments(Request $request)
    {
        $query = Payment::with('user', 'subscription');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'filters' => $request->only(['status'])
        ]);
    }

    public function subscriptions()
    {

        $subscriptions = Subscription::with('user', 'plan')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Subscriptions/Index', [
            'subscriptions' => $subscriptions
        ]);
    }

    public function subscriptionPlans()
    {
        $plans = SubscriptionPlan::withCount('subscriptions')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/SubscriptionPlans/Index', [
            'plans' => $plans,
        ]);
    }

    public function storeSubscriptionPlan(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|max:255|unique:subscription_plans,slug',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'property_limit'     => 'required|integer|min:1',
            'billing_cycle_days' => 'required|integer|min:1',
            'features'           => 'nullable|array',
            'features.*'         => 'string|max:255',
            'is_active'          => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        SubscriptionPlan::create($validated);

        return back()->with('success', 'Subscription plan created successfully!');
    }

    public function updateSubscriptionPlan(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|max:255|unique:subscription_plans,slug,' . $subscriptionPlan->id,
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'property_limit'     => 'required|integer|min:1',
            'billing_cycle_days' => 'required|integer|min:1',
            'features'           => 'nullable|array',
            'features.*'         => 'string|max:255',
            'is_active'          => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $subscriptionPlan->update($validated);

        return back()->with('success', 'Subscription plan updated successfully!');
    }

    public function deleteSubscriptionPlan(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete a plan with active subscriptions.');
        }

        $subscriptionPlan->delete();

        return back()->with('success', 'Subscription plan deleted successfully!');
    }

    public function toggleSubscriptionPlan(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update(['is_active' => ! $subscriptionPlan->is_active]);

        $status = $subscriptionPlan->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Plan {$status} successfully!");
    }
}
