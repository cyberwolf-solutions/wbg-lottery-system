<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import axios from 'axios';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your_pusher_key',
    cluster: 'mt1',
    forceTLS: true
});

// Store the picked numbers (those that are disabled)
const pickedNumbers = ref([]);

// Listen for a number being picked through the Pusher event
window.Echo.channel('lottery')
    .listen('NumberPicked', (event) => {
        console.log("Number Picked Event Received:", event);
        pickedNumbers.value.push(event.pickedNumber.picked_number);
    });


function pickNumber(number, dashboardId) {
    // Send a POST request to mark the number as picked in the database
    // alert(JSON.stringify(selectedLotteryDetails.value[0]?.id , 2));



    axios.post('/api/pick-number', {
        number: number,
        lottery_dashboard_id: selectedLotteryDetails.value[0]?.id,
    }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`
        }
    }).then(response => {
        pickedNumbers.value.push(response.data.data.number);
    }).catch(error => {
        alert(error.response.data.message);
    });

}




const props = defineProps({
    lotterie: Object,
    lotterydashboards: Array
});
const showModal = ref(true);
const selectedLottery = ref(props.lottery?.name || '');
const selectedLotteryDetails = ref([]);
// const countdownTimer = ref('');
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
function startCountdown() {
    selectedLotteryDetails.value.forEach(ticket => {
        const targetDate = new Date(ticket.date).getTime();

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
            }
        }

        // Start countdown update every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
}
// Format number (ensure two-digit format)
const formatNumber = (num) => num.toString().padStart(2, '0');
// Compute winning numbers dynamically based on each draw number
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

// const pickedNumbers = ref([]); // Stores picked numbers
const selectedNumber = ref(null); // Stores the number user selects



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
                                        <div v-for="number in Array.from({ length: 100 }, (_, i) => i + 1)"
                                            :key="number" :class="{
                                                'bg-gray-100': !pickedNumbers.includes(number),
                                                'bg-gray-400 cursor-not-allowed': pickedNumbers.includes(number)
                                            }"
                                            @click="!pickedNumbers.includes(number) && pickNumber(number, ticket.dashboard_id)"
                                            class="w-8 h-8 flex items-center justify-center rounded-full cursor-pointer hover:bg-gray-300">
                                            {{ formatNumber(number) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div v-if="pickedNumbers.length > 0">
            <h3 class="text-lg font-bold">Picked Numbers</h3>
            <ul>
                <li v-for="number in pickedNumbers" :key="number">{{ number }}</li>
            </ul>
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