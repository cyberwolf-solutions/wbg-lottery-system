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
                    class="ml-4 px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded shadow hover:bg-blue-600" style="background-color: rgb(96, 200, 242);"
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

        <!-- view card Modal -->
        <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Card details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Card number</label>
                            <input type="email" class="form-control form-control-sm" disabled id="exampleFormControlInput1"
                                placeholder="1002 9737 3822" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">CVV</label>
                            <input type="email" class="form-control form-control-sm" disabled id="exampleFormControlInput1"
                                placeholder="5684" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Expiary date</label>
                            <input type="email" class="form-control form-control-sm" disabled id="exampleFormControlInput1"
                                placeholder="09/27" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                            <input type="email" class="form-control form-control-sm" disabled id="exampleFormControlInput1"
                                placeholder="name" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: rgb(96, 200, 242);;" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>

          <!-- edit card Modal -->
          <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Card details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Card number</label>
                            <input type="email" class="form-control form-control-sm"  id="exampleFormControlInput1"
                                placeholder="Enter Card number" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">CVV</label>
                            <input type="email" class="form-control form-control-sm"  id="exampleFormControlInput1"
                                placeholder="Enter CVV" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Expiary date</label>
                            <input type="email" class="form-control form-control-sm"  id="exampleFormControlInput1"
                                placeholder="Enter Expiary date" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                            <input type="email" class="form-control form-control-sm"  id="exampleFormControlInput1"
                                placeholder="Enter name" style="border-radius: 10px;font-size: 13px;height: 10px;">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: rgb(96, 200, 242);;" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
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
                        <div class="d-flex justify-content-between align-items-center bg-light p-4 rounded shadow-sm">
                            <!-- Total Budget -->
                            <div class="text-center">
                                <p class="mb-2 text-secondary">Total Budget</p>
                                <h3 class="fw-bold text-gray">$85,125.00</h3>
                            </div>
                            <!-- Credit Limit -->
                            <div class="text-center">
                                <p class="mb-2 text-secondary">Credit Limit</p>
                                <h3 class="fw-bold" style="color: rgb(96, 200, 242);">$110,000.00</h3>
                            </div>
                            <!-- My Goals -->
                            <div class="text-center">
                                <p class="mb-2 text-secondary">My Goals</p>
                                <h3 class="fw-bold" style="color: rgb(96, 200, 242);">$2,177.00</h3>
                            </div>
                            <!-- Saved -->
                            <div class="text-center">
                                <p class="mb-2 text-secondary">Saved</p>
                                <h3 class="fw-bold" style="color: rgb(96, 200, 242);">$15,125.00</h3>
                            </div>
                        </div>

                        <!-- Card Section -->
                        <div class="p-4 my-4 bg-white rounded shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Card Info -->
                                <div class="d-flex align-items-center">
                                    <div class="me-4 text-center">
                                        <h5 class="mb-0 text-primary">VISA</h5>
                                        <small class="text-muted">Gold</small>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-0 fw-semibold">**** 2321</p>
                                        <small class="text-secondary">Exp: 02/26</small>
                                    </div>
                                </div>
                                <!-- Badge -->
                                <div>
                                    <button class="btn-sm font-bold text-white rounded-lg ml-4" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop"
                                        style="background-color: rgb(96, 200, 242);border-radius: 100px;"><i class="bi bi-eye-fill"></i></button>

                                    <button class="btn-sm btn-warning font-bold text-white rounded-lg ml-4" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop1"
                                        style="border-radius: 100px;"><i class="bi bi-pencil-square"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="border-style: none; background:white;">
                        <div class="card-body">
                            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                <table class="table-auto w-full border-collapse border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Lottery
                                                Name
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Picked
                                                Numbers
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Prize
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Paypal</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$2,000.00</td>
                                            <td class="px-4 py-3 text-gray-600">20.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Meta</td>
                                            <td class="px-4 py-3 text-red-500 font-medium">-$170.00</td>
                                            <td class="px-4 py-3 text-gray-600">20.02.2023</td>
                                            <td class="px-4 py-3 text-yellow-600">In Progress</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Apple</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$2,187.00</td>
                                            <td class="px-4 py-3 text-gray-600">18.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Playstation</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$4,177.00</td>
                                            <td class="px-4 py-3 text-gray-600">16.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Amazon</td>
                                            <td class="px-4 py-3 text-red-500 font-medium">-$277.00</td>
                                            <td class="px-4 py-3 text-gray-600">15.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
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

                            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                <table class="table-auto w-full border-collapse border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Lottery
                                                Name
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Picked
                                                Numbers
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Prize
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Paypal</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$2,000.00</td>
                                            <td class="px-4 py-3 text-gray-600">20.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Meta</td>
                                            <td class="px-4 py-3 text-red-500 font-medium">-$170.00</td>
                                            <td class="px-4 py-3 text-gray-600">20.02.2023</td>
                                            <td class="px-4 py-3 text-yellow-600">In Progress</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Apple</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$2,187.00</td>
                                            <td class="px-4 py-3 text-gray-600">18.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Playstation</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$4,177.00</td>
                                            <td class="px-4 py-3 text-gray-600">16.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Amazon</td>
                                            <td class="px-4 py-3 text-red-500 font-medium">-$277.00</td>
                                            <td class="px-4 py-3 text-gray-600">15.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
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

                            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                <table class="table-auto w-full border-collapse border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Lottery
                                                Name
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Picked
                                                Numbers
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Prize
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Paypal</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$2,000.00</td>
                                            <td class="px-4 py-3 text-gray-600">20.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Meta</td>
                                            <td class="px-4 py-3 text-red-500 font-medium">-$170.00</td>
                                            <td class="px-4 py-3 text-gray-600">20.02.2023</td>
                                            <td class="px-4 py-3 text-yellow-600">In Progress</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Apple</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$2,187.00</td>
                                            <td class="px-4 py-3 text-gray-600">18.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Playstation</td>
                                            <td class="px-4 py-3 text-green-500 font-medium">$4,177.00</td>
                                            <td class="px-4 py-3 text-gray-600">16.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800">Amazon</td>
                                            <td class="px-4 py-3 text-red-500 font-medium">-$277.00</td>
                                            <td class="px-4 py-3 text-gray-600">15.02.2023</td>
                                            <td class="px-4 py-3 text-gray-800">Completed</td>
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
