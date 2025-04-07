<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Player Report</h2>
                    <button @click="downloadPDF" class="btn btn-primary pdf-btn">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </button>
                </div>

                <div class="lottery-table">
                    <!-- Search Bar -->
                    <input v-model="searchQuery" type="text" class="form-control mb-3"
                           placeholder="Search by Player name..." />

                    <!-- Users Report Table -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Wallet Balance</th>
                                <th>Affiliate Count</th>
                                <th>Status</th>
                                <th>Join Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in paginatedUsers" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>${{ safeFormatBalance(user.wallet?.available_balance) }}</td>
                                <td>{{ user.affiliates?.length || 0 }}</td>
                                <td>
                                    <span :class="['badge', user.status === 1 ? 'bg-success' : 'bg-danger']">
                                        {{ user.status === 1 ? 'Active' : 'Not Active' }}
                                    </span>
                                </td>
                                <td>{{ formatDate(user.created_at) }}</td>
                            </tr>
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
        users: {
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
            perPage: 10
        };
    },
    computed: {
        filteredUsers() {
            return this.users.filter(user =>
                user.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
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
        safeFormatBalance(value) {
            if (value === null || value === undefined) return '0.00';
            const num = Number(value);
            return isNaN(num) ? '0.00' : num.toFixed(2);
        },
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
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
        downloadPDF() {
            const doc = new jsPDF();

            // Add title
            doc.setFontSize(18);
            doc.text('Users Report', 14, 20);

            // Prepare table data
            const tableData = this.filteredUsers.map(user => [
                user.id,
                user.name,
                user.email,
                `$${this.safeFormatBalance(user.wallet?.available_balance)}`,
                user.affiliates?.length || 0,
                user.status === 1 ? 'Active' : 'Not Active',
                this.formatDate(user.created_at)
            ]);

            // Add table using autoTable
            autoTable(doc, {
                startY: 30,
                head: [['ID', 'Name', 'Email', 'Wallet Balance', 'Affiliates', 'Status', 'Join Date']],
                body: tableData,
                theme: 'striped',
                headStyles: { fillColor: [66, 66, 66] },
                styles: { textColor: [0, 0, 0], fontSize: 10 }
            });

            // Add timestamp
            const date = new Date().toLocaleString();
            doc.setFontSize(8);
            doc.text(`Generated on: ${date}`, 14, doc.internal.pageSize.height - 10);

            // Download the PDF
            doc.save(`users_report_${date.split(',')[0].replace(/\//g, '-')}.pdf`);
        }
    }
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

tbody tr:hover {
    background-color: #444;
}
</style>