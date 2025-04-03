<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Player Details</h2>
                </div>

                <div class="lottery-table">
                    <h3 class="mt-4">Players List</h3>

                    <!-- Search Bar -->
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="form-control mb-3"
                        placeholder="Search by user name..."
                    />

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Wallet Balance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in paginatedUsers" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>${{ safeFormatBalance(user.wallet?.available_balance) }}</td>
                                <td>
                                    <button @click="openWalletModal(user)" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View Wallet
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-between mt-3">
                        <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-sm btn-primary">
                            Previous
                        </button>
                        <span>Page {{ currentPage }} of {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages" class="btn btn-sm btn-primary">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Details Modal -->
        <div v-if="showWalletModal" class="modal-overlay">
            <div class="modal-content" style="max-width: 800px;">
                <div class="modal-header">
                    <h3>Wallet Details for {{ selectedUser?.name }}</h3>
                    <button @click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Summary</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Available Balance:</strong> ${{
                                        safeFormatBalance(selectedUser?.wallet?.available_balance) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Recent Transactions</h5>
                            <div class="transaction-list">
                                <div v-if="!selectedUser?.wallet?.transactions?.length" class="alert alert-info">
                                    No transactions found
                                </div>
                                <table v-else class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="tx in selectedUser?.wallet?.transactions" :key="tx.id">
                                            <td>{{ formatDate(tx.created_at) }}</td>
                                            <td>{{ tx.type }}</td>
                                            <td :class="{ 'text-success': tx.amount > 0, 'text-danger': tx.amount < 0 }">
                                                ${{ safeFormatBalance(Math.abs(tx.amount)) }}
                                            </td>
                                            <td>{{ tx.description }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="closeModal" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';

export default {
    components: {
        Sidebar,
    },
    props: {
        users: {
            type: Array,
            required: true,
            default: () => []
        }
    },
    data() {
        return {
            isSidebarVisible: true,
            showWalletModal: false,
            selectedUser: null,
            searchQuery: "",
            currentPage: 1,
            perPage: 10
        };
    },
    computed: {
        // Filter users based on search query
        filteredUsers() {
            return this.users.filter(user => 
                user.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },

        // Paginate the filtered users
        paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredUsers.slice(start, start + this.perPage);
        },

        // Calculate total number of pages
        totalPages() {
            return Math.ceil(this.filteredUsers.length / this.perPage);
        }
    },
    methods: {
        safeFormatBalance(value) {
            if (value === null || value === undefined) return '0.00';
            const num = Number(value);
            return isNaN(num) ? '0.00' : num.toFixed(2);
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString();
        },

        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },

        openWalletModal(user) {
            this.selectedUser = user;
            this.showWalletModal = true;
        },

        closeModal() {
            this.showWalletModal = false;
            this.selectedUser = null;
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        }
    }
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    max-height: 80vh;
    overflow-y: auto;
}

.transaction-list {
    max-height: 300px;
    overflow-y: auto;
}

.text-success {
    color: green;
}

.text-danger {
    color: red;
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