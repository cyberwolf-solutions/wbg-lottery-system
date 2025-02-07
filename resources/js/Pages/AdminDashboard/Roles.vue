<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Dashboard Widgets -->
            <div class="dashboard-banner">
                <!-- Top Navbar Section -->
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Roles</h2>
                    <button class="btn btn-primary create-button" @click="openAddRolePage">
                        <i class="fa fa-plus"></i>
                    </button>

                </div>

                <!-- Lottery Table Section -->
                <div class="lottery-table">
                    <h3 class="mt-4">Role Details</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permission</th>
                                <th>Action</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(dashboard, index) in dashboards" :key="index">
                                <td><a href="#">{{ dashboard.Role }}</a></td>
                                <td>{{ dashboard.permission }}</td>
                                <td><!-- Edit Button -->
                                    <button @click="editDashboard(dashboard)" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button @click="confirmDelete(dashboard)" class="btn btn-danger btn-sm mx-2">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>


                            </tr>
                        </tbody>
                    </table>
                </div>


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
            isModalOpen: false,
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            dashboards: [
                { id: 1, Role: 'Role 1', permission: 'Add , Delete , Update', Action: '' },
                { id: 2, Role: 'Role 2', permission: 'Add , Delete , Update', Action: '' },
                { id: 3, Role: 'Role 3', permission: 'Add , Delete , Update', Action: '' },
            ],
            newDashboard: {
                price: '',
                date: '',
                draw: '',
                drawNumber: '',
            },
            editingDashboard: {},
        };
    },
    methods: {
        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        openAddRolePage() {
            window.location.href = "http://127.0.0.1:8000/api/Roles/Add";
        }

    },
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

.lottery-name {
    font-size: 24px;
    color: #e0e0e0;
}

.create-dashboard-btn {
    background-color: #5a5a5a;
    color: #e0e0e0;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}

.create-dashboard-btn:hover {
    background-color: #777;
}

.lottery-prices,
.dashboards {
    margin-top: 20px;
}

.lottery-prices h3,
.dashboards h3 {
    color: #e0e0e0;
    font-size: 20px;
}

.lottery-prices ul,
.dashboards ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.lottery-prices li,
.dashboards li {
    color: #b0b0b0;
    font-size: 16px;
}

.dashboards a {
    color: #1e90ff;
    text-decoration: none;
}

.dashboards a:hover {
    text-decoration: underline;
}

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
    overflow: hidden;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    height: auto;
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

td a {
    color: #1e90ff;
    text-decoration: none;
}

td a:hover {
    text-decoration: underline;
}

tbody tr:hover {
    background-color: #444;
}
</style>
<style scoped>
/* Modal Overlay */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Modal Content */
.modal-content {
    background-color: #333;
    padding: 20px;
    border-radius: 8px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    color: #e0e0e0;
}

h3 {
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    font-size: 14px;
    background-color: #444;
    color: #e0e0e0;
    border: 1px solid #555;
    border-radius: 5px;
}

button {
    padding: 10px 20px;
    margin-top: 10px;
    border-radius: 5px;
}

button.btn-success {
    background-color: #28a745;
    color: #fff;
}

button.btn-danger {
    background-color: #dc3545;
    color: #fff;
}

button:hover {
    opacity: 0.9;
}
</style>