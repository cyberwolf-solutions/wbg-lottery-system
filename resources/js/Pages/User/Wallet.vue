<script setup>
import { ref, onMounted } from 'vue';  // Make sure to import onMounted
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
    winnings: Array
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

// Handle withdraw request
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

    try {
        const response = await axios.post('/api/wallet/withdraw', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        showResponse('Withdrawal request submitted successfully!', 'bottom');
        console.log(response.data);
        window.location.reload();

        // Close the modal after success
        const modal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
        if (modal) {
            modal.hide();
        }
    } catch (error) {
        console.error("Error submitting withdrawal request:", error);
        showResponse('Failed to submit withdrawal request.', 'bottom');
    }
};

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
                        Bank details
                    </label>
                    <input id="bank" type="text" :value="props.bank.length ? props.bank[0]?.bank : 'No Bank Available'"
                        disabled
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>

                <div class="mb-4">
                    <label for="Note" class="block text-sm font-medium text-gray-700">
                        Account Number
                    </label>
                    <input id="number" type="text"
                        :value="props.bank.length ? props.bank[0]?.number : 'No Bank Available'" disabled
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="wnumber" class="block text-sm font-medium text-gray-700">
                        Wallet Address
                    </label>
                    <input id="wnumber" type="text"
                        :value="props.walletAddress.length ? props.walletAddress[0]?.address : 'No wallet address'"
                        disabled
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
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
                    <label for="depositType" class="block text-sm font-medium text-gray-700">
                        Deposit Type (Optional)
                    </label>
                    <input id="depositType" v-model="depositType" type="text"
                        placeholder="Enter deposit type (Optional)"
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
                                Withdrawal Type
                            </label>
                            <input id="depositType" v-model="depositType" type="text" placeholder="Enter deposit type "
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
                                            <!-- <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Lottery Name</th> -->
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Transaction Type</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Picked Numbers</th>
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
                                        <!-- Loop through transactions and display data -->
                                        <tr v-for="transaction in transactions" :key="transaction.id">

                                            <!-- <td class="px-6 py-4 text-gray-800">{{ transaction.lottery.name ? transaction.name : 'N/A'  }}</td> -->
                                            <td class="px-6 py-4 text-gray-800">{{ transaction.type }}</td>
                                            <td class="px-6 py-4 text-green-500 font-medium">
                                                {{ transaction.picked_number === null ? transaction.type :
                                                transaction.picked_number }}
                                            </td>

                                            <td class="px-6 py-4 text-gray-600">{{ transaction.transaction_date ?
                                                transaction.transaction_date : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-800">{{
                                                transaction.amount
                                            }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- table 3 -->
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
                                                Date</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="withdrawalItem in withdrawal" :key="withdrawalItem.id"
                                            class="hover:bg-gray-100 transition-all duration-200 ease-in-out">
                                            <!-- <td class="px-6 py-4 text-gray-800">{{ withdrawalItem.wallet.name }}</td> -->
                                            <td class="px-6 py-4 text-green-500 font-medium">{{
                                                withdrawalItem.amount }}
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
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Table -->

                    <!-- table 3 -->
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
                                                Date</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Status
                                            </th>
                                            <!-- <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Status
                                            </th> -->

                                        </tr>
                                    </thead>
                                    <tbody>


                                        <tr v-for="depositItem in deposit" :key="depositItem.id"
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

                                            <!-- <td class="px-6 py-4 text-gray-800">
                                                {{ depositItem.status === 0 ? 'Pending' : (depositItem.status === 1 ?
                                                    'Completed' : 'Declined') }}
                                            </td> -->
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



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
                                                Price</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Winning Number</th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Lottery Name
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Draw Number
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                                Draw Date
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>


                                        <tr v-for="winningsItem in winnings" :key="winningsItem.id"
                                            class="hover:bg-gray-100 transition-all duration-200 ease-in-out">
                                            <td class="px-6 py-4 text-gray-800">{{ winningsItem.price }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ winningsItem.winning_number }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-800">{{ winningsItem.lottery.name }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{
                                                winningsItem.lottery_dashboard.draw_number }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{
                                                winningsItem.lottery_dashboard.date }}
                                            </td>

                                        </tr>


                                    </tbody>
                                </table>
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
</style>