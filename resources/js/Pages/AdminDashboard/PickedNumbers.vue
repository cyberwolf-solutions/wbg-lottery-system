<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Picked Numbers Report</h2>
                    <button @click="downloadPDF" class="btn btn-primary pdf-btn" :disabled="loading">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </button>
                </div>

                <div class="lottery-table">
                    <!-- Search and Filter Bar -->
                    <!-- Search and Filter Bar -->
                    <div class="search-filter-container mb-3">
                        <!-- Search Input -->
                        <div class="search-input-container">
                            <i class="bi bi-search search-icon"></i>
                            <input v-model="searchQuery" type="text" class="form-control search-input"
                                placeholder="Search by lottery, dashboard, or user..." />
                        </div>

                        <!-- Date Filters -->
                        <div class="date-filters-container">
                            <!-- Single Date Filter -->
                            <div class="date-filter">
                                <label class="filter-label">
                                    <i class="bi bi-calendar2-day"></i> Single Date
                                </label>
                                <div class="date-input-wrapper">
                                    <input v-model="singleDate" type="date" class="form-control date-input" />
                                    <button v-if="singleDate" @click="clearSingleDate" class="clear-date-btn"
                                        type="button">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Date Range Filter -->
                            <div class="date-filter">
                                <label class="filter-label">
                                    <i class="bi bi-calendar2-range"></i> Date Range
                                </label>
                                <div class="date-range-wrapper">
                                    <div class="date-range-inputs">
                                        <div class="date-input-wrapper">
                                            <input v-model="dateRange.start" type="date" class="form-control date-input"
                                                placeholder="Start" />
                                            <button v-if="dateRange.start" @click="dateRange.start = null"
                                                class="clear-date-btn" type="button">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <span class="date-range-separator">to</span>
                                        <div class="date-input-wrapper">
                                            <input v-model="dateRange.end" type="date" class="form-control date-input"
                                                placeholder="End" />
                                            <button v-if="dateRange.end" @click="dateRange.end = null"
                                                class="clear-date-btn" type="button">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button v-if="dateRange.start || dateRange.end" @click="clearDateRange"
                                        class="clear-range-btn" type="button">
                                        Clear Range
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div v-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>

                    <!-- Main Lotteries Table -->
                    <table v-if="!loading && !error">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Lottery Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(lottery, index) in filteredLotteries" :key="lottery.id">
                                <tr>
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ lottery.name }}</td>
                                    <td>{{ lottery.description || 'N/A' }}</td>
                                    <td>
                                        <button @click="toggleLotteryDashboards(lottery.id)"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi"
                                                :class="expandedLotteries.includes(lottery.id) ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                            {{ expandedLotteries.includes(lottery.id) ? 'Hide' : 'Show' }} Dashboards
                                        </button>
                                    </td>
                                </tr>

                                <!-- Expanded row for dashboards -->
                                <tr v-if="expandedLotteries.includes(lottery.id)" class="expanded-row">
                                    <td colspan="4">
                                        <div class="dashboard-subtable">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Dashboard ID</th>
                                                        <th>Dashboard Name</th>
                                                        <th>Draw Number</th>
                                                        <th>Type</th>
                                                        <th>Price</th>
                                                        <th>Draw Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template
                                                        v-for="dashboard in getFilteredLotteryDashboards(lottery.id)"
                                                        :key="dashboard.id">
                                                        <tr>
                                                            <td>{{ dashboard.id }}</td>
                                                            <td>{{ dashboard.dashboard }}</td>
                                                            <td>{{ dashboard.draw_number }}</td>
                                                            <td>{{ dashboard.dashboardType || 'Regular' }}</td>
                                                            <td>${{ dashboard.price }}</td>
                                                            <td>{{ formatDate(dashboard.date) }}</td>
                                                            <td>
                                                                <button @click="toggleDashboardPicks(dashboard.id)"
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="bi"
                                                                        :class="expandedDashboards.includes(dashboard.id) ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                                    {{ expandedDashboards.includes(dashboard.id) ?
                                                                        'Hide' : 'Show' }} Picks
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <!-- Expanded row for picks -->
                                                        <tr v-if="expandedDashboards.includes(dashboard.id)"
                                                            class="expanded-picks-row">
                                                            <td colspan="7">
                                                                <div class="picks-subtable">
                                                                    <table>
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Pick ID</th>
                                                                                <th>Number</th>
                                                                                <th>User</th>
                                                                                <th>Picked Date</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <template
                                                                                v-for="pick in getFilteredDashboardPicks(dashboard.id)"
                                                                                :key="pick.id">
                                                                                <tr
                                                                                    :class="{ 'cancelled-pick': pick.deleted_at }">
                                                                                    <td>{{ pick.id }}</td>
                                                                                    <td>{{ pick.picked_number }}</td>
                                                                                    <td>
                                                                                        <span v-if="pick.user">
                                                                                            {{ pick.user.name }} (ID: {{
                                                                                                pick.user.id }})
                                                                                        </span>
                                                                                        <span v-else>N/A</span>
                                                                                    </td>
                                                                                    <td>{{ formatDate(pick.created_at)
                                                                                    }}</td>
                                                                                    <td>
                                                                                        <span v-if="pick.deleted_at"
                                                                                            class="badge bg-danger">Cancelled</span>
                                                                                        <span v-else class="badge"
                                                                                            :class="pick.status === 'picked' ? 'bg-success' : 'bg-warning'">
                                                                                            {{ pick.status === 'picked'
                                                                                                ? 'Picked' : 'Pending' }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </template>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-between mt-3 pagination-container" v-if="!loading && !error">
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
    </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export default {
    components: {
        Sidebar,
    },
    props: {
        refunds: {
            type: Array,
            required: true,
            default: () => []
        }
    },
    data() {
        return {
            isSidebarVisible: true,
            searchQuery: "",
            singleDate: null,
            dateRange: {
                start: null,
                end: null
            },
            currentPage: 1,
            perPage: 5,
            expandedLotteries: [],
            expandedDashboards: [],
            loading: false,
            error: null
        };
    },
    computed: {
        // Get unique lotteries from the data
        uniqueLotteries() {
            const lotteriesMap = new Map();
            this.refunds.forEach(pick => {
                if (pick.lottery && !lotteriesMap.has(pick.lottery.id)) {
                    lotteriesMap.set(pick.lottery.id, pick.lottery);
                }
            });
            return Array.from(lotteriesMap.values());
        },

        // Filter lotteries based on search query and date filters
        filteredLotteries() {
            const query = this.searchQuery.toLowerCase();
            return this.uniqueLotteries.filter(lottery => {
                // Search in lottery properties
                if (lottery.name.toLowerCase().includes(query) ||
                    lottery.description?.toLowerCase().includes(query)) {
                    return true;
                }

                // Search in dashboards
                const dashboards = this.getLotteryDashboards(lottery.id);
                if (dashboards.some(d =>
                    d.dashboard.toLowerCase().includes(query) ||
                    d.dashboardType?.toLowerCase().includes(query) ||
                    d.price.toString().includes(query)
                )) {
                    return true;
                }

                // Search in picks
                const picks = this.getLotteryPicks(lottery.id);
                if (picks.some(p =>
                    p.picked_number.toString().includes(query) ||
                    (p.user && p.user.name.toLowerCase().includes(query))
                )) {
                    return true;
                }

                return false;
            });
        },

        paginatedLotteries() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredLotteries.slice(start, start + this.perPage);
        },

        totalPages() {
            return Math.ceil(this.filteredLotteries.length / this.perPage);
        }
    },
    methods: {
        // Get dashboards for a specific lottery with date filtering
        getFilteredLotteryDashboards(lotteryId) {
            let dashboards = this.getLotteryDashboards(lotteryId);

            // Apply date filters if they exist
            if (this.singleDate) {
                const filterDate = new Date(this.singleDate).toISOString().split('T')[0];
                dashboards = dashboards.filter(d => {
                    const dashboardDate = new Date(d.date).toISOString().split('T')[0];
                    return dashboardDate === filterDate;
                });
            } else if (this.dateRange.start || this.dateRange.end) {
                const startDate = this.dateRange.start ? new Date(this.dateRange.start) : null;
                const endDate = this.dateRange.end ? new Date(this.dateRange.end) : null;

                dashboards = dashboards.filter(d => {
                    const dashboardDate = new Date(d.date);

                    if (startDate && endDate) {
                        return dashboardDate >= startDate && dashboardDate <= endDate;
                    } else if (startDate) {
                        return dashboardDate >= startDate;
                    } else if (endDate) {
                        return dashboardDate <= endDate;
                    }
                    return true;
                });
            }

            return dashboards;
        },

        // Get picks for a specific dashboard with date filtering
        getFilteredDashboardPicks(dashboardId) {
            let picks = this.getDashboardPicks(dashboardId);

            // Apply date filters if they exist
            if (this.singleDate) {
                const filterDate = new Date(this.singleDate).toISOString().split('T')[0];
                picks = picks.filter(p => {
                    const pickDate = new Date(p.created_at).toISOString().split('T')[0];
                    return pickDate === filterDate;
                });
            } else if (this.dateRange.start || this.dateRange.end) {
                const startDate = this.dateRange.start ? new Date(this.dateRange.start) : null;
                const endDate = this.dateRange.end ? new Date(this.dateRange.end) : null;

                picks = picks.filter(p => {
                    const pickDate = new Date(p.created_at);

                    if (startDate && endDate) {
                        return pickDate >= startDate && pickDate <= endDate;
                    } else if (startDate) {
                        return pickDate >= startDate;
                    } else if (endDate) {
                        return pickDate <= endDate;
                    }
                    return true;
                });
            }

            return picks;
        },

        // Original methods (unchanged)
        getLotteryDashboards(lotteryId) {
            const dashboardsMap = new Map();
            this.refunds.forEach(pick => {
                if (pick.lottery?.id === lotteryId && pick.lottery_dashboard && !dashboardsMap.has(pick.lottery_dashboard.id)) {
                    dashboardsMap.set(pick.lottery_dashboard.id, pick.lottery_dashboard);
                }
            });
            return Array.from(dashboardsMap.values());
        },

        getLotteryPicks(lotteryId) {
            return this.refunds.filter(pick => pick.lottery?.id === lotteryId);
        },

        getDashboardPicks(dashboardId) {
            return this.refunds.filter(pick => pick.lottery_dashboard?.id === dashboardId);
        },

        toggleLotteryDashboards(lotteryId) {
            const index = this.expandedLotteries.indexOf(lotteryId);
            if (index === -1) {
                this.expandedLotteries.push(lotteryId);
            } else {
                this.expandedLotteries.splice(index, 1);
                // Also remove any expanded dashboards for this lottery
                const dashboards = this.getLotteryDashboards(lotteryId);
                dashboards.forEach(d => {
                    const dashboardIndex = this.expandedDashboards.indexOf(d.id);
                    if (dashboardIndex !== -1) {
                        this.expandedDashboards.splice(dashboardIndex, 1);
                    }
                });
            }
        },

        toggleDashboardPicks(dashboardId) {
            const index = this.expandedDashboards.indexOf(dashboardId);
            if (index === -1) {
                this.expandedDashboards.push(dashboardId);
            } else {
                this.expandedDashboards.splice(index, 1);
            }
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleDateString();
            } catch {
                return 'Invalid Date';
            }
        },

        clearSingleDate() {
            this.singleDate = null;
        },

        clearDateRange() {
            this.dateRange.start = null;
            this.dateRange.end = null;
        },

        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },

        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },

        async downloadPDF() {
            try {
                this.loading = true;
                const doc = new jsPDF();

                // Add title
                doc.setFontSize(18);
                doc.text('Picked Numbers Report', 14, 20);

                // Prepare table data
                const tableData = [];
                this.filteredLotteries.forEach(lottery => {
                    // Add lottery row
                    tableData.push([
                        { content: lottery.name, colSpan: 5, styles: { fontStyle: 'bold', fillColor: [220, 220, 220] } }
                    ]);

                    // Add dashboard rows for this lottery
                    this.getFilteredLotteryDashboards(lottery.id).forEach(dashboard => {
                        tableData.push([
                            { content: dashboard.dashboard, colSpan: 5, styles: { fillColor: [240, 240, 240] } }
                        ]);

                        // Add pick rows for this dashboard
                        this.getFilteredDashboardPicks(dashboard.id).forEach(pick => {
                            const status = pick.deleted_at ? 'Cancelled' :
                                (pick.status === 'picked' ? 'Picked' : 'Pending');
                            const userName = pick.user ? `${pick.user.name} (ID: ${pick.user.id})` : 'N/A';

                            tableData.push([
                                pick.id,
                                pick.picked_number,
                                userName,
                                this.formatDate(pick.created_at),
                                status
                            ]);
                        });
                    });
                });

                // Add table using autoTable
                autoTable(doc, {
                    startY: 30,
                    head: [['Pick ID', 'Number', 'User', 'Picked Date', 'Status']],
                    body: tableData,
                    theme: 'grid',
                    headStyles: { fillColor: [66, 66, 66] },
                    styles: { textColor: [0, 0, 0], fontSize: 10 },
                    columnStyles: {
                        0: { cellWidth: 20 }, // Pick ID
                        1: { cellWidth: 20 }, // Number
                        2: { cellWidth: 50 }, // User
                        3: { cellWidth: 30 }, // Date
                        4: { cellWidth: 20 }  // Status
                    },
                    didParseCell: (data) => {
                        // Style lottery and dashboard rows
                        if (data.cell.raw.colSpan) {
                            data.cell.styles = data.cell.raw.styles || {};
                        }
                        // Style cancelled picks
                        if (data.cell.raw === 'Cancelled') {
                            data.cell.styles.textColor = [255, 0, 0];
                            data.cell.styles.fontStyle = 'bold';
                        }
                    }
                });

                // Add timestamp
                const date = new Date().toLocaleString();
                doc.setFontSize(8);
                doc.text(`Generated on: ${date}`, 14, doc.internal.pageSize.height - 10);

                // Download the PDF
                doc.save(`picked_numbers_report_${date.split(',')[0].replace(/\//g, '-')}.pdf`);
            } catch (error) {
                console.error("Error generating PDF:", error);
                alert("Failed to generate PDF. Please try again.");
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
.dark-theme {
    background-color: #121212;
    color: #e0e0e0;
    min-height: 100vh;
}

.main-content {
    margin-left: 250px;
    transition: margin-left 0.3s ease;
    background-color: #121212;
    min-height: 100vh;
    padding: 20px;
}

.sidebar-hidden {
    margin-left: 0;
}

.dashboard-banner {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
}

.lottery-table {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.pagination-container {
    background-color: #1a1a1a;
    padding: 15px;
    border-radius: 8px;
}

.flex-fill {
    background-color: #121212;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: #2c2c2c;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 0;
}

th,
td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #444;
}

th {
    background-color: #333;
    color: #fff;
}

.dashboard-subtable,
.picks-subtable {
    margin: 10px;
    padding: 10px;
    background-color: #252525;
    border-radius: 5px;
}

.dashboard-subtable table,
.picks-subtable table {
    background-color: #252525;
    box-shadow: none;
}

.expanded-row {
    background-color: #2a2a2a;
}

.expanded-picks-row {
    background-color: #222;
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    border-radius: 0.25rem;
}

.bg-success {
    background-color: #198754;
}

.bg-danger {
    background-color: #dc3545;
}

.bg-warning {
    background-color: #ffc107;
    color: #000;
}

.cancelled-pick {
    opacity: 0.7;
    text-decoration: line-through;
    color: #ff6b6b;
}

.btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    transition: all 0.15s ease;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-info:hover {
    background-color: #0bb6d4;
    border-color: #0aaec8;
}

.bi {
    margin-right: 5px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.alert {
    padding: 1rem;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c2c7;
    color: #842029;
}

.input-group-text {
    background-color: #333;
    color: #fff;
    border-color: #444;
}

.form-control {
    background-color: #2c2c2c;
    color: #e0e0e0;
    border-color: #444;
}

.form-control:focus {
    background-color: #2c2c2c;
    color: #e0e0e0;
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }

    table {
        display: block;
        overflow-x: auto;
    }
}

/* Search and Filter Container */
.search-filter-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    background-color: #252525;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Search Input */
.search-input-container {
    position: relative;
    flex-grow: 1;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
}

.search-input {
    padding-left: 40px;
    background-color: #2c2c2c;
    border: 1px solid #444;
    color: #e0e0e0;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Date Filters Container */
.date-filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.date-filter {
    flex: 1;
    min-width: 250px;
}

.filter-label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: #adb5bd;
}

.filter-label i {
    margin-right: 8px;
    color: #0dcaf0;
}

/* Date Inputs */
.date-input-wrapper {
    position: relative;
}

.date-input {
    background-color: #2c2c2c;
    border: 1px solid #444;
    color: #e0e0e0;
    padding-right: 35px;
}

.date-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.clear-date-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #6c757d;
    padding: 0 5px;
    cursor: pointer;
    transition: color 0.2s;
}

.clear-date-btn:hover {
    color: #dc3545;
}

/* Date Range Specific Styles */
.date-range-wrapper {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.date-range-inputs {
    display: flex;
    align-items: center;
    gap: 10px;
}

.date-range-separator {
    color: #6c757d;
    font-size: 0.9rem;
    white-space: nowrap;
}

.clear-range-btn {
    align-self: flex-end;
    background: transparent;
    border: none;
    color: #6c757d;
    font-size: 0.8rem;
    padding: 2px 5px;
    cursor: pointer;
    transition: color 0.2s;
}

.clear-range-btn:hover {
    color: #dc3545;
    text-decoration: underline;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .date-filters-container {
        flex-direction: column;
    }

    .date-filter {
        min-width: 100%;
    }

    .date-range-inputs {
        flex-direction: column;
        align-items: stretch;
    }

    .date-range-separator {
        text-align: center;
        margin: 5px 0;
    }
}
</style>