<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Dashboard Widgets -->
            <div class="dashboard-banner">
                <!-- Top Navbar Section -->

                <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
                    <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
                        {{ responseMessage }}
                    </div>
                </div>

                <!-- Lottery Tables Section -->
                <div v-for="lottery in lotteries" :key="lottery.id" class="lottery-table mb-8">
                    <h3 class="mt-4">{{ lottery.name }} Dashboards</h3>

                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Dashboard</th>
                                <th>Price</th>
                                <th>Draw Date</th>
                                <th>Draw Number</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dashboard in paginatedDashboards(lottery.id)" :key="dashboard.id">
                                <td>{{ dashboard.dashboard }}</td>
                                <td>{{ dashboard.price }}</td>
                                <td>{{ formatDate(dashboard.date) }}</td>
                                <td>{{ dashboard.draw_number }}</td>
                                <td>{{ dashboard.dashboardType }}</td>
                                <td>
                                    <button @click="editDashboard(dashboard)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                        Edit Date
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div class="pagination-controls mt-4">
                        <button @click="prevPage(lottery.id)" :disabled="currentPages[lottery.id] === 1"
                            class="pagination-button">
                            Previous
                        </button>
                        <span class="page-info">
                            Page {{ currentPages[lottery.id] || 1 }} of {{ totalPages(lottery.id) }}
                        </span>
                        <button @click="nextPage(lottery.id)"
                            :disabled="currentPages[lottery.id] === totalPages(lottery.id)" class="pagination-button">
                            Next
                        </button>
                    </div>
                </div>

                <div v-if="isEditModalOpen"
                    class="modal-overlay   fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="modal-content bg-black p-6 rounded-lg shadow-lg w-1/3">
                        <h3 class="text-xl font-bold mb-4">Edit Dashboard Date</h3>
                        <form @submit.prevent="updateDashboard">
                            <div class="form-group mb-4">
                                <label for="edit-date" class="block text-gray-700 mb-2">Date:</label>
                                <input 
                        type="date" 
                        v-model="editingDashboard.date" 
                        id="edit-date" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded" 
                        :min="minDate"
                        :disabled="isLoading"
                        @change="checkHoliday"
                    />
                    <p v-if="isDateDisabled(editingDashboard.date)" class="text-red-500 mt-2">
                        This date is a holiday and cannot be selected.
                    </p>

                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2"
                                    :disabled="isLoading">
                                    <span v-if="isLoading">Updating...</span>
                                    <span v-else>Update Date</span>
                                </button>
                                <button type="button" @click="closeEditModal"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                    :disabled="isLoading">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import Sidebar from '@/components/AdminSidebar.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    components: {
        Sidebar,
    },
    props: {
        lotteries: {
            type: Array,
            default: () => []
        },
        holidays: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            isSidebarVisible: true,
            isEditModalOpen: false,
            currentPages: {}, // Stores current page for each lottery
            itemsPerPage: 5,
            editingDashboard: {},
            responseMessage: ref(null),
            responseClass: ref('bottom-response'),
            holidayDates: this.holidays

        };
    },
    methods: {

        isDateDisabled(date) {
            // Convert date to YYYY-MM-DD format for comparison
            const dateStr = new Date(date).toISOString().split('T')[0];
            return this.holidayDates.includes(dateStr);
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },

        paginatedDashboards(lotteryId) {
            const lottery = this.lotteries.find(l => l.id === lotteryId);
            if (!lottery || !lottery.dashboards) return [];

            const currentPage = this.currentPages[lotteryId] || 1;
            const start = (currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;

            return lottery.dashboards.slice(start, end);
        },

        totalPages(lotteryId) {
            const lottery = this.lotteries.find(l => l.id === lotteryId);
            if (!lottery || !lottery.dashboards) return 0;

            return Math.ceil(lottery.dashboards.length / this.itemsPerPage);
        },

        nextPage(lotteryId) {
            if (!this.currentPages[lotteryId]) {
                this.currentPages[lotteryId] = 1;
            }
            if (this.currentPages[lotteryId] < this.totalPages(lotteryId)) {
                this.currentPages[lotteryId]++;
            }
        },

        prevPage(lotteryId) {
            if (!this.currentPages[lotteryId]) {
                this.currentPages[lotteryId] = 1;
            }
            if (this.currentPages[lotteryId] > 1) {
                this.currentPages[lotteryId]--;
            }
        },

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

        editDashboard(dashboard) {
            this.editingDashboard = { ...dashboard };
            this.isEditModalOpen = true;
        },

        closeEditModal() {
            this.isEditModalOpen = false;
        },
        checkHoliday(event) {
            const selectedDate = event.target.value;
            if (this.holidays.includes(selectedDate)) {
                this.showResponse('Selected date is a holiday. Please choose another date.', 'top');
                this.editingDashboard.date = '';
            }
        }
        ,

        async updateDashboard() {
            this.isLoading = true;
            try {
                const response = await axios.put(
                    `/api/admin/dashboards/${this.editingDashboard.id}`,
                    {
                        date: this.editingDashboard.date
                    },
                    {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }
                );

                if (response.data.success) {
                    this.showResponse(response.data.message);

                    // Refresh the data to get all updated dashboards
                    router.reload({
                        only: ['lotteries'],
                        preserveState: true
                    });

                    this.closeEditModal();
                }
            } catch (error) {
                console.error('Error updating dashboard:', error);
                let errorMessage = 'Error updating dashboard date';

                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }

                this.showResponse(errorMessage, 'top');
            } finally {
                this.isLoading = false;
            }
        },
    },
    computed: {
        minDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = (today.getMonth() + 1).toString().padStart(2, '0');
            const day = today.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        
    },
    mounted() {
        // Initialize current pages for each lottery
        this.lotteries.forEach(lottery => {
            this.currentPages[lottery.id] = 1;
        });
    }
};
</script>


<style scoped>
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

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin: 1rem 0;
}

.pagination-button {
    padding: 0.5rem 1rem;
    background-color: #60c8f2;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.pagination-button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.page-info {
    font-weight: bold;
}
</style>