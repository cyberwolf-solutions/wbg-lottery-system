<script setup>
// import { ref, computed } from 'vue';
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Head } from '@inertiajs/vue3';

const isModalOpen = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

// Sample data
const tableData = ref([
  { name: 'Paypal',type: '12', pickedNumbers: '$2,000.00', date: '20.02.2023', prize: 'Completed' },
  { name: 'Meta', type: '12', pickedNumbers: '-$170.00', date: '20.02.2023', prize: 'In Progress' },
  { name: 'Apple',type: '12',  pickedNumbers: '$2,187.00', date: '18.02.2023', prize: 'Completed' },
  { name: 'Playstation',type: '12',  pickedNumbers: '$4,177.00', date: '16.02.2023', prize: 'Completed' },
  { name: 'Amazon', type: '12', pickedNumbers: '-$277.00', date: '15.02.2023', prize: 'Completed' },
  { name: 'Netflix',type: '12',  pickedNumbers: '$300.00', date: '14.02.2023', prize: 'Pending' },
  { name: 'Spotify', type: '12', pickedNumbers: '-$25.00', date: '13.02.2023', prize: 'Completed' },
]);

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

// Pagination logic
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredData.value.slice(start, end);
});

const filteredData = computed(() => {
  if (!searchQuery.value) return tableData.value;
  return tableData.value.filter(item =>
    Object.values(item)
      .join(' ')
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase())
  );
});

const totalPages = computed(() => Math.ceil(filteredData.value.length / itemsPerPage));

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};
</script>

<template>

  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Your Prizes</h2>
      </div>
    </template>

    <div class="container mx-auto px-4 mt-6">
      <!-- Search Bar -->
      <div class="flex justify-between items-center mb-4">
        <input type="text" v-model="searchQuery" placeholder="Search..."
          class="px-4 py-2 border border-gray-300 rounded-lg 
          shadow-sm focus:ring focus:ring-blue-300 focus:outline-none w-1/3" />
      </div>

      <!-- Table -->
      <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
        <table class="min-w-full table-auto">
          <thead class="text-white" style="background-color: #60c8f2;">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Lottery Name</th>
              <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Board Type</th>
              <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Board Price</th>
              <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Winning Prize</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-for="(item, index) in paginatedData" :key="index"
              class="hover:bg-gray-50 transition-all duration-200 ease-in-out">
              <td class="px-6 py-4 text-gray-800 font-medium">{{ item.name }}</td>
              <td class="px-6 py-4 text-gray-800 font-medium">{{ item.type }}</td>
              <td class="px-6 py-4 text-green-600 font-medium">{{ item.pickedNumbers }}</td>
              <td class="px-6 py-4 text-gray-700">{{ item.date }}</td>
              <td class="px-6 py-4 text-gray-800 font-semibold">{{ item.prize }}</td>
            </tr>
            <tr v-if="paginatedData.length === 0">
              <td colspan="4" class="px-6 py-4 text-center text-gray-500">No results found</td>
            </tr>
          </tbody>
        </table>
      </div>


      <!-- Pagination -->
      <div class="flex justify-between items-center mt-4 px-4">
        <button
          class="px-6 py-3  text-white rounded-lg hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 transition-all duration-200 ease-in-out"
          :disabled="currentPage === 1" @click="changePage(currentPage - 1)" style="background-color: #60c8f2;">
          Previous
        </button>
        <div class="text-sm text-gray-700 font-semibold">
          Page {{ currentPage }} of {{ totalPages }}
        </div>
        <button
          class="px-6 py-3  text-white rounded-lg hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 transition-all duration-200 ease-in-out"
          :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)" style="background-color: #60c8f2;">
          Next
        </button>
      </div>

    </div>
  </AuthenticatedLayout>
</template>
