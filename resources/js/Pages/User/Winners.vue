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

        <div class="d-flex justify-content-center align-items-center mt-4"
            style="height: auto; flex-direction: column; text-align: center;">
            <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">List of lottery winners</p>
            <p style="font-size: smaller; color: gray; margin-top: 0;margin-bottom: 50px;">Our biggest winners have won lottery jackpots
                and million dollar prizes. Read their storie below.
            </p>
        </div>



        <div class="row d-flex justify-content-center align-items-center mt-4">
            <div class="col-8">
                <div class="row">
                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl1" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl2" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl1" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl2" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-4" style="border-style: none;">
                        <div class="card" style="width: 14rem;border-style: none;">
                            <img :src="logoUrl" alt="Logo" class="card-img-top" style="height: 200px;">
                            <div class="card-body" style="border-top-color: aqua;">
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the
                                    card's content.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <!-- ------ -->

        <div class="row d-flex justify-content-center align-items-center" style="margin-bottom: 50px;margin-top: 150px;border-top-color: aqua;border-style: solid;">
            <div class="col-12" style="background-color:#EAF4FC;">

                <div class="row d-flex justify-content-center align-items-center mt-4">
                    <div class="col-10 text-center">
                        <h1
                            style="margin-top: 50px;color: #333; margin-bottom: 20px;font-size: 30px;font-weight: bold;">
                            How to
                            Collect Your Wins</h1>
                        <div style="width: 50%;margin-left: auto;margin-right: auto;">
                            <p style="color: #555; font-size: 14px; margin-bottom: 40px;">
                                Winners updated weekly. Prize value listed as won may not reflect actual net claims
                                payment
                                amount
                                in photo
                                due to combined prize claim amounts and other adjustments. Purchase location shown where
                                applicable.
                                All Prizes are 100% commission-FREE.
                            </p>
                        </div>

                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-bottom: 100px;">

                            <!-- Win Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;">
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;"> -->
                                <h3 style="color: #333; margin-bottom: 10px;font-weight: bolder;">Win</h3>
                                <p style="color: #555; font-size: 12px;">
                                    Win any prize while playing your favourite lotteries online at Sorteo.
                                </p>
                            </div>

                            <!-- Notification Section -->
                            <div
                            style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;">
                                <h3 style="color: #333; margin-bottom: 10px;">Notification</h3>
                                <p style="color: #555; font-size: 14px;">
                                    Receive an instant notification by email or SMS!
                                </p>
                            </div>

                            <!-- Collect Prize Section -->
                            <div
                            style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;">
                            <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;"> -->
                                <h3 style="color: #333; margin-bottom: 10px;">Collect Your Prize</h3>
                                <p style="color: #555; font-size: 14px;">
                                    Your win will be directly transferred to your account on Sorteo!
                                </p>
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
            logoUrl: '/assets/winners/a.jpeg', // Path to your logo
            logoUrl1: '/assets/winners/b.jpeg', // Path to your logo
            logoUrl2: '/assets/winners/c.jpeg', // Path to your logo
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


