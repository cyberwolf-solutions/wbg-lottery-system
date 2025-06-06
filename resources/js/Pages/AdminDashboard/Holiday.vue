<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Holiday Management</h2>
                </div>

                <div class="holiday-table">
                    <h3 class="mt-4 d-flex justify-content-between align-items-center">
                        Holiday List
                        <button @click="showAddHolidayModal = true" class="btn btn-sm btn-success">
                            + Add Holiday
                        </button>
                    </h3>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Holiday Name</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(holiday, index) in holidays" :key="holiday.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ holiday.name }}</td>
                                <td>{{ formatDate(holiday.date) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Holiday Modal -->
        <div v-if="showAddHolidayModal" class="modal-overlay">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>Add New Holiday</h3>
                    <button @click="closeAddHolidayModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Holiday Name</label>
                        <input v-model="newHoliday.name" type="text" class="form-control" />
                    </div>
                    <div class="form-group mb-2">
                        <label>Date</label>
                        <input v-model="newHoliday.date" type="date" class="form-control" />
                    </div>
                    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
                </div>
                <div class="modal-footer">
                    <button @click="addHoliday" class="btn btn-primary">Save</button>
                    <button @click="closeAddHolidayModal" class="btn btn-secondary">Cancel</button>
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
        holidays: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    data() {
        return {
            isSidebarVisible: true,
            showAddHolidayModal: false,
            newHoliday: {
                name: '',
                date: '',
            },
            errorMessage: '',
        };
    },
    methods: {
        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleDateString();
        },
        closeAddHolidayModal() {
            this.showAddHolidayModal = false;
            this.newHoliday = { name: '', date: '' };
            this.errorMessage = '';
        },
        async addHoliday() {
            const { name, date } = this.newHoliday;

            this.errorMessage = '';

            if (!name || !date) {
                this.errorMessage = 'Please provide both name and date.';
                return;
            }

            try {
                // Check for duplicates locally
                const exists = this.holidays.some(
                    h => h.name.toLowerCase() === name.toLowerCase() && h.date === date
                );

                if (exists) {
                    this.errorMessage = 'This holiday already exists.';
                    return;
                }

                // Send POST request to your Laravel backend
                const response = await axios.post('/api/admin/holiday/store', {
                    name,
                    date
                });

                // On successful response, push to local list
                this.holidays.push(response.data);
                this.closeAddHolidayModal();
            } catch (error) {
                if (error.response && error.response.status === 409) {
                    this.errorMessage = 'Holiday to this date already exists.';
                } else {
                    this.errorMessage = 'An error occurred while adding the holiday.';
                    console.error(error);
                }
            }
        },
    },
};
</script>

<style scoped>
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    color: white;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.bg-success {
    background-color: #198754;
}

.bg-danger {
    background-color: #dc3545;
}

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