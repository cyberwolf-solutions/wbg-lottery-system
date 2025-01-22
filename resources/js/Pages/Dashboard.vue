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

                            <a href="route('wallet.index')"
                                style="display: inline-block; width: 100%; text-align: center; color: rgb(96, 200, 242); text-decoration: none; border: none; border-radius: 5px; padding: 10px 0; font-size: 14px; cursor: pointer; transition: transform 0.3s ease;">
                                View more wallet
                            </a>


                        </div>


                        <!-- Cart Summary Section -->
                        <!-- <div
                            style="width: 35%; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px;">
                            <h3 style="margin: 0 0 20px 0; color: rgb(96, 200, 242); font-size: 16px;">Cart Summary</h3>
                            <div
                                style="margin-bottom: 10px; display: flex; justify-content: space-between; font-size: 12px; color: #6c757d;">
                                <span>Subtotal</span>
                                <span>€7.00</span>
                            </div>
                            <div
                                style="margin-bottom: 20px; display: flex; justify-content: space-between; font-size: 12px; color: #6c757d;">
                                <span>Discount</span>
                                <span>€0.00</span>
                            </div>
                            <div
                                style="margin-bottom: 20px; display: flex; justify-content: space-between; font-weight: bold; font-size: 14px;">
                                <span>Total Payment</span>
                                <span>€7.00</span>
                            </div>
                            <button
                                style="width: 100%; background: rgb(96, 200, 242); color: white; border: none; border-radius: 5px; padding: 10px 0; font-size: 14px; cursor: pointer;">
                                Pay (€7.00)
                            </button>
                          
                        </div> -->
                    </div>
                </div>
            </div>
        </div>




        <!-- ---------- -->
        <div class="py-12">

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-left">
                            <h1 class="text-2xl font-bold">US Powerball</h1>
                            <p class="text-xl font-bold text-green-500">US $541,000,000 </p>
                            <div id="countdown" class="text-lg font-medium text-gray-700 mt-2">
                                Next Draw In : <span id="days">00</span>d
                                <span id="hours">00</span>h
                                <span id="minutes">00</span>m
                                <span id="seconds">00</span>s
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <div v-for="ticket in 4" :key="ticket" class="border rounded-lg p-4 relative">
                                <h3 class="text-lg font-semibold titlelot mb-4">Pick 5 Numbers</h3>

                                <div class="button-container flex justify-between mt-4">
                                    <button class="btn-clear">Clear</button>
                                    <button class="btn-quick-pick">Quick Pick</button>
                                </div>

                                <div class="grid grid-cols-6 gap-3 mt-4">
                                    <div v-for="number in 100" :key="number"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 cursor-pointer hover:bg-blue-500 hover:text-white text-sm">
                                        {{ formatNumber(number) }}
                                    </div>
                                </div>

                                <!-- <h3 class="text-lg font-semibold titlelot mb-2 mt-2">Pick 5 Numbers</h3>

                                <div class="grid grid-cols-5 gap-6 mb-5">
                                    <div v-for="number in 10" :key="number"
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 cursor-pointer hover:bg-blue-500 hover:text-white">
                                        {{ number }}
                                    </div>
                                </div>

                                <div class="absolute left-0 mt-4 bottom-0 w-full text-white text-center py-3 rounded-b-lg"
                                    style="background-color: rgb(44, 186, 242);">
                                    <div class="text-sm font-medium">
                                        Selected Numbers: 9, 13, 20, 22, 29, 8
                                    </div>
                                </div> -->

                            </div>
                        </div>

                        <div
                            class="mt-6 flex flex-col sm:flex-row justify-between items-center p-4 bg-white rounded-lg shadow-md">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Winning Chances</p>
                                <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden mt-1">
                                    <div class="absolute top-0 left-0 h-full "
                                        style="width: 54%;background-color: rgb(44, 186, 242);"></div>
                                </div>
                            </div>

                            <div class="mt-4 sm:mt-0 text-left">
                                <div>
                                    <p class="text-sm text-gray-500">1 draw with 2 tickets @ 2 × €3.50</p>
                                    <p class="text-sm text-gray-500">Total Discount: <span
                                            class="text-blue-500">-€0.00</span>
                                    </p>
                                </div>
                                <div class="flex items-center mt-1 bg-blue-500 rounded-full py-2 px-4">
                                    <span class="text-lg font-bold text-white"> $9.99 </span>
                                    <button class="font-bold text-white rounded-lg ml-4">Add to Cart</button>
                                </div>
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
            // Format numbers as 01, 02, ..., 99, and 00 for 100
            return number === 100 ? "00" : String(number).padStart(2, "0");
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