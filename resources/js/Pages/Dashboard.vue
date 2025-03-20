<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';





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
            <div class="card col-8 shadow-lg" style="border: none;">
                <div class="card-body">
                    <div style="display: flex; justify-content: center; align-items: flex-start; gap: 20px;">
                        <!-- Cart Items Section -->
                        <div
                            style="width: 60%; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 12px; color: #6c757d;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #eee;">
                                        <th style="text-align: left; padding: 10px;">ITEM</th>
                                        <th style="text-align: center; padding: 10px;">QUANTITY</th>
                                        <th style="text-align: center; padding: 10px;">SUBSCRIPTION</th>
                                        <th style="text-align: center; padding: 10px;">COST</th>
                                        <th style="text-align: center; padding: 10px;">DISCOUNT</th>
                                        <th style="text-align: center; padding: 10px;">REMOVE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px;">Powerball</td>
                                        <td style="text-align: center; padding: 10px;">2 Line</td>
                                        <td style="text-align: center; padding: 10px;">×</td>
                                        <td style="text-align: center; padding: 10px;">€7.00</td>
                                        <td style="text-align: center; padding: 10px;">-€0.00</td>
                                        <td style="text-align: center; padding: 10px;">
                                            <button
                                                style="background: none; border: none; color: red; cursor: pointer; font-size: 14px;">🗑️</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="margin-top: 10px; text-align: right; font-size: 10px; color: #6c757d;">1 Items
                            </div>

                            <a href=route(wallet.index)
                                style="display: inline-block; width: 100%; text-align: center; color: rgb(96, 200, 242); text-decoration: none; border: none; border-radius: 5px; padding: 10px 0; font-size: 14px; cursor: pointer; transition: transform 0.3s ease;">
                                View more wallet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Purchase History</h3>
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2">Draw Number</th>
                                        <th class="py-2">Date</th>
                                        <th class="py-2">Lottery</th>
                                        <th class="py-2">Numbers Picked</th>
                                        
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
                
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    methods: {
        formatNumber(number) {

            return number === 100 ? "00" : String(number).padStart(2, "0");
        },
    },
    data() {
        return {
            currentPage: 1,
            pageSize: 5, // Number of items per page
        };
    },
    props: {
        purchaseHistory: Array
    },
    computed: {
        totalPages() {
            return Math.ceil(this.purchaseHistory.length / this.pageSize);
        },
        currentPagePurchases() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            return this.purchaseHistory.slice(start, end);
        },
    },
    methods: {
        changePage(pageNumber) {
            if (pageNumber < 1 || pageNumber > this.totalPages) return;
            this.currentPage = pageNumber;
        },
    },

};
// Set the target date and time
const targetDate = new Date("2025-01-23T00:00:00").getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const timeLeft = targetDate - now;

    if (timeLeft > 0) {
        // Calculate days, hours, minutes, and seconds
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        // Update the HTML
        document.getElementById("days").textContent = days.toString().padStart(2, "0");
        document.getElementById("hours").textContent = hours.toString().padStart(2, "0");
        document.getElementById("minutes").textContent = minutes.toString().padStart(2, "0");
        document.getElementById("seconds").textContent = seconds.toString().padStart(2, "0");
    } else {
        // If the countdown is over
        document.getElementById("countdown").textContent = "Time's up!";
    }
}

// Update the countdown every second
setInterval(updateCountdown, 1000);

</script>

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