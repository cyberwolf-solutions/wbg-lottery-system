<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const responseMessage = ref(null);
const responseClass = ref('bottom-response');
const countdownTime = ref(600);
const countdownInterval = ref(null);
const isCheckingOut = ref(false);

function showResponse(message, position = 'bottom') {
    responseMessage.value = message;
    responseClass.value = position === 'bottom' ? 'top-response' : 'bottom-response';

    setTimeout(() => {
        responseMessage.value = null;
    }, 3000);
}

const authToken = localStorage.getItem('auth_token');
console.log('Auth Token:', authToken);
let checkoutTimeout;

const isManualAction = ref(false);

//call checkout function after 10 mins
function startCountdownTimer() {
    // Clear any existing interval
    if (countdownInterval.value) {
        clearInterval(countdownInterval.value);
    }

    // Reset countdown time
    countdownTime.value = 600; // 10 minutes

    countdownInterval.value = setInterval(() => {
        if (countdownTime.value > 0) {
            countdownTime.value--;
        } else {
            clearInterval(countdownInterval.value);
            // Only checkout if we have numbers
            if (pickedNumbers.value.length > 0) {
                checkout();
            }
        }
    }, 1000);
}

const formattedCountdown = computed(() => {
    const minutes = Math.floor(countdownTime.value / 60);
    const seconds = countdownTime.value % 60;
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

// Handle beforeunload event
const handleBeforeUnload = (event) => {
    if (pickedNumbers.value.length > 0 && !isCheckingOut.value) {
        event.preventDefault();
        event.returnValue = 'You have unsaved number selections. Are you sure you want to leave?';

        // Show alert to user
        const confirmation = confirm('You have unsaved number selections. Do you want to checkout before leaving?');

        if (confirmation) {
            isCheckingOut.value = true;
            checkout().finally(() => {
                // Allow the unload to proceed after checkout completes
                window.removeEventListener('beforeunload', handleBeforeUnload);
                window.location.reload(); // or let the navigation proceed
            });
            return false;
        } else {
            // User chose to leave without saving - now we'll checkout instead of delete
            checkout().finally(() => {
                window.removeEventListener('beforeunload', handleBeforeUnload);
                window.location.reload();
            });
            return false;
        }
    }
};

// Setup navigation guard and beforeunload handler
const setupNavigationHandlers = () => {
    // Inertia.js navigation guard
    router.on('before', (event) => {
        if (pickedNumbers.value.length > 0 && !isCheckingOut.value) {
            event.preventDefault();

            const confirmation = confirm('You have unsaved number selections. Do you want to checkout before leaving?');

            if (confirmation) {
                isCheckingOut.value = true;
                checkout().finally(() => {
                    isCheckingOut.value = false;
                    router.visit(event.detail.visit.url); // Proceed with navigation after checkout
                });
            } else {
                // User chose to leave without saving - now we'll checkout instead of delete
                isCheckingOut.value = true;
                checkout().finally(() => {
                    isCheckingOut.value = false;
                    router.visit(event.detail.visit.url);
                });
            }
        }
    });

    // Standard beforeunload for page reload/close
    window.addEventListener('beforeunload', handleBeforeUnload);
};

onMounted(() => {
    // Load data first
    loadPickedNumbersFromLocalStorage();

    // Then start the timer
    startCountdownTimer();

    // Setup navigation handlers
    setupNavigationHandlers();

    window.Echo.channel('lottery')
        .listen('NumberPicked', (event) => {
            // Push number and its price when picked
            pickedNumbers.value.push({
                number: event.pickedNumber.picked_number,
                price: event.pickedNumber.price
            });
        });

    startCheckoutTimer();
});

onUnmounted(() => {
    if (countdownInterval.value) {
        clearInterval(countdownInterval.value);
    }
    window.removeEventListener('beforeunload', handleBeforeUnload);
    router.off('before'); // Remove Inertia navigation guard
});

// Pagination variables
const currentPage = ref(1);
const itemsPerPage = ref(4); // Adjust as needed

// Computed property for paginated tickets
const paginatedTickets = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return selectedLotteryDetails.value.slice(start, end);
});

