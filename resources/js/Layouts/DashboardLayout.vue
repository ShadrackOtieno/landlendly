<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <Link :href="route('home')" class="flex items-center">
                            <span class="text-2xl font-bold text-blue-600"
                                >Landlendly</span
                            >
                        </Link>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button
                                @click="showDropdown = !showDropdown"
                                class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900"
                            >
                                {{ $page.props.auth.user.name }}
                                <svg
                                    class="ml-1 w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </button>
                            <div
                                v-show="showDropdown"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10"
                            >
                                <Link
                                    :href="route('profile.edit')"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Profile
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Logout
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <aside class="w-64 bg-white shadow-sm min-h-screen">
                <nav class="mt-5 px-2">
                    <slot name="sidebar" />
                </nav>
            </aside>

            <main class="flex-1 p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const showDropdown = ref(false);
</script>
