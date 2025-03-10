<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import axios from 'axios';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const authToken = localStorage.getItem('auth_token');
console.log('Auth Token:', authToken);

Pusher.logToConsole = true;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your_pusher_key',
    cluster: 'mt1',
    forceTLS: true
});

const pickedNumbers = ref([]);
const showConfirmModal = ref(false);
const numberToPick = ref(null);
const dashboardIdToPick = ref(null);
const totalPrice = computed(() => {
    // Get the price from the selected lottery dashboard
    const dashboardPrice = selectedLotteryDetails.value[0]?.price || 0; // Default to 0 if no price found

    // Calculate the total price based on picked numbers
    const pickedPrice = pickedNumbers.value.length * parseFloat(dashboardPrice); // Total price = number of picked numbers * dashboard price

    return pickedPrice.toFixed(2); // Return the total price
});

// Store picked numbers in localStorage
function savePickedNumbersToLocalStorage() {
    localStorage.setItem('pickedNumbers', JSON.stringify(pickedNumbers.value));
}

// Retrieve picked numbers from localStorage
function loadPickedNumbersFromLocalStorage() {
    const storedPickedNumbers = localStorage.getItem('pickedNumbers');
    if (storedPickedNumbers) {
        pickedNumbers.value = JSON.parse(storedPickedNumbers);
    }
}



onMounted(() => {
    window.Echo.channel('lottery')
        .listen('NumberPicked', (event) => {
            // Push number and its price when picked
            pickedNumbers.value.push({
                number: event.pickedNumber.picked_number,
                price: event.pickedNumber.price
            });
        });

    loadPickedNumbersFromLocalStorage(); // Load from localStorage when the page reloads

    // Call deletePickedNumbers if necessary (e.g., reset or cleanup on page load)
    deletePickedNumbers();

});



const storedNumbers = ref([...Array(100).keys()].map(n => n.toString().padStart(2, '0')));

function confirmPickNumber(number, dashboardId) {
    numberToPick.value = number;
    dashboardIdToPick.value = dashboardId;
    showConfirmModal.value = true;
}

function pickNumberConfirmed() {
    console.log('Confirming pick for number:', numberToPick.value);
    console.log('Dashboard ID:', dashboardIdToPick.value); // Use the correct dashboard ID

    axios.post('/api/pick-number', {
        number: numberToPick.value,
        lottery_dashboard_id: dashboardIdToPick.value, // Use the correct dashboard ID
        lottery_id: props.lotterie.id,
    })
        .then(response => {
            console.log('Full Response:', response);
            if (response.data && response.data.number) {
                pickedNumbers.value.push({
                    number: response.data.number,  // Ensure number is stored properly
                    price: selectedLotteryDetails.value[0]?.price || 0,  // Store price
                    lottery_dashboard_id: dashboardIdToPick.value, // Use the correct dashboard ID
                });
            } else {
                console.error('Response data is missing or malformed');
            }

            console.log("ok" + pickedNumbers);
        })
        .catch(error => {
            alert(error.response?.data?.message || error.message);
        })
        .finally(() => {
            showConfirmModal.value = false;
            numberToPick.value = null;
            dashboardIdToPick.value = null;
        });
}



const props = defineProps({
    lotterie: Object,
    lotterydashboards: Array
});

const showModal = ref(true);
const selectedLottery = ref(props.lottery?.name || '');
const selectedLotteryDetails = ref([]);
const countdowns = ref({});
const selectedDashboardCount = computed(() => selectedLotteryDetails.value.length);

function selectLottery(dashboard) {
    selectedLottery.value = dashboard.dashboard;
    selectedLotteryDetails.value = props.lotterydashboards.filter(d => d.dashboard === dashboard.dashboard);
    showModal.value = false;
    startCountdown();
}

const uniqueDashboards = computed(() => {
    const seen = new Set();
    return props.lotterydashboards.filter(dashboard => {
        if (!seen.has(dashboard.dashboard)) {
            seen.add(dashboard.dashboard);
            return true;
        }
        return false;
    });
});

let countdownIntervals = {}; // Store countdown intervals

function startCountdown() {
    selectedLotteryDetails.value.forEach(ticket => {
        const targetDate = new Date(ticket.date);
        targetDate.setHours(20, 0, 0, 0); // Set time to 8:00 PM (20:00)

        if (countdownIntervals[ticket.draw_number]) {
            clearInterval(countdownIntervals[ticket.draw_number]); // Clear existing interval
        }

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = targetDate - now;

            if (timeLeft > 0) {
                countdowns.value[ticket.draw_number] = {
                    days: Math.floor(timeLeft / (1000 * 60 * 60 * 24)),
                    hours: Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                    minutes: Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60)),
                    seconds: Math.floor((timeLeft % (1000 * 60)) / 1000),
                };
            } else {
                countdowns.value[ticket.draw_number] = { expired: true };
                clearInterval(countdownIntervals[ticket.draw_number]); // Stop countdown
                deactivateDashboard(ticket.dashboard_id);
            }
            console.log("Time left:", timeLeft, countdowns.value[ticket.draw_number]);
        }

        updateCountdown();
        countdownIntervals[ticket.draw_number] = setInterval(updateCountdown, 1000);
    });
}

