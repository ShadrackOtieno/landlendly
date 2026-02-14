<template>
    <DashboardLayout>
        <template #sidebar>
            <Link
                :href="route('admin.dashboard')"
                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md"
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
                class="flex items-center px-4 py-2 mt-2 text-gray-700 bg-gray-100 rounded-md"
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

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Subscriptions</h1>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Total Subscriptions</p>
                <p class="text-2xl font-bold">{{ subscriptions.total }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ activeCount }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Expired / Cancelled</p>
                <p class="text-2xl font-bold text-red-500">
                    {{ inactiveCount }}
                </p>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            User
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Plan
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Started
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Expires
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="sub in subscriptions.data"
                        :key="sub.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm"
                                >
                                    {{
                                        sub.user?.name
                                            ?.charAt(0)
                                            .toUpperCase() ?? "?"
                                    }}
                                </div>
                                <div class="ml-3">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ sub.user?.name ?? "—" }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ sub.user?.email ?? "" }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ sub.plan?.name ?? "—" }}
                                </p>
                                <p
                                    class="text-xs text-gray-500"
                                    v-if="sub.plan?.price"
                                >
                                    KSh {{ sub.plan.price.toLocaleString() }} /
                                    {{ sub.plan?.billing_cycle ?? "month" }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="subStatusClass(sub.status)"
                                class="px-2 py-1 text-xs rounded-full font-medium"
                            >
                                {{ sub.status }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(sub.created_at) }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            <span
                                :class="
                                    isExpiringSoon(sub.ends_at)
                                        ? 'text-orange-600 font-medium'
                                        : ''
                                "
                            >
                                {{
                                    sub.ends_at ? formatDate(sub.ends_at) : "—"
                                }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="subscriptions.data.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No subscriptions found.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div
                class="px-6 py-4 flex items-center justify-between border-t border-gray-200"
            >
                <p class="text-sm text-gray-700">
                    Showing {{ subscriptions.from ?? 0 }} to
                    {{ subscriptions.to ?? 0 }} of
                    {{ subscriptions.total }} results
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in subscriptions.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        :class="[
                            'px-3 py-1 text-sm rounded border',
                            link.active
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'text-gray-600 border-gray-300 hover:bg-gray-100',
                            !link.url ? 'opacity-50 cursor-not-allowed' : '',
                        ]"
                        :preserve-scroll="true"
                    />
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
    subscriptions: Object,
});

const activeCount = computed(
    () => props.subscriptions.data.filter((s) => s.status === "active").length,
);

const inactiveCount = computed(
    () => props.subscriptions.data.filter((s) => s.status !== "active").length,
);

const subStatusClass = (status) => {
    const classes = {
        active: "bg-green-100 text-green-800",
        expired: "bg-red-100 text-red-800",
        cancelled: "bg-gray-100 text-gray-700",
        trial: "bg-blue-100 text-blue-800",
    };
    return classes[status] ?? "bg-gray-100 text-gray-800";
};

const isExpiringSoon = (endsAt) => {
    if (!endsAt) return false;
    const daysLeft = (new Date(endsAt) - new Date()) / (1000 * 60 * 60 * 24);
    return daysLeft > 0 && daysLeft <= 7;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-KE", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>
