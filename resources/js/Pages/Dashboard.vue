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

        <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="text-left">
                    <h1 class="text-2xl font-bold">US Powerball</h1>
                    <p class="text-xl font-bold text-green-500">US $541,000,000 - Draw in 3 days</p>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div v-for="ticket in 4" :key="ticket" class="border rounded-lg p-4 relative">
                        <h3 class="text-lg font-semibold titlelot mb-4">Pick 5 Numbers</h3>

                        <div class="button-container flex justify-between mt-4">
                            <button class="btn-clear">Clear</button>
                            <button class="btn-quick-pick">Quick Pick</button>
                        </div>

                        <div class="grid grid-cols-5 gap-6 mt-4">
                            <div v-for="number in 50" :key="number"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 cursor-pointer hover:bg-blue-500 hover:text-white">
                                {{ number }}
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold titlelot mb-2 mt-2">Pick 5 Numbers</h3>

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
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row justify-between items-center p-4 bg-white rounded-lg shadow-md">
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
                            <p class="text-sm text-gray-500">Total Discount: <span class="text-blue-500">-€0.00</span></p>
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
