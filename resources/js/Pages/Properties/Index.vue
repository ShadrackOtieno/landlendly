<template>
    <AppLayout>
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-bold">Properties for Rent</h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <aside class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-semibold mb-4">Filters</h3>
                        <form @submit.prevent="applyFilters">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2"
                                    >Search</label
                                >
                                <input
                                    v-model="form.search"
                                    type="text"
                                    class="w-full border rounded-md px-3 py-2"
                                    placeholder="Search..."
                                />
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2"
                                    >Property Type</label
                                >
                                <select
                                    v-model="form.type"
                                    class="w-full border rounded-md px-3 py-2"
                                >
                                    <option value="">All Types</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="house">House</option>
                                    <option value="studio">Studio</option>
                                    <option value="bedsitter">Bedsitter</option>
                                    <option value="commercial">
                                        Commercial
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2"
                                    >City</label
                                >
                                <input
                                    v-model="form.city"
                                    type="text"
                                    class="w-full border rounded-md px-3 py-2"
                                    placeholder="City"
                                />
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2"
                                    >Price Range</label
                                >
                                <div class="grid grid-cols-2 gap-2">
                                    <input
                                        v-model="form.min_price"
                                        type="number"
                                        class="border rounded-md px-3 py-2"
                                        placeholder="Min"
                                    />
                                    <input
                                        v-model="form.max_price"
                                        type="number"
                                        class="border rounded-md px-3 py-2"
                                        placeholder="Max"
                                    />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2"
                                    >Bedrooms</label
                                >
                                <select
                                    v-model="form.bedrooms"
                                    class="w-full border rounded-md px-3 py-2"
                                >
                                    <option value="">Any</option>
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>
                                </select>
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700"
                            >
                                Apply Filters
                            </button>
                            <button
                                type="button"
                                @click="resetFilters"
                                class="w-full mt-2 border border-gray-300 py-2 rounded-md hover:bg-gray-50"
                            >
                                Reset
                            </button>
                        </form>
                    </div>
                </aside>

                <div class="lg:col-span-3">
                    <div class="mb-4 flex justify-between items-center">
                        <p class="text-gray-600">
                            {{ properties.total }} properties found
                        </p>
                        <select
                            v-model="form.sort"
                            @change="applyFilters"
                            class="border rounded-md px-3 py-2"
                        >
                            <option value="">Featured</option>
                            <option value="newest">Newest</option>
                            <option value="price_asc">
                                Price: Low to High
                            </option>
                            <option value="price_desc">
                                Price: High to Low
                            </option>
                        </select>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
                    >
                        <Link
                            v-for="property in properties.data"
                            :key="property.id"
                            :href="route('properties.show', property.slug)"
                            class="bg-white rounded-lg shadow hover:shadow-lg transition"
                        >
                            <div class="relative">
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
                                <span
                                    v-if="property.is_featured"
                                    class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-semibold"
                                >
                                    Featured
                                </span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2">
                                    {{ property.title }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-2">
                                    {{ property.city }}, {{ property.county }}
                                </p>
                                <div
                                    class="flex items-center text-sm text-gray-600 mb-3"
                                >
                                    <span class="mr-4"
                                        >🛏️ {{ property.bedrooms }} beds</span
                                    >
                                    <span
                                        >🚿 {{ property.bathrooms }} baths</span
                                    >
                                </div>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-blue-600 font-bold text-xl"
                                        >KSh
                                        {{
                                            property.price.toLocaleString()
                                        }}/mo</span
                                    >
                                    <span class="text-xs text-gray-500"
                                        >{{ property.views }} views</span
                                    >
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div
                        v-if="properties.data.length === 0"
                        class="text-center py-12"
                    >
                        <p class="text-gray-500">
                            No properties found matching your criteria.
                        </p>
                    </div>

                    <div
                        v-if="properties.links.length > 3"
                        class="mt-8 flex justify-center"
                    >
                        <nav class="flex space-x-2">
                            <component
                                v-for="(link, index) in properties.links"
                                :key="index"
                                :is="link.url ? Link : 'span'"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-4 py-2 border rounded',
                                    link.active
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-white hover:bg-gray-50',
                                    !link.url &&
                                        'opacity-50 cursor-not-allowed',
                                ]"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { reactive } from "vue";

const props = defineProps({
    properties: Object,
    filters: Object,
});

const form = reactive({
    search: props.filters.search || "",
    type: props.filters.type || "",
    city: props.filters.city || "",
    county: props.filters.county || "",
    min_price: props.filters.min_price || "",
    max_price: props.filters.max_price || "",
    bedrooms: props.filters.bedrooms || "",
    sort: props.filters.sort || "",
});

const applyFilters = () => {
    router.get(route("properties.index"), form, { preserveState: true });
};

const resetFilters = () => {
    Object.keys(form).forEach((key) => (form[key] = ""));
    applyFilters();
};
</script>
