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
                class="flex items-center px-4 py-2 mt-2 text-gray-700 bg-gray-100 rounded-md"
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

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Properties</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by title..."
                class="border border-gray-300 rounded-md px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @keyup.enter="applyFilters"
            />
            <select
                v-model="statusFilter"
                class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @change="applyFilters"
            >
                <option value="">All Statuses</option>
                <option value="available">Available</option>
                <option value="rented">Rented</option>
                <option value="maintenance">Maintenance</option>
            </select>
            <button
                @click="applyFilters"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
            >
                Search
            </button>
            <button
                v-if="filters.search || filters.status"
                @click="clearFilters"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300"
            >
                Clear
            </button>
        </div>

        <!-- Properties Table -->
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
                            Owner
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
                            Featured
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Date
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="property in properties.data"
                        :key="property.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ property.title }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ property.location }}
                                </p>
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                        >
                            {{ property.user?.name ?? "—" }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"
                        >
                            KSh {{ property.price?.toLocaleString() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="statusClass(property.status)"
                                class="px-2 py-1 text-xs rounded-full font-medium"
                            >
                                {{ property.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="
                                    property.is_featured
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-gray-100 text-gray-600'
                                "
                                class="px-2 py-1 text-xs rounded-full font-medium"
                            >
                                {{ property.is_featured ? "Featured" : "No" }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(property.created_at) }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2"
                        >
                            <button
                                @click="openEditModal(property)"
                                class="text-blue-600 hover:text-blue-900"
                            >
                                Edit
                            </button>
                            <button
                                @click="confirmDelete(property)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="properties.data.length === 0">
                        <td
                            colspan="7"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No properties found.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div
                class="px-6 py-4 flex items-center justify-between border-t border-gray-200"
            >
                <p class="text-sm text-gray-700">
                    Showing {{ properties.from ?? 0 }} to
                    {{ properties.to ?? 0 }} of {{ properties.total }} results
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in properties.links"
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

        <!-- Edit Modal -->
        <div
            v-if="editModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Edit Property</h2>
                <form @submit.prevent="submitEdit">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Status</label
                            >
                            <select
                                v-model="editForm.status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                v-model="editForm.is_featured"
                                type="checkbox"
                                id="is_featured"
                                class="rounded"
                            />
                            <label
                                for="is_featured"
                                class="text-sm font-medium text-gray-700"
                                >Featured Property</label
                            >
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button
                            type="button"
                            @click="editModal = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="deleteModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
                <h2 class="text-xl font-bold mb-2">Delete Property</h2>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete
                    <strong>{{ selectedProperty?.title }}</strong
                    >? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="deleteModal = false"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
    properties: Object,
    filters: Object,
});

const searchQuery = ref(props.filters?.search ?? "");
const statusFilter = ref(props.filters?.status ?? "");
const editModal = ref(false);
const deleteModal = ref(false);
const selectedProperty = ref(null);

const editForm = reactive({
    status: "",
    is_featured: false,
});

const applyFilters = () => {
    router.get(
        route("admin.properties.index"),
        {
            search: searchQuery.value,
            status: statusFilter.value,
        },
        { preserveState: true, replace: true },
    );
};

const clearFilters = () => {
    searchQuery.value = "";
    statusFilter.value = "";
    applyFilters();
};

const openEditModal = (property) => {
    selectedProperty.value = property;
    editForm.status = property.status;
    editForm.is_featured = property.is_featured;
    editModal.value = true;
};

const submitEdit = () => {
    router.put(
        route("admin.properties.update", selectedProperty.value.id),
        editForm,
        {
            onSuccess: () => {
                editModal.value = false;
            },
        },
    );
};

const confirmDelete = (property) => {
    selectedProperty.value = property;
    deleteModal.value = true;
};

const submitDelete = () => {
    router.delete(
        route("admin.properties.destroy", selectedProperty.value.id),
        {
            onSuccess: () => {
                deleteModal.value = false;
            },
        },
    );
};

const statusClass = (status) => {
    const classes = {
        available: "bg-green-100 text-green-800",
        rented: "bg-blue-100 text-blue-800",
        maintenance: "bg-orange-100 text-orange-800",
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
