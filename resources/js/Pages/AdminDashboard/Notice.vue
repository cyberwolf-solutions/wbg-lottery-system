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


                <div class="notice-form mb-4">
                    <h3>Create Notice</h3>
                    <form @submit.prevent="submitNotice">
                        <div class="form-group mb-2">
                            <label>Title</label>
                            <input type="text" v-model="newNotice.title" class="form-control" required />
                        </div>
                        <div class="form-group mb-2">
                            <label>Message</label>
                            <textarea v-model="newNotice.message" class="form-control" required></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label>Target User</label>
                            <select v-model="newNotice.user_id" class="form-control">
                                <option :value="null">All Users</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Notice</button>
                    </form>
                </div>

                <div class="notice-table mt-5">
                    <h3>All Notices</h3>
                    <table class="table table-striped table-dark">

                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Message</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="notice in paginatedNotices" :key="notice.id">

                                <td>{{ notice.title }}</td>
                                <td>{{ notice.message }}</td>
                                <td>{{ notice.user ? notice.user.name : 'All Users' }}</td>
                                <td>{{ new Date(notice.created_at).toLocaleString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination mt-3">
                        <button class="btn btn-secondary me-2" @click="currentPage--"
                            :disabled="currentPage === 1">Prev</button>
                        <span>Page {{ currentPage }} of {{ totalPages }}</span>
                        <button class="btn btn-secondary ms-2" @click="currentPage++"
                            :disabled="currentPage === totalPages">Next</button>
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
            newNotice: {
                title: '',
                message: '',
                user_id: null,
            },

            responseMessage: ref(null),
            responseClass: ref('bottom-response'),
            currentPage: 1,
            perPage: 5,
        };
    },
    props: {
        notices: Array,
        users: Array,
    },
    computed: {
        filteredDashboards() {
            const selectedLottery = this.lotteries.find(
                (lottery) => lottery.id === this.newResult.lottery
            );
            // Convert the dashboards object into an array
            return selectedLottery ? Object.values(selectedLottery.dashboards || {}) : [];
        },
        paginatedNotices() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.notices.slice(start, end);
        },
        totalPages() {
            return Math.ceil(this.notices.length / this.perPage);
        }
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
        async submitNotice() {
            try {
                await axios.post('/api/admin/notices/store', this.newNotice);
                this.newNotice = { title: '', message: '', user_id: null };
                this.showResponse('Notice sent successfully');
                location.reload(); // or re-fetch notices if you want it more elegant
            } catch (e) {
                console.error('Error submitting notice', e);
            }
        }


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