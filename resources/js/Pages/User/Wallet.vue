<script setup>
import { ref, onMounted, computed } from 'vue';  // Make sure to import onMounted
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const isModalOpen = ref(false);
const attachment = ref(null);
const withdrawalAmount = ref(null);

const responseMessage = ref(null);
const responseClass = ref('bottom-response');

function showResponse(message, position = 'bottom') {
    responseMessage.value = message;
    responseClass.value = position === 'bottom' ? 'top-response' : 'bottom-response';

    // Hide the message after 5 seconds
    setTimeout(() => {
        responseMessage.value = null;
    }, 3000);
}


const props = defineProps({
    status: String,
    transactions: Array,
    withdrawal: Array,
    deposit: Array,
    wallet: Array,
    bank: Array,
    walletAddress: Array,
    winnings: Array,
    affiliateData: Array
});

// Modal control functions
const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};
// handleFileUpload
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        attachment.value = file;
    }
};
const handleRequest = async () => {
    if (!creditAmount.value || !referenceNumber.value || !attachment.value) {
        // alert("Please fill in all required fields.");
        showResponse('Please fill in all required fields.', 'bottom');
        return;
    }

    const formData = new FormData();
    formData.append('bank', props.bank[0]?.bank || '');
    formData.append('account_number', props.bank[0]?.number || '');
    formData.append('amount', creditAmount.value);
    formData.append('reference', referenceNumber.value);
    formData.append('attachment', attachment.value);

    // alert(attachment.value);

    if (depositType.value) {
        formData.append('deposit_type', depositType.value);
    }

    try {
        const response = await axios.post('/api/wallet/request', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        // alert("Request submitted successfully!");
        showResponse('Request submitted successfully', 'bottom');
        console.log(response.data);

        // Close the modal after success
        closeModal();
    } catch (error) {
        console.error("Error submitting request:", error);
        // alert("Failed to submit request.");
        showResponse('Failed to submit request.', 'bottom');
    }
};
const handlewithdraw = async () => {
    if (!withdrawalAmount.value || withdrawalAmount.value <= 0) {
        showResponse('Please enter a valid withdrawal amount.', 'bottom');
        return;
    }

    if (!depositType.value) {
        showResponse('Please add withdraw type.', 'bottom');
        return;
    }

    const formData = new FormData();
    formData.append('wallet_id', props.wallet.id);
    formData.append('amount', withdrawalAmount.value);
    if (depositType.value) {
        formData.append('withdrawal_type', depositType.value);
    }
    formData.append('wallet_address', Address.value);

    const modal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
    if (modal) {
        modal.hide();
    }


    try {
        const response = await axios.post('/api/wallet/withdraw', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        showResponse('Withdrawal request submitted successfully!', 'bottom');
        console.log(response.data);

        // Close the modal using Bootstrap's modal method
        const modal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
        if (modal) {
            modal.hide();
        }

        // Reset form fields
        withdrawalAmount.value = null;
        depositType.value = null;

        // Optional: reload the page after a short delay
        setTimeout(() => {
            window.location.reload();
        }, 1500);

    } catch (error) {
        console.error("Error submitting withdrawal request:", error);

        showResponse('Failed to submit withdrawal request.', 'bottom');
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }
};

const currentPage = ref({
    transactions: 1,
    refunds: 1,
    withdrawals: 1,
    deposits: 1,
    winnings: 1,
    affiliate: 1
});

const itemsPerPage = 8;


const visiblePageRange = computed(() => {
    return (table) => {
        const current = currentPage.value[table];
        const total = totalPages.value[table];
        const range = 2;
        let start = Math.max(1, current - range);
        let end = Math.min(total, current + range);


        if (current <= range + 1) {
            end = Math.min(1 + (range * 2), total);
        }
        if (current >= total - range) {
            start = Math.max(total - (range * 2), 1);
        }

        return Array.from({ length: end - start + 1 }, (_, i) => start + i);
    };
});
// Computed properties for each table
const filteredTransactions = computed(() => {
    return props.transactions;
});

const filteredRefunds = computed(() => {
    return props.transactions.filter(t => t.type === 'refund');
});

const paginatedTransactions = computed(() => {
    const start = (currentPage.value.transactions - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredTransactions.value.slice(start, end);
});

const paginatedRefunds = computed(() => {
    const start = (currentPage.value.refunds - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredRefunds.value.slice(start, end);
});

const paginatedWithdrawals = computed(() => {
    const start = (currentPage.value.withdrawals - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.withdrawal.slice(start, end);
});

const paginatedDeposits = computed(() => {
    const start = (currentPage.value.deposits - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.deposit.slice(start, end);
});

const paginatedWinnings = computed(() => {
    const start = (currentPage.value.winnings - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.winnings.slice(start, end);
});

const paginatedAffiliate = computed(() => {
    const start = (currentPage.value.affiliate - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.affiliateData.slice(start, end);
});

// Total pages for each table
const totalPages = computed(() => ({
    transactions: Math.ceil(filteredTransactions.value.length / itemsPerPage),
    refunds: Math.ceil(filteredRefunds.value.length / itemsPerPage),
    withdrawals: Math.ceil(props.withdrawal.length / itemsPerPage),
    deposits: Math.ceil(props.deposit.length / itemsPerPage),
    winnings: Math.ceil(props.winnings.length / itemsPerPage),
    affiliate: Math.ceil(props.affiliateData.length / itemsPerPage)
}));




// Navigation functions for each table
function nextPage(table) {
    if (currentPage.value[table] < totalPages.value[table]) {
        currentPage.value[table]++;
    }
}

function prevPage(table) {
    if (currentPage.value[table] > 1) {
        currentPage.value[table]--;
    }
}

function goToPage(table, page) {
    currentPage.value[table] = page;
}

// Log wallet data when the component is mounted
onMounted(() => {
    console.log(props.wallet); // This will log the wallet data to the console
    console.log(props.bank);
});
</script>


<template>


    <Head title="Wallet" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Wallet
                </h2>
                <!-- <button
                    class="ml-4 px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded shadow hover:bg-blue-600"
                    style="background-color: rgb(96, 200, 242);" @click="openModal">
                    Request
                </button> -->
            </div>

            <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
                <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
                    {{ responseMessage }}
                </div>
            </div>



        </template>
        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Request Credit</h3>
                <div class="mb-4">
                    <label for="Note" class="block text-sm font-medium text-gray-700">
                        USDT Deposit Wallet
                    </label>
                    <input id="bank" type="text" :value="props.bank.length ? props.bank[0]?.bank : 'No Bank Available'"
                        disabled
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>

                <div class="mb-4">
                    <label for="Note" class="block text-sm font-medium text-gray-700">
                        Wallet address and details
                    </label>
                    <div class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm bg-gray-100"
                        style="white-space: pre-line;">
                        {{ props.bank.length ? props.bank[0]?.number : 'No Bank Available' }}
                    </div>
                </div>


                <div class="mb-4">
                    <label for="creditAmount" class="block text-sm font-medium text-gray-700">
                        Deposit Amount
                    </label>
                    <input id="creditAmount" v-model="creditAmount" type="number" placeholder="Enter credit amount"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>

                <div class="mb-4">
                    <label for="referenceNumber" class="block text-sm font-medium text-gray-700">
                        Reference no.
                    </label>
                    <input id="referenceNumber" v-model="referenceNumber" type="text"
                        placeholder="Add a reference number."
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>



                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">
                        Attachment
                    </label>
                    <input id="attachment" type="file" @change="handleFileUpload" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                </div>

                <div class="flex justify-end space-x-4">
                    <button class="px-4 py-2 bg-gray-300 text-gray-800 rounded shadow hover:bg-gray-400"
                        @click="closeModal">
                        Cancel
                    </button>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600"
                        @click="handleRequest">
                        Request
                    </button>
                </div>
            </div>
        </div>

        <!-- view card Modal -->
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-lg shadow-lg">
                    <div class="modal-header border-0">
                        <h1 class="modal-title fs-5 text-lg font-semibold" id="staticBackdropLabel">Bank Details
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Account Number Input -->
                        <div class="mb-4">
                            <label for="accountNumber" class="block text-sm font-medium text-gray-700">
                                Wallet ID
                            </label>
                            <input type="text" id="walletId" disabled
                                class="form-control form-control-sm mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                :value="props.wallet.id">

                        </div>
                        <div class="mb-4">
                            <label for="accountNumber" class="block text-sm font-medium text-gray-700">Available
                                balance
                            </label>
                            <input type="text" id="accountNumber" disabled
                                class="form-control form-control-sm mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                :value="props.wallet.available_balance">

                        </div>

                        <div class="mb-4">
                            <label for="depositType" class="block text-sm font-medium text-gray-700">
                                Withdrawal Type (TRC/ERC/USDT Wallet)
                            </label>
                            <input id="depositType" v-model="depositType" type="text" placeholder="Enter deposit type "
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                        </div>
                        <div class="mb-4">
                            <label for="Address" class="block text-sm font-medium text-gray-700">
                                Wallet Address
                            </label>
                            <input id="Address" v-model="Address" type="text" placeholder="Enter wallet address"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                        </div>


                        <!-- Account Name Input -->
                        <div class="mb-4">
                            <label for="accountName" id="amount"
                                class="block text-sm font-medium text-gray-700">Withdrawal
                                amount</label>
                            <input type="number" id="accountName" v-model="withdrawalAmount"
                                class="form-control form-control-sm mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="enter">
                        </div>


                    </div>
                    <div class="modal-footer border-0">
                        <button class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600"
                            @click="handlewithdraw">
                            Withdraw
                        </button>
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded shadow hover:bg-gray-400"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- edit card Modal -->
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-lg shadow-lg">
                    <div class="modal-header border-0">
                        <h1 class="modal-title fs-5 text-lg font-semibold" id="staticBackdropLabel">Bank Details
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Account Number Input -->
                        <div class="mb-4">
                            <label for="accountNumber" class="block text-sm font-medium text-gray-700">Account
                                Number</label>
                            <input type="text" id="accountNumber"
                                class="form-control form-control-sm mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter Account Number">
                        </div>

                        <!-- Account Name Input -->
                        <div class="mb-4">
                            <label for="accountName" class="block text-sm font-medium text-gray-700">Account
                                Name</label>
                            <input type="text" id="accountName"
                                class="form-control form-control-sm mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter Account Name">
                        </div>

                        <!-- Bank Name Input -->
                        <div class="mb-4">
                            <label for="bankName" class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" id="bankName"
                                class="form-control form-control-sm mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter Bank Name">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded shadow hover:bg-gray-400"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600">Save</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="container-fluid items-center justify-center rounded">
            <div class="row items-center justify-center rounded">
                <!-- Main Content -->
                <div class="col-10">
                    <!-- <div class="d-flex justify-content-between align-items-center py-4 px-3">
                        <h4>Balance</h4>
                       <p style="color: silver;">Here you can see all the statistics</p>
                    </div> -->
                    <div class="px-3 mt-6">
                        <!-- Summary Section -->
                        <div
                            class="d-flex justify-content-between align-items-center bg-gradient p-4 rounded-xl shadow-lg">
                            <!-- Total Budget -->
                            <div class="text-center">
                                <p class="mb-2 text-muted">Total Balance</p>
                                <h3 class="fw-bold text-dark">{{ props.wallet?.available_balance }}</h3>
                            </div>
                            <!-- Credit Limit -->
                            <div class="text-center">
                                <button class="custom-button mt-3" @click="openModal">Deposit</button>
                            </div>
                            <!-- My Goals -->
                            <div class="text-center">
                                <button class="custom-button mt-3" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"
                                    style="transition: background-color 0.3s ease;">Withdrawal</button>
                            </div>

                        </div>


                    </div>






                    <!-- table 2 -->
                    <div
                        class="card bg-gradient-to-r from-gray-100 via-blue-50 to-indigo-50 rounded-xl shadow-lg p-4 mt-16">
                        <div class="card-header text-xl font-bold text-gray-800 mb-4">Transactions</div>
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-xl rounded-lg p-6">
                                <table class="min-w-full table-auto">
                                    <thead style="background-color: #60c8f2;">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Transaction Type</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Type || Picked Numbers</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="transaction in paginatedTransactions" :key="transaction.id">
                                            <td class="px-6 py-4 text-gray-800">{{ transaction.type }}</td>
                                            <td class="px-6 py-4 text-green-500 font-medium">
                                                {{ transaction.picked_number === null ? transaction.type :
                                                    transaction.picked_number }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                {{ transaction.transaction_date ? transaction.transaction_date : 'N/A'
                                                }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-800">{{ transaction.amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div
                                    class="flex items-center justify-between mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ (currentPage.transactions - 1) *
                                                    itemsPerPage + 1
                                                    }}</span>
                                                to
                                                <span class="font-medium">{{ Math.min(currentPage.transactions *
                                                    itemsPerPage,
                                                    filteredTransactions.length) }}</span>
                                                of
                                                <span class="font-medium">{{ filteredTransactions.length }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination">
                                                <button @click="prevPage('transactions')"
                                                    :disabled="currentPage.transactions === 1"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.transactions === 1 }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    &larr; Previous
                                                </button>

                                                <button v-for="page in visiblePageRange('transactions')" :key="page"
                                                    @click="goToPage('transactions', page)"
                                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600 z-10': currentPage.transactions === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage.transactions !== page }"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                    {{ page }}
                                                </button>

                                                <button @click="nextPage('transactions')"
                                                    :disabled="currentPage.transactions === totalPages.transactions"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.transactions === totalPages.transactions }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    Next &rarr;
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Refund Transactions Table -->
                    <div
                        class="card bg-gradient-to-r from-gray-100 via-red-50 to-pink-50 rounded-xl shadow-lg p-4 mt-16">
                        <div class="card-header text-xl font-bold text-gray-800 mb-4">Transaction Refunds</div>
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-xl rounded-lg p-6">
                                <table class="min-w-full table-auto">
                                    <thead style="background-color: #f28585;">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Transaction Type</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Lottery</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Lottery Draw</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="transaction in paginatedRefunds" :key="transaction.id">
                                            <td class="px-6 py-4 text-gray-800">Refund</td>
                                            <td class="px-6 py-4 text-green-500 font-medium">
                                                {{ transaction.lottery.name ?? 'N/A' }} || {{
                                                    transaction.lottery_dashboard.dashboardType }}
                                            </td>
                                            <td class="px-6 py-4 text-green-500 font-medium">
                                                {{
                                                    transaction.lottery_dashboard.dashboard }} ||{{
                                                    transaction.lottery_dashboard.draw_number }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">{{ transaction.transaction_date ?? 'N/A'
                                            }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{ transaction.amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination for Refunds -->
                                <div
                                    class="flex items-center justify-between mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ (currentPage.refunds - 1) * itemsPerPage +
                                                    1
                                                }}</span>
                                                to
                                                <span class="font-medium">{{ Math.min(currentPage.refunds *
                                                    itemsPerPage,
                                                    filteredRefunds.length) }}</span>
                                                of
                                                <span class="font-medium">{{ filteredRefunds.length }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination">
                                                <button @click="prevPage('refunds')"
                                                    :disabled="currentPage.refunds === 1"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.refunds === 1 }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    &larr; Previous
                                                </button>

                                                <button v-for="page in visiblePageRange('refunds')" :key="page"
                                                    @click="goToPage('refunds', page)"
                                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600 z-10': currentPage.refunds === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage.refunds !== page }"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                    {{ page }}
                                                </button>

                                                <button @click="nextPage('refunds')"
                                                    :disabled="currentPage.refunds === totalPages.refunds"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.refunds === totalPages.refunds }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    Next &rarr;
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawals Table -->
                    <div
                        class="card bg-gradient-to-r from-gray-100 via-blue-50 to-indigo-50 rounded-xl shadow-lg p-4 mt-16">
                        <div class="card-header text-xl font-bold text-gray-800 mb-4">Withdrawals</div>
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-xl rounded-lg p-6">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gradient-to-r from-gray-300 to-gray-400 text-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Amount</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="withdrawalItem in paginatedWithdrawals" :key="withdrawalItem.id"
                                            class="hover:bg-gray-100 transition-all duration-200 ease-in-out">
                                            <td class="px-6 py-4 text-green-500 font-medium">{{ withdrawalItem.amount }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">{{ withdrawalItem.withdrawal_date }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-800">
                                                {{
                                                    withdrawalItem.status === 0 ? 'Pending' :
                                                        (withdrawalItem.status === 1 ? 'Completed' : 'Declined: ' +
                                                            withdrawalItem.decline_reason)
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination for Withdrawals -->
                                <div
                                    class="flex items-center justify-between mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ (currentPage.withdrawals - 1) *
                                                    itemsPerPage + 1
                                                }}</span>
                                                to
                                                <span class="font-medium">{{ Math.min(currentPage.withdrawals *
                                                    itemsPerPage,
                                                    withdrawal.length) }}</span>
                                                of
                                                <span class="font-medium">{{ withdrawal.length }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination">
                                                <button @click="prevPage('withdrawals')"
                                                    :disabled="currentPage.withdrawals === 1"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.withdrawals === 1 }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    &larr; Previous
                                                </button>

                                                <button v-for="page in visiblePageRange('withdrawals')" :key="page"
                                                    @click="goToPage('withdrawals', page)"
                                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600 z-10': currentPage.withdrawals === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage.withdrawals !== page }"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                    {{ page }}
                                                </button>

                                                <button @click="nextPage('withdrawals')"
                                                    :disabled="currentPage.refunds === totalPages.withdrawals"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.withdrawals === totalPages.withdrawals }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    Next &rarr;
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deposits Table -->
                    <div
                        class="card bg-gradient-to-r from-gray-100 via-blue-50 to-indigo-50 rounded-xl shadow-lg p-4 mt-16">
                        <div class="card-header text-xl font-bold text-gray-800 mb-4">Deposit</div>
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-xl rounded-lg p-6">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gradient-to-r from-gray-300 to-gray-400 text-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Amount</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="depositItem in paginatedDeposits" :key="depositItem.id"
                                            class="hover:bg-gray-100 transition-all duration-200 ease-in-out">
                                            <td class="px-6 py-4 text-gray-800">{{ depositItem.amount }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ depositItem.deposit_date }}</td>
                                            <td class="px-6 py-4 text-gray-800">
                                                {{
                                                    depositItem.status === 0 ? 'Pending' :
                                                        (depositItem.status === 1 ? 'Completed' : 'Declined: ' + (
                                                            depositItem.decline_reason))
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination for Deposits -->
                                <div
                                    class="flex items-center justify-between mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ (currentPage.deposits - 1) * itemsPerPage +
                                                    1
                                                }}</span>
                                                to
                                                <span class="font-medium">{{ Math.min(currentPage.deposits *
                                                    itemsPerPage,
                                                    deposit.length) }}</span>
                                                of
                                                <span class="font-medium">{{ deposit.length }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination">
                                                <button @click="prevPage('deposits')"
                                                    :disabled="currentPage.deposits === 1"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.deposits === 1 }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    &larr; Previous
                                                </button>

                                                <button v-for="page in visiblePageRange('deposits')" :key="page"
                                                    @click="goToPage('deposits', page)"
                                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600 z-10': currentPage.deposits === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage.deposits !== page }"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                    {{ page }}
                                                </button>

                                                <button @click="nextPage('deposits')"
                                                    :disabled="currentPage.deposits === totalPages.deposits"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.deposits === totalPages.deposits }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    Next &rarr;
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Winnings Table -->
                    <div
                        class="card bg-gradient-to-r from-gray-100 via-blue-50 to-indigo-50 rounded-xl shadow-lg p-4 mt-16">
                        <div class="card-header text-xl font-bold text-gray-800 mb-4">Winnings</div>
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-xl rounded-lg p-6">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gradient-to-r from-gray-300 to-gray-400 text-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Winning Number</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Lottery Name</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Draw
                                                Number</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Draw
                                                Date</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Type
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="winningsItem in paginatedWinnings" :key="winningsItem.id"
                                            class="hover:bg-gray-100 transition-all duration-200 ease-in-out">
                                            <td class="px-6 py-4 text-gray-800">{{ winningsItem.price }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ winningsItem.winning_number }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{ winningsItem.lottery.name }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{
                                                winningsItem.lottery_dashboard.draw_number }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-800">{{ winningsItem.lottery_dashboard.date
                                            }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{
                                                winningsItem.lottery_dashboard.dashboardType
                                            }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination for Winnings -->
                                <div
                                    class="flex items-center justify-between mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ (currentPage.winnings - 1) * itemsPerPage +
                                                    1
                                                }}</span>
                                                to
                                                <span class="font-medium">{{ Math.min(currentPage.winnings *
                                                    itemsPerPage,
                                                    winnings.length) }}</span>
                                                of
                                                <span class="font-medium">{{ winnings.length }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination">
                                                <button @click="prevPage('winnings')"
                                                    :disabled="currentPage.winnings === 1"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.winnings === 1 }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    &larr; Previous
                                                </button>

                                                <button v-for="page in visiblePageRange('winnings')" :key="page"
                                                    @click="goToPage('winnings', page)"
                                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600 z-10': currentPage.winnings === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage.winnings !== page }"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                    {{ page }}
                                                </button>

                                                <button @click="nextPage('winnings')"
                                                    :disabled="currentPage.winnings === totalPages.winnings"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.deposits === totalPages.winnings }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    Next &rarr;
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Affiliate Table -->
                    <div
                        class="card bg-gradient-to-r from-gray-100 via-blue-50 to-indigo-50 rounded-xl shadow-lg p-4 mt-16">
                        <div class="card-header text-xl font-bold text-gray-800 mb-4">Affiliate</div>
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-xl rounded-lg p-6">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gradient-to-r from-gray-300 to-gray-400 text-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                User
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="affiliateItem in paginatedAffiliate" :key="affiliateItem.id"
                                            class="hover:bg-gray-100 transition-all duration-200 ease-in-out">
                                            <td class="px-6 py-4 text-gray-800">{{ affiliateItem.price }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ affiliateItem.date }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{ affiliateItem.affiliate_user.name }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination for Affiliate -->
                                <div
                                    class="flex items-center justify-between mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ (currentPage.affiliate - 1) * itemsPerPage
                                                    + 1
                                                }}</span>
                                                to
                                                <span class="font-medium">{{ Math.min(currentPage.affiliate *
                                                    itemsPerPage,
                                                    affiliateData.length) }}</span>
                                                of
                                                <span class="font-medium">{{ affiliateData.length }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination">
                                                <button @click="prevPage('affiliate')"
                                                    :disabled="currentPage.affiliate === 1"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.affiliate === 1 }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    &larr; Previous
                                                </button>

                                                <button v-for="page in visiblePageRange('affiliate')" :key="page"
                                                    @click="goToPage('affiliate', page)"
                                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600 z-10': currentPage.affiliate === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage.affiliate !== page }"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                    {{ page }}
                                                </button>

                                                <button @click="nextPage('affiliate')"
                                                    :disabled="currentPage.winnings === totalPages.affiliate"
                                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage.deposits === totalPages.affiliate }"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    Next &rarr;
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- <span>{{ winnings }}</span> -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
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

.bottom-response {
    bottom: 20px;
}

.top-response {
    top: 20px;
}

.custom-button {
    background-color: rgb(96, 200, 242);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.custom-button:hover {
    background-color: rgb(76, 180, 222);
}
</style>