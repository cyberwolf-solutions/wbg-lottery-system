<!-- <script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


</script> -->

<template>


    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template> -->

        <div class="landing-page container-fluid px-0">
            <div class="d-flex justify-content-center align-items-center mt-4"
                style="height: auto; flex-direction: column; text-align: center;">
                <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">Lottery Page
                </p>
                <p style="margin-bottom: 50px;font-size: smaller; color: gray; margin-top: 0;">Our biggest winners have
                    won
                    lottery
                    jackpots
                    and million dollar prizes. Read their storie below.
                </p>
            </div>





            <div class="container py-5">
                <div class="row g-4 d-flex justify-content-center align-items-center">
                    <!-- Card 1 -->

                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow" style="border-radius: 15px; border: none; overflow: hidden;">
                            <div class="card-body text-center">
                                <div class="row" style="height: 70px;">
                                    <div class="col-6">
                                        <img :src="logoUrl" alt="Logo" class="card-img-top"
                                            style="height: 100px;width: auto;">
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <button class="btn"
                                            style="border-radius: 50px; padding: 8px 20px; font-size: 14px;background-color: rgb(96, 200, 242);;">
                                            PLAY NOW !
                                        </button>
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 30px 0;" />

                                <p style="font-size: 14px; color: #555; margin-bottom: 20px;">Winning numbers</p>
                                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        31
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        21
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: rgb(96, 200, 242);; color: #fff; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        69
                                    </span>
                                </div>
                            </div>
                            <!-- Bottom Blue Section -->
                            <div style="background-color: rgb(96, 200, 242);; padding: 10px; text-align: center; 
                    border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                                <p style="font-size: 14px; color: #fff; margin: 0;">
                                    <i class="fas fa-clock"></i> 3 days left
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 1 -->

                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow" style="border-radius: 15px; border: none; overflow: hidden;">
                            <div class="card-body text-center">
                                <div class="row" style="height: 70px;">
                                    <div class="col-6">
                                        <img :src="logoUrl2" alt="Logo" class="card-img-top"
                                            style="height: 100px;width: auto;">
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <button class="btn"
                                            style="border-radius: 50px; padding: 8px 20px; font-size: 14px;background-color: rgb(96, 200, 242);;">
                                            PLAY NOW !
                                        </button>
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 30px 0;" />

                                <p style="font-size: 14px; color: #555; margin-bottom: 20px;">Winning numbers</p>
                                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        31
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        21
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: rgb(96, 200, 242);; color: #fff; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        69
                                    </span>
                                </div>
                            </div>
                            <!-- Bottom Blue Section -->
                            <div style="background-color: rgb(96, 200, 242);; padding: 10px; text-align: center; 
                    border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                                <p style="font-size: 14px; color: #fff; margin: 0;">
                                    <i class="fas fa-clock"></i> 3 days left
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 1 -->

                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow" style="border-radius: 15px; border: none; overflow: hidden;">
                            <div class="card-body text-center">
                                <div class="row" style="height: 70px;">
                                    <div class="col-6">
                                        <img :src="logoUrl1" alt="Logo" class="card-img-top"
                                            style="height: 100px;width: auto;">
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <button class="btn"
                                            style="border-radius: 50px; padding: 8px 20px; font-size: 14px;background-color: rgb(96, 200, 242);;">
                                            PLAY NOW !
                                        </button>
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 30px 0;" />

                                <p style="font-size: 14px; color: #555; margin-bottom: 20px;">Winning numbers</p>
                                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        31
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        21
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: rgb(96, 200, 242);; color: #fff; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        69
                                    </span>
                                </div>
                            </div>
                            <!-- Bottom Blue Section -->
                            <div style="background-color: rgb(96, 200, 242);; padding: 10px; text-align: center; 
                    border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                                <p style="font-size: 14px; color: #fff; margin: 0;">
                                    <i class="fas fa-clock"></i> 3 days left
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 1 -->

                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow" style="border-radius: 15px; border: none; overflow: hidden;">
                            <div class="card-body text-center">
                                <div class="row" style="height: 70px;">
                                    <div class="col-6">
                                        <img :src="logoUrl" alt="Logo" class="card-img-top"
                                            style="height: 100px;width: auto;">
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <button class="btn"
                                            style="border-radius: 50px; padding: 8px 20px; font-size: 14px;background-color: rgb(96, 200, 242);;">
                                            PLAY NOW !
                                        </button>
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 30px 0;" />

                                <p style="font-size: 14px; color: #555; margin-bottom: 20px;">Winning numbers</p>
                                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        31
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        21
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: #f0f0f0; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        19
                                    </span>
                                    <span style="width: 35px; height: 35px; background-color: rgb(96, 200, 242); color: #fff; 
                             border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        69
                                    </span>
                                </div>
                            </div>
                            <!-- Bottom Blue Section -->
                            <div style="background-color: rgb(96, 200, 242);; padding: 10px; text-align: center; 
                    border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                                <p style="font-size: 14px; color: #fff; margin: 0;">
                                    <i class="fas fa-clock"></i> 3 days left
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Add more cards as necessary -->
                </div>

                <div class="text-center mt-4">
                    <button class="btn"
                        style="background-color: rgb(96, 200, 242);border-radius: 50px; padding: 10px 30px;">Load
                        More</button>
                </div>
            </div>


            <div class="row d-flex justify-content-center align-items-center"
                style="margin-bottom: 50px;margin-top: 150px;border-top-color: aqua;border-style: solid;">
                <div class="col-12" style="background-color:#EAF4FC;">

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
                                            <button class="btn"
                                                style="background-color: rgb(96, 200, 242);border-radius: 50px; padding: 10px 30px;color: white;">Check
                                                FAQs
                                            </button>
                                        </div>
                                    </div>
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
    data() {
        return {
            logoUrl: '/assets/images/1.png', // Path to your logo
            logoUrl1: '/assets/images/2.png', // Path to your logo
            logoUrl2: '/assets/images/3.png', // Path to your logo
        };
    },
};
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
