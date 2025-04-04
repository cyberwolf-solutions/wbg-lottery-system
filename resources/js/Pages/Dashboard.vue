<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    purchaseHistory: Array,
    wallet: Object,
    pickedNumbers: Array,
    lotteryDashboards: Array,
    deposits: Array,
    withdrawals: Array,
    generalNotices: Array,
    userNotices: Array,
});

// Cart functionality
const cartItems = ref(props.pickedNumbers || []);
const selectedLottery = ref(null);
const selectedNumbers = ref([]);

const userPage = ref(1)
const userPerPage = 3

const paginatedUserNotices = computed(() => {
    const start = (userPage.value - 1) * userPerPage
    return props.userNotices.slice(start, start + userPerPage)
})

const totalUserPages = computed(() =>
    Math.ceil(props.userNotices.length / userPerPage)
)

// General Notices Pagination
const generalPage = ref(1)
const generalPerPage = 3

const paginatedGeneralNotices = computed(() => {
    const start = (generalPage.value - 1) * generalPerPage
    return props.generalNotices.slice(start, start + generalPerPage)
})

const totalGeneralPages = computed(() =>
    Math.ceil(props.generalNotices.length / generalPerPage)
)
// Add to cart function
const addToCart = (lottery) => {
    if (selectedNumbers.value.length > 0) {
        cartItems.value.push({
            lottery_id: lottery.id,
            lottery_name: lottery.lottery.name,
            numbers: [...selectedNumbers.value],
            price: lottery.price,
            quantity: selectedNumbers.value.length
        });
        selectedNumbers.value = [];
    }
};

// Remove from cart
const removeFromCart = (index) => {
    cartItems.value.splice(index, 1);
};

// Cart totals
const cartTotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

// Pagination for purchase history
const currentPage = ref(1);
const pageSize = ref(5);

const totalPages = computed(() => {
    return Math.ceil(props.purchaseHistory.length / pageSize.value);
});

const currentPagePurchases = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    return props.purchaseHistory.slice(start, end);
});

const changePage = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages.value) return;
    currentPage.value = pageNumber;
};

// Countdown timer
const countdown = ref({
    days: '00',
    hours: '00',
    minutes: '00',
    seconds: '00'
});

// Initialize countdown
const updateCountdown = () => {
    const targetDate = new Date("2025-01-23T00:00:00").getTime();
    const now = new Date().getTime();
    const timeLeft = targetDate - now;

    if (timeLeft > 0) {
        countdown.value.days = Math.floor(timeLeft / (1000 * 60 * 60 * 24)).toString().padStart(2, "0");
        countdown.value.hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, "0");
        countdown.value.minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, "0");
        countdown.value.seconds = Math.floor((timeLeft % (1000 * 60)) / 1000).toString().padStart(2, "0");
    }
};

setInterval(updateCountdown, 1000);
updateCountdown(); // Initial call

