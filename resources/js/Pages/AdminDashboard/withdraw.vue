<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Dashboard Widgets -->
            <div class="dashboard-banner">
                <!-- Top Navbar Section -->
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Withdraw</h2>

                </div>

                <!-- Lottery Table Section -->
                <div class="lottery-table">
                    <h3 class="mt-4">Request Details:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Date</th>
                                <th>Ammount</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(deposit, index) in credits" :key="index">

                                <td><a href="#">{{ deposit.wallet?.user?.name || 'N/A' }}</a></td>

                                <td>{{ deposit.withdrawal_date }}</td>
                                <td>{{ deposit.amount }}</td>

                                <td>
                                    <!-- Approve Button -->
                                    <button v-if="deposit.status === 0" @click="editDashboard(deposit)"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-check"></i>
                                    </button>

                                    <!-- Approved Label -->
                                    <span v-if="deposit.status === 1" class="text-success">Approved</span>

                                    <!-- Declined Label -->
                                    <span v-else-if="deposit.status === 2" class="text-danger">Declined</span>

                                    <!-- Delete Button -->
                                    <button v-if="deposit.status === 0" @click="confirmDelete(deposit)"
                                        class="btn btn-danger btn-sm mx-2">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        </tbody>


                    </table>
                </div>

                <!-- Confirmation Modal for approving -->
                <div v-if="isEditModalOpen" class="modal-overlay">
                    <div class="modal-content">
                        <h3>Are you sure you want to Accept this request</h3>
                        <div class="row align-items-center justify-content-center">
                            <button @click="deleteDashboard" class=" btn btn-danger mx-2 col-5">Yes</button>
                            <button @click="closeDeleteModal" class="btn btn-secondary col-5">Cancel</button>
                        </div>

                    </div>
                </div>


                <!-- Confirmation Modal for Deleting -->
                <div v-if="isDeleteModalOpen" class="modal-overlay">
                    <div class="modal-content">
                        <h3>Are you sure you want to decline this request?</h3>
                        <textarea v-model="declineMessage" class="form-control"
                            placeholder="Enter your reason..."></textarea>
                        <div class="row align-items-center justify-content-center mt-3">
                            <button @click="declineRequest" class="btn btn-danger mx-2 col-5">Decline</button>
                            <button @click="closeDeleteModal" class="btn btn-secondary col-5">Cancel</button>
                        </div>
                    </div>
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
    props: {
        credits: Array, // Receive Credit data from Laravel
    },
    data() {
        return {
            isSidebarVisible: true,
            isModalOpen: false,
            isEditModalOpen: false,
            isDeleteModalOpen: false,


            editingDashboard: {},
        };
    },

    methods: {

        confirmDelete(dashboard) {
            this.editingDashboard = dashboard;
            this.isDeleteModalOpen = true;
            this.declineMessage = "";
        },
        closeDeleteModal() {
            this.isDeleteModalOpen = false;
        },
        declineRequest() {
            if (!this.declineMessage.trim()) {
                alert("Please enter a reason for declining the request.");
                return;
            }

            axios.post(`/api/admin/withdraw/decline/${this.editingDashboard.id}`, {
                reason: this.declineMessage
            })
                .then(response => {
                    alert(response.data.message);
                    this.editingDashboard.status = 2; // Update UI immediately (Assuming 2 is the declined status)
                    this.closeDeleteModal();
                })
                .catch(error => {
                    alert(error.response?.data?.message || "Failed to decline deposit.");
                });
        },

        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        openModal() {
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
        },

        editDashboard(deposit) {
            if (!confirm("Are you sure you want to approve this request?")) {
                return;
            }

            axios.post(`/api/admin/withdraw/approve/${deposit.id}`)
                .then(response => {
                    alert(response.data.message);
                    deposit.status = 1; // Update UI immediately
                })
                .catch(error => {
                    alert(error.response.data.message || "Failed to approve deposit.");
                });
        }
        ,
        closeEditModal() {
            this.isEditModalOpen = false;
        },
        updateDashboard() {
            const index = this.dashboards.findIndex(d => d.id === this.editingDashboard.id);
            if (index !== -1) {
                this.dashboards.splice(index, 1, this.editingDashboard);
            }
            this.closeEditModal();
        },
        // confirmDelete(dashboard) {
        //     this.editingDashboard = dashboard;
        //     this.isDeleteModalOpen = true;
        // },
        // closeDeleteModal() {
        //     this.isDeleteModalOpen = false;
        // },
        deleteDashboard() {
            this.dashboards = this.dashboards.filter(d => d.id !== this.editingDashboard.id);
            this.closeDeleteModal();
        },
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