<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Dashboard Widgets -->
            <div class="dashboard-banner">
                <!-- Top Navbar Section -->
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Lottery</h2>
                    <h2 class="lottery-name fw-bold text-danger">Results</h2>
                </div>

                <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
                    <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
                        {{ responseMessage }}
                    </div>
                </div>


                <!-- Lottery Results Form -->
                <div class="lottery-form">
                    <h3 class="mt-4">Add Lottery Results:</h3>
                    <form @submit.prevent="addResult">
                        <div class="row">
                            <!-- Lottery Select -->
                            <!-- Lottery Select -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lotterySelect">Lottery Select:</label>
                                    <select id="lotterySelect" v-model="newResult.lottery" class="form-control">
                                        <option v-for="(lottery, index) in lotteries" :key="index" :value="lottery.id">
                                            {{ lottery.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dashboard Select -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dashboardSelect">Draw Number:</label>
                                    <select id="dashboardSelect" v-model="newResult.dashboard" class="form-control"
                                        @change="updateDashboardName">
                                        <!-- Loop through the dashboards based on selected lottery -->
                                        <option v-for="(dashboard, index) in filteredDashboards" :key="index"
                                            :value="dashboard.id">
                                            {{ dashboard.draw_number }} | {{ dashboard.dashboard }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" v-model="newResult.dashboardName" />

                            <!-- Price Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Price *:</label>
                                    <input type="number" id="price" v-model="newResult.price" class="form-control"
                                        readonly />
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">First Winning Number:</label>
                                    <input type="number" id="number" v-model="newResult.winning_number"
                                        class="form-control" min="0" max="99" @input="validateWinningNumber" required />
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Last Winning Number:</label>
                                    <input type="number" id="number" v-model="newResult.lwinning_number"
                                        class="form-control" min="0" max="99" @input="validateWinningNumber" required />
                                </div>

                            </div>
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success">Add Result</button>
                    </form>
                </div>

                <!-- Lottery Table Section -->
                <div v-for="(results, lotteryId) in results" :key="lotteryId">
                    <div class="lottery-table">
                        <h4 v-if="results[0] && results[0].lottery">{{ results[0].lottery.name }} Results</h4>

                        <table class="table table-dark">
                            <thead>
                                <tr>

                                    <th>Dashboard</th>
                                    <th>Draw Number</th>
                                    <th>Draw Date</th>
                                    <th>Price</th>
                                    <th>Winning Number</th>
                                    <th>Draw Number</th>

                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through the results for this specific lottery -->
                                <tr v-for="result in results" :key="result.id">
                                    <td><a href="#">{{ result.dashboard.dashboard }} | {{ result.dashboard.dashboardType
                                    }}</a></td>
                                    <td>{{ result.dashboard.draw }}</td>
                                    <td>{{ result.dashboard.date }}</td>
                                    <td>{{ result.dashboard.price * 70 }}</td>
                                    <td>{{ result.winning_number }}</td>
                                    <td>{{ result.dashboard.draw_number}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <router-view />
    </div>
</template>


<script>
import Sidebar from '@/components/AdminSidebar.vue';
import axios from 'axios';
import { ref } from 'vue';

export default {
    components: {
        Sidebar,
    },
    data() {
        return {
            isSidebarVisible: true,
            newResult: {
                lottery: null,
                dashboard: null,
                price: 70,
                dashboardName: '',
            },

            responseMessage: ref(null),
            responseClass: ref('bottom-response'),
        };
    },
    props: {
        results: Array,
        lotteries: Array,
    },
    computed: {
        filteredDashboards() {
        const selectedLottery = this.lotteries.find(
            (lottery) => lottery.id === this.newResult.lottery
        );

        if (!selectedLottery || !selectedLottery.dashboards) {
            return [];
        }

        // Convert dashboards object into an array
        const dashboardsArray = Object.values(selectedLottery.dashboards);

        // Remove duplicates based on "draw_number + dashboard"
        const uniqueDashboards = [];

        const seen = new Set();

        for (const dashboard of dashboardsArray) {
            const identifier = `${dashboard.draw_number}|${dashboard.dashboard}`; // unique identifier

            if (!seen.has(identifier)) {
                seen.add(identifier);
                uniqueDashboards.push(dashboard);
            }
        }

        return uniqueDashboards;
    },
    },
    mounted() {
        console.log(this.results);
    },
    methods: {
        updateDashboardName() {
            const selectedDashboard = this.filteredDashboards.find(d => d.id === this.newResult.dashboard);
            if (selectedDashboard) {
                this.newResult.dashboardName = selectedDashboard.dashboard; // Store the dashboard name
            }
        },

        showResponse(message, position = 'bottom') {
            this.responseMessage = message;
            this.responseClass = position === 'bottom' ? 'top-response' : 'bottom-response';

            // Hide the message after 3 seconds
            setTimeout(() => {
                this.responseMessage = null;
            }, 3000);
        },


        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },

        async addResult() {
            console.log('filteredDashboards:', this.filteredDashboards); // Debugging
            console.log('lotteries:', this.lotteries);

            if (!this.newResult.lottery || !this.newResult.dashboard || !this.newResult.winning_number ||
                !this.newResult.lwinning_number || !this.newResult.price || !this.newResult.dashboardName) {
                this.showToast('Please fill in all required fields before submitting.', 'error');
                return;
            }

            try {
                const selectedDashboard = this.filteredDashboards.find(d => d.id === this.newResult.dashboard);
                const response = await axios.post('/api/admin/results/store', {
                    lottery_id: this.newResult.lottery,
                    dashboard_id: this.newResult.dashboard,
                    winning_number: this.newResult.winning_number,
                    lwinning_number: this.newResult.lwinning_number, // Fix: Use this.newResult.lwinning_number
                    price: this.newResult.price,
                    dashboard_name: this.newResult.dashboardName,
                    draw_number: selectedDashboard ? selectedDashboard.draw_number : null
                });

                if (response.data.success) {
                    console.log('Result added successfully');
                    this.showResponse('Result Added', 'bottom');
                    location.reload();
                }
            } catch (error) {
                console.error('Error adding result:', error);
            }
        },
        validateWinningNumber() {
            if (this.newResult.winning_number < 0) {
                this.newResult.winning_number = "00";
            } else if (this.newResult.winning_number > 99) {
                this.newResult.winning_number = "99";
            } else {
                this.newResult.winning_number = String(this.newResult.winning_number).padStart(2, '0');
            }

            if (this.newResult.lwinning_number < 0) {
                this.newResult.lwinning_number = "00";
            } else if (this.newResult.lwinning_number > 99) {
                this.newResult.lwinning_number = "99";
            } else {
                this.newResult.lwinning_number = String(this.newResult.lwinning_number).padStart(2, '0');
            }
        },
        showToast(message, type = 'info') {
            // Simple alert fallback
            if (!this.$toast) {
                alert(message);
                return;
            }

            switch (type) {
                case 'success':
                    this.$toast.success(message, { position: 'bottom-right' });
                    break;
                case 'error':
                    this.$toast.error(message, { position: 'bottom-right' });
                    break;
                default:
                    this.$toast.info(message, { position: 'bottom-right' });
            }
        },
    },
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

.lottery-form {
    margin-top: 20px;
    background-color: #2c2c2c;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.lottery-form .form-group {
    margin-bottom: 15px;
}

.lottery-form label {
    font-size: 14px;
    color: #e0e0e0;
}

.lottery-form select,
.lottery-form input {
    width: 100%;
    padding: 10px;
    background-color: #444;
    color: #e0e0e0;
    border: 1px solid #555;
    border-radius: 5px;
}

.lottery-form button {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.lottery-form button:hover {
    background-color: #218838;
}

.lottery-table {
    margin-top: 30px;
}

.lottery-table table {
    width: 100%;
    border-collapse: collapse;
    background-color: #2c2c2c;
    border-radius: 8px;
}

.lottery-table th,
.lottery-table td {
    padding: 12px 15px;
    text-align: left;
    color: #e0e0e0;
    border-bottom: 1px solid #444;
}

.lottery-table th {
    background-color: #333;
}

.lottery-table td a {
    color: #1e90ff;
    text-decoration: none;
}

.lottery-table td a:hover {
    text-decoration: underline;
}

.lottery-table tbody tr:hover {
    background-color: #444;
}
</style>