// Function to send API request to deactivate dashboard
function deactivateDashboard(dashboardId) {
    axios.post('/api/deactivate-dashboard', { dashboard_id: dashboardId })
        .then(response => {
            console.log('Dashboard deactivated:', response.data);
        })
        .catch(error => {
            console.error('Error deactivating dashboard:', error.response?.data || error);
        });
}


const formatNumber = (num) => num.toString().padStart(2, '0');

const winningNumbers = computed(() => {
    let numbersMap = {};
    selectedLotteryDetails.value.forEach(ticket => {
        if (ticket.winning_numbers) {
            numbersMap[ticket.draw_number] =
                Array.isArray(ticket.winning_numbers) ? ticket.winning_numbers :
                    typeof ticket.winning_numbers === 'string' ? JSON.parse(ticket.winning_numbers) : [];
        } else {
            numbersMap[ticket.draw_number] = [];
        }
    });
    return numbersMap;
});




function checkout() {
    if (pickedNumbers.value.length === 0) {
        alert("No numbers selected!");
        return;
    }

    const numbersData = pickedNumbers.value.map(picked => ({
        number: picked.number,  // Ensure number is correctly formatted
        price: parseFloat(picked.price),  // Ensure price is a number
        dashboard_id: picked.lottery_dashboard_id
    }));

    const dashboardId = selectedLotteryDetails.value[0]?.id;

    if (!dashboardId) {
        alert("Dashboard ID is missing!");
        return;
    }

    console.log("Checkout Data:", {
        numbers: numbersData,
        lottery_id: props.lotterie.id,
        dashboard_id: dashboardId,
        total_price: totalPrice.value,
    });

    axios.post('/api/checkout', {
        numbers: numbersData,
        lottery_id: props.lotterie.id,
        dashboard_id: dashboardId,
        total_price: totalPrice.value,
    })
        .then(response => {
            alert("Number allocated successfully!");
            pickedNumbers.value = [];  // Clear picked numbers after checkout
        })
        .catch(error => {
            console.error("Checkout failed:", error.response.data.message);
            alert("Checkout failed! Please try again.");
        });
}




function deletePickedNumbers() {
    axios.post('/api/delete-picked-numbers')
        .then(response => {
            console.log(response.data.message);
            pickedNumbers.value = []; // Clear the picked numbers locally
            savePickedNumbersToLocalStorage(); // Save the updated empty list to localStorage
        })
        .catch(error => {
            console.error('Error deleting picked numbers:', error.response?.data?.message || error.message);
        });
}


const selectedNumber = ref(null);
</script>