// Computed property for total pages
const totalPages = computed(() => {
    return Math.ceil(selectedLotteryDetails.value.length / itemsPerPage.value);
});

// Method to navigate to a specific page
function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
}

// Method to go to the next page
function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}

// Method to go to the previous page
function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

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

// Store picked numbers in localStorage with additional metadata
function savePickedNumbersToLocalStorage() {
    const data = {
        numbers: pickedNumbers.value,
        lottery_id: props.lotterie.id,
        dashboard_id: selectedLotteryDetails.value[0]?.id,
        timestamp: new Date().getTime()
    };
    localStorage.setItem('pickedNumbers', JSON.stringify(data));
}

// Retrieve picked numbers from localStorage with validation
function loadPickedNumbersFromLocalStorage() {
    const storedData = localStorage.getItem('pickedNumbers');
    if (storedData) {
        try {
            const data = JSON.parse(storedData);
            // Check if data is not too old (e.g., within 24 hours)
            if (data.timestamp && (new Date().getTime() - data.timestamp) < 86400000) {
                pickedNumbers.value = data.numbers || [];
                return true;
            }
        } catch (e) {
            console.error("Error parsing localStorage data:", e);
        }
    }
    return false;
}

const props = defineProps({
    lotterie: Object,
    lotterydashboards: Array,
    pickedNumbers: Array,
    wallet: Array
});

const storedNumbers = ref([...Array(100).keys()].map(n => n.toString().padStart(2, '0')));

function confirmPickNumber(number, dashboardId) {
    numberToPick.value = number;
    dashboardIdToPick.value = dashboardId;
    showConfirmModal.value = true;
}

function pickNumberConfirmed() {
    axios.post('/api/pick-number', {
        number: numberToPick.value,
        lottery_dashboard_id: dashboardIdToPick.value,
        lottery_id: props.lotterie.id,
    })
        .then(response => {
            if (response.data?.number) {
                pickedNumbers.value.push({
                    number: response.data.number,
                    price: selectedLotteryDetails.value[0]?.price || 0,
                    lottery_dashboard_id: dashboardIdToPick.value
                });

                // Save immediately after adding
                savePickedNumbersToLocalStorage();

                showResponse('Number allocated successfully!', 'bottom');
            }
        })
        .catch(error => {
            showResponse(error.response?.data?.message || error.message, 'bottom');
        })
        .finally(() => {
            showConfirmModal.value = false;
            numberToPick.value = null;
            dashboardIdToPick.value = null;
        });
}

function isNumberPicked(number, dashboardId) {
    return props.pickedNumbers.some(picked =>
        picked.picked_number === number && picked.lottery_dashboard_id === dashboardId
    );
}

const showModal = ref(true);
const selectedLottery = ref(props.lottery?.name || '');
const selectedLotteryDetails = ref([]);
const countdowns = ref({});
const selectedDashboardCount = computed(() => selectedLotteryDetails.value.length);

function selectLottery(dashboard) {
    selectedLottery.value = dashboard.dashboard;
    selectedLotteryDetails.value = props.lotterydashboards.filter(d =>
        d.dashboard === dashboard.dashboard && d.dashboardType === dashboard.dashboardType
    );
    showModal.value = false;
    startCountdown();
}

