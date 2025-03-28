
<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Users</h2>
                    <button class="btn btn-primary" @click="openModal">
                        <i class="fa fa-plus"></i> Add User
                    </button>
                </div>

                <div class="transactions-cards">
                    <h3 class="mt-4">User Details</h3>
                    <div class="card-container">
                        <div class="transaction-card relative" v-for="(admin, index) in admins" :key="admin.id">
                            <div class="card-header flex justify-between items-center">
                                <span>{{ admin.name }}</span>
                                <div class="relative">
                                    <button @click="toggleMenu(index)" class="text-gray-500 focus:outline-none">
                                        &#8942;
                                    </button>
                                    <div v-if="menuIndex === index"
                                        <!-- class="absolute right-0 mt-2 w-40 bg-white shadow-md rounded-lg border">
                                        <button @click="editUser(admin)"
                                            class="block w-full text-left px-4 py-2 text-black text-sm hover:bg-gray-100">
                                            Edit
                                        </button>
                                        <button @click="changePassword(admin)"
                                            class="block w-full text-left px-4 py-2 text-black text-sm hover:bg-gray-100">
                                            Change Password
                                        </button> -->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p><strong>Email:</strong> {{ admin.email }}</p>
                                <p><strong>Role:</strong> {{ admin.role }}</p>
                                <p><strong>Status:</strong>
                                    <span :class="{ 
                                        'text-green-500': admin.status === 'Active', 
                                        'text-blue-500': admin.status === 'Super Admin',
                                        'text-red-500': admin.status === 'Inactive' 
                                    }">
                                        {{ admin.status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Modal -->
        <div v-if="isModalOpen" class="modal-overlay">
            <div class="modal-content">
                <h3 class="modal-title">Add New User</h3>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" v-model="newUser.name" class="form-control" placeholder="Enter name" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" v-model="newUser.email" class="form-control" placeholder="Enter email" />
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select v-model="newUser.role" class="form-control">
                            <option v-for="role in roles" :value="role" :key="role">
                                {{ role }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" v-model="newUser.password" class="form-control" 
                               placeholder="Enter password" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" @click="createUser" :disabled="isSubmitting">
                        <span v-if="isSubmitting" class="spinner-border spinner-border-sm"></span>
                        {{ isSubmitting ? 'Creating...' : 'Create' }}
                    </button>
                    <button class="btn btn-danger" @click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Sidebar from '@/components/AdminSidebar.vue';

const props = defineProps({
    admins: Array,
    roles: Array
});

const isSidebarVisible = ref(true);
const isModalOpen = ref(false);
const menuIndex = ref(null);
const isSubmitting = ref(false);

const newUser = ref({
    name: '',
    email: '',
    role: props.roles[0] || 'Admin',
    password: ''
});

const handleSidebarToggle = (isVisible) => {
    isSidebarVisible.value = isVisible;
};

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    resetForm();
};

import axios from 'axios';

const createUser = async () => {
    isSubmitting.value = true;

    try {
        const response = await axios.post('/api/admin/users', newUser.value);
        
        // Handle success
        closeModal();
    } catch (error) {
        if (error.response && error.response.data) {
            alert(Object.values(error.response.data.errors || {}).join('\n'));
        } else {
            alert('An error occurred. Please try again.');
        }
    } finally {
        isSubmitting.value = false;
    }
};


const resetForm = () => {
    newUser.value = { 
        name: '', 
        email: '', 
        role: props.roles[0] || 'Admin', 
        password: '' 
    };
};

const toggleMenu = (index) => {
    menuIndex.value = menuIndex.value === index ? null : index;
};

const editUser = (admin) => {
    alert(`Editing user: ${admin.name}`);
    menuIndex.value = null;
};

const changePassword = (admin) => {
    alert(`Changing password for: ${admin.name}`);
    menuIndex.value = null;
};
</script>

<style scoped>
/* Dark Theme */
#app.dark-theme {
    background-color: #121212;
    color: #e0e0e0;
    font-family: 'Arial', sans-serif;
    min-height: 100vh;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    background-color: #1e1e1e;
    transition: margin-left 0.3s ease, width 0.3s ease;
    width: calc(100% - 250px);
}

.main-content.sidebar-hidden {
    margin-left: 0;
    width: 100%;
}

.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.dashboard-banner {
    background-color: #1a1a1a;
    border-radius: 8px;
    overflow: hidden;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    height: auto;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 2px solid #444;
}

.transaction-card {
    background-color: #2c2c2c;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    padding: 15px;
    color: #e0e0e0;
    transition: transform 0.3s ease;
}

.transaction-card:hover {
    transform: translateY(-5px);
}

.card-header {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #f39c12;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #222;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.modal-title {
    color: #f39c12;
    font-size: 22px;
    margin-bottom: 15px;
}

.modal-body {
    text-align: left;
}

.form-group {
    margin-bottom: 10px;
}

.form-group label {
    display: block;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: none;
}

.modal-footer {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
}
</style>