<template>

    <Head :title="props ? props.lotterie.name : 'Lottery'" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Price {{ selectedLottery ? selectedLottery : 'Lotteries' }}
            </h2>
        </template>
        <!-- Modal -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal-container">
                <h3 class="modal-title">Select a Lottery</h3>
                <div class="button-row">
                    <button v-for="dashboard in uniqueDashboards" :key="dashboard.dashboard"
                        @click="selectLottery(dashboard)" class="lottery-button lottery-b">
                        {{ dashboard.dashboard }}
                        <!-- {{ dashboard.id }} -->
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <!-- Modal Header -->
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold" id="exampleModalLabel">
                            ⚠ Insufficient Balance
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body text-center p-4">
                        <i class="fas fa-exclamation-circle text-danger fs-1 mb-3"></i>
                        <p class="fs-5 text-secondary">
                            Your available balance is not enough to make this draw.
                        </p>
                        <p class="fw-bold text-dark">
                            Please make a settlement to continue.
                        </p>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger px-4 py-2 rounded-pill">
                            Make a Settlement
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div v-if="!showModal" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <div v-for="ticket in selectedLotteryDetails" :key="ticket.draw_number"
                                class="border rounded-lg p-4 relative">
                                <h2 class="text-lg font-semibold titlelot mb-4">Pick Your Lucky Number</h2>

                                <div class="info-container bg-light p-3 rounded shadow-sm">
                                    <!-- Additional ticket details -->
                                    <div class="button-container">
                                        <span class="fw-bold" style="font-size: 12px;">Draw Number</span>
                                        <span style="font-size: 12px;">{{ ticket.draw_number }}</span>
                                        <span style="font-size: 12px;">{{ ticket.id }}</span>
                                    </div>
                                    <div class="button-container">
                                        <span class="fw-bold" style="font-size: 12px;">Draw Date</span>
                                        <span style="font-size: 12px;">{{ ticket.date }}</span>
                                    </div>

                                    <div class="button-container">
                                        <span class="fw-bold" style="font-size: 12px;">Prize</span>
                                        <span style="font-size: 12px;">{{ ticket.price }}</span>
                                    </div>
                                    <div id="countdown" class="countdown mt-3">
                                        ⏳
                                        <span v-if="!countdowns[ticket.draw_number]?.expired">
                                            <span>{{ countdowns[ticket.draw_number]?.days || '00' }}</span>d
                                            <span>{{ countdowns[ticket.draw_number]?.hours || '00' }}</span>h
                                            <span>{{ countdowns[ticket.draw_number]?.minutes || '00' }}</span>m
                                            <span>{{ countdowns[ticket.draw_number]?.seconds || '00' }}</span>s
                                        </span>
                                        <span v-else class="text-red-500">Time's up!</span>
                                    </div>
                                </div>
                                <!-- Number Buttons -->
                                <div class="mt-4">
                                    <h3 class="text-sm font-semibold">Pick a Number</h3>
                                    <div class="grid grid-cols-6 gap-3 mt-4">
                                        <div v-for="number in storedNumbers" :key="number" :class="{
                                            'bg-gray-100': !pickedNumbers.includes(number),
                                            'bg-gray-400 cursor-not-allowed': pickedNumbers.includes(number)
                                        }" @click="!pickedNumbers.includes(number) && confirmPickNumber(number, ticket.id)"
                                            class="w-8 h-8 flex items-center justify-center rounded-full cursor-pointer hover:bg-gray-300">
                                            {{ number }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Confirmation Modal -->
                    <div v-if="showConfirmModal" class="modal-overlay">
                        <div class="modal-container">
                            <h3 class="modal-title">Confirm Number Pick</h3>
                            <p>Are you sure you want to pick number {{ numberToPick }}?</p>
                            <div class="button-row">
                                <button @click="showConfirmModal = false" class="cancel-button">Cancel</button>
                                <button @click="pickNumberConfirmed" class="confirm-button">Yes, Pick</button>
                            </div>
                        </div>
                    </div>
                    <div
                        class="mt-6 flex flex-col sm:flex-row justify-between items-center p-4 bg-white rounded-lg shadow-md">
                        <div
                            class="winning-chances mt-6 flex flex-col sm:flex-row justify-between items-center p-4 bg-white rounded-lg shadow-md">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Winning Chances</p>
                                <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden mt-1">
                                    <div class="absolute top-0 left-0 h-full bg-blue-500" style="width: 100%;"></div>
                                </div>
                            </div>

                        </div>


                        <div class="mt-4 sm:mt-0 text-left">
                            <div class="allocated-numbers mt-4 sm:mt-0 text-left mx-3">
                                <p class="text-sm text-gray-500">
                                    Allocated Numbers:
                                    <span class="text-blue-500">{{pickedNumbers.map(picked => picked.number).join(', ')
                                        }}</span>

                                </p>
                            </div>

                            <div class="flex items-center mt-1 bg-blue-500 rounded-full py-2 px-4">
                                <span class="text-lg font-bold text-white">{{ totalPrice }}</span>

                                <button type="button" class="font-bold text-white rounded-lg ml-4"
                                    @click="deletePickedNumbers">
                                    Cancel
                                </button>

                                <button @click="checkout" class="font-bold text-white rounded-lg ml-4">Checkout</button>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<style>
/* Modal Overlay */
.modal-overlay {
    position: fixed;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    /* Semi-transparent black */
    z-index: 50;
    border-style: none;
}

.button-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    /* Reduced font size */
    color: #333;
    /* Professional dark gray */
    padding: 8px 12px;
    /* Adjusted padding for a compact look */
    border-bottom: 1px solid #ddd;
    /* Subtle divider */
}

.button-container:last-child {
    border-bottom: none;
    /* Remove border from the last element */
}

.countdown {
    font-size: 13px;
    /* Smaller font size */
    color: #007bff;
    /* Professional blue shade */
    font-weight: 500;
    padding: 10px 0;
    text-align: center;
}

.countdown span {
    font-weight: bold;
    color: #dc3545;
    /* Red to emphasize urgency */
}

/* Modal Container */
.modal-container {
    background-color: rgba(255, 255, 255, 0.9);
    /* Transparent white */
    /* border: 2px solid #00ffff; */
    /* Aqua border */
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 400px;
}

/* Modal Title */
.modal-title {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 1.5rem;
}

/* Button Row */
.button-row {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    /* Spacing between buttons */
}

/* Lottery Buttons */
.lottery-button {
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 50px;
    /* Fully rounded buttons */
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

/* Hover and Specific Styles for Buttons */
.lottery-button:hover {
    color: #fff;
    transform: scale(1.05);
}

.lottery-a {
    color: #333;
    /* Blue text */
    border-color: rgb(96, 200, 242);
    /* Blue border */
}

.lottery-a:hover {
    background-color: rgb(96, 200, 242);
}

.lottery-b {
    color: #333;
    /* Dark text */
    border: 1px solid rgb(96, 200, 242);
    /* Thinner blue border */
    border-radius: 50px;
    /* Maintain rounded corners */
    padding: 0.75rem 1.5rem;
    /* Adjust padding to balance thinner border */
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}


.lottery-b:hover {
    background-color: rgb(96, 200, 242);
}

.lottery-c {
    color: #333;
    /* Red text */
    border-color: rgb(96, 200, 242);
    /* Red border */
}

.lottery-c:hover {
    background-color: rgb(96, 200, 242);
}



body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
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

    margin-right: auto;
    font-style: italic;
}
</style>