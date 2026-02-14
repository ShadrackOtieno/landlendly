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
                class="flex items-center px-4 py-2 mt-2 text-gray-700 bg-gray-100 rounded-md"
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

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Payments</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-4">
            <select
                v-model="statusFilter"
                class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @change="applyFilters"
            >
                <option value="">All Statuses</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
            </select>
            <button
                v-if="filters.status"
                @click="clearFilters"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300"
            >
                Clear
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Total Payments</p>
                <p class="text-2xl font-bold">{{ payments.total }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Completed Revenue</p>
                <p class="text-2xl font-bold text-green-600">
                    KSh {{ completedTotal.toLocaleString() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Pending Amount</p>
                <p class="text-2xl font-bold text-yellow-600">
                    KSh {{ pendingTotal.toLocaleString() }}
                </p>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Reference
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            User
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Amount
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Method
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="payment in payments.data"
                        :key="payment.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-mono text-gray-700">
                                {{ payment.reference ?? `#${payment.id}` }}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ payment.user?.name ?? "—" }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ payment.user?.email ?? "" }}
                                </p>
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"
                        >
                            KSh {{ payment.amount?.toLocaleString() }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 capitalize"
                        >
                            {{ payment.payment_method ?? "—" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="paymentStatusClass(payment.status)"
                                class="px-2 py-1 text-xs rounded-full font-medium"
                            >
                                {{ payment.status }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(payment.created_at) }}
                        </td>
                    </tr>
                    <tr v-if="payments.data.length === 0">
                        <td
                            colspan="6"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No payments found.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div
                class="px-6 py-4 flex items-center justify-between border-t border-gray-200"
            >
                <p class="text-sm text-gray-700">
                    Showing {{ payments.from ?? 0 }} to
                    {{ payments.to ?? 0 }} of {{ payments.total }} results
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in payments.links"
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
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
    payments: Object,
    filters: Object,
});

const statusFilter = ref(props.filters?.status ?? "");

const completedTotal = computed(() =>
    props.payments.data
        .filter((p) => p.status === "completed")
        .reduce((sum, p) => sum + (p.amount ?? 0), 0),
);

const pendingTotal = computed(() =>
    props.payments.data
        .filter((p) => p.status === "pending")
        .reduce((sum, p) => sum + (p.amount ?? 0), 0),
);

const applyFilters = () => {
    router.get(
        route("admin.payments.index"),
        {
            status: statusFilter.value,
        },
        { preserveState: true, replace: true },
    );
};

const clearFilters = () => {
    statusFilter.value = "";
    applyFilters();
};

const paymentStatusClass = (status) => {
    const classes = {
        completed: "bg-green-100 text-green-800",
        pending: "bg-yellow-100 text-yellow-800",
        failed: "bg-red-100 text-red-800",
    };
    return classes[status] ?? "bg-gray-100 text-gray-800";
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-KE", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>
