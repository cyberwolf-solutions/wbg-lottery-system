<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Cancelled Picks Report</h2>
                    <button @click="downloadPDF" class="btn btn-primary pdf-btn">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </button>
                </div>

                <div class="lottery-table">
                    <!-- Search Bar -->
                    <input v-model="searchQuery" type="text" class="form-control mb-3"
                        placeholder="Search by amount, date, or user..." />

                    <!-- Refund Report Table -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Number</th>
                                <!-- <th>Type</th> -->
                                <th>Lottery</th>
                                <th>Dashboard Id</th>
                                <th>Cancelled Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(refund, index) in paginatedRefunds" :key="refund.id">
                                <td>{{ getItemNumber(index) }}</td>
                                <td>
                                    <span v-if="refund.user">
                                        {{ refund.user.name }}
                                    </span>
                                    <span v-else>N/A</span>
                                </td>
                                <td>{{ (refund.picked_number) }}</td>
                                <!-- <td>{{ refund.type }}</td> -->
                                <td>
                                    <span v-if="refund.lottery">
                                        {{ refund.lottery.name }}
                                    </span>
                                    <span v-else>N/A</span>
                                </td>
                                <td>
                                    <span v-if="refund.lottery_dashboard">
                                        {{ refund.lottery_dashboard.dashboard }} || {{
                                            formatDate(refund.lottery_dashboard.date) }} ||
                                        {{ refund.lottery_dashboard.draw_number }}
                                    </span>
                                    <span v-else>N/A</span>
                                </td>
                                <td>{{ formatDate(refund.deleted_at) }}</td>
                                <td>Not Enough Participants</td>
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
            perPage: 10,
        };
    },
    computed: {
        filteredRefunds() {
            const query = this.searchQuery.toLowerCase();
            return this.refunds.filter(refund => {
                // Check picked_number
                if (refund.picked_number?.toString().includes(query)) {
                    return true;
                }

                // Check user name if available
                if (refund.user?.name?.toLowerCase().includes(query)) {
                    return true;
                }

                // Check lottery name
                if (refund.lottery?.name?.toLowerCase().includes(query)) {
                    return true;
                }

                // Check dashboard name or date
                if (refund.lottery_dashboard?.dashboard?.toLowerCase().includes(query) ||
                    refund.lottery_dashboard?.date?.toLowerCase().includes(query)) {
                    return true;
                }

                return false;
            });
        }
        ,
        paginatedRefunds() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredRefunds.slice(start, start + this.perPage);
        },
        totalPages() {
            return Math.ceil(this.filteredRefunds.length / this.perPage);
        }
    },
    methods: {
        getItemNumber(index) {
            return (this.currentPage - 1) * this.perPage + index + 1;
        },
        safeFormatBalance(value) {
            if (value === null || value === undefined) return '0.00';
            const num = Number(value);
            return isNaN(num) ? '0.00' : num.toFixed(2);
        },
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        },
        formatDateTime(dateTimeString) {
            if (!dateTimeString) return '';
            const date = new Date(dateTimeString);
            return date.toLocaleString();
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
            doc.text('Refund Report', 14, 20);

            // Prepare table data with sequential numbering
            const tableData = this.filteredRefunds.map((refund, index) => [
                (this.currentPage - 1) * this.perPage + index + 1, // Sequential number
                refund.wallet && refund.wallet.user ?
                    `${refund.wallet.user.name} || ID: ${refund.wallet.user.id}` : 'N/A',
                `$${this.safeFormatBalance(refund.amount)}`,
                refund.lottery ? refund.lottery.name : 'N/A',
                refund.lottery_dashboard ?
                    `${refund.lottery_dashboard.dashboard} || ${this.formatDate(refund.lottery_dashboard.date)} || ${refund.lottery_dashboard.draw_number}` : 'N/A',
                this.formatDate(refund.transaction_date),
                this.formatDateTime(refund.created_at)
            ]);

            // Add table using autoTable
            autoTable(doc, {
                startY: 30,
                head: [['No.', 'User', 'Amount', 'Lottery', 'Dashboard', 'Transaction Date', 'Created At']],
                body: tableData,
                theme: 'striped',
                headStyles: { fillColor: [66, 66, 66] },
                styles: { textColor: [0, 0, 0], fontSize: 10 },
                columnStyles: {
                    0: { cellWidth: 10 }, // No. column
                    1: { cellWidth: 30 }, // User column
                    2: { cellWidth: 20 }, // Amount column
                    3: { cellWidth: 25 }, // Lottery column
                    4: { cellWidth: 40 }, // Dashboard column (wider for more info)
                    5: { cellWidth: 25 }, // Transaction Date
                    6: { cellWidth: 25 }  // Created At
                }
            });

            // Add timestamp
            const date = new Date().toLocaleString();
            doc.setFontSize(8);
            doc.text(`Generated on: ${date}`, 14, doc.internal.pageSize.height - 10);

            // Download the PDF
            doc.save(`refund_report_${date.split(',')[0].replace(/\//g, '-')}.pdf`);
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