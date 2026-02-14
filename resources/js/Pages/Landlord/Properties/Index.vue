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
            <Link
                :href="route('landlord.subscription')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Subscription
            </Link>
        </template>

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">My Properties</h1>
            <Link
                :href="route('landlord.properties.create')"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700"
            >
                Add Property
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Property
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Price
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Views
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="property in properties.data" :key="property.id">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-gray-200 rounded mr-4 flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-gray-400"
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
                                <div>
                                    <div class="font-medium">
                                        {{ property.title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ property.city }},
                                        {{ property.county }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold">
                                KSh {{ property.price.toLocaleString() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="[
                                    'px-2 py-1 text-xs rounded-full',
                                    property.status === 'available'
                                        ? 'bg-green-100 text-green-800'
                                        : property.status === 'rented'
                                          ? 'bg-blue-100 text-blue-800'
                                          : 'bg-yellow-100 text-yellow-800',
                                ]"
                            >
                                {{ property.status }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ property.views }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <Link
                                :href="route('properties.show', property.slug)"
                                class="text-blue-600 hover:text-blue-900 mr-3"
                            >
                                View
                            </Link>
                            <Link
                                :href="
                                    route(
                                        'landlord.properties.edit',
                                        property.id,
                                    )
                                "
                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                            >
                                Edit
                            </Link>
                            <button
                                @click="deleteProperty(property)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="properties.data.length === 0"
                class="p-8 text-center text-gray-500"
            >
                No properties yet. Click "Add Property" to get started.
            </div>
        </div>

        <div
            v-if="properties.links.length > 3"
            class="mt-6 flex justify-center"
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
                        !link.url && 'opacity-50 cursor-not-allowed',
                    ]"
                />
            </nav>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Link, router } from "@inertiajs/vue3";

defineProps({
    properties: Object,
});

const deleteProperty = (property) => {
    if (confirm("Are you sure you want to delete this property?")) {
        router.delete(route("landlord.properties.destroy", property.id));
    }
};
</script>