const walletBalance = computed(() => {
    if (!props.wallet || props.wallet.available_balance === null || props.wallet.available_balance === undefined) {
        return '0.00';
    }
    return Number(props.wallet.available_balance).toFixed(2);
});
</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="row d-flex justify-content-center align-items-center mt-4">
            <!-- Wallet Summary Widget -->
            <div class="card col-8 shadow-lg mb-4" style="border: none;">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-3">Wallet Summary</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Available Balance</h4>
                            <p class="text-2xl font-bold">USDT {{ walletBalance }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Total Deposits</h4>
                            <p class="text-2xl font-bold">USDT {{ deposits.total_amount }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Total Withdrawals</h4>
                            <p class="text-2xl font-bold">USDT {{ withdrawals.total_amount }}</p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Notices Section -->
            <!-- <div class="card col-8 shadow-lg mb-4" style="border: none;">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-4">🛎️ Your Notices</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                       
                        <div class="flex flex-col">
                            <div>
                                <h4 class="text-md font-bold text-blue-700 mb-2">🎯 Direct to You</h4>
                                <div v-if="props.userNotices.length" class="space-y-3">
                                    <div v-for="notice in paginatedUserNotices" :key="notice.id"
                                        class="bg-blue-100 p-3 rounded-lg shadow">
                                        <h5 class="font-semibold text-blue-900">{{ notice.title }}</h5>
                                        <p class="text-sm text-blue-800">{{ notice.message }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ new Date(notice.created_at).toLocaleString() }}
                                        </p>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500">No personal notices.</div>
                            </div>

                           
                            <div v-if="props.userNotices.length" class="flex justify-between mt-auto pt-4 text-sm">
                                <button class="text-blue-700 hover:underline" :disabled="userPage === 1"
                                    @click="userPage--">
                                    ⬅️ Prev
                                </button>
                                <span>Page {{ userPage }} / {{ totalUserPages }}</span>
                                <button class="text-blue-700 hover:underline" :disabled="userPage === totalUserPages"
                                    @click="userPage++">
                                    Next ➡️
                                </button>
                            </div>
                        </div>

                      
                        <div class="flex flex-col">
                            <div>
                                <h4 class="text-md font-bold text-gray-800 mb-2">🌐 Notices for Everyone</h4>
                                <div v-if="props.generalNotices.length" class="space-y-3">
                                    <div v-for="notice in paginatedGeneralNotices" :key="notice.id"
                                        class="bg-gray-100 p-3 rounded-lg shadow">
                                        <h5 class="font-semibold text-gray-900">{{ notice.title }}</h5>
                                        <p class="text-sm text-gray-700">{{ notice.message }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ new Date(notice.created_at).toLocaleString() }}
                                        </p>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500">No general notices.</div>
                            </div>

                           
                            <div v-if="props.generalNotices.length" class="flex justify-between mt-auto pt-4 text-sm">
                                <button class="text-gray-700 hover:underline" :disabled="generalPage === 1"
                                    @click="generalPage--">
                                    ⬅️ Prev
                                </button>
                                <span>Page {{ generalPage }} / {{ totalGeneralPages }}</span>
                                <button class="text-gray-700 hover:underline"
                                    :disabled="generalPage === totalGeneralPages" @click="generalPage++">
                                    Next ➡️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Purchase History -->
            <div class="card col-8 shadow-lg" style="border: none;">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-3">Purchase History</h3>
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2">Draw Number</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Lottery</th>
                                <th class="py-2">Numbers Picked</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="purchase in currentPagePurchases" :key="purchase.id">
                                <td class="border px-4 py-2 text-center">
                                    {{ purchase.lottery_dashboard?.draw_number || 'N/A' }}
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    {{ purchase.lottery_dashboard?.date || 'N/A' }}
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    {{ purchase.lottery_dashboard?.lottery.name || 'N/A' }}
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    {{ purchase.picked_number || 'N/A' }}
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs" :class="{
                                        'bg-green-100 text-green-800': purchase.status === 'won',
                                        'bg-red-100 text-red-800': purchase.status === 'lost',
                                        'bg-yellow-100 text-yellow-800': purchase.status === 'pending'
                                    }">
                                        {{ purchase.status || 'pending' }}
                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div class="mt-4 flex justify-center">
                        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-4 py-2 bg-blue-500 text-white rounded-l-lg">Previous</button>
                        <span class="px-4 py-2">Page {{ currentPage }} of {{ totalPages }}</span>
                        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg">Next</button>
                    </div>
                </div>
            </div>



        </div>
    </AuthenticatedLayout>
</template>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

a:hover {
    transform: scale(1.1);
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    font-size: 24px;
    margin: 0;
    color: #333;
}

.header .draw-info {
    font-size: 16px;
    color: #666;
}

.lottery-grid {
    display: flex;
    gap: 10px;
}

.ticket {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    flex: 1;
    text-align: center;
}

.ticket h3 {
    font-size: 18px;
    margin: 10px 0;
    color: #333;
}

.number-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 5px;
}

.number {
    width: 40px;
    height: 40px;
    line-height: 40px;
    background: #f0f0f0;
    border-radius: 50%;
    text-align: center;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    transition: background 0.3s;
}

.number.selected {
    background: #007bff;
    color: #fff;
}

.actions {
    margin-top: 15px;
}

.actions button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.clear-button {
    background: #dc3545;
    color: #fff;
    margin-right: 5px;
}

.quick-pick-button {
    background: #007bff;
    color: #fff;
}

.summary {
    margin-top: 20px;
    text-align: right;
    font-size: 16px;
}

.summary .total {
    font-weight: bold;
    color: #333;
}

/* Base button styles */
button {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    background: transparent;
    /* Transparent background by default */
    transition: background-color 0.3s, color 0.3s;
    /* Smooth hover effect */
}

/* Clear button styles */
.btn-clear {
    border: 1px solid rgb(77, 149, 57);
    color: rgb(77, 149, 57);
    border-radius: 100px;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    -ms-border-radius: 100px;
    -o-border-radius: 100px;
    margin-bottom: 20px;
}

.btn-clear:hover {
    background-color: rgb(77, 149, 57);
    color: white;
}

/* Quick Pick button styles */
.btn-quick-pick {
    border: 1px solid rgb(96, 200, 242);
    color: rgb(44, 186, 242);
    border-radius: 100px;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    -ms-border-radius: 100px;
    -o-border-radius: 100px;
    margin-bottom: 20px;
}

.btn-quick-pick:hover {

    background-color: rgb(96, 200, 242);
    color: white;
}

.titlelot {
    color: rgb(44, 186, 242);
    margin-left: 25%;
    margin-right: auto;
    font-style: italic;
}
</style>