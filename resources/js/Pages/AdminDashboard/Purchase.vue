<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Lottery Purchases</h2>
                    <div class="search-controls">
                        <input v-model="searchDate" type="date" class="form-control" placeholder="Filter by date">
                        <select v-model="searchStatus" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
                <div class="lottery-table">
                    <h3 class="mt-4">{{ lottery[0]?.lottery?.name }} Purchase Details</h3>

                    <!-- Grouped by Dashboard Name -->
                    <div v-for="dashboardGroup in paginatedDashboardGroups" :key="dashboardGroup.name" class="dashboard-group">
                        <h4 class="dashboard-name-header">{{ dashboardGroup.name }}</h4>
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Draw Number</th>
                                    <th>Draw Date</th>
                                    <th>Status</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="dashboard in getPaginatedItems(dashboardGroup.name, dashboardGroup.items)" :key="dashboard.id" @click="viewDetails(dashboard)">

                                    <td>{{ dashboard.draw_number }}</td>
                                    <td>{{ formatDate(dashboard.date) }}</td>
                                    <td>
                                        <span :class="['status-badge', dashboard.status === 'active' ? 'active' : 'closed']">
                                            {{ dashboard.status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="percentage-bar">
                                            <div class="percentage-fill" :style="{ width: calculatePercentage(dashboard.id) + '%' }">
                                                {{ calculatePercentage(dashboard.id) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="pagination-controls mt-2">
    <button class="btn btn-sm btn-primary" 
        @click="groupPrevPage(dashboardGroup.name)"
        :disabled="(groupPagination[dashboardGroup.name] || 1) === 1">
        Previous
    </button>

    <span>
        Page {{ groupPagination[dashboardGroup.name] || 1 }} 
        of {{ Math.ceil(dashboardGroup.items.length / perPage) }}
    </span>

    <button class="btn btn-sm btn-primary" 
        @click="groupNextPage(dashboardGroup.name, dashboardGroup.items.length)"
        :disabled="(groupPagination[dashboardGroup.name] || 1) === Math.ceil(dashboardGroup.items.length / perPage)">
        Next
    </button>
</div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Number Picker Modal -->
        <div v-if="isPickerOpen" class="modal-overlay">
            <div class="modal-content">
                <button @click="isPickerOpen = false" class="close-button">×</button>
                <h3>Picked Numbers for {{ currentDashboard?.dashboard }} (Draw #{{ currentDashboard?.draw_number }})</h3>
                <div class="number-grid-container">
                    <div class="number-grid">
                        <button v-for="num in numberOptions" :key="num"
                            :class="['number-button', { 'picked': isNumberPicked(num) }]" 
                            @mouseover="showUserTooltip(num)" 
                            @mouseleave="hideUserTooltip">
                            {{ num }}
                            <span v-if="isNumberPicked(num)" class="picked-badge">✓</span>
                        </button>
                    </div>
                </div>

                <div v-if="hoveredNumber && hoveredUser" class="user-tooltip">
                    Number {{ hoveredNumber }} picked by: {{ hoveredUser }}
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
        lottery: {
            type: Array,
            default: () => []
        },
        pickedNumbers: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
    return {
        isSidebarVisible: true,
        isPickerOpen: false,
        currentDashboard: null,
        selectedPickedNumbers: [],
        numberOptions: Array.from({ length: 100 }, (_, i) => String(i).padStart(2, "0")),
        hoveredNumber: null,
        hoveredUser: null,
        currentPage: 1,
        perPage: 6,
        groupPagination: {}, 
        searchDate: '',
        searchStatus: ''
    };
},

    computed: {
        filteredDashboards() {
            return this.lottery.filter(dashboard => {
                const matchesDate = !this.searchDate || 
                    new Date(dashboard.date).toDateString() === new Date(this.searchDate).toDateString();
                const matchesStatus = !this.searchStatus || 
                    dashboard.status === this.searchStatus;
                return matchesDate && matchesStatus;
            });
        },
        groupedDashboards() {
            const groups = {};
            this.filteredDashboards.forEach(dashboard => {
                if (!groups[dashboard.dashboard]) {
                    groups[dashboard.dashboard] = {
                        name: dashboard.dashboard,
                        items: []
                    };
                }
                groups[dashboard.dashboard].items.push(dashboard);
            });
            return Object.values(groups);
        },
        paginatedDashboardGroups() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.groupedDashboards.slice(start, end);
        },
        totalPages() {
            return Math.ceil(this.groupedDashboards.length / this.perPage);
        }
    },
    methods: {
        getPaginatedItems(groupName, items) {
        const page = this.groupPagination[groupName] || 1;
        const start = (page - 1) * this.perPage;
        const end = start + this.perPage;
        return items.slice(start, end);
    },
    groupPrevPage(groupName) {
        if (!this.groupPagination[groupName]) {
            this.groupPagination = { ...this.groupPagination, [groupName]: 1 };
        }
        if (this.groupPagination[groupName] > 1) {
            this.groupPagination = { 
                ...this.groupPagination, 
                [groupName]: this.groupPagination[groupName] - 1 
            };
        }
    },
    groupNextPage(groupName, totalItems) {
        if (!this.groupPagination[groupName]) {
            this.groupPagination = { ...this.groupPagination, [groupName]: 1 };
        }
        const totalPages = Math.ceil(totalItems / this.perPage);
        if (this.groupPagination[groupName] < totalPages) {
            this.groupPagination = { 
                ...this.groupPagination, 
                [groupName]: this.groupPagination[groupName] + 1 
            };
        }
    },
        calculatePercentage(dashboardId) {
            const pickedNumbers = this.pickedNumbers[dashboardId] || [];
            const pickedCount = pickedNumbers.length;
            const percentage = Math.min(100, pickedCount);
            return percentage;
        },
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        viewDetails(dashboard) {
            this.currentDashboard = dashboard;
            this.selectedPickedNumbers = this.pickedNumbers[dashboard.id] || [];
            this.isPickerOpen = true;
        },
        isNumberPicked(num) {
            return this.selectedPickedNumbers.some(item => item.number === num);
        },
        showUserTooltip(num) {
            const pickedNumber = this.selectedPickedNumbers.find(item => item.number === num);
            if (pickedNumber) {
                this.hoveredNumber = num;
                this.hoveredUser = pickedNumber.user;
            }
        },
        hideUserTooltip() {
            this.hoveredNumber = null;
            this.hoveredUser = null;
        },
        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        }
    }
};
</script>
<style scoped>
/* Main layout styles */
.dark-theme {
    background-color: #121212;
    color: #e0e0e0;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.sidebar-hidden {
    margin-left: 0;
}

.dashboard-banner {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Search controls */
.search-controls {
    display: flex;
    gap: 10px;
    margin-left: auto;
}

.search-controls .form-control {
    width: 200px;
    background-color: #2c2c2c;
    color: #e0e0e0;
    border: 1px solid #444;
}

/* Dashboard group styles */
.dashboard-group {
    margin-bottom: 30px;
    background-color: #2c2c2c;
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
}

.dashboard-name-header {
    color: #f8f9fa;
    padding: 10px 15px;
    background-color: #333;
    border-radius: 4px;
    margin-bottom: 15px;
}

/* Table styles */
.dashboard-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.dashboard-table th,
.dashboard-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #444;
    cursor: pointer;
}

.dashboard-table th {
    background-color: #333;
    color: #fff;
}

.dashboard-table tr:hover {
    background-color: #444;
}

/* Status badges */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: bold;
}

.status-badge.active {
    background-color: #28a745;
    color: white;
}

.status-badge.closed {
    background-color: #dc3545;
    color: white;
}

/* Percentage bar */
.percentage-bar {
    width: 100%;
    background-color: #444;
    border-radius: 4px;
    height: 24px;
    position: relative;
}

.percentage-fill {
    height: 100%;
    border-radius: 4px;
    background-color: #4CAF50;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    transition: width 0.3s ease;
}

/* Pagination controls */
.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin: 30px 0 10px; /* Adjusted margin for bottom placement */
}

/* Modal styles */
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
    z-index: 1000;
}

.modal-content {
    background-color: #2c2c2c;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    color: #e0e0e0;
    font-size: 24px;
    cursor: pointer;
}

.number-grid-container {
    max-height: 60vh;
    overflow-y: auto;
}

.number-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
    gap: 10px;
    padding: 15px;
}

.number-button {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid #e0e0e0;
    background-color: #333;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.number-button.picked {
    background-color: #28a745;
    border-color: #28a745;
}

.picked-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #fff;
    color: #28a745;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
}

.user-tooltip {
    position: fixed;
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    pointer-events: none;
    z-index: 1001;
}
</style>