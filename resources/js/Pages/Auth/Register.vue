<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "tenant", // default role
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Name -->
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Role Selection -->
            <div>
                <InputLabel value="I am here to" />

                <div class="mt-2 space-y-3">
                    <!-- Tenant -->
                    <label
                        class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:border-indigo-500"
                    >
                        <input
                            type="radio"
                            value="tenant"
                            v-model="form.role"
                            class="text-indigo-600 focus:ring-indigo-500"
                        />
                        <div>
                            <p class="font-medium text-gray-800">
                                Look for properties
                            </p>
                            <p class="text-sm text-gray-500">
                                I want to rent or find a property
                            </p>
                        </div>
                    </label>

                    <!-- Landlord -->
                    <label
                        class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:border-indigo-500"
                    >
                        <input
                            type="radio"
                            value="landlord"
                            v-model="form.role"
                            class="text-indigo-600 focus:ring-indigo-500"
                        />
                        <div>
                            <p class="font-medium text-gray-800">
                                Upload my properties
                            </p>
                            <p class="text-sm text-gray-500">
                                I want to list and manage properties
                            </p>
                        </div>
                    </label>
                </div>

                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <!-- Password -->
            <div>
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
