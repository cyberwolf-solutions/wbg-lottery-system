<!-- <script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
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

            <div class="mt-4">
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

            <div class="mt-4">
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

            <div class="mt-4">
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

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
</template> -->
<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';


const activeTab = ref('login');

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const submitLogin = () => {
    loginForm.post(route('login'), {
        onFinish: () => loginForm.reset('password'),
    });
};

const switchTab = (tab) => {
    activeTab.value = tab;
};
</script>

<template>
    <!-- <GuestLayout> -->

    <Head title="Register" />
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg mx-auto">
        <!-- Tabs -->
        <div class="flex justify-between border-b mb-6">
            <button @click="switchTab('login')"
                class="w-1/2 text-center py-2 text-gray-500 border-b-2 border-transparent hover:text-blue-500"
                :class="{ 'text-blue-500 border-blue-500 font-bold': activeTab === 'login' }">
                Login
            </button>
            <button @click="switchTab('register')"
                class="w-1/2 text-center py-2 text-gray-500 border-b-2 border-transparent hover:text-blue-500"
                :class="{ 'text-blue-500 border-blue-500 font-bold': activeTab === 'register' }">
                Register
            </button>
        </div>

        <!-- Form -->
        <div v-if="activeTab === 'register'">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        v-model="form.name" required autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mb-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        v-model="form.email" required autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mb-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput id="password" type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.password" required
                        autocomplete="new-password" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mb-4">
                    <InputLabel for="password_confirmation" value="Confirm Password" />
                    <TextInput id="password_confirmation" type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        v-model="form.password_confirmation" required autocomplete="new-password" />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" id="terms" class="mr-2" />
                    <label for="terms" class="text-sm text-gray-500">
                        I agree with the Terms of Use
                    </label>
                </div>

                <PrimaryButton class=" py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-md"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </form>
        </div>
        <div v-else>
            <form @submit.prevent="submitLogin">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="email" type="email"
                        v-model="loginForm.email" required autofocus autocomplete="username" />
                    <InputError class="mt-2" :message="loginForm.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="password"
                        type="password" v-model="loginForm.password" required autocomplete="current-password" />
                    <InputError class="mt-2" :message="loginForm.errors.password" />
                </div>

                <!-- Remember me checkbox -->
                <div class="mt-4 block">
                    <label class="flex items-center">
                        <input type="checkbox" v-model="loginForm.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <!-- Forgot password link -->
                <div class="mt-4 flex items-center justify-end">
                    <Link :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900">
                    Forgot your password?
                    </Link>

                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': loginForm.processing }"
                        :disabled="loginForm.processing">
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </div>


        <!-- Social Login -->
        <div class="text-center my-6 text-gray-500">Or login with</div>
        <div class="flex justify-center space-x-4">
            <button class="w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                F
            </button>
            <button class="w-10 h-10 bg-red-600 text-white rounded-full hover:bg-red-700">
                G+
            </button>
            <button class="w-10 h-10 bg-yellow-500 text-white rounded-full hover:bg-yellow-600">
                Google
            </button>
            <button class="w-10 h-10 bg-blue-400 text-white rounded-full hover:bg-blue-500">
                T
            </button>
        </div>

        <!-- Footer -->
        <!-- <div class="text-center mt-6 text-gray-500">
            Already have an account?
            <Link :href="route('login')" class="text-blue-500 hover:underline">
            Login
            </Link>
        </div> -->
    </div>
    <!-- </GuestLayout> -->
</template>
