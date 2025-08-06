<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">User Picks Report</h2>
                    <button @click="downloadPDF" class="btn btn-primary pdf-btn">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </button>
                </div>

                <div class="lottery-table">
                    <!-- Search Bar -->
                    <input v-model="searchQuery" type="text" class="form-control mb-3"
                        placeholder="Search by user name or ID..." />

                    <!-- Main Users Table -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(user, index) in paginatedUsers" :key="user?.id || index">
                                <tr v-if="user">
                                    <td>{{ getItemNumber(index) }}</td>
                                    <td>{{ user.id || 'N/A' }}</td>
                                    <td>{{ user.name || 'N/A' }}</td>
                                    <td>
                                        <button @click="toggleUserLotteries(user.id)" class="btn btn-sm btn-primary">
                                            <i class="bi"
                                                :class="expandedUsers.includes(user.id) ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                            {{ expandedUsers.includes(user.id) ? 'Hide' : 'Show' }} Lotteries
                                        </button>
                                    </td>
                                </tr>
                                <!-- Expanded row for lotteries -->
                                <tr v-if="user && expandedUsers.includes(user.id)" class="expanded-row">
                                    <td colspan="4">
                                        <div class="lottery-subtable">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Lottery ID</th>
                                                        <th>Lottery Name</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="lottery in getUserLotteries(user.id)"
                                                        :key="'lottery-'+(lottery?.id || index)">
                                                        <tr v-if="lottery">
                                                            <td>{{ lottery.id || 'N/A' }}</td>
                                                            <td>{{ lottery.name || 'N/A' }}</td>
                                                            <td>
                                                                <button @click="toggleLotteryPicks(user.id, lottery.id)"
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="bi"
                                                                        :class="expandedLotteries.includes(`${user.id}-${lottery.id}`) ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                                    {{
                                                                    expandedLotteries.includes(`${user.id}-${lottery.id}`)
                                                                    ? 'Hide' : 'Show' }} Picks
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <!-- Expanded row for picks -->
                                                        <tr v-if="lottery && expandedLotteries.includes(`${user.id}-${lottery.id}`)"
                                                            class="expanded-picks-row">
                                                            <td colspan="3">
                                                                <div class="picks-subtable">
                                                                    <table>
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Pick ID</th>
                                                                                <th>Number</th>
                                                                                <th>Dashboard</th>
                                                                                <th>Date</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <template
                                                                                v-for="pick in getUserLotteryPicks(user.id, lottery.id)"
                                                                                :key="pick?.id || index">
                                                                                <tr v-if="pick"
                                                                                    :class="{ 'cancelled-pick': pick.deleted_at }">
                                                                                    <td>{{ pick.id || 'N/A' }}</td>
                                                                                    <td>{{ pick.picked_number || 'N/A'
                                                                                        }}</td>
                                                                                    <td>
                                                                                        <span
                                                                                            v-if="pick.lottery_dashboard">
                                                                                            {{
                                                                                            pick.lottery_dashboard.dashboard
                                                                                            || 'N/A' }}
                                                                                            ({{
                                                                                            formatDate(pick.lottery_dashboard.date)
                                                                                            }})
                                                                                        </span>
                                                                                        <span v-else>N/A</span>
                                                                                    </td>
                                                                                    <td>{{ formatDate(pick.deleted_at ||
                                                                                        pick.created_at) }}</td>
                                                                                    <td>
                                                                                        <span v-if="pick.deleted_at"
                                                                                            class="badge bg-danger">Cancelled</span>
                                                                                        <span v-else class="badge"
                                                                                            :class="pick.status === 'picked' ? 'bg-success' : 'bg-warning'">
                                                                                            {{ pick.status === 'picked'
                                                                                            ? 'Picked' : (pick.status ||
                                                                                            'Pending') }}
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
            currentPage: 1,
            perPage: 5,
            expandedUsers: [],
            expandedLotteries: [],
            loading: false,
            error: null
        };
    },
    computed: {
        // Get unique users from the refunds data with null checks
        uniqueUsers() {
            try {
                const usersMap = new Map();
                this.refunds.forEach(refund => {
                    if (refund?.user?.id && !usersMap.has(refund.user.id)) {
                        usersMap.set(refund.user.id, refund.user);
                    }
                });
                return Array.from(usersMap.values());
            } catch (error) {
                console.error("Error processing users:", error);
                this.error = "Failed to process user data";
                return [];
            }
        },

        // Filter users based on search query
        filteredUsers() {
            const query = this.searchQuery.toLowerCase();
            return this.uniqueUsers.filter(user => {
                try {
                    return (user?.name?.toLowerCase().includes(query) ||
                        user?.id?.toString().includes(query));
                } catch {
                    return false;
                }
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
        // Get unique lotteries for a specific user with null checks
        getUserLotteries(userId) {
            try {
                const lotteriesMap = new Map();
                this.refunds.forEach(refund => {
                    if (refund?.user?.id === userId && refund?.lottery?.id && !lotteriesMap.has(refund.lottery.id)) {
                        lotteriesMap.set(refund.lottery.id, refund.lottery);
                    }
                });
                return Array.from(lotteriesMap.values());
            } catch (error) {
                console.error("Error processing lotteries:", error);
                return [];
            }
        },

        // Get all picks for a specific user and lottery with null checks
        getUserLotteryPicks(userId, lotteryId) {
            try {
                return this.refunds.filter(refund => {
                    return refund?.user?.id === userId &&
                        refund?.lottery?.id === lotteryId;
                });
            } catch (error) {
                console.error("Error processing picks:", error);
                return [];
            }
        },

        // Toggle display of lotteries for a user
        toggleUserLotteries(userId) {
            const index = this.expandedUsers.indexOf(userId);
            if (index === -1) {
                this.expandedUsers.push(userId);
            } else {
                this.expandedUsers.splice(index, 1);
                // Also remove any expanded lotteries for this user
                this.expandedLotteries = this.expandedLotteries.filter(item => !item.startsWith(`${userId}-`));
            }
        },

        // Toggle display of picks for a user's lottery
        toggleLotteryPicks(userId, lotteryId) {
            const key = `${userId}-${lotteryId}`;
            const index = this.expandedLotteries.indexOf(key);
            if (index === -1) {
                this.expandedLotteries.push(key);
            } else {
                this.expandedLotteries.splice(index, 1);
            }
        },

        getItemNumber(index) {
            return (this.currentPage - 1) * this.perPage + index + 1;
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleDateString();
            } catch {
                return 'Invalid Date';
            }
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
                doc.text('User Picks Report', 14, 20);

                // Prepare table data
                const tableData = [];
                this.filteredUsers.forEach(user => {
                    // Add user row
                    tableData.push([
                        { content: user?.id || 'N/A', rowSpan: 1 },
                        { content: user?.name || 'N/A', rowSpan: 1 },
                        { content: '', rowSpan: 1 },
                        { content: '', rowSpan: 1 },
                        { content: '', rowSpan: 1 }
                    ]);

                    // Add lottery rows for this user
                    this.getUserLotteries(user.id).forEach(lottery => {
                        tableData.push([
                            { content: '', rowSpan: 1 },
                            { content: lottery?.id || 'N/A', rowSpan: 1 },
                            { content: lottery?.name || 'N/A', rowSpan: 1 },
                            { content: '', rowSpan: 1 },
                            { content: '', rowSpan: 1 }
                        ]);

                        // Add pick rows for this lottery
                        this.getUserLotteryPicks(user.id, lottery.id).forEach(pick => {
                            const status = pick?.deleted_at ? 'Cancelled' :
                                (pick?.status === 'picked' ? 'Picked' : 'Pending');
                            const date = pick?.deleted_at || pick?.created_at;

                            tableData.push([
                                { content: '', rowSpan: 1 },
                                { content: '', rowSpan: 1 },
                                { content: pick?.picked_number || 'N/A', rowSpan: 1 },
                                {
                                    content: pick?.lottery_dashboard ?
                                        `${pick.lottery_dashboard.dashboard || 'N/A'} (${this.formatDate(pick.lottery_dashboard.date)})` : 'N/A',
                                    rowSpan: 1
                                },
                                { content: status, rowSpan: 1 }
                            ]);
                        });
                    });
                });

                // Add table using autoTable
                autoTable(doc, {
                    startY: 30,
                    head: [['User ID', 'User Name', 'Lottery', 'Pick Details', 'Status']],
                    body: tableData,
                    theme: 'grid',
                    headStyles: { fillColor: [66, 66, 66] },
                    styles: { textColor: [0, 0, 0], fontSize: 10 },
                    columnStyles: {
                        0: { cellWidth: 20 }, // User ID
                        1: { cellWidth: 30 }, // User Name
                        2: { cellWidth: 30 }, // Lottery
                        3: { cellWidth: 60 }, // Pick Details
                        4: { cellWidth: 20 }  // Status
                    }
                });

                // Add timestamp
                const date = new Date().toLocaleString();
                doc.setFontSize(8);
                doc.text(`Generated on: ${date}`, 14, doc.internal.pageSize.height - 10);

                // Download the PDF
                doc.save(`user_picks_report_${date.split(',')[0].replace(/\//g, '-')}.pdf`);
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
/* Your existing styles remain the same */
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

.bg-warning {
    background-color: #ffc107;
    color: #000;
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

.pdf-btn {
    background-color: #007bff;
    border: none;
    padding: 8px 16px;
}

.pdf-btn:hover {
    background-color: #0056b3;
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

tbody tr:hover {
    background-color: #444;
}

/* Subtable styles */
.lottery-subtable,
.picks-subtable {
    margin: 10px;
    padding: 10px;
    background-color: #252525;
    border-radius: 5px;
}

.lottery-subtable table,
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

.cancelled-pick {
    opacity: 0.7;
    text-decoration: line-through;
    color: #ff6b6b;
}

/* Button styles */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

/* Icon styles */
.bi {
    margin-right: 5px;
}
</style>