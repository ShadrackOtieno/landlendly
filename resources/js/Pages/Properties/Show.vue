<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div
                        class="bg-gray-200 h-96 rounded-lg mb-6 flex items-center justify-center"
                    >
                        <svg
                            class="w-32 h-32 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>

                    <h1 class="text-3xl font-bold mb-4">
                        {{ property.title }}
                    </h1>

                    <div class="flex items-center text-gray-600 mb-6">
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                        </svg>
                        {{ property.address }}, {{ property.city }},
                        {{ property.county }}
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">🛏️</div>
                            <p class="text-gray-600 text-sm">Bedrooms</p>
                            <p class="font-semibold">{{ property.bedrooms }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">🚿</div>
                            <p class="text-gray-600 text-sm">Bathrooms</p>
                            <p class="font-semibold">
                                {{ property.bathrooms }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">📐</div>
                            <p class="text-gray-600 text-sm">Size</p>
                            <p class="font-semibold">{{ property.size }} m²</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-4">Description</h2>
                        <p class="text-gray-700 leading-relaxed">
                            {{ property.description }}
                        </p>
                    </div>

                    <div
                        v-if="property.amenities && property.amenities.length"
                        class="mb-8"
                    >
                        <h2 class="text-2xl font-bold mb-4">Amenities</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div
                                v-for="amenity in property.amenities"
                                :key="amenity"
                                class="flex items-center"
                            >
                                <svg
                                    class="w-5 h-5 text-green-500 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                                {{ amenity }}
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="property.reviews && property.reviews.length"
                        class="mb-8"
                    >
                        <h2 class="text-2xl font-bold mb-4">Reviews</h2>
                        <div class="space-y-4">
                            <div
                                v-for="review in property.reviews"
                                :key="review.id"
                                class="bg-gray-50 p-4 rounded-lg"
                            >
                                <div class="flex items-center mb-2">
                                    <div class="font-semibold mr-2">
                                        {{ review.user.name }}
                                    </div>
                                    <div class="text-yellow-500">
                                        ★ {{ review.rating }}/5
                                    </div>
                                </div>
                                <p class="text-gray-700">
                                    {{ review.comment }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                        <div class="text-3xl font-bold text-blue-600 mb-6">
                            KSh {{ property.price.toLocaleString()
                            }}<span class="text-lg text-gray-600">/month</span>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Property Type</span>
                                <span class="font-semibold capitalize">{{
                                    property.type
                                }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span
                                    class="font-semibold capitalize text-green-600"
                                    >{{ property.status }}</span
                                >
                            </div>
                        </div>

                        <div class="border-t pt-6 mb-6">
                            <h3 class="font-semibold mb-3">Landlord</h3>
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-gray-200 rounded-full mr-3 flex items-center justify-center"
                                >
                                    <span class="text-lg font-semibold">{{
                                        property.user.name[0]
                                    }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        {{ property.user.name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ property.user.email }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <Link
                            v-if="
                                $page.props.auth.user &&
                                $page.props.auth.user.id !== property.user_id
                            "
                            :href="route('messages.show', property.user_id)"
                            class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-semibold"
                        >
                            Contact Landlord
                        </Link>
                        <Link
                            v-else-if="!$page.props.auth.user"
                            :href="route('login')"
                            class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-semibold"
                        >
                            Login to Contact
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="similarProperties.length" class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Similar Properties</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <Link
                        v-for="similar in similarProperties"
                        :key="similar.id"
                        :href="route('properties.show', similar.slug)"
                        class="bg-white rounded-lg shadow hover:shadow-lg transition"
                    >
                        <div
                            class="h-48 bg-gray-200 rounded-t-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-16 h-16 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                ></path>
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold mb-2">
                                {{ similar.title }}
                            </h3>
                            <p class="text-blue-600 font-bold">
                                KSh {{ similar.price.toLocaleString() }}/mo
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    property: Object,
    similarProperties: Array,
    averageRating: Number,
});
</script>
