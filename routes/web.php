<?php
// routes/web.php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SubscriptionController;

// Public routes
// Route::get('/', function () {
//     return Inertia::render('Home', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//     ]);
// })->name('home');

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isLandlord()) {
            return redirect()->route('landlord.dashboard');
        } else {
            return redirect()->route('tenant.dashboard');
        }
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/{user}', [MessageController::class, 'show'])->name('show');
        Route::post('/', [MessageController::class, 'store'])->name('store');
    });

    // Subscriptions
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::post('/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/mpesa/initiate', [PaymentController::class, 'initiateMpesa'])->name('mpesa.initiate');
        Route::post('/mpesa/callback', [PaymentController::class, 'callback'])->name('mpesa.callback');
    });
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    // Properties management
    Route::get('/properties', [AdminController::class, 'properties'])->name('properties.index');
    Route::put('/properties/{property}', [AdminController::class, 'updateProperty'])->name('properties.update');
    Route::delete('/properties/{property}', [AdminController::class, 'deleteProperty'])->name('properties.destroy');

    // Payments
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');

    // Subscriptions
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions.index');


    // Subscription Plans
    Route::get('/subscription-plans', [AdminController::class, 'subscriptionPlans'])->name('subscription-plans.index');
    Route::post('/subscription-plans', [AdminController::class, 'storeSubscriptionPlan'])->name('subscription-plans.store');
    Route::put('/subscription-plans/{subscriptionPlan}', [AdminController::class, 'updateSubscriptionPlan'])->name('subscription-plans.update');
    Route::delete('/subscription-plans/{subscriptionPlan}', [AdminController::class, 'deleteSubscriptionPlan'])->name('subscription-plans.destroy');
    Route::put('/subscription-plans/{subscriptionPlan}/toggle-active', [AdminController::class, 'toggleSubscriptionPlan'])->name('subscription-plans.toggle-active');
});

// Landlord routes
Route::middleware(['auth', 'role:landlord'])->prefix('landlord')->name('landlord.')->group(function () {
    Route::get('/dashboard', [LandlordController::class, 'dashboard'])->name('dashboard');
    Route::get('/properties', [LandlordController::class, 'properties'])->name('properties.index');
    Route::get('/inquiries', [LandlordController::class, 'inquiries'])->name('inquiries');

    // Property CRUD
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::patch('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
});

Route::post('/api/mpesa/callback', [PaymentController::class, 'callback'])
    ->name('mpesa.callback');

// Tenant routes
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Tenant/Dashboard');
    })->name('dashboard');
});

require __DIR__ . '/auth.php';
