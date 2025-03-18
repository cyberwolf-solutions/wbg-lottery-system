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
                    <h2 class="lottery-name fw-bold text-danger">Lottery</h2>
                    <h2 class="lottery-name fw-bold text-danger">{{ lottery.name }}</h2>
                </div>

                <!-- Loop through grouped winners and display them in separate tables -->
                <div v-for="(group, dashboardName) in winners" :key="dashboardName" class="lottery-table">
                    <h3 class="mt-4">{{ dashboardName }} Winners:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Winner</th>
                                <th>Draw Date</th>
                                <th>Draw Number</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(winner, index) in group" :key="index">
                                <td><a href="#">{{ winner.user ? winner.user.name : 'N/A' }}</a></td>
                                <td>{{ winner.lottery_dashboard ? winner.lottery_dashboard.date : 'N/A' }}</td>
                                <td>{{ winner.lottery_dashboard ? winner.lottery_dashboard.draw_number : 'N/A' }}</td>
                                <td>{{ winner.lottery_dashboard ? winner.price : 'N/A' }}</td>
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
import { ref } from 'vue';

export default {
    components: {
        Sidebar,
    },
    props: {
        winners: Object,
        lottery: Array
    },
    data() {
        return {
            isSidebarVisible: true,
            responseMessage: ref(null),  // Use `ref` to make this reactive
            responseClass: ref('bottom-response'),
        };
    },
    methods: {
        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        showResponse(message, position = 'bottom') {
            this.responseMessage = message;
            this.responseClass = position === 'bottom' ? 'top-response' : 'bottom-response';

            // Hide the message after 3 seconds
            setTimeout(() => {
                this.responseMessage = null;
            }, 3000);
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