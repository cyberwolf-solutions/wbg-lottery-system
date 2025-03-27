<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    winnings: Array,
    totalWinnings: Number
});

const isModalOpen = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

// Format date for display
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

// Pagination logic
const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredData.value.slice(start, end);
});

const filteredData = computed(() => {
    if (!searchQuery.value) return props.winnings;
    return props.winnings.filter(win => {
        const searchString = [
            win.lottery.name,
            win.lottery_dashboard.dashboard,
            win.price,
            formatDate(win.created_at),
            win.winning_number
        ].join(' ').toLowerCase();
        return searchString.includes(searchQuery.value.toLowerCase());
    });
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
                <div class="text-lg font-semibold">
                    Total Winnings: ${{ totalWinnings.toFixed(2) }}
                </div>
            </div>
        </template>

        <div class="container mx-auto px-4 mt-6">
            <!-- Search Bar -->
            <div class="flex justify-between items-center mb-4">
                <input type="text" v-model="searchQuery" placeholder="Search prizes..."
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
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Date Won</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Winning Number</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Prize Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-for="(win, index) in paginatedData" :key="index"
                            class="hover:bg-gray-50 transition-all duration-200 ease-in-out">
                            <td class="px-6 py-4 text-gray-800 font-medium flex items-center">
                                <img :src="win.lottery.image" class="w-8 h-8 rounded-full mr-2" :alt="win.lottery.name">
                                {{ win.lottery.name }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ win.lottery_dashboard.dashboard }}</td>
                            <td class="px-6 py-4 text-gray-700">${{ win.lottery_dashboard.price }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ formatDate(win.created_at) }}</td>
                            <td class="px-6 py-4 text-gray-800 font-semibold">{{ win.winning_number }}</td>
                            <td class="px-6 py-4 text-green-600 font-semibold">${{ win.price }}</td>
                        </tr>
                        <tr v-if="paginatedData.length === 0">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No winnings found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4 px-4">
                <button
                    class="px-6 py-3 text-white rounded-lg hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 transition-all duration-200 ease-in-out"
                    :disabled="currentPage === 1" @click="changePage(currentPage - 1)" style="background-color: #60c8f2;">
                    Previous
                </button>
                <div class="text-sm text-gray-700 font-semibold">
                    Page {{ currentPage }} of {{ totalPages }}
                </div>
                <button
                    class="px-6 py-3 text-white rounded-lg hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 transition-all duration-200 ease-in-out"
                    :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)" style="background-color: #60c8f2;">
                    Next
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>