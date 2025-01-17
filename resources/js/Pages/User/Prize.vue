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
      </div>
    </template>


    <div class="container-fluid items-center justify-center rounded">
      <div class="row items-center justify-center rounded">
        <!-- Main Content -->
        <div class="col-10 mt-4">

          <div class="card" style="border-style: none; background:white;">
            <div class="card-body">
              <div class="px-3">
                <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
                  <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Name</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Amount</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
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

              <div class="px-3">
                <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
                  <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Name</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Amount</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
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

              <div class="px-3">
                <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
                  <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Name</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Amount</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
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
