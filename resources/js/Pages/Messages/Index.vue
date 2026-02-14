<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold mb-8">Messages</h1>

            <div class="bg-white rounded-lg shadow">
                <div
                    v-if="conversations.length === 0"
                    class="p-8 text-center text-gray-500"
                >
                    No messages yet. Start a conversation by contacting a
                    landlord.
                </div>
                <div v-else class="divide-y">
                    <Link
                        v-for="conversation in conversations"
                        :key="conversation.id"
                        :href="
                            route(
                                'messages.show',
                                conversation.sender_id ===
                                    $page.props.auth.user.id
                                    ? conversation.receiver_id
                                    : conversation.sender_id,
                            )
                        "
                        class="flex items-center p-4 hover:bg-gray-50"
                    >
                        <div
                            class="w-12 h-12 bg-gray-200 rounded-full mr-4 flex items-center justify-center"
                        >
                            <span class="text-lg font-semibold">
                                {{
                                    (conversation.sender_id ===
                                    $page.props.auth.user.id
                                        ? conversation.receiver.name
                                        : conversation.sender.name)[0]
                                }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-semibold">
                                    {{
                                        conversation.sender_id ===
                                        $page.props.auth.user.id
                                            ? conversation.receiver.name
                                            : conversation.sender.name
                                    }}
                                </h3>
                                <span class="text-xs text-gray-500">{{
                                    new Date(
                                        conversation.created_at,
                                    ).toLocaleDateString()
                                }}</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">
                                {{ conversation.message }}
                            </p>
                            <p
                                v-if="conversation.property"
                                class="text-xs text-blue-600 mt-1"
                            >
                                Re: {{ conversation.property.title }}
                            </p>
                        </div>
                        <div
                            v-if="
                                !conversation.is_read &&
                                conversation.receiver_id ===
                                    $page.props.auth.user.id
                            "
                            class="w-3 h-3 bg-blue-600 rounded-full ml-4"
                        ></div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    conversations: Array,
});
</script>
