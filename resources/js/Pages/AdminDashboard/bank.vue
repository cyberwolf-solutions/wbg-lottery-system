<template>
    <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
        <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
            {{ responseMessage }}
        </div>
    </div>

    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Bank Details Section -->
            <div class="wallet-card">
                <div class="wallet-section">
                    <span class="wallet-label">USDT Deposit Wallet</span>
                    <p class="wallet-value">{{ bankDetails.bank || 'N/A' }}</p>
                </div>

                <div class="wallet-section">
                    <span class="wallet-label">Wallet address and details</span>
                    <pre class="wallet-value wallet-address">{{ bankDetails.number || 'N/A' }}</pre>
                </div>

                <button @click="editBankDetails" class="btn btn-primary btn-sm mt-3">Edit</button>
            </div>



            <!-- Edit Bank Details Modal -->
            <div v-if="isEditModalOpen" class="modal-overlay">
                <div class="edit-modal">
                    <h3 class="modal-title">Edit Wallet Details</h3>
                    <form @submit.prevent="updateBankDetails">
                        <div class="modal-form-group">
                            <label for="bankName">USDT Deposit Wallet</label>
                            <input type="text" id="bankName" v-model="bankDetails.bank" required />
                        </div>

                        <div class="modal-form-group">
                            <label for="accountNumber">Wallet address and details</label>
                            <textarea id="accountNumber" v-model="bankDetails.number" rows="4" required></textarea>
                        </div>

                        <div class="modal-actions">
                            <button type="submit" class="btn-save">💾 Save</button>
                            <button type="button" @click="closeEditModal" class="btn-cancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Modal for update -->
            <div v-if="isUpdateModalOpen" class="modal-overlay">
                <div class="modal-content">
                    <h3>{{ updateSuccess ? 'Success' : 'Error' }}</h3>
                    <p>{{ updateMessage }}</p>
                    <button @click="closeUpdateModal" class="btn btn-primary">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';
import { ref } from 'vue';

export default {
    components: {
        Sidebar,
    },
    props: {
        bankDetails: Object,
    },
    data() {
        return {
            isSidebarVisible: true,
            isEditModalOpen: false,
            isUpdateModalOpen: false,
            updateSuccess: false,
            updateMessage: '',

            responseMessage: ref(null),
            responseClass: ref('bottom-response'),
        };
    },
    methods: {
        showResponse(message, position = 'bottom') {
            this.responseMessage = message;
            this.responseClass = position === 'bottom' ? 'top-response' : 'bottom-response';

            setTimeout(() => {
                this.responseMessage = null;
            }, 3000);
        },

        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },

        editBankDetails() {
            this.isEditModalOpen = true;
        },

        closeEditModal() {
            this.isEditModalOpen = false;
        },

        async updateBankDetails() {
            try {
                // API call to update bank details (replace with actual API call)
                const response = await axios.put(`/api/admin/bank-details/update`, this.bankDetails);
                this.updateSuccess = true;
                this.updateMessage = response.data.message || 'Bank details updated successfully!';
                this.showResponse(this.updateMessage, 'bottom');
            } catch (error) {
                this.updateSuccess = false;
                this.updateMessage = error.response?.data?.message || 'Failed to update bank details.';
                this.showResponse(this.updateMessage, 'bottom');
            }
            this.isUpdateModalOpen = true;
            this.closeEditModal();
        },

        closeUpdateModal() {
            this.isUpdateModalOpen = false;
        }
    },
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
}

.edit-modal {
    width: 100%;
    max-width: 450px;
    background: rgba(40, 40, 40, 0.95);
    border: 1px solid #444;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    color: #e0e0e0;
}

.modal-title {
    margin-bottom: 20px;
    font-size: 20px;
    font-weight: 600;
    color: #ffffff;
}

.modal-form-group {
    margin-bottom: 18px;
}

.modal-form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 6px;
    color: #bbb;
}

.modal-form-group input,
.modal-form-group textarea {
    width: 100%;
    background: #1e1e1e;
    border: 1px solid #555;
    border-radius: 6px;
    padding: 10px;
    font-size: 14px;
    color: #e0e0e0;
    resize: vertical;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 15px;
}

.btn-save {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-cancel {
    background-color: #555;
    color: white;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-save:hover {
    background-color: #218838;
}

.btn-cancel:hover {
    background-color: #444;
}

.wallet-card {
    width: 100%;
    max-width: 100%;
    background-color: #2c2c2c;
    border: 1px solid #444;
    border-radius: 10px;
    padding: 15px;
    margin-top: 20px;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
}

.wallet-section {
    margin-bottom: 20px;
}

.wallet-label {
    display: block;
    font-size: 14px;
    color: #888;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.wallet-value {
    font-size: 16px;
    color: #e0e0e0;
    background-color: #1a1a1a;
    padding: 10px;
    border-radius: 6px;
    white-space: pre-line;
    word-wrap: break-word;
}

.wallet-address {
    font-family: monospace;
    font-size: 14px;
}

/* Optional: Ensure content doesn’t overflow horizontally on small screens */
@media (max-width: 576px) {
    .wallet-card {
        padding: 10px;
    }

    .wallet-value {
        font-size: 14px;
    }
}


.btn-edit {
    background-color: #1e90ff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-edit:hover {
    background-color: #0d74d1;
}

.wallet-body {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.wallet-row {
    display: flex;
    flex-direction: column;
}

.label {
    font-weight: 600;
    color: #bbbbbb;
    font-size: 14px;
    margin-bottom: 2px;
}

.value {
    font-size: 15px;
    color: #e0e0e0;
    word-break: break-word;
}

.multiline {
    white-space: pre-line;
}

.top-response {
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    position: fixed;
    z-index: 999;
}

.bottom-response {
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    position: fixed;
    z-index: 999;
}

.bg-blue-500 {
    background-color: #3b82f6;
}

.text-white {
    color: white;
}

.p-3 {
    padding: 12px;
}

.rounded-lg {
    border-radius: 8px;
}

.shadow-md {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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