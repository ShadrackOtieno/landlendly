<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $landlords = User::where('role', 'landlord')->get();

        $properties = [
            [
                'title' => 'Modern 2 Bedroom Apartment in Kilimani',
                'description' => 'Beautiful modern apartment with stunning city views. Features include spacious bedrooms, modern kitchen, and 24/7 security.',
                'type' => 'apartment',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'size' => 85.5,
                'price' => 45000,
                'address' => 'Kilimani Road',
                'city' => 'Nairobi',
                'county' => 'Nairobi',
                'amenities' => ['Parking', 'Security', 'Water', 'Backup Generator'],
                'status' => 'available',
            ],
            [
                'title' => 'Spacious 3 Bedroom House in Karen',
                'description' => 'Luxurious family home in the heart of Karen. Features large garden, swimming pool, and modern amenities.',
                'type' => 'house',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'size' => 200.0,
                'price' => 120000,
                'address' => 'Karen Road',
                'city' => 'Nairobi',
                'county' => 'Nairobi',
                'amenities' => ['Swimming Pool', 'Garden', 'Parking', 'Security', 'DSQ'],
                'status' => 'available',
                'is_featured' => true,
            ],
            [
                'title' => 'Cozy Studio Apartment in Westlands',
                'description' => 'Perfect for young professionals. Located near shopping malls and entertainment centers.',
                'type' => 'studio',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'size' => 35.0,
                'price' => 25000,
                'address' => 'Westlands',
                'city' => 'Nairobi',
                'county' => 'Nairobi',
                'amenities' => ['WiFi', 'Security', 'Water'],
                'status' => 'available',
            ],
            [
                'title' => 'Affordable Bedsitter in Kasarani',
                'description' => 'Clean and affordable bedsitter close to public transport.',
                'type' => 'bedsitter',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'size' => 20.0,
                'price' => 12000,
                'address' => 'Kasarani',
                'city' => 'Nairobi',
                'county' => 'Nairobi',
                'amenities' => ['Water', 'Security'],
                'status' => 'available',
            ],
            [
                'title' => 'Commercial Space in Mombasa CBD',
                'description' => 'Prime commercial space perfect for retail or office use.',
                'type' => 'commercial',
                'bedrooms' => 0,
                'bathrooms' => 2,
                'size' => 150.0,
                'price' => 80000,
                'address' => 'Moi Avenue',
                'city' => 'Mombasa',
                'county' => 'Mombasa',
                'amenities' => ['Parking', 'Security', 'Elevator'],
                'status' => 'available',
                'is_featured' => true,
            ],
            [
                'title' => 'Luxury 4 Bedroom Apartment in Nyali',
                'description' => 'Beachfront luxury apartment with ocean views.',
                'type' => 'apartment',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'size' => 180.0,
                'price' => 95000,
                'address' => 'Nyali Beach',
                'city' => 'Mombasa',
                'county' => 'Mombasa',
                'amenities' => ['Swimming Pool', 'Gym', 'Security', 'Beach Access', 'Parking'],
                'status' => 'available',
            ],
        ];

        foreach ($properties as $index => $propertyData) {
            $landlord = $landlords[$index % $landlords->count()];

            Property::create([
                'user_id' => $landlord->id,
                'title' => $propertyData['title'],
                'slug' => Str::slug($propertyData['title']) . '-' . Str::random(6),
                'description' => $propertyData['description'],
                'type' => $propertyData['type'],
                'bedrooms' => $propertyData['bedrooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'size' => $propertyData['size'],
                'price' => $propertyData['price'],
                'address' => $propertyData['address'],
                'city' => $propertyData['city'],
                'county' => $propertyData['county'],
                'amenities' => $propertyData['amenities'],
                'images' => [],
                'status' => $propertyData['status'],
                'is_featured' => $propertyData['is_featured'] ?? false,
                'views' => rand(10, 500),
            ]);
        }
    }
}
