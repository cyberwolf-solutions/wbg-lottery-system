<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Roles</h2>
                </div>

                <div class="lottery-table">
                    <h3 class="mt-4">Assign Permissions to Role</h3>
                    <div class="role-name-container">
                        <label for="roleName">Role Name:</label>
                        <input type="text" id="roleName" v-model="roleName" placeholder="Enter role name" class="role-name-input">
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Permission</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(module, index) in modules" :key="index">
                                <td>{{ module.name }}</td>
                                <td>
                                    <div class="permissions">
                                        <label v-for="(permission, i) in module.permissions" :key="i">
                                            <input type="checkbox" :value="permission" v-model="module.selectedPermissions"> 
                                            {{ permission }}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <button class="btn btn-success create-button">Create Role</button>
            </div>
        </div>

        <router-view />
    </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';

export default {
    components: {
        Sidebar,
    },
    data() {
        return {
            isSidebarVisible: true,
            modules: [
                { name: 'Users', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete', 'Change status'], selectedPermissions: [] },
                { name: 'Roles', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete'], selectedPermissions: [] },
                { name: 'Employees', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete'], selectedPermissions: [] },
                { name: 'Customers', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete'], selectedPermissions: [] },
                { name: 'Customers', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete'], selectedPermissions: [] },
                { name: 'Customers', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete'], selectedPermissions: [] },
                { name: 'Customers', permissions: ['Manage', 'Create', 'View', 'Edit', 'Delete'], selectedPermissions: [] },
                
            ]
        };
    },
    methods: {
        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        }
    }
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

th, td {
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