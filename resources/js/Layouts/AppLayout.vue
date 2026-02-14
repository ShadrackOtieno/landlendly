<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <Link :href="route('home')" class="flex items-center">
                            <span class="text-2xl font-bold text-blue-600"
                                >Landlendly</span
                            >
                        </Link>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <Link
                                :href="route('properties.index')"
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                            >
                                Properties
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div
                            v-if="$page.props.auth.user"
                            class="flex items-center space-x-4"
                        >
                            <Link
                                :href="route('messages.index')"
                                class="text-gray-500 hover:text-gray-700"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    ></path>
                                </svg>
                            </Link>
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
                                        :href="route('dashboard')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        Dashboard
                                    </Link>
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
                        <div v-else class="space-x-4">
                            <Link
                                :href="route('login')"
                                class="text-gray-700 hover:text-gray-900"
                                >Login</Link
                            >
                            <Link
                                :href="route('register')"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                            >
                                Sign Up
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <footer class="bg-gray-800 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Landlendly</h3>
                        <p class="text-gray-400">
                            Find your perfect rental property in Kenya
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <Link :href="route('properties.index')"
                                    >Properties</Link
                                >
                            </li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">For Landlords</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <Link :href="route('register')"
                                    >List Property</Link
                                >
                            </li>
                            <li>
                                <Link :href="route('subscription.index')"
                                    >Pricing</Link
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>Email: info@landlendly.co.ke</li>
                            <li>Phone: +254 712 345 678</li>
                        </ul>
                    </div>
                </div>
                <div
                    class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400"
                >
                    <p>&copy; 2024 Landlendly. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const showDropdown = ref(false);
</script>