const uniqueDashboards = computed(() => {
    const seen = new Set();
    return props.lotterydashboards.filter(dashboard => {
        const key = `${dashboard.dashboard}-${dashboard.dashboardType}`;
        if (!seen.has(key)) {
            seen.add(key);
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

const selectedNumber = ref(null);

const pickedPercentage = computed(() => {
    const percentages = {};
    selectedLotteryDetails.value.forEach(ticket => {
        const totalNumbers = storedNumbers.value.length;
        const pickedNumbersCount = props.pickedNumbers.filter(picked =>
            picked.lottery_dashboard_id === ticket.id
        ).length;
        percentages[ticket.id] = ((pickedNumbersCount / totalNumbers) * 100).toFixed(2);
    });
    return percentages;
});

async function checkout() {
    try {
        // First ensure we have loaded data from localStorage
        loadPickedNumbersFromLocalStorage();

        // Then check if we have valid data
        if (pickedNumbers.value.length === 0) {
            console.log("No numbers picked to checkout");
            return true; // Return true since there's nothing to checkout
        }

        const numbersData = pickedNumbers.value.map(picked => ({
            number: picked.number,
            price: parseFloat(picked.price || selectedLotteryDetails.value[0]?.price || 0),
            dashboard_id: picked.lottery_dashboard_id || selectedLotteryDetails.value[0]?.id
        }));

        const dashboardId = numbersData[0]?.dashboard_id || selectedLotteryDetails.value[0]?.id;

        if (!dashboardId) {
            console.error("Dashboard ID is missing!");
            return false;
        }

        console.log("Checkout Data:", {
            numbers: numbersData,
            lottery_id: props.lotterie.id,
            dashboard_id: dashboardId,
            total_price: totalPrice.value,
        });

        const response = await axios.post('/api/checkout', {
            numbers: numbersData,
            lottery_id: props.lotterie.id,
            dashboard_id: dashboardId,
            total_price: totalPrice.value,
        });

        showResponse("Checkout successful", "bottom");
        pickedNumbers.value = [];
        localStorage.removeItem('pickedNumbers');
        window.location.reload();
        return true;

    } catch (error) {
        console.error("Checkout failed:", error.response?.data?.message || error.message);
        showResponse(error.response?.data?.message || "Checkout failed! Please try again.", "bottom");
        return false;
    } finally {
        isCheckingOut.value = false;
    }
}

function deletePickedNumbers() {
    // Don't proceed if no numbers are picked
    if (pickedNumbers.value.length === 0) return;

    // Show loading state for automatic deletions
    if (!isManualAction.value) {
        showResponse("Clearing your number selections...", "bottom");
    }

    axios.post('/api/delete-picked-numbers')
        .then(response => {
            console.log(response.data.message);
            pickedNumbers.value = [];
            localStorage.removeItem('pickedNumbers');

            if (!isManualAction.value) {
                showResponse("Your number selections have been cleared", "bottom");
            }
        })
        .catch(error => {
            console.error('Error:', error.response?.data?.message || error.message);
            showResponse("Failed to clear selections", "bottom");
        });
}

// For manual cancel button clicks
function handleManualDelete() {
    isManualAction.value = true;
    deletePickedNumbers();
    setTimeout(() => isManualAction.value = false, 1000);
}
</script>

<template>


    <Head :title="props ? props.lotterie.name : 'Lottery'" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Price {{ selectedLottery ? selectedLottery : 'Lotteries' }}
            </h2>
        </template>


        <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
            <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
                {{ responseMessage }}
            </div>
        </div>


        <!-- Modal -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal-container1">
                <h3 class="modal-title">Select a Lottery</h3>
                <div class="button-row">
                    <button v-for="dashboard in uniqueDashboards"
                        :key="dashboard.dashboard + '-' + dashboard.dashboardType" @click="selectLottery(dashboard)"
                        class="lottery-button lottery-b">
                        {{ dashboard.dashboard }} | {{ dashboard.dashboardType }}
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
                        <button type="button" class="font-bold text-white rounded-lg ml-4" @click="handleManualDelete">
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
        <div :style="{ backgroundColor: selectedLotteryDetails[0]?.lottery?.color }" v-if="!showModal" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <!-- Loop through paginated tickets -->
                            <div v-for="ticket in paginatedTickets" :key="ticket.draw_number"
                                class="border rounded-lg p-4 relative">
                                <h2 class="text-lg font-semibold titlelot mb-4">Pick Your Lucky Number</h2>

                                <div :style="{ backgroundColor: selectedLotteryDetails[0]?.lottery?.color }"
                                    class="info-container p-3 rounded shadow-sm">
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
                                        <span style="font-size: 12px;">{{ ticket.price * 70 }}</span>

                                    </div>
                                    <div class="button-container">
                                        <span class="fw-bold" style="font-size: 12px;">Picked Percentage</span>
                                        <!-- <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                            <div class="bg-blue-500 h-2.5 rounded-full"
                                                :style="{ width: pickedPercentage[ticket.id] + '%' }"></div>
                                        </div> -->
                                        <span style="font-size: 12px;">{{ pickedPercentage[ticket.id] }}%</span>
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
                                            'bg-gray-100 cursor-pointer hover:bg-gray-300': !isNumberPicked(number, ticket.id),
                                            'bg-gray-400 cursor-not-allowed opacity-50': isNumberPicked(number, ticket.id)
                                        }" @click="!isNumberPicked(number, ticket.id) && confirmPickNumber(number, ticket.id)"
                                            class="w-8 h-8 flex items-center justify-center rounded-full">
                                            {{ number }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="mt-6 flex justify-center">
                            <button :style="{ backgroundColor: selectedLotteryDetails[0]?.lottery?.color }"
                                @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                                class="px-4 py-2 bg-blue-500 text-white rounded">
                                Prev
                            </button>
                            <span class="px-4 py-2">{{ currentPage }} / {{ totalPages }}</span>
                            <button :style="{ backgroundColor: selectedLotteryDetails[0]?.lottery?.color }"
                                @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                                class="px-4 py-2 bg-blue-500 text-white rounded">
                                Next
                            </button>
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
                                    <div :style="{ backgroundColor: selectedLotteryDetails[0]?.lottery?.color }"
                                        class="absolute top-0 left-0 h-full bg-blue-500" style="width: 100%;"></div>
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

                            <div :style="{ backgroundColor: selectedLotteryDetails[0]?.lottery?.color }"
                                class="flex items-center mt-1 bg-blue-500 rounded-full py-2 px-4">
                                <span class="text-lg font-bold text-white">USD {{ totalPrice }}</span>

                                <button type="button" class="font-bold text-white rounded-lg ml-4"
                                    @click="deletePickedNumbers">
                                    Cancel
                                </button>

                                <button @click="checkout" class="font-bold text-white rounded-lg ml-4">Checkout</button>
                                <br>



                            </div>

                            <div class="allocated-numbers mt-4 sm:mt-0 text-left mx-3">
                                <span>Avalable balance : {{ wallet.available_balance }}</span>
                                <p class="text-sm text-gray-500">
                                    <b>Note</b> - You have <b>{{ formattedCountdown }}</b> minutes to checkout.<br> If
                                    not
                                    completed, the system will
                                    <br>
                                    auto-confirm your
                                    selection and <br> proceed with booking
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<style>
.top-response {
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    position: fixed;
    z-index: 999;
}

.bottom-response {
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    position: fixed;
    z-index: 999;
}

.bg-blue-500 {
    background-color: #3b82f6;
}

.text-white {
    color: white;
}

.p-3 {
    padding: 12px;
}

.rounded-lg {
    border-radius: 8px;
}

.shadow-md {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}



.disabled {
    cursor: not-allowed;
    pointer-events: none;
    /* Prevents clicking */
    opacity: 0.5;
    /* Optional: Makes it look visually disabled */
}

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

.modal-container1 {
    background-color: rgba(255, 255, 255, 0.9);
    /* Transparent white */
    /* border: 2px solid #00ffff; */
    /* Aqua border */
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 800px;
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
    flex-direction: row;
    /* Align items in a row */
    gap: 1rem;
    /* Spacing between buttons */
    flex-wrap: wrap;
    /* Allow buttons to wrap to the next line if needed */
}


.button-row {
    display: flex;
    flex-wrap: wrap;
    /* Allow wrapping to the next line */
    gap: 1rem;
    /* Space between buttons */
    justify-content: space-between;
    /* Ensure equal distribution of buttons */
}

.lottery-button {
    width: calc(25% - 0.75rem);
    /* Each button takes up 25% of the container width minus the gap */
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 50px;
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