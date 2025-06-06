<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

// Define props using defineProps in the <script setup> syntax
const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: Object,
});

const form = useForm({
    image: null,
})

// Bind the affiliate link to a reactive variable
const affiliateLink = ref(props.user.user_affiliate_link); // Use the affiliate link from the user data

const copyToClipboard = () => {
    navigator.clipboard.writeText(affiliateLink.value).then(() => {
        // alert('Affiliate link copied to clipboard!');
    }).catch(err => {
        // alert('Failed to copy link: ', err);
    });
};

// Mocked user profile picture URL
const profilePicture = ref(props.user?.image
    ? `/${props.user.image}`
    : '/assets/winners/default.jpg');

// Temporary variable to hold the uploaded file
const uploadedFile = ref(null);

// Handle image upload
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        uploadedFile.value = file;
        form.image = file; 
        
        const reader = new FileReader();
        reader.onload = (e) => {
            profilePicture.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const saveProfilePicture = () => {
    if (!form.image) {
        alert('Please select an image first');
        return;
    }

    form.post(route('profile.image.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Update the profile picture display after successful upload
            if (usePage().props.user?.image) {
                profilePicture.value = `/${usePage().props.user.image}`;
            }
        },
    });
};
</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

                <!-- Profile Picture Section -->
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-col items-center">
                    <div class="relative w-32 h-32">
                        <!-- Profile Picture -->
                        <img :src="profilePicture" alt="Profile Picture"
                            class="w-32 h-32 rounded-full shadow-md object-cover" />

                        <!-- Upload Button -->
                        <label for="profile-upload"
                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer shadow-lg hover:bg-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 013.536 3.536L9.5 20.5H5v-4.5l11.232-11.232z" />
                            </svg>
                        </label>
                        <input id="profile-upload" type="file" class="hidden" accept="image/*"
                            @change="handleImageUpload" />
                    </div>

                    <button @click="saveProfilePicture"
                        class="mt-6 px-6 py-2 bg-gray-800 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition">
                        Save
                    </button>
                </div>

                <!-- Update Profile Information -->
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                        class="max-w-xl" />
                </div>

                <!-- Update Password -->
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- Affiliate Link Section -->
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="flex flex-col items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Your Affiliate Link</h3>
                        <p class="text-sm text-gray-600 mt-2">Share this link to earn commissions on referred sales.</p>

                        <!-- Affiliate Link Display -->
                        <div class="mt-4 w-full max-w-xs">
                            <input type="text" v-model="affiliateLink" readonly
                                class="block w-full p-2 border border-gray-300 rounded-md bg-gray-100 text-gray-800" />
                        </div>

                        <!-- Copy Button -->
                        <button @click="copyToClipboard"
                            class="mt-4 px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition">
                            Copy Link
                        </button>
                    </div>
                </div>

                <!-- Delete User Section -->
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
