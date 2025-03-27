<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

// Fetch data from Laravel backend
const page = usePage();
const totalEarnings = ref(page.props.totalEarnings || 0);
const activeReferrals = ref(page.props.activeReferrals || 0);
const earningsByMonth = ref(page.props.earningsByMonth || {});
const recentReferrals = ref(page.props.recentReferrals || []);
const userlink = page.props.userlink;

// Define chart canvas ref
const chartCanvas = ref(null);
let chartInstance = null;

// Function to ensure all months are included (January to December)
const getEarningsByMonth = () => {
    // Array of month names in English
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    // Fill missing months with 0 earnings
    const filledEarnings = months.map((month) => {
        return earningsByMonth.value[month] || 0;
    });

    return filledEarnings;
};

// Function to initialize chart
const createChart = () => {
    if (chartCanvas.value) {
        const ctx = chartCanvas.value.getContext('2d');

        if (chartInstance) {
            chartInstance.destroy(); // Destroy old instance if re-rendering
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ],
                datasets: [{
                    label: 'Earnings',
                    data: getEarningsByMonth(), // Processed earnings data
                    borderColor: '#7F9CF5',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 90, // Rotate the x-axis labels if necessary
                            minRotation: 45 // Ensure that the labels do not overflow
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        }
                    }
                }
            }
        });
    }
};

// Watch for changes and update chart
watch(earningsByMonth, createChart, { deep: true });

onMounted(createChart);

// Copy referral link
const referralLink = ref(userlink);

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(referralLink.value);
        alert("Copied: " + referralLink.value);
    } catch (err) {
        console.error("Failed to copy: ", err);
    }
};

const props = defineProps({
    recentReferrals: Array,
});
</script>


<template>

    <Head title="Affiliate Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Affiliate
            </h2>
        </template>

        <div class="p-6 bg-gray-100 min-h-screen">
            <h2 class="text-2xl font-bold mb-2">Affiliate Dashboard</h2>
            <p class="text-gray-600 mb-6">Track your referrals and earnings</p>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-4 mb-6 w-full">
                <div
                    class="p-4 bg-white rounded-lg shadow flex flex-col items-center justify-center col-span-6 sm:col-span-1 md:col-span-3">
                    <p class="text-gray-500">Total Earnings</p>
                    <h3 class="text-2xl font-bold">USDT {{ totalEarnings }}</h3>
                </div>

                <div
                    class="p-4 bg-white rounded-lg shadow flex flex-col items-center justify-center col-span-6 sm:col-span-1 md:col-span-3">
                    <p class="text-gray-500">Active Referrals</p>
                    <h3 class="text-2xl font-bold">{{ activeReferrals }}</h3>
                </div>
            </div>



            <!-- Performance Chart -->
            <div class="p-6 bg-white rounded-lg shadow mb-6" style="height: 600px; overflow: hidden;">
                <h3 class="text-lg font-bold mb-4">Earnings Overview</h3>
                <canvas ref="chartCanvas" class="w-full h-[calc(100%-30px)]"></canvas>
                
            </div>


            <!-- Referral Link & Recent Referrals -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 ">
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Your Referral Link</h3>
                    <div class="flex items-center border rounded">
                        <input type="text" id="referralLink" class="w-full p-2 outline-none" :value="referralLink"
                            readonly>
                        <button @click="copyToClipboard" class="p-2 bg-gray-200 hover:bg-gray-300 rounded-r">
                            📋
                        </button>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Recent Referrals</h3>
                    <ul>
                        <li v-for="(referral, index) in recentReferrals" :key="index"
                            class="flex justify-between py-2 border-b">
                            {{ referral.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>





<style scoped>
span {
    font-weight: bold;
}

canvas {
    max-height: 500px !important;
}
</style>
