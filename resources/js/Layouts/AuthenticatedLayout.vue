<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';

const showingNavigationDropdown = ref(false);



const lotteries = ref([
    { id: 1, name: "Lottery 1" },
    { id: 2, name: "Lottery 2" },
    { id: 3, name: "Lottery 3" },
]);

const fetchLotteryData = async (lotteryId) => {
    try {
        // const response = await axios.get(`/api/lottery/${lotteryId}`);
        const response = await axios.get(`/api/lottery/${lotteryId}`);
        // This will navigate to the lottery view and pass the lottery data
        this.$inertia.visit(`/lottery/${lotteryId}`, {
            method: 'get', // specify the HTTP method if needed
            data: { lotteryData: response.data },
        });
    } catch (error) {
        console.error("Error fetching lottery data:", error);
    }
};

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                <ApplicationLogo class="" height="70" width="100" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('wallet.index')" :active="route().current('wallet.index')">
                                    Wallet
                                </NavLink>

                                <!-- <NavLink :href="route('wallet.index')" :active="route().current('wallet.index')">
                                    Lotteries
                                </NavLink> -->

                                <NavLink :href="route('prize.index')" :active="route().current('prize.index')">
                                    prizes
                                </NavLink>

                                <!-- <NavLink :href="route('winner.index')" :active="route().current('winner.index')">
                                    Winners
                                </NavLink>

                                <NavLink :href="route('latest.index')" :active="route().current('latest.index')">
                                    Latest results
                                </NavLink>

                                <NavLink :href="route('hiw.index')" :active="route().current('hiw.index')">
                                    How It Works
                                </NavLink>

                                <NavLink :href="route('contact.index')" :active="route().current('contact.index')">
                                    Contact 
                                </NavLink> -->
                                <NavLink :href="route('affiliate.index')" :active="route().current('affiliate.index')">
                                    Affiliate 
                                </NavLink>
                                <div class="hidden sm:ms-6 sm:flex sm:items-center">
                                    <div class="relative ms-3">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button type="button"
                                                        class="inline-flex items-center rounded-md  bg-transparent px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                        Lotteries
                                                        <svg class="-me-0.5 ms-2 h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>
                                            <template #content>
                                                <div v-for="lottery in lotteries" :key="lottery.id">
                                                    <!-- <DropdownLink @click="fetchLotteryData(lottery.id)" -->
                                                    <DropdownLink @click="fetchLotteryData(1)"
                                                        class="text-sm text-gray-700 hover:text-gray-900">
                                                        {{ lottery.name }}
                                                    </DropdownLink>
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </div>
                                
                                <!-- notofocation -->
                                <!-- <NavLink :active="route().current('notification.index')" class=""
                                    data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                                    aria-controls="offcanvasExample">
                                    <i class="bi bi-bell-fill"></i>
                                </NavLink> -->
                                <!-- notofocation -->

                            </div>
                        </div>

                        <!-- notification -->
                        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
                            aria-labelledby="offcanvasExampleLabel" style="background-color:whitesmoke;">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Notifications</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <div class="card" style="border-style: none;">
                                    <div class="card-header"
                                        style="background-color: whitesmoke;border-style: none;font-size: 10px;">
                                        9.00 a.m
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">New login at 8.56 a.m</h5>

                                    </div>
                                </div>

                                <div class="card" style="border-style: none;">
                                    <div class="card-header"
                                        style="background-color: whitesmoke;border-style: none;font-size: 10px;">
                                        9.00 a.m
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">New login at 8.56 a.m</h5>

                                    </div>
                                </div>

                                <div class="card" style="border-style: none;">
                                    <div class="card-header"
                                        style="background-color: whitesmoke;border-style: none;font-size: 10px;">
                                        9.00 a.m
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">New login at 8.56 a.m</h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- notification -->


                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                        <NavLink :active="route().current('notification.index')" class="mx-2"
                                            data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                                            aria-controls="offcanvasExample">
                                            <i class="bi bi-bell-fill"></i>
                                        </NavLink>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>

                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="
                                showingNavigationDropdown =
                                !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>

    <!-- Footer component -->
    <Footer />

</template>
