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

    allNotices: Array,
});

const hiddenNotices = ref(JSON.parse(localStorage.getItem('hiddenNotices') || '[]'));


// Cart functionality
const cartItems = ref(props.pickedNumbers || []);
const selectedLottery = ref(null);
const selectedNumbers = ref([]);

const userPage = ref(1)
const userPerPage = 3


const hideNotice = (noticeId) => {
    if (!hiddenNotices.value.includes(noticeId)) {
        hiddenNotices.value.push(noticeId);
        localStorage.setItem('hiddenNotices', JSON.stringify(hiddenNotices.value));
    }
};
const visibleNotices = computed(() => {
    return props.allNotices.data.filter(notice => !hiddenNotices.value.includes(notice.id));
});
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
// Add these methods to your script setup
const prevPage = () => {
    if (props.allNotices.current_page > 1) {
        window.location.href = props.allNotices.prev_page_url;
    }
};

const nextPage = () => {
    if (props.allNotices.current_page < props.allNotices.last_page) {
        window.location.href = props.allNotices.next_page_url;
    }
};

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

        <div class="flex-grow row d-flex justify-content-center align-items-center mt-4 mb-0">

            <!-- Wallet Summary Widget -->
            <div class="card col-12 col-md-10 col-lg-8 shadow-lg mb-4" style="border: none;">
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

            <!-- Notices Section -->
            <div class="card col-12 col-md-10 col-lg-8 shadow-lg mb-4" style="border: none;">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="bg-blue-100 p-2 rounded-full mr-2">📢</span>
                        All Notices
                    </h3>

                    <div v-if="visibleNotices.length" class="space-y-3">
                        <div v-for="notice in visibleNotices" :key="notice.id"
                            class="p-4 rounded-lg shadow-sm border relative" :class="{
                                'border-blue-200 bg-blue-50': notice.user_id,
                                'border-gray-200 bg-gray-50': !notice.user_id
                            }">
                            <!-- Close button -->
                            <button @click="hideNotice(notice.id)"
                                class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div class="flex items-start pr-5">
                                <span v-if="notice.user_id" class="text-blue-500 mr-2">🔔</span>
                                <span v-else class="text-gray-500 mr-2">🌐</span>
                                <div>
                                    <h5 class="font-semibold" :class="{
                                        'text-blue-800': notice.user_id,
                                        'text-gray-800': !notice.user_id
                                    }">
                                        {{ notice.title }}
                                    </h5>
                                    <p class="text-sm mt-1" :class="{
                                        'text-blue-700': notice.user_id,
                                        'text-gray-700': !notice.user_id
                                    }">
                                        {{ notice.message }}
                                    </p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-xs" :class="{
                                            'text-blue-600': notice.user_id,
                                            'text-gray-600': !notice.user_id
                                        }">
                                            {{ new Date(notice.created_at).toLocaleString() }}
                                        </span>
                                        <span v-if="notice.user_id"
                                            class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                            Personal
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ... rest of your existing template ... -->
                    </div>

                    <div v-else class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">📭</div>
                        <p>No notices available at the moment.</p>
                    </div>
                </div>
            </div>

            <!-- Purchase History -->
            <div class="card col-12 col-md-10 col-lg-8 shadow-lg mb-4" style="border: none;">
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
/* Responsive adjustments */
@media (max-width: 768px) {
    .grid.grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .grid.grid-cols-1.md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }

    .min-w-full.bg-white {
        display: block;
        overflow-x: auto;
    }

    .card-body {
        padding: 1rem;
    }

    .text-2xl {
        font-size: 1.25rem;
    }
}

/* Table responsiveness */
@media (max-width: 640px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    th,
    td {
        min-width: 120px;
    }
}

/* Add this to your style section */
.min-h-screen {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.flex-grow {
    flex-grow: 1;
}

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