<!-- <script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


</script> -->

<template>

    <Nav />

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="d-flex justify-content-center align-items-center mt-4"
            style="height: auto; flex-direction: column; text-align: center;">
            <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">Latest lottery results
            </p>
            <p style="margin-bottom: 50px;font-size: smaller; color: gray; margin-top: 0;">Our biggest winners have won
                lottery
                jackpots
                and million dollar prizes. Read their storie below.
            </p>
        </div>





        <div class="container py-5">
            <div class="row g-4 d-flex justify-content-center align-items-center">
                <div v-for="lottery in lotteries" :key="lottery.id" class="col-md-6 col-lg-5">
                    <div class="card shadow" style="border-radius: 15px; border: none; overflow: hidden;">
                        <div class="card-body text-center">
                            <div class="row" style="height: 70px;">
                                <div class="col-12 d-flex justify-content-center align-items-center">
                                    <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden;">
                                        <img :src="`/${lottery.image}`" alt="Logo"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </div>


                            </div>
                            <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 30px 0;" />

                            <p style="font-size: 14px; color: #555; margin-bottom: 20px;">Winning numbers</p>

                            <div v-if="lottery.dashboardInfo?.displayDigits"
                                style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                                <!-- Highlighted Digit -->
                                <span v-for="(digit, index) in lottery.dashboardInfo.displayDigits.digits"
                                    :key="'digit-' + index" style="width: 35px; height: 35px; background-color: rgb(96, 200, 242); color: #fff; 
                        border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    {{ digit }}
                                </span>

                                <!-- Display position indicator -->
                                <span style="display: flex; align-items: center; margin-left: 10px; font-size: 12px;">
                                    <!-- ({{ lottery.dashboardInfo.displayDigits.position }} digit) -->
                                </span>
                            </div>

                            <div v-else style="margin-bottom: 15px;">
                                <p>No results available yet</p>
                            </div>
                        </div>

                        <!-- Dashboard Info -->
                        <div class="draw-info" v-if="lottery.dashboardInfo">
                            <p>
                                <span>Lottery:</span> <span>{{ lottery.name || 'N/A' }}</span>
                                <br>
                                <i class="fas fa-ticket-alt draw-icon"></i>
                                <span>Type:</span> <span>{{ lottery.dashboardInfo.dashboardType || 'N/A' }}</span>
                                <br />
                                <i class="fas fa-hashtag draw-icon"></i>
                                <span>Draw Number:</span> <span>{{ lottery.dashboardInfo.draw_number || 'N/A' }}</span>
                                <br />
                                <i class="far fa-calendar-alt draw-icon"></i>
                                <span>Draw Date:</span> <span>{{ formatDate(lottery.dashboardInfo.date) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row d-flex justify-content-center align-items-center "
            style="margin-bottom: 50px;margin-top: 150px;border-top-color: aqua;border-style: solid;">
            <div class="col-12 mb-0" style="background-color:#EAF4FC;">

                <div class="row d-flex mt-4">
                    <div class="col-10 text-center">

                        <div class="row">
                            <div class="col-6 d-flex justify-content-center align-items-center">
                                <i class="bi bi-patch-question-fill"
                                    style="color: rgb(96, 200, 242) ;font-size: 150px;"></i>
                                <!-- <i class="bi bi-patch-question-fil" ></i> -->

                                <!-- <img :src="logoUrl" alt="Logo" class="card-img-top" style="height: 100px;width: auto;"> -->
                            </div>
                            <div class="col-6">
                                <h1
                                    style="margin-top: 50px;color: #333; margin-bottom: 20px;font-size: 30px;font-weight: bold;">
                                    If you have any questions</h1>
                                <div style="width: 50%;margin-left: auto;margin-right: auto;align-items: left;">
                                    <p style="color: #555; font-size: 14px; margin-bottom: 40px;text-align: left;">
                                        Winners updated weekly. Prize value listed as won may not reflect actual net
                                        claims
                                        payment
                                        amount
                                        in photo
                                        due to combined prize claim amounts and other adjustments. Purchase location
                                        shown where
                                        applicable.
                                        All Prizes are 100% commission-FREE.
                                    </p>

                                    <div class="text-center mt-4" style="margin-bottom: 30px;">
                                        <a :href="route('faq')" :active="route().current('faq')">
                                            <button class="btn"
                                                style="background-color: rgb(96, 200, 242);border-radius: 50px; padding: 10px 30px;color: white;">Check
                                                FAQs
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>




        <Footer class="mt-0" />
    </AuthenticatedLayout>
</template>

<script>
import Footer from "@/components/Landing/footer.vue";
import Nav from "@/components/Landing/nav.vue";
import { Head } from '@inertiajs/vue3';

export default {
    data() {
        return {
            logoUrl: '/assets/images/1.png',
            logoUrl1: '/assets/images/2.png',
            logoUrl2: '/assets/images/3.png',
        };
    },
    props: {
        lotteries: {
            type: Array,
            required: true,
            default: () => []
        }
    },

    methods: {
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    },
    components: {
        Footer,
        Nav,
        Head
    }
};
</script>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: white;
}

.draw-info {
    background: linear-gradient(135deg, #4a90e2, #60c8f2);
    /* Smooth gradient */
    padding: 12px;
    text-align: center;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    color: #fff;
    font-weight: 500;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    /* Soft shadow */
}

.draw-info p {
    font-size: 14px;
    margin: 0;
    line-height: 1.6;
}

.draw-info span {
    font-weight: bold;
    font-size: 15px;
    letter-spacing: 0.5px;
}

.draw-icon {
    margin-right: 5px;
    font-size: 16px;
}

a:hover {
    transform: scale(1.1);
}

/* .container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
} */

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
    background: rgb(96, 200, 242);
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
    background: rgb(96, 200, 242);
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
