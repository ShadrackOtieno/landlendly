<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for getting started',
                'price' => 999.00,
                'property_limit' => 5,
                'billing_cycle_days' => 30,
                'features' => [
                    'Up to 5 property listings',
                    'Basic analytics',
                    'Email support',
                    'Property photos (up to 5 per listing)',
                    'Basic messaging'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Best for growing landlords',
                'price' => 2499.00,
                'property_limit' => 20,
                'billing_cycle_days' => 30,
                'features' => [
                    'Up to 20 property listings',
                    'Advanced analytics',
                    'Priority support',
                    'Unlimited property photos',
                    'Advanced messaging',
                    'Featured listings (3 per month)',
                    'Virtual tours'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For professional property managers',
                'price' => 4999.00,
                'property_limit' => 100,
                'billing_cycle_days' => 30,
                'features' => [
                    'Up to 100 property listings',
                    'Custom analytics & reports',
                    '24/7 dedicated support',
                    'Unlimited property photos',
                    'Advanced messaging with automation',
                    'Unlimited featured listings',
                    'Virtual tours & 3D walkthroughs',
                    'Custom branding',
                    'API access'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
