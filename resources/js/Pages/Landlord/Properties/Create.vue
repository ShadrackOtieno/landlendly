<template>
    <DashboardLayout>
        <template #sidebar>
            <Link
                :href="route('landlord.dashboard')"
                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Dashboard
            </Link>
            <Link
                :href="route('landlord.properties.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-700 bg-gray-100 rounded-md"
            >
                My Properties
            </Link>
        </template>

        <div class="max-w-4xl">
            <h1 class="text-3xl font-bold mb-8">Add New Property</h1>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-lg shadow p-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2"
                            >Property Title *</label
                        >
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full border rounded-md px-3 py-2"
                            placeholder="e.g., Modern 2 Bedroom Apartment"
                        />
                        <div
                            v-if="form.errors.title"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2"
                            >Description *</label
                        >
                        <textarea
                            v-model="form.description"
                            rows="4"
                            required
                            class="w-full border rounded-md px-3 py-2"
                            placeholder="Describe your property..."
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >Property Type *</label
                        >
                        <select
                            v-model="form.type"
                            required
                            class="w-full border rounded-md px-3 py-2"
                        >
                            <option value="">Select Type</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="studio">Studio</option>
                            <option value="bedsitter">Bedsitter</option>
                            <option value="commercial">Commercial</option>
                        </select>
                        <div
                            v-if="form.errors.type"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.type }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >Price (KSh/month) *</label
                        >
                        <input
                            v-model="form.price"
                            type="number"
                            required
                            class="w-full border rounded-md px-3 py-2"
                            placeholder="e.g., 45000"
                        />
                        <div
                            v-if="form.errors.price"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.price }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >Bedrooms *</label
                        >
                        <input
                            v-model="form.bedrooms"
                            type="number"
                            min="0"
                            required
                            class="w-full border rounded-md px-3 py-2"
                        />
                        <div
                            v-if="form.errors.bedrooms"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.bedrooms }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >Bathrooms *</label
                        >
                        <input
                            v-model="form.bathrooms"
                            type="number"
                            min="0"
                            required
                            class="w-full border rounded-md px-3 py-2"
                        />
                        <div
                            v-if="form.errors.bathrooms"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.bathrooms }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >Size (m²)</label
                        >
                        <input
                            v-model="form.size"
                            type="number"
                            step="0.01"
                            class="w-full border rounded-md px-3 py-2"
                        />
                        <div
                            v-if="form.errors.size"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.size }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >City *</label
                        >
                        <input
                            v-model="form.city"
                            type="text"
                            required
                            class="w-full border rounded-md px-3 py-2"
                            placeholder="e.g., Nairobi"
                        />
                        <div
                            v-if="form.errors.city"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.city }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2"
                            >County *</label
                        >
                        <input
                            v-model="form.county"
                            type="text"
                            required
                            class="w-full border rounded-md px-3 py-2"
                            placeholder="e.g., Nairobi"
                        />
                        <div
                            v-if="form.errors.county"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.county }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2"
                            >Address *</label
                        >
                        <input
                            v-model="form.address"
                            type="text"
                            required
                            class="w-full border rounded-md px-3 py-2"
                            placeholder="e.g., Kilimani Road"
                        />
                        <div
                            v-if="form.errors.address"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2"
                            >Amenities</label
                        >
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label
                                v-for="amenity in availableAmenities"
                                :key="amenity"
                                class="flex items-center"
                            >
                                <input
                                    type="checkbox"
                                    :value="amenity"
                                    v-model="form.amenities"
                                    class="mr-2"
                                />
                                {{ amenity }}
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2"
                            >Property Images * (Max 5)</label
                        >
                        <input
                            @change="handleFileChange"
                            type="file"
                            multiple
                            accept="image/*"
                            class="w-full border rounded-md px-3 py-2"
                        />
                        <div
                            v-if="form.errors.images"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.images }}
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Upload up to 5 images (JPEG, PNG)
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <Link
                        :href="route('landlord.properties.index')"
                        class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{
                            form.processing ? "Creating..." : "Create Property"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    title: "",
    description: "",
    type: "",
    bedrooms: 0,
    bathrooms: 0,
    size: "",
    price: "",
    address: "",
    city: "",
    county: "",
    amenities: [],
    images: [],
});

const availableAmenities = [
    "Parking",
    "Security",
    "Water",
    "Backup Generator",
    "WiFi",
    "Swimming Pool",
    "Gym",
    "Garden",
    "Elevator",
    "DSQ",
    "Beach Access",
];

const handleFileChange = (e) => {
    form.images = Array.from(e.target.files).slice(0, 5);
};

const submit = () => {
    form.post(route("landlord.properties.store"));
};
</script>
