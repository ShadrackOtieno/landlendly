<template>
    <AppLayout>
        <div class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold mb-4">Choose Your Plan</h1>
                    <p class="text-xl text-gray-600">
                        Start with a 14-day free trial. No credit card required.
                    </p>
                </div>

                <div
                    v-if="currentSubscription"
                    class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8"
                >
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">
                                Current Plan:
                                {{ currentSubscription.plan.name }}
                            </h3>
                            <p class="text-gray-600">
                                Status:
                                <span class="font-semibold capitalize">{{
                                    currentSubscription.status
                                }}</span>
                                | Renews:
                                {{
                                    new Date(
                                        currentSubscription.ends_at,
                                    ).toLocaleDateString()
                                }}
                            </p>
                        </div>
                        <button
                            v-if="currentSubscription.auto_renew"
                            @click="cancelSubscription"
                            class="text-red-600 hover:text-red-800"
                        >
                            Cancel Auto-Renewal
                        </button>
                    </div>
                </div>

                <div
                    v-if="onTrial"
                    class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 text-center"
                >
                    <p class="text-lg">
                        🎉 Your free trial is active! Expires on
                        {{ new Date(trialEndsAt).toLocaleDateString() }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        v-for="plan in plans"
                        :key="plan.id"
                        :class="[
                            'bg-white rounded-lg shadow-lg overflow-hidden',
                            plan.slug === 'premium'
                                ? 'ring-2 ring-blue-600 transform scale-105'
                                : '',
                        ]"
                    >
                        <div class="p-8">
                            <div
                                v-if="plan.slug === 'premium'"
                                class="bg-blue-600 text-white text-center py-1 px-4 rounded-full inline-block mb-4"
                            >
                                POPULAR
                            </div>
                            <h3 class="text-2xl font-bold mb-2">
                                {{ plan.name }}
                            </h3>
                            <p class="text-gray-600 mb-6">
                                {{ plan.description }}
                            </p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold"
                                    >KSh {{ plan.price.toLocaleString() }}</span
                                >
                                <span class="text-gray-600">/month</span>
                            </div>
                            <ul class="space-y-3 mb-8">
                                <li
                                    v-for="feature in plan.features"
                                    :key="feature"
                                    class="flex items-start"
                                >
                                    <svg
                                        class="w-5 h-5 text-green-500 mr-2 mt-0.5"
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
                                    {{ feature }}
                                </li>
                            </ul>
                            <button
                                @click="selectPlan(plan)"
                                :class="[
                                    'w-full py-3 rounded-lg font-semibold',
                                    plan.slug === 'premium'
                                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                                        : 'border-2 border-blue-600 text-blue-600 hover:bg-blue-50',
                                ]"
                            >
                                {{
                                    currentSubscription &&
                                    currentSubscription.plan.id === plan.id
                                        ? "Current Plan"
                                        : "Subscribe"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showCheckout"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Complete Payment</h2>
                <p class="mb-4">
                    Plan: <strong>{{ selectedPlan.name }}</strong>
                </p>
                <p class="mb-6">
                    Amount:
                    <strong
                        >KSh {{ selectedPlan.price.toLocaleString() }}</strong
                    >
                </p>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2"
                        >M-Pesa Phone Number *</label
                    >
                    <input
                        v-model="phoneNumber"
                        type="text"
                        placeholder="254712345678"
                        class="w-full border rounded-md px-3 py-2"
                    />
                    <p class="text-sm text-gray-600 mt-1">
                        Format: 254XXXXXXXXX
                    </p>
                </div>

                <div class="flex space-x-4">
                    <button
                        @click="showCheckout = false"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        @click="processPayment"
                        :disabled="processing"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                    >
                        {{ processing ? "Processing..." : "Pay with M-Pesa" }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    plans: Array,
    currentSubscription: Object,
    onTrial: Boolean,
    trialEndsAt: String,
});

const showCheckout = ref(false);
const selectedPlan = ref(null);
const phoneNumber = ref("");
const processing = ref(false);

const selectPlan = (plan) => {
    if (
        props.currentSubscription &&
        props.currentSubscription.plan.id === plan.id
    ) {
        return;
    }
    selectedPlan.value = plan;
    showCheckout.value = true;
};

const processPayment = async () => {
    if (!phoneNumber.value || !phoneNumber.value.match(/^254[0-9]{9}$/)) {
        alert("Please enter a valid M-Pesa number (254XXXXXXXXX)");
        return;
    }

    processing.value = true;

    try {
        await axios.post(route("payments.mpesa.initiate"), {
            phone: phoneNumber.value,
            amount: selectedPlan.value.price,
            subscription_plan_id: selectedPlan.value.id,
        });

        alert(
            "Payment initiated! Please check your phone for the M-Pesa prompt.",
        );
        showCheckout.value = false;
        router.reload();
    } catch (error) {
        alert("Payment failed. Please try again.");
    } finally {
        processing.value = false;
    }
};

const cancelSubscription = () => {
    if (confirm("Are you sure you want to cancel auto-renewal?")) {
        router.post(route("subscription.cancel"));
    }
};
</script>
