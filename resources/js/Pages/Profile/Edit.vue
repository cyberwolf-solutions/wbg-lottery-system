<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

import { ref } from 'vue';

// Mocked user profile picture URL
const profilePicture = ref('/path/to/profile-picture.jpg');

// Temporary variable to hold the uploaded file
const uploadedFile = ref(null);

// Handle image upload
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        uploadedFile.value = file; // Save the file to be uploaded
        const reader = new FileReader();
        reader.onload = (e) => {
            profilePicture.value = e.target.result; // Display preview of the uploaded image
        };
        reader.readAsDataURL(file);
    }
};

// Save profile picture (dummy function for now)
const saveProfilePicture = () => {
    if (!uploadedFile.value) {
        alert('Please upload an image before saving.');
        return;
    }

    // Here, you can send the `uploadedFile.value` to the backend using Inertia's form or any API logic
    alert('Profile picture saved successfully!');
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

                <!-- Profile Picture Section -->
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-col items-center">
                    <div class="relative w-32 h-32">
                        <!-- Profile Picture -->
                        <img
                            :src="profilePicture"
                            alt="Profile Picture"
                            class="w-32 h-32 rounded-full shadow-md object-cover"
                        />
                        
                        <!-- Upload Button -->
                        <label
                            for="profile-upload"
                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer shadow-lg hover:bg-blue-600 transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 013.536 3.536L9.5 20.5H5v-4.5l11.232-11.232z" />
                            </svg>
                        </label>
                        <input
                            id="profile-upload"
                            type="file"
                            class="hidden"
                            accept="image/*"
                            @change="handleImageUpload"
                        />
                    </div>

                    <p class="text-gray-600 mt-4 text-sm">
                        Upload a profile picture (max 2MB)
                    </p>

                    <!-- Save Button -->
                    <button
                        @click="saveProfilePicture" 
                        class="mt-6 px-6 py-2 bg-gray-800 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition"
                    >
                        Save
                    </button>
                </div>

                <!-- Update Profile Information -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- Update Password -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- Delete User -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
