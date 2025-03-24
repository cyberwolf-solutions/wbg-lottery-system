<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Lottery Purchases</h2>
                </div>
                <div class="lottery-table">
                    <h3 class="mt-4">{{ lottery[0]?.lottery?.name }} Purchase Details</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Dashboard ID</th>
                                <th>Dashboard</th>
                                <th>Draw Number</th>
                                <th>Draw Date</th>
                                <th>Type</th>
                                <th>Percentage</th>
                                <th>View</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(dashboard, index) in lottery" :key="index">
                                <td>{{ dashboard.id }}</td>
                                <td>{{ dashboard.dashboard }}</td>
                                <td>{{ dashboard.draw_number }}</td>
                                <td>{{ dashboard.date }}</td>
                                <td>{{ dashboard.dashboardType }}</td>
                                <td>{{ calculatePercentage(dashboard.id) }}%</td>

                                <td>
                                    <button @click="viewDetails(dashboard)" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <button @click="confirmDelete(dashboard)" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Number Picker Modal -->
        <div v-if="isPickerOpen" class="modal-overlay">
            <div class="modal-content">
                <button @click="isPickerOpen = false" class="close-button">×</button>
                <h3>Picked Numbers</h3>
                <div class="number-grid">
                    <button v-for="num in numberOptions" :key="num"
                        :class="['number-button', { 'highlighted': isNumberPicked(num) }]" @click="selectNumber(num)">
                        {{ num }}
                    </button>

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
        lottery: Array,
        pickedNumbers: Array, // Accept pickedNumbers as a prop
    },
    data() {
        return {
            isSidebarVisible: true,
            isPickerOpen: false,
            selectedDashboard: null, // Store selected dashboard
            selectedPickedNumbers: [], // Store picked numbers for the selected dashboard
            numberOptions: Array.from({ length: 100 }, (_, i) => String(i).padStart(2, "0")),
        };
    },
    methods: {
        calculatePercentage(dashboardId) {
            const pickedNumbers = this.pickedNumbers[dashboardId] || []; // Get picked numbers for the specific dashboard
            const totalNumbers = this.numberOptions.length;
            const pickedCount = pickedNumbers.length;
            return ((pickedCount / totalNumbers) * 100).toFixed(1);
        },
        viewDetails(dashboard) {
            this.selectedDashboard = dashboard.id;
            this.selectedPickedNumbers = this.pickedNumbers[dashboard.id] || []; // Load numbers for selected dashboard
            this.isPickerOpen = true;
        },
        selectNumber(num) {
            this.selectedNumber = num;
        },
        confirmSelection() {
            alert(`Selected Number: ${this.selectedNumber}`);
            this.isPickerOpen = false;
        },
        isNumberPicked(num) {
            return this.selectedPickedNumbers.includes(num); // Check numbers for selected dashboard
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

/* Modal Overlay */
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
    max-width: 500px;
    width: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    color: #e0e0e0;
    position: relative;
    max-height: 80vh;
    /* Limit the height of the modal */
    overflow: hidden;
    /* Hide any overflow outside the modal */
}

/* Number Picker Grid */
.number-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
    gap: 8px;
    padding: 15px;
    max-height: 60vh;
    /* Set a specific height for the grid */
    overflow-y: auto;
    /* Enable scrolling within the grid if there are many numbers */
}

/* Adjusting the overflow behavior of the modal itself */
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
    overflow: auto;
    /* Allow overflow to be handled properly */
}

/* Number Button */
.number-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e0e0e0;
    background-color: #333;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Hover & Active Effects */
.number-button:hover {
    background-color: #555;
    transform: scale(1.1);
}

.number-button.highlighted {
    background-color: green;
    color: white;
}
</style>