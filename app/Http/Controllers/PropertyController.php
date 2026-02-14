<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('user', 'reviews')
            ->where('status', 'available');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->city) {
            $query->where('city', $request->city);
        }

        if ($request->county) {
            $query->where('county', $request->county);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'newest') {
            $query->latest();
        } else {
            $query->orderBy('is_featured', 'desc')->latest();
        }

        $properties = $query->paginate(12)->withQueryString();

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
            'filters' => $request->only(['search', 'type', 'city', 'county', 'min_price', 'max_price', 'bedrooms', 'sort'])
        ]);
    }

    public function show(Property $property)
    {
        $property->incrementViews();

        $property->load(['user', 'reviews.user']);

        $similarProperties = Property::where('type', $property->type)
            ->where('id', '!=', $property->id)
            ->where('status', 'available')
            ->limit(4)
            ->get();

        return Inertia::render('Properties/Show', [
            'property' => $property,
            'similarProperties' => $similarProperties,
            'averageRating' => $property->averageRating()
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if (!$user->canAddProperty()) {
            return redirect()->route('landlord.subscription')
                ->with('error', 'You have reached your property limit. Please upgrade your subscription.');
        }

        return Inertia::render('Landlord/Properties/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->canAddProperty()) {
            return back()->with('error', 'You have reached your property limit.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:apartment,house,studio,bedsitter,commercial',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'size' => 'nullable|numeric',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string',
            'county' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'amenities' => 'nullable|array',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = $imagePaths;
        $validated['user_id'] = $user->id;

        $property = Property::create($validated);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property created successfully!');
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        return Inertia::render('Landlord/Properties/Edit', [
            'property' => $property
        ]);
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:apartment,house,studio,bedsitter,commercial',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'size' => 'nullable|numeric',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string',
            'county' => 'required|string',
            'status' => 'required|in:available,rented,maintenance',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'existing_images' => 'nullable|array'
        ]);

        $imagePaths = $request->existing_images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = $imagePaths;
        $property->update($validated);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        $property->delete();

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}
