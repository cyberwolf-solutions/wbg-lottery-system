<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const isModalOpen = ref(false);


const openModal = () => {
    isModalOpen.value = true;
};


const closeModal = () => {
    isModalOpen.value = false;
};


const handleRequest = () => {
    console.log("Request submitted!");
    closeModal();
};

</script>

<template>


    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Wallet
                </h2>
                <button
                    class="ml-4 px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded shadow hover:bg-blue-600"
                    @click="openModal">
                    Request
                </button>
            </div>





        </template>
        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Request Credit</h3>
                <div class="mb-4">
                    <label for="creditAmount" class="block text-sm font-medium text-gray-700">
                        Credit Amount
                    </label>
                    <input id="creditAmount" type="number" placeholder="Enter credit amount"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="Note" class="block text-sm font-medium text-gray-700">
                        Credit Amount
                    </label>
                    <input id="Note" type="text" placeholder="Add a note"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">
                        Attachment
                    </label>
                    <input id="attachment" type="file"
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

        <div class="container-fluid items-center justify-center rounded">
            <div class="row items-center justify-center rounded">
                <!-- Main Content -->
                <div class="col-10">
                    <!-- <div class="d-flex justify-content-between align-items-center py-4 px-3">
                        <h4>Balance</h4>
                       <p style="color: silver;">Here you can see all the statistics</p>
                    </div> -->
                    <div class="px-3 mt-6">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p>Total Budget</p>
                                <h3 style="font-weight: bold;font-size: larger;color:gray">$85,125.00</h3>
                            </div>
                            <div>
                                <p>Credit Limit</p>
                                <h3 style="font-weight: bold;font-size: larger;color:rgb(96, 200, 242)">$110,000.00</h3>
                            </div>
                            <div>
                                <p>My Goals</p>
                                <h3 style="font-weight: bold;font-size: larger;color:rgb(96, 200, 242)">$2,177.00</h3>
                            </div>
                            <div>
                                <p>Saved</p>
                                <h3 style="font-weight: bold;font-size: larger;color:rgb(96, 200, 242)">$15,125.00</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Card Section -->
                    <div class=" p-4 my-4 rounded" style="border-style: none; background:white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h5 class="mb-0">VISA</h5>
                                    <small>Gold</small>
                                </div>
                                <div>
                                    <p class="mb-0">**** 2321</p>
                                    <small>Exp: 02/26</small>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-warning text-dark">Classic</span>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="border-style: none; background:white;">
                        <div class="card-body">
                            <div class="px-3">
                                <table class="table table-hover">
                                    <thead style=" border-bottom: 0.1px solid silver;">
                                        <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Paypal</td>
                                            <td class="text-success">$2,000.00</td>
                                            <td>20.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Meta</td>
                                            <td class="text-danger">-$170.00</td>
                                            <td>20.02.2023</td>
                                            <td>In Progress</td>
                                        </tr>
                                        <tr>
                                            <td>Apple</td>
                                            <td class="text-success">$2,187.00</td>
                                            <td>18.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Playstation</td>
                                            <td class="text-success">$4,177.00</td>
                                            <td>16.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Amazon</td>
                                            <td class="text-danger">-$277.00</td>
                                            <td>15.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- table 2 -->
                    <div class="card" style="border-style: none; background:white;margin-top: 60px;">
                        <div class="card-header">
                            table 2
                        </div>
                        <div class="card-body">

                            <div class="px-3">
                                <table class="table table-hover">
                                    <thead style=" border-bottom: 0.1px solid silver;">
                                        <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Paypal</td>
                                            <td class="text-success">$2,000.00</td>
                                            <td>20.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Meta</td>
                                            <td class="text-danger">-$170.00</td>
                                            <td>20.02.2023</td>
                                            <td>In Progress</td>
                                        </tr>
                                        <tr>
                                            <td>Apple</td>
                                            <td class="text-success">$2,187.00</td>
                                            <td>18.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Playstation</td>
                                            <td class="text-success">$4,177.00</td>
                                            <td>16.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Amazon</td>
                                            <td class="text-danger">-$277.00</td>
                                            <td>15.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- table 3 -->
                    <div class="card" style="border-style: none; background:white;margin-top: 60px;">
                        <div class="card-header">
                            table 3
                        </div>
                        <div class="card-body">

                            <div class="px-3">
                                <table class="table table-hover">
                                    <thead style=" border-bottom: 0.1px solid silver;">
                                        <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Paypal</td>
                                            <td class="text-success">$2,000.00</td>
                                            <td>20.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Meta</td>
                                            <td class="text-danger">-$170.00</td>
                                            <td>20.02.2023</td>
                                            <td>In Progress</td>
                                        </tr>
                                        <tr>
                                            <td>Apple</td>
                                            <td class="text-success">$2,187.00</td>
                                            <td>18.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Playstation</td>
                                            <td class="text-success">$4,177.00</td>
                                            <td>16.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                        <tr>
                                            <td>Amazon</td>
                                            <td class="text-danger">-$277.00</td>
                                            <td>15.02.2023</td>
                                            <td>Completed</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Transactions Table -->

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
