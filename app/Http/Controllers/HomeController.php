<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        // Featured properties (is_featured = true, limit 8)
        $featuredProperties = Property::where('is_featured', true)
            ->where('status', 'available')
            ->with('user')
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'slug' => $property->slug,
                    'description' => $property->description,
                    'type' => $property->type,
                    'price' => $property->price,
                    'price_period' => 'monthly', // Add this to your database if you want
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'size' => $property->size,
                    'city' => $property->city,
                    'county' => $property->county,
                    'images' => $property->images,
                    'is_featured' => $property->is_featured,
                    'verified' => true, // Add verification field if needed
                    'status' => $property->status,
                ];
            });

        // Recent properties (latest 8)
        $recentProperties = Property::where('status', 'available')
            ->with('user')
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'slug' => $property->slug,
                    'type' => $property->type,
                    'price' => $property->price,
                    'price_period' => 'monthly',
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'city' => $property->city,
                    'county' => $property->county,
                    'images' => $property->images,
                ];
            });

        // Property types with counts
        $propertyTypes = Property::where('status', 'available')
            ->select('type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('type')
            ->get();

        // Top locations with property counts
        $locations = Property::where('status', 'available')
            ->select('city', 'county')
            ->selectRaw('COUNT(*) as property_count')
            ->groupBy('city', 'county')
            ->orderByDesc('property_count')
            ->limit(8)
            ->get()
            ->map(function ($location) {
                return [
                    'id' => md5($location->city . $location->county),
                    'name' => $location->city,
                    'county' => $location->county,
                    'property_count' => $location->property_count,
                ];
            });

        // Platform statistics
        $stats = [
            'total_properties' => Property::where('status', 'available')->count(),
            'total_locations' => Property::distinct('city')->count('city'),
            'satisfaction_rate' => 98, // You can calculate this from reviews
        ];

        return Inertia::render('Home', [
            'featuredProperties' => $featuredProperties,
            'recentProperties' => $recentProperties,
            'propertyTypes' => $propertyTypes,
            'locations' => $locations,
            'stats' => $stats,
        ]);
    }
}
