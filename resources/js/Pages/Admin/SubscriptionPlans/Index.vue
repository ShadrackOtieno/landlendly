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
                class="flex items-center px-4 py-2 mt-2 text-gray-600 hover:bg-gray-100 rounded-md"
            >
                Subscriptions
            </Link>
            <Link
                :href="route('admin.subscription-plans.index')"
                class="flex items-center px-4 py-2 mt-2 text-gray-700 bg-gray-100 rounded-md"
            >
                Plans
            </Link>
        </template>

        <!-- Flash Messages -->
        <div
            v-if="$page.props.flash?.success"
            class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between"
        >
            <span>{{ $page.props.flash.success }}</span>
            <button
                @click="dismissFlash"
                class="text-green-600 hover:text-green-900 text-lg leading-none"
            >
                &times;
            </button>
        </div>
        <div
            v-if="$page.props.flash?.error"
            class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between"
        >
            <span>{{ $page.props.flash.error }}</span>
            <button
                @click="dismissFlash"
                class="text-red-600 hover:text-red-900 text-lg leading-none"
            >
                &times;
            </button>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold">Subscription Plans</h1>
                <p class="text-gray-500 mt-1">
                    Manage pricing plans available to landlords.
                </p>
            </div>
            <button
                @click="openCreateModal"
                class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-medium flex items-center gap-2"
            >
                <span class="text-lg leading-none">+</span> New Plan
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Total Plans</p>
                <p class="text-3xl font-bold">{{ plans.total }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Active Plans</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ activePlansCount }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Total Subscriptions</p>
                <p class="text-3xl font-bold text-blue-600">
                    {{ totalSubscriptions }}
                </p>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            <div
                v-for="plan in plans.data"
                :key="plan.id"
                :class="[
                    'bg-white rounded-xl shadow border-2 transition-all',
                    plan.is_active
                        ? 'border-transparent'
                        : 'border-gray-200 opacity-75',
                ]"
            >
                <!-- Plan Header -->
                <div
                    :class="[
                        'px-6 py-5 rounded-t-xl',
                        plan.is_active ? 'bg-blue-600' : 'bg-gray-400',
                    ]"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-white font-bold text-xl">
                                {{ plan.name }}
                            </h3>
                            <p class="text-blue-100 text-sm mt-1">
                                {{ plan.slug }}
                            </p>
                        </div>
                        <span
                            :class="[
                                'text-xs px-2 py-1 rounded-full font-medium',
                                plan.is_active
                                    ? 'bg-white text-blue-700'
                                    : 'bg-gray-300 text-gray-700',
                            ]"
                        >
                            {{ plan.is_active ? "Active" : "Inactive" }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-white text-3xl font-extrabold"
                            >KSh {{ Number(plan.price).toLocaleString() }}</span
                        >
                        <span class="text-blue-100 text-sm ml-1"
                            >/ {{ billingLabel(plan.billing_cycle_days) }}</span
                        >
                    </div>
                </div>

                <!-- Plan Details -->
                <div class="px-6 py-4">
                    <p
                        v-if="plan.description"
                        class="text-gray-600 text-sm mb-4"
                    >
                        {{ plan.description }}
                    </p>

                    <div
                        class="flex items-center gap-4 text-sm text-gray-600 mb-4"
                    >
                        <div class="flex items-center gap-1">
                            <span class="font-semibold text-gray-800">{{
                                plan.property_limit
                            }}</span>
                            <span>properties</span>
                        </div>
                        <div class="h-4 w-px bg-gray-200"></div>
                        <div class="flex items-center gap-1">
                            <span class="font-semibold text-gray-800">{{
                                plan.subscriptions_count ?? 0
                            }}</span>
                            <span>subscribers</span>
                        </div>
                    </div>

                    <!-- Features List -->
                    <ul
                        v-if="plan.features && plan.features.length"
                        class="space-y-1 mb-4"
                    >
                        <li
                            v-for="(feature, i) in plan.features"
                            :key="i"
                            class="flex items-center gap-2 text-sm text-gray-700"
                        >
                            <svg
                                class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            {{ feature }}
                        </li>
                    </ul>
                    <p v-else class="text-sm text-gray-400 italic mb-4">
                        No features listed.
                    </p>

                    <!-- Actions -->
                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        <button
                            @click="openEditModal(plan)"
                            class="flex-1 text-sm text-blue-600 border border-blue-200 rounded-md py-2 hover:bg-blue-50 font-medium"
                        >
                            Edit
                        </button>
                        <button
                            @click="toggleActive(plan)"
                            :class="[
                                'flex-1 text-sm border rounded-md py-2 font-medium',
                                plan.is_active
                                    ? 'text-orange-600 border-orange-200 hover:bg-orange-50'
                                    : 'text-green-600 border-green-200 hover:bg-green-50',
                            ]"
                        >
                            {{ plan.is_active ? "Deactivate" : "Activate" }}
                        </button>
                        <button
                            @click="confirmDelete(plan)"
                            class="px-3 text-sm text-red-500 border border-red-200 rounded-md py-2 hover:bg-red-50"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="plans.data.length === 0"
                class="col-span-3 bg-white rounded-xl shadow p-12 text-center"
            >
                <svg
                    class="w-16 h-16 text-gray-300 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    />
                </svg>
                <p class="text-gray-500 text-lg">No subscription plans yet.</p>
                <button
                    @click="openCreateModal"
                    class="mt-4 text-blue-600 font-medium hover:underline"
                >
                    Create your first plan →
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="plans.last_page > 1" class="flex justify-end gap-1">
            <Link
                v-for="link in plans.links"
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
            />
        </div>

        <!-- ======================== CREATE / EDIT MODAL ======================== -->
        <div
            v-if="formModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        >
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
            >
                <div
                    class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
                >
                    <h2 class="text-xl font-bold">
                        {{ isEditing ? "Edit Plan" : "Create New Plan" }}
                    </h2>
                    <button
                        @click="formModal = false"
                        class="text-gray-400 hover:text-gray-600 text-2xl leading-none"
                    >
                        &times;
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="px-6 py-5 space-y-5">
                    <!-- Name -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Plan Name
                            <span class="text-red-500">*</span></label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Basic, Professional, Enterprise"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @input="autoSlug"
                        />
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Slug</label
                        >
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="auto-generated from name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                        />
                        <p v-if="errors.slug" class="text-red-500 text-xs mt-1">
                            {{ errors.slug }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            rows="3"
                            placeholder="Short description of this plan..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        ></textarea>
                    </div>

                    <!-- Price + Billing Cycle -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Price (KSh)
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                v-model="form.price"
                                type="number"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p
                                v-if="errors.price"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ errors.price }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Billing Cycle
                                <span class="text-red-500">*</span></label
                            >
                            <select
                                v-model="form.billing_cycle_days"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option :value="7">Weekly (7 days)</option>
                                <option :value="30">Monthly (30 days)</option>
                                <option :value="90">Quarterly (90 days)</option>
                                <option :value="180">
                                    Semi-Annual (180 days)
                                </option>
                                <option :value="365">Annual (365 days)</option>
                                <option value="custom">Custom...</option>
                            </select>
                            <input
                                v-if="
                                    form.billing_cycle_days === 'custom' ||
                                    isCustomDays
                                "
                                v-model="form.billing_cycle_days"
                                type="number"
                                min="1"
                                placeholder="Number of days"
                                class="w-full mt-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p
                                v-if="errors.billing_cycle_days"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ errors.billing_cycle_days }}
                            </p>
                        </div>
                    </div>

                    <!-- Property Limit -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Property Limit
                            <span class="text-red-500">*</span></label
                        >
                        <input
                            v-model="form.property_limit"
                            type="number"
                            min="1"
                            placeholder="Max number of properties"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <p
                            v-if="errors.property_limit"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ errors.property_limit }}
                        </p>
                    </div>

                    <!-- Features -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Features</label
                            >
                            <button
                                type="button"
                                @click="addFeature"
                                class="text-xs text-blue-600 hover:underline"
                            >
                                + Add feature
                            </button>
                        </div>
                        <div
                            v-for="(feature, index) in form.features"
                            :key="index"
                            class="flex gap-2 mb-2"
                        >
                            <input
                                v-model="form.features[index]"
                                type="text"
                                placeholder="e.g. Priority support"
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button
                                type="button"
                                @click="removeFeature(index)"
                                class="text-red-400 hover:text-red-600 px-2"
                            >
                                &times;
                            </button>
                        </div>
                        <p
                            v-if="form.features.length === 0"
                            class="text-sm text-gray-400 italic"
                        >
                            No features added yet.
                        </p>
                    </div>

                    <!-- Is Active -->
                    <div
                        class="flex items-center gap-3 py-2 px-4 bg-gray-50 rounded-lg"
                    >
                        <label
                            class="relative inline-flex items-center cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                v-model="form.is_active"
                                class="sr-only peer"
                            />
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                            ></div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">
                            {{
                                form.is_active
                                    ? "Active – visible to users"
                                    : "Inactive – hidden from users"
                            }}
                        </span>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="formModal = false"
                            class="px-5 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium"
                        >
                            {{ isEditing ? "Save Changes" : "Create Plan" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================== DELETE MODAL ======================== -->
        <div
            v-if="deleteModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm">
                <div class="text-center mb-4">
                    <div
                        class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3"
                    >
                        <svg
                            class="w-7 h-7 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Delete Plan?
                    </h2>
                    <p class="text-gray-600 mt-2 text-sm">
                        Are you sure you want to delete
                        <strong>{{ selectedPlan?.name }}</strong
                        >? Plans with active subscriptions cannot be deleted.
                    </p>
                </div>
                <div class="flex justify-center gap-3">
                    <button
                        @click="deleteModal = false"
                        class="px-5 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitDelete"
                        class="px-5 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
    plans: Object,
});

