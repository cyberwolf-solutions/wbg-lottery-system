    <script setup>
    import { ref } from 'vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { useReCaptcha } from 'vue-recaptcha-v3';
    import { watchEffect } from 'vue';

    const { executeRecaptcha } = useReCaptcha();  // This should now work

    watchEffect(() => {
        if (!executeRecaptcha) {
            console.error("reCAPTCHA is not initialized properly");
        } else {
            console.log("reCAPTCHA initialized successfully");
        }
    });

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




    const submit = async () => {
        try {
            const recaptchaToken = await executeRecaptcha('register');
            console.log('reCAPTCHA Token:', recaptchaToken);

            // Create a FormData object and append all form fields
            const formData = new FormData();
            formData.append('name', form.name);
            formData.append('email', form.email);
            formData.append('password', form.password);
            formData.append('password_confirmation', form.password_confirmation);
            formData.append('recaptcha_token', recaptchaToken);


            axios.post(route('register'), formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {
                    console.log('Registration successful');
                    window.location.href = route('dashboard');
                })
                .catch(error => {
                    console.error('Registration failed:', error.response.data);
                });

        } catch (error) {
            console.error('Recaptcha error:', error);
        }
    };

    const submitLogin = async () => {
        try {
            // Generate the reCAPTCHA token
            const recaptchaToken = await executeRecaptcha('login');
            console.log('reCAPTCHA Token:', recaptchaToken);

            // Create a FormData object and append all form fields
            const formData = new FormData();
            formData.append('email', loginForm.email); // Replace with your form field names
            formData.append('password', loginForm.password);
            formData.append('recaptcha_token', recaptchaToken); // Append the reCAPTCHA token

            // Use axios or fetch to send the form data
            axios.post(route('login'), formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data', // Ensure the correct content type
                },
            })
                .then(response => {
                    console.log('Login successful');
                    window.location.href = route('dashboard'); // Redirect on success
                })
                .catch(error => {
                    console.error('Login failed:', error.response.data);
                    // Handle errors (e.g., display error messages to the user)
                });

        } catch (error) {
            console.error('Recaptcha error:', error);
        }
    };






    const submitPasswordRequest = () => {
        loginForm.reset(); // Reset the form data

        // Redirect to the password request page
        window.location.href = route('password.request');
    };


    const switchTab = (tab) => {
        activeTab.value = tab;
    };


    const loginWithGoogle = () => {
        window.location.href = "http://localhost:8000/api/auth/google";
        // window.location.href = '/auth/google';
    };


    const handleGoogleCallback = (response) => {
        localStorage.setItem("token", response.token);
        // Optionally, redirect to a protected route
        this.$router.push("/dashboard");
    };

</script>

    <template>
        <!-- <GuestLayout> -->

        <!-- <Head title="Register" /> -->
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg mx-auto">
            <!-- Tabs -->
            <div class="flex justify-between border-b mb-6">

                <button @click="switchTab('login')"
                    class="w-1/2 text-center py-2 border-b-2 border-transparent hover:text-blue-500" :class="{
                        'bg-blue-500 text-white font-bold': activeTab === 'login',
                        'bg-gray-100 text-gray-500': activeTab !== 'login'
                    }">
                    Login
                </button>
                <!-- Register Tab -->
                <button @click="switchTab('register')"
                    class="w-1/2 text-center py-2 border-b-2 border-transparent hover:text-blue-500" :class="{
                        'bg-blue-500 text-white font-bold': activeTab === 'register',
                        'bg-gray-100 text-gray-500': activeTab !== 'register'
                    }">
                    Register
                </button>
            </div>

            <!-- Form -->
            <div v-if="activeTab === 'register'">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" type="text" class=" block w-full rounded-md border-gray-300 shadow-sm"
                            v-model="form.name" required autofocus autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class=" block w-full rounded-md border-gray-300 shadow-sm"
                            v-model="form.email" required autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password"
                            class=" block w-full rounded-md border-gray-300 shadow-sm" v-model="form.password" required
                            autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password_confirmation" value="Confirm Password" />
                        <TextInput id="password_confirmation" type="password"
                            class=" block w-full rounded-md border-gray-300 shadow-sm"
                            v-model="form.password_confirmation" required autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>


                    <div class="mt-4 block d-flex justify-content-center">
                        <PrimaryButton class="w-100 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-md"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            style="text-align: center; display: block;">
                            Register
                        </PrimaryButton>
                    </div>

                    <div class="flex items-center mb-6 mt-2">
                        <input type="checkbox" id="terms" class="mr-2" />
                        <label for="terms" class="text-sm text-gray-500">
                            I agree with the Terms of Use
                        </label>
                    </div>
                </form>
            </div>
            <div v-else>
                <form @submit.prevent="submitLogin">
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="email"
                            type="email" v-model="loginForm.email" required autofocus autocomplete="username" />
                        <InputError class="mt-2" :message="loginForm.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="password"
                            type="password" v-model="loginForm.password" required autocomplete="current-password" />
                        <InputError class="mt-2" :message="loginForm.errors.password" />
                    </div>

                    <!-- Remember me checkbox -->
                    <div class="mt-4 block d-flex justify-content-center">
                        <PrimaryButton class="w-100 btn btn-primary" :class="{ 'opacity-25': loginForm.processing }"
                            :disabled="loginForm.processing" style="text-align: center; display: block;">
                            Log in
                        </PrimaryButton>
                    </div>



                    <!-- Forgot password link -->
                    <div class="mt-4 flex items-center justify-between">
                        <!-- Remember Me -->
                        <label class="flex items-center">
                            <input type="checkbox" v-model="loginForm.remember"
                                class="rounded border-gray-300 text-blue-500 focus:ring-blue-500" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <!-- Forgot Password -->
                        <Link :href="route('password.request')"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900"
                            @click.prevent="submitPasswordRequest">
                        Forgot your password?
                        </Link>


                    </div>

                </form>
            </div>


            <!-- Social Login -->
            <!-- <div class="text-center my-6 text-gray-500">Or login with</div>
            <div class="flex flex-col md:flex-row justify-center md:space-x-4 space-y-4 md:space-y-0">

                <button
                    class="flex flex-col items-center md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <span class="material-icons hidden md:block">f</span>
                    <span class="text-center">Facebook</span>
                </button>


                <button @click="loginWithGoogle"
                    class="flex flex-col items-center md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <span class="material-icons hidden md:block">G</span>
                    <span class="text-center">Google</span>
                </button>


                <button
                    class="flex flex-col items-center md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-2 px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500">
                    <span class="material-icons hidden md:block">t</span>
                    <span class="text-center">Twitter</span>
                </button>
            </div> -->






        </div>
        <!-- </GuestLayout> -->
    </template>
