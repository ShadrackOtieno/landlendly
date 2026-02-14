<template>
    <DashboardLayout>
        <template #sidebar>
            <Link
                :href="route('tenant.dashboard')"
                class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-md"
            >
                Dashboard
            </Link>
            <Link
                :href="route('properties.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Browse Properties
            </Link>
            <Link
                :href="route('messages.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Messages
            </Link>
        </template>

        <h1 class="text-3xl font-bold mb-8">Tenant Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">
                    Find Your Perfect Home
                </h2>
                <p class="text-gray-600 mb-4">
                    Browse thousands of verified properties across Kenya.
                </p>
                <Link
                    :href="route('properties.index')"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 inline-block"
                >
                    Browse Properties
                </Link>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">
                    Get Personalized Help
                </h2>
                <p class="text-gray-600 mb-4">
                    Contact landlords directly and get quick responses.
                </p>
                <Link
                    :href="route('messages.index')"
                    class="border-2 border-blue-600 text-blue-600 px-6 py-2 rounded-md hover:bg-blue-50 inline-block"
                >
                    View Messages
                </Link>
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-8 text-white"
        >
            <h2 class="text-2xl font-bold mb-4">
                Looking for something specific?
            </h2>
            <p class="mb-6">
                Use our advanced search to find properties that match your exact
                needs.
            </p>
            <Link
                :href="route('properties.index')"
                class="bg-white text-blue-600 px-6 py-3 rounded-md hover:bg-gray-100 inline-block font-semibold"
            >
                Advanced Search
            </Link>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>