// ─── State ────────────────────────────────────────────────────
const formModal = ref(false);
const deleteModal = ref(false);
const isEditing = ref(false);
const selectedPlan = ref(null);
const errors = ref({});
const showFlash = ref(true);

const defaultForm = () => ({
    name: "",
    slug: "",
    description: "",
    price: "",
    property_limit: "",
    billing_cycle_days: 30,
    features: [],
    is_active: true,
});

const form = reactive(defaultForm());

// ─── Computed ─────────────────────────────────────────────────
const activePlansCount = computed(
    () => props.plans.data.filter((p) => p.is_active).length,
);
const totalSubscriptions = computed(() =>
    props.plans.data.reduce((s, p) => s + (p.subscriptions_count ?? 0), 0),
);
const isCustomDays = computed(
    () => ![7, 30, 90, 180, 365].includes(Number(form.billing_cycle_days)),
);

// ─── Helpers ──────────────────────────────────────────────────
const billingLabel = (days) => {
    const map = {
        7: "week",
        30: "month",
        90: "quarter",
        180: "6 months",
        365: "year",
    };
    return map[days] ?? `${days} days`;
};

const autoSlug = () => {
    if (!isEditing.value) {
        form.slug = form.name
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, "-")
            .replace(/^-|-$/g, "");
    }
};

