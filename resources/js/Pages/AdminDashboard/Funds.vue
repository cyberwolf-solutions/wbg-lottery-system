<template>
    <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
        <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
            {{ responseMessage }}
        </div>
    </div>

    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Wallet Management</h2>
                </div>

                <div class="lottery-table">
                    <h3 class="mt-4">User Wallets</h3>

                    <!-- Search Bar -->
                    <input v-model="searchQuery" type="text" class="form-control mb-3"
                        placeholder="Search by user name or email..." @input="searchUsers" />

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Balance</th>
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
                                    <button @click="openAddModal(user)" class="btn btn-sm btn-success me-1">
                                        <i class="bi bi-plus-lg"></i> Add
                                    </button>
                                    <button @click="openDeductModal(user)" class="btn btn-sm btn-danger">
                                        <i class="bi bi-dash-lg"></i> Deduct
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

        <!-- Add Funds Modal -->
        <div v-if="showAddModal" class="modal-overlay">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>Add Funds</h3>
                    <button @click="closeAddModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>User:</label>
                        <input type="text" :value="selectedUser.name" class="form-control" readonly />
                    </div>
                    <div class="form-group mb-3">
                        <label>Current Balance:</label>
                        <input type="text" :value="safeFormatBalance(selectedUser.wallet?.available_balance)"
                            class="form-control" readonly />
                    </div>
                    <div class="form-group mb-3">
                        <label>Amount to Add:</label>
                        <input type="number" v-model="addAmount" min="0.01" step="0.01" class="form-control" />
                    </div>
                    <!-- <div class="form-group mb-3">
                        <label>Notes (Optional):</label>
                        <textarea v-model="addNotes" class="form-control" rows="3"></textarea>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button @click="addFunds" class="btn btn-success me-2">
                        <i class="bi bi-check-lg"></i> Confirm
                    </button>
                    <button @click="closeAddModal" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Deduct Funds Modal -->
        <div v-if="showDeductModal" class="modal-overlay">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>Deduct Funds</h3>
                    <button @click="closeDeductModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>User:</label>
                        <input type="text" :value="selectedUser.name" class="form-control" readonly />
                    </div>
                    <div class="form-group mb-3">
                        <label>Current Balance:</label>
                        <input type="text" :value="safeFormatBalance(selectedUser.wallet?.available_balance)"
                            class="form-control" readonly />
                    </div>
                    <div class="form-group mb-3">
                        <label>Amount to Deduct:</label>
                        <input type="number" v-model="deductAmount" min="0.01" step="0.01" class="form-control" />
                    </div>
                    <!-- <div class="form-group mb-3">
                        <label>Notes (Optional):</label>
                        <textarea v-model="deductNotes" class="form-control" rows="3"></textarea>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button @click="deductFunds" class="btn btn-danger me-2">
                        <i class="bi bi-check-lg"></i> Confirm
                    </button>
                    <button @click="closeDeductModal" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';
// import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        Sidebar,
    },
    props: {
        users: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            isSidebarVisible: true,
            searchQuery: '',
            showAddModal: false,
            showDeductModal: false,
            selectedUser: {},
            addAmount: '',
            deductAmount: '',
            addNotes: '',
            deductNotes: '',
            currentPage: 1,
            perPage: 10,
            searchTimeout: null,

            responseMessage: null,
            responseClass: 'bottom-response',

        };
    },
    computed: {
        filteredUsers() {
            if (!this.users.data) return [];
            return this.users.data.filter(user => {
                const search = this.searchQuery.toLowerCase();
                return (
                    user.name.toLowerCase().includes(search) ||
                    user.email.toLowerCase().includes(search)
                );
            });
        },
        paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredUsers.slice(start, start + this.perPage);
        },
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
        showResponse(message, position = 'bottom') {
            this.responseMessage = message;
            this.responseClass = position === 'bottom' ? 'bottom-response' : 'top-response';

            setTimeout(() => {
                this.responseMessage = null;
            }, 3000);
        },

        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        searchUsers() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                Inertia.get(route('admin.funds.index'), { search: this.searchQuery }, {
                    preserveState: true,
                    replace: true
                });
            }, 500);
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        openAddModal(user) {
            this.selectedUser = user;
            this.addAmount = '';
            this.addNotes = '';
            this.showAddModal = true;
        },
        openDeductModal(user) {
            this.selectedUser = user;
            this.deductAmount = '';
            this.deductNotes = '';
            this.showDeductModal = true;
        },
        closeAddModal() {
            this.showAddModal = false;
        },
        closeDeductModal() {
            this.showDeductModal = false;
        },
        async addFunds() {
            if (!this.addAmount || parseFloat(this.addAmount) <= 0) {
                this.showResponse('Please enter a valid amount', 'bottom');
                return;
            }

            try {
                const response = await axios.post(`/api/admin/funds/${this.selectedUser.id}/update`, {
                    amount: this.addAmount,
                    type: 'add',
                    notes: this.addNotes
                });

                this.showResponse('Funds added successfully!', 'bottom');
                this.closeAddModal();
                window.location.reload();

            } catch (error) {
                this.showResponse('Failed to add funds. Please try again.', 'bottom');
            }
        },

        async deductFunds() {
            if (!this.deductAmount || parseFloat(this.deductAmount) <= 0) {
                this.showResponse('Please enter a valid amount', 'bottom');
                return;
            }

            const currentBalance = parseFloat(this.selectedUser.wallet?.available_balance || 0);
            if (parseFloat(this.deductAmount) > currentBalance) {
                this.showResponse('Deduction amount cannot exceed available balance', 'bottom');
                return;
            }

            try {
                const response = await axios.post(`/api/admin/funds/${this.selectedUser.id}/update`, {
                    amount: this.deductAmount,
                    type: 'deduct',
                    notes: this.deductNotes
                });

                this.showResponse('Funds deducted successfully!', 'bottom');
                this.closeDeductModal();
                window.location.reload();

            } catch (error) {
                this.showResponse('Failed to deduct funds. Please try again.', 'bottom');
            }
        }




    }
};
</script>

<style scoped>
/* Main Layout */
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

/* Table Styles */
.lottery-table {
    margin-top: 20px;
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
    margin-bottom: 20px;
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
    font-weight: 600;
}

tbody tr:hover {
    background-color: #444;
}

/* Form Controls */
.form-control {
    background-color: #2c2c2c;
    border: 1px solid #444;
    color: #e0e0e0;
    padding: 8px 12px;
    border-radius: 4px;
    width: 100%;
}

.form-control:focus {
    border-color: #1e90ff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(30, 144, 255, 0.2);
}

textarea.form-control {
    min-height: 100px;
}

/* Buttons */
.btn {
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.875rem;
}

.btn-primary {
    background-color: #1e90ff;
    border-color: #1e90ff;
    color: white;
}

.btn-primary:hover {
    background-color: #1a7fd9;
    border-color: #1a7fd9;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #218838;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #c82333;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #5a6268;
}

.btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

/* Pagination */
.d-flex.justify-content-between {
    align-items: center;
}

/* Modal Styles */
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

.modal-content {
    background-color: #2c2c2c;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    color: #e0e0e0;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #444;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #444;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-close {
    background: none;
    border: none;
    color: #e0e0e0;
    font-size: 1.25rem;
    cursor: pointer;
    opacity: 0.7;
}

.btn-close:hover {
    opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }

    table {
        display: block;
        overflow-x: auto;
    }

    .modal-content {
        width: 95%;
        margin: 0 auto;
    }
}
</style>