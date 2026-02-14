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
                class="flex items-center px-4 py-2 mt-2 text-gray-700 bg-gray-100 rounded-md"
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

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Users</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by name or email..."
                class="border border-gray-300 rounded-md px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @keyup.enter="applyFilters"
            />
            <select
                v-model="roleFilter"
                class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @change="applyFilters"
            >
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="landlord">Landlord</option>
                <option value="tenant">Tenant</option>
            </select>
            <button
                @click="applyFilters"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
            >
                Search
            </button>
            <button
                v-if="filters.search || filters.role"
                @click="clearFilters"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300"
            >
                Clear
            </button>
        </div>

        <!-- Users Table -->
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
                            Role
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Joined
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
                        v-for="user in users.data"
                        :key="user.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold"
                                >
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="roleClass(user.role)"
                                class="px-2 py-1 text-xs rounded-full font-medium"
                            >
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="
                                    user.is_active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'
                                "
                                class="px-2 py-1 text-xs rounded-full font-medium"
                            >
                                {{ user.is_active ? "Active" : "Inactive" }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(user.created_at) }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2"
                        >
                            <button
                                @click="openEditModal(user)"
                                class="text-blue-600 hover:text-blue-900"
                            >
                                Edit
                            </button>
                            <button
                                @click="confirmDelete(user)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="users.data.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No users found.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div
                class="px-6 py-4 flex items-center justify-between border-t border-gray-200"
            >
                <p class="text-sm text-gray-700">
                    Showing {{ users.from ?? 0 }} to {{ users.to ?? 0 }} of
                    {{ users.total }} results
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in users.links"
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
                <h2 class="text-xl font-bold mb-4">Edit User</h2>
                <form @submit.prevent="submitEdit">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Name</label
                            >
                            <input
                                v-model="editForm.name"
                                type="text"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p
                                v-if="editErrors.name"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ editErrors.name }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Email</label
                            >
                            <input
                                v-model="editForm.email"
                                type="email"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p
                                v-if="editErrors.email"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ editErrors.email }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Role</label
                            >
                            <select
                                v-model="editForm.role"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="admin">Admin</option>
                                <option value="landlord">Landlord</option>
                                <option value="tenant">Tenant</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                v-model="editForm.is_active"
                                type="checkbox"
                                id="is_active"
                                class="rounded"
                            />
                            <label
                                for="is_active"
                                class="text-sm font-medium text-gray-700"
                                >Active</label
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
                <h2 class="text-xl font-bold mb-2">Delete User</h2>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete
                    <strong>{{ selectedUser?.name }}</strong
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
import { Link, router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
    users: Object,
    filters: Object,
});

const searchQuery = ref(props.filters?.search ?? "");
const roleFilter = ref(props.filters?.role ?? "");

const editModal = ref(false);
const deleteModal = ref(false);
const selectedUser = ref(null);
const editErrors = ref({});

const editForm = reactive({
    name: "",
    email: "",
    role: "",
    is_active: true,
});

const applyFilters = () => {
    router.get(
        route("admin.users.index"),
        {
            search: searchQuery.value,
            role: roleFilter.value,
        },
        { preserveState: true, replace: true },
    );
};

const clearFilters = () => {
    searchQuery.value = "";
    roleFilter.value = "";
    applyFilters();
};

const openEditModal = (user) => {
    selectedUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    editForm.is_active = user.is_active;
    editErrors.value = {};
    editModal.value = true;
};

const submitEdit = () => {
    router.put(route("admin.users.update", selectedUser.value.id), editForm, {
        onSuccess: () => {
            editModal.value = false;
        },
        onError: (errors) => {
            editErrors.value = errors;
        },
    });
};

const confirmDelete = (user) => {
    selectedUser.value = user;
    deleteModal.value = true;
};

const submitDelete = () => {
    router.delete(route("admin.users.destroy", selectedUser.value.id), {
        onSuccess: () => {
            deleteModal.value = false;
        },
    });
};

const roleClass = (role) => {
    const classes = {
        admin: "bg-purple-100 text-purple-800",
        landlord: "bg-blue-100 text-blue-800",
        tenant: "bg-green-100 text-green-800",
    };
    return classes[role] ?? "bg-gray-100 text-gray-800";
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-KE", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>
