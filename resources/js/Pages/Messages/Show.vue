<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6 flex items-center">
                <Link
                    :href="route('messages.index')"
                    class="text-blue-600 hover:text-blue-800 mr-4"
                >
                    ← Back to Messages
                </Link>
                <div
                    class="w-10 h-10 bg-gray-200 rounded-full mr-3 flex items-center justify-center"
                >
                    <span class="text-sm font-semibold">{{
                        recipient.name[0]
                    }}</span>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">{{ recipient.name }}</h2>
                    <p class="text-sm text-gray-600">{{ recipient.email }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow mb-4">
                <div class="h-96 overflow-y-auto p-6 space-y-4">
                    <div
                        v-for="message in messages"
                        :key="message.id"
                        :class="[
                            'flex',
                            message.sender_id === $page.props.auth.user.id
                                ? 'justify-end'
                                : 'justify-start',
                        ]"
                    >
                        <div
                            :class="[
                                'max-w-xs lg:max-w-md px-4 py-2 rounded-lg',
                                message.sender_id === $page.props.auth.user.id
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-100 text-gray-900',
                            ]"
                        >
                            <p>{{ message.message }}</p>
                            <p
                                :class="[
                                    'text-xs mt-1',
                                    message.sender_id ===
                                    $page.props.auth.user.id
                                        ? 'text-blue-100'
                                        : 'text-gray-500',
                                ]"
                            >
                                {{
                                    new Date(
                                        message.created_at,
                                    ).toLocaleString()
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="sendMessage"
                class="bg-white rounded-lg shadow p-4"
            >
                <div class="flex space-x-4">
                    <input
                        v-model="form.message"
                        type="text"
                        required
                        class="flex-1 border rounded-md px-4 py-2"
                        placeholder="Type your message..."
                    />
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50"
                    >
                        Send
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    recipient: Object,
    messages: Array,
});

const form = useForm({
    receiver_id: props.recipient.id,
    message: "",
});

const sendMessage = () => {
    form.post(route("messages.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset("message"),
    });
};
</script>
