<template>
    <DashboardLayout>
        <template #sidebar>
            <Link
                :href="route('admin.dashboard')"
                class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-md"
            >
                Dashboard
            </Link>
            <Link
                :href="route('admin.users.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Users
            </Link>
            <Link
                :href="route('admin.properties.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Properties
            </Link>
            <Link
                :href="route('admin.payments.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Payments
            </Link>
            <Link
                :href="route('admin.subscriptions.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Subscriptions
            </Link>

            <Link
                :href="route('admin.subscription-plans.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Plans
            </Link>
        </template>

        <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Total Users</p>
                <p class="text-3xl font-bold">{{ stats.total_users }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Total Properties</p>
                <p class="text-3xl font-bold">{{ stats.total_properties }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Active Subscriptions</p>
                <p class="text-3xl font-bold">
                    {{ stats.active_subscriptions }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold">
                    KSh {{ stats.total_revenue.toLocaleString() }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Recent Users</h2>
                <div class="space-y-3">
                    <div
                        v-for="user in recentUsers"
                        :key="user.id"
                        class="flex justify-between items-center p-3 hover:bg-gray-50 rounded"
                    >
                        <div>
                            <p class="font-semibold">{{ user.name }}</p>
                            <p class="text-sm text-gray-600">
                                {{ user.email }}
                            </p>
                        </div>
                        <span
                            class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded"
                            >{{ user.role }}</span
                        >
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Recent Properties</h2>
                <div class="space-y-3">
                    <div
                        v-for="property in recentProperties"
                        :key="property.id"
                        class="flex justify-between items-center p-3 hover:bg-gray-50 rounded"
                    >
                        <div>
                            <p class="font-semibold">{{ property.title }}</p>
                            <p class="text-sm text-gray-600">
                                by {{ property.user.name }}
                            </p>
                        </div>
                        <span class="text-sm font-semibold"
                            >KSh {{ property.price.toLocaleString() }}</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    stats: Object,
    recentUsers: Array,
    recentProperties: Array,
    recentPayments: Array,
});
</script>
