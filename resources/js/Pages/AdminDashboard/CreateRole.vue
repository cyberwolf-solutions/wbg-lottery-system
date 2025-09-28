<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Create New Role</h2>
                    <!-- <router-link to="/admin/roles" class="btn btn-secondary">
                        Back to Roles
                    </router-link> -->
                </div>

                <div class="lottery-table">

                    <div class="alert alert-danger" v-if="errors.length">
                        <ul>
                            <li v-for="error in errors" :key="error">{{ error }}</li>
                        </ul>
                    </div>

                    <div class="role-name-container mb-4">
                        <label for="roleName" class="form-label">Role Name:</label>
                        <input type="text" id="roleName" v-model="roleName" placeholder="Enter role name"
                            class="form-control" required>
                    </div>

                    <h3 class="mt-4 mb-3">Assign Permissions</h3>
                    <table class="table table-bordered">
                        <thead class="table-dark">

                            <tr>
                                <th>Module</th>
                                <th>Permissions</th>
                                <th>
                                    <input type="checkbox" @change="toggleAllPermissions"
                                        :checked="allPermissionsSelected">
                                    Select All
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(module, index) in permissionModules" :key="index">
                                <td>{{ module.name }}</td>
                                <td>
                                    <div class="permissions d-flex flex-wrap gap-3">
                                        <div v-for="(permission, i) in module.permissions" :key="i" class="form-check">
                                            <input type="checkbox" :id="`permission-${permission.name}`"
                                                :value="permission.name" v-model="selectedPermissions"
                                                class="form-check-input">
                                            <label :for="`permission-${permission.name}`" class="form-check-label">
                                                {{ permission.action }}
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="checkbox" @change="toggleModulePermissions(module)"
                                        :checked="areAllModulePermissionsSelected(module)">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button class="btn btn-success create-button mt-4" @click="createRole" :disabled="isSubmitting">
                    <span v-if="isSubmitting" class="spinner-border spinner-border-sm" role="status"></span>
                    {{ isSubmitting ? 'Creating...' : 'Create Role' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Sidebar from '@/components/AdminSidebar.vue';

const props = defineProps({
    permissionModules: Array
});

const isSidebarVisible = ref(true);
const roleName = ref('');
const selectedPermissions = ref([]);
const errors = ref([]);
const isSubmitting = ref(false);

const allPermissionsSelected = computed(() => {
    const allPermissions = props.permissionModules.flatMap(
        module => module.permissions.map(p => p.name)
    );
    return allPermissions.length > 0 &&
        allPermissions.every(p => selectedPermissions.value.includes(p));
});

const handleSidebarToggle = (isVisible) => {
    isSidebarVisible.value = isVisible;
};

const toggleAllPermissions = (event) => {
    if (event.target.checked) {
        selectedPermissions.value = props.permissionModules.flatMap(
            module => module.permissions.map(p => p.name)
        );
    } else {
        selectedPermissions.value = [];
    }
};

const toggleModulePermissions = (module) => {
    const modulePermissions = module.permissions.map(p => p.name);
    const allSelected = modulePermissions.every(p =>
        selectedPermissions.value.includes(p)
    );

    if (allSelected) {
        // Remove all permissions for this module
        selectedPermissions.value = selectedPermissions.value.filter(
            p => !modulePermissions.includes(p)
        );
    } else {
        // Add all permissions for this module
        selectedPermissions.value = [
            ...new Set([...selectedPermissions.value, ...modulePermissions])
        ];
    }
};

const areAllModulePermissionsSelected = (module) => {
    return module.permissions.every(p =>
        selectedPermissions.value.includes(p.name)
    );
};
const createRole = async () => {
    errors.value = [];

    if (!roleName.value.trim()) {
        errors.value.push('Role name is required');
        return;
    }

    if (selectedPermissions.value.length === 0) {
        errors.value.push('At least one permission must be selected');
        return;
    }

    isSubmitting.value = true;

    try {
        const response = await axios.post('/api/admin/admin/roles', {
            name: roleName.value,
            permissions: selectedPermissions.value
        }, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        });

        // Handle success
        if (response.data.success) {
            // Show success message
            showSuccessToast('Role created successfully');

            // Redirect to roles list after a short delay
            setTimeout(() => {
                window.location.href = ('/api/admin/Roles')
            }, 1500);
        } else {
            errors.value = response.data.errors || ['Failed to create role'];
        }
    } catch (error) {
        // Handle error
        if (error.response) {
            // The request was made and the server responded with a status code
            if (error.response.data.errors) {
                errors.value = Object.values(error.response.data.errors).flat();
            } else {
                errors.value = [error.response.data.message || 'An error occurred'];
            }
        } else if (error.request) {
            // The request was made but no response was received
            errors.value = ['No response from server'];
        } else {
            // Something happened in setting up the request
            errors.value = [error.message];
        }
    } finally {
        isSubmitting.value = false;
    }
};

const showSuccessToast = (message) => {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'success-toast';
    toast.textContent = message;

    // Add to DOM
    document.body.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
};
</script>
<style scoped>
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
    transition: margin-left 0.3s ease;
}

.main-content.sidebar-hidden {
    margin-left: 0;
}

.dashboard-banner {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.lottery-table h3 {
    color: #e0e0e0;
    font-size: 20px;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: #2c2c2c;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

th,
td {
    padding: 12px 15px;
    text-align: left;
    color: #e0e0e0;
    border-bottom: 1px solid #444;
}

th {
    background-color: #333;
}

.permissions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.create-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 24px;
    font-size: 16px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.2s;
}

.create-button:hover {
    background-color: #218838;
    transform: scale(1.05);
}


.role-name-container {
    margin-bottom: 15px;
}

.role-name-container label {
    font-size: 16px;
    color: #e0e0e0;
    margin-right: 10px;
}

.role-name-input {
    padding: 8px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 100%;
    max-width: 300px;
    background-color: #2c2c2c;
    color: #fff;
}
</style>