const addFeature = () => form.features.push("");
const removeFeature = (i) => form.features.splice(i, 1);

const dismissFlash = () => {
    showFlash.value = false;
};

// ─── Modals ───────────────────────────────────────────────────
const openCreateModal = () => {
    Object.assign(form, defaultForm());
    errors.value = {};
    isEditing.value = false;
    formModal.value = true;
};

const openEditModal = (plan) => {
    selectedPlan.value = plan;
    Object.assign(form, {
        name: plan.name,
        slug: plan.slug,
        description: plan.description ?? "",
        price: plan.price,
        property_limit: plan.property_limit,
        billing_cycle_days: plan.billing_cycle_days,
        features: plan.features ? [...plan.features] : [],
        is_active: plan.is_active,
    });
    errors.value = {};
    isEditing.value = true;
    formModal.value = true;
};

const confirmDelete = (plan) => {
    selectedPlan.value = plan;
    deleteModal.value = true;
};

// ─── Submissions ──────────────────────────────────────────────
const submitForm = () => {
    const payload = {
        ...form,
        features: form.features.filter((f) => f.trim() !== ""),
    };

    if (isEditing.value) {
        router.put(
            route("admin.subscription-plans.update", selectedPlan.value.id),
            payload,
            {
                onSuccess: () => {
                    formModal.value = false;
                },
                onError: (e) => {
                    errors.value = e;
                },
            },
        );
    } else {
        router.post(route("admin.subscription-plans.store"), payload, {
            onSuccess: () => {
                formModal.value = false;
            },
            onError: (e) => {
                errors.value = e;
            },
        });
    }
};

const submitDelete = () => {
    router.delete(
        route("admin.subscription-plans.destroy", selectedPlan.value.id),
        {
            onSuccess: () => {
                deleteModal.value = false;
            },
        },
    );
};

const toggleActive = (plan) => {
    router.patch(route("admin.subscription-plans.toggle-active", plan.id));
};
</script>
