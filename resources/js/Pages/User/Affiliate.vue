<script setup>
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Define the ref for the chart canvas
const chartCanvas = ref(null);

onMounted(() => {
    if (chartCanvas.value) {
        const ctx = chartCanvas.value.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Earnings',
                    data: [50, 25, 40, 60, 55, 80],
                    borderColor: '#7F9CF5',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});

const referralLink = ref("https://yourstore.com/?ref=user123");

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(referralLink.value);
        alert("Copied: " + referralLink.value);
    } catch (err) {
        console.error("Failed to copy: ", err);
    }
};

</script>

<template>

    <Head title="Dashboard" />
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-white rounded-lg shadow">
                    <p class="text-gray-500">Total Earnings</p>
                    <h3 class="text-2xl font-bold">$1,234</h3>
                    <p class="text-green-500">+20% from last month</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow">
                    <p class="text-gray-500">Conversion Rate</p>
                    <h3 class="text-2xl font-bold">12.5%</h3>
                    <p class="text-green-500">+2.2% from last month</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow">
                    <p class="text-gray-500">Total Clicks</p>
                    <h3 class="text-2xl font-bold">845</h3>
                    <p class="text-green-500">+201 from last month</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow">
                    <p class="text-gray-500">Active Referrals</p>
                    <h3 class="text-2xl font-bold">45</h3>
                    <p class="text-green-500">+3 from last month</p>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="p-6 bg-white rounded-lg shadow mb-6" style="height: 300px;">
                <h3 class="text-lg font-bold mb-4">Performance Overview</h3>
                <canvas ref="chartCanvas" class="w-full h-full"></canvas>
            </div>

            <!-- Referral Link & Recent Referrals -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Your Referral Link</h3>
                    <div class="flex items-center border rounded">
                        <input type="text" id="referralLink" class="w-full p-2 outline-none"
                            value="https://yourstore.com/?ref=user123" readonly>
                        <button onclick="copyToClipboard()" class="p-2 bg-gray-200 hover:bg-gray-300 rounded-r">
                            📋
                        </button>
                    </div>
                </div>
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Recent Referrals</h3>
                    <ul>
                        <li class="flex justify-between py-2 border-b">John Doe <span>$50.00</span></li>
                        <li class="flex justify-between py-2 border-b">Jane Smith <span>$75.00</span></li>
                        <li class="flex justify-between py-2">Mike Johnson <span>$100.00</span></li>
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
