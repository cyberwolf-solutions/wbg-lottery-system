<template>
    <Nav  />

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="container d-flex justify-content-center align-items-center mt-4"
            style="height: auto; flex-direction: column; text-align: center;">
            <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">How it works
            </p>
            <p style="margin-bottom: 50px;font-size: smaller; color: gray; margin-top: 0;">Start booking your numbers
                today and
                invite friends to multiply the fun and
                rewards!
            </p>
        </div>

        <div class="container py-5">
            <div class="row g-4">
                <!-- Video Section -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-0">
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/dbH5eJ0Bb28" title="How to play lottery"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <div class="p-4">
                                <h4 class="text-center mb-3">How to Play & Win</h4>
                                <p class="text-muted text-center">
                                    Watch our quick guide to understand how to participate and win big!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Steps Section -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">Simple Steps to Win</h3>

                            <!-- Step 1 -->
                            <div class="d-flex mb-4">
                                <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; flex-shrink: 0;">
                                    1
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Choose your lottery & pick your numbers</h5>
                                    <p class="text-muted mb-0">Select from our exciting lottery options</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="d-flex mb-4">
                                <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; flex-shrink: 0;">
                                    2
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Buy</h5>
                                    <p class="text-muted mb-0">Secure your tickets with our easy payment system</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="d-flex mb-4">
                                <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; flex-shrink: 0;">
                                    3
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Complete your purchase</h5>
                                    <p class="text-muted mb-0">Get instant confirmation of your tickets</p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="d-flex">
                                <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; flex-shrink: 0;">
                                    4
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Win</h5>
                                    <p class="text-muted mb-0">Start dreaming, you're almost there</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center mb-0"
            style="margin-bottom: 50px;margin-top: 150px;border-top-color: aqua;border-style: solid;">
            <div class="col-12" style="background-color:#EAF4FC;">

                <div class="row d-flex justify-content-center align-items-center mt-4">
                    <div class="col-10 text-center">
                        <h1
                            style="margin-top: 50px;color: #333; margin-bottom: 20px;font-size: 30px;font-weight: bold;">
                            Win Board Game Rewards</h1>
                        <div style="width: 50%;margin-left: auto;margin-right: auto;">
                            <p style="color: #555; font-size: 14px; margin-bottom: 40px;">
                                Winners will be updated daily. Prize value is 70 times of the initial value of that selected
                                board
                                Purchase
                                user id shown where applicable. All Prizes are 100% commission-FREE.
                            </p>
                        </div>

                        <div
                            style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-bottom: 100px;">

                            <!-- Win Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <i class="bi bi-calculator"
                                    style="margin-bottom: 15px;margin-left: auto;margin-right: auto;font-size: 60px;color:rgb(96, 200, 242);"></i>

                                <h3 style="color: #333; margin-bottom: 10px;font-weight: bolder;">Win</h3>
                                <p style="color: #555; font-size: 12px;">
                                    Win 70 times your booking price if your numbers hit
                                </p>
                            </div>

                            <!-- Notification Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <i class="bi bi-boxes"
                                    style="margin-bottom: 15px;margin-left: auto;margin-right: auto;font-size: 60px;color:rgb(96, 200, 242);"></i>

                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;"> -->
                                <h3 style="color: #333; margin-bottom: 10px;">Notification</h3>
                                <p style="color: #555; font-size: 14px;">
                                    Receive Notification to your mail and USDT to your winboard wallet.
                                </p>
                            </div>

                            <!-- Collect Prize Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <i class="bi bi-browser-edge"
                                    style="margin-bottom: 15px;margin-left: auto;margin-right: auto;font-size: 60px;color:rgb(96, 200, 242);"></i>

                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;"> -->
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;"> -->
                                <h3 style="color: #333; margin-bottom: 10px;">Collect Your Prize</h3>
                                <p style="color: #555; font-size: 14px;">
                                    Your win will be directly transferred to your winboard wallet and withdraw from it
                                    anytime
                                </p>
                            </div>

                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div class="row d-flex justify-content-center align-items-center mt-0"
            style="margin-bottom: 50px;margin-top: 150px;border-top-color: aqua;border-style: solid;">
            <div class="col-12" style="background-color:#EAF4FC;">

                <div class="row d-flex mt-4">
                    <div class="col-10 text-center">

                        <div class="row">
                            <div class="col-6 d-flex justify-content-center align-items-center">
                                <i class="bi bi-patch-question-fill"
                                    style="color: rgb(96, 200, 242) ;font-size: 150px;"></i>

                                <!-- <img :src="logoUrl" alt="Logo" class="card-img-top" style="height: 100px;width: auto;"> -->
                            </div>
                            <div class="col-6">
                                <h1
                                    style="margin-top: 50px;color: #333; margin-bottom: 20px;font-size: 30px;font-weight: bold;">
                                    If you have any questions</h1>
                                <div style="width: 50%;margin-left: auto;margin-right: auto;align-items: left;">
                                    <p style="color: #555; font-size: 14px; margin-bottom: 40px;text-align: left;">
                                        Start booking your numbers today and invite friends to multiply the fun and
                                        rewards!
                                        Stay tuned here for updates, winner announcements, and special offers!
                                        Contact us for any questions.
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




        <Footer />
    </AuthenticatedLayout>
</template>

<script>
import Footer from "@/components/Landing/footer.vue";
import Nav from "@/components/Landing/nav.vue";

export default {
    data() {
        return {
            logoUrl: '/assets/images/s.png', // Path to your logo
            logoUrl1: '/assets/images/2.png', // Path to your logo
            logoUrl2: '/assets/images/3.png', // Path to your logo
        };
    },
    name: "hom",
    components: {
        Footer,
        Nav,

    },
};
</script>

<style>

.step-number {
  font-weight: bold;
  font-size: 1.2rem;
}
.card {
  border: none;
  border-radius: 12px;
  transition: transform 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: white;
}

a:hover {
    transform: scale(1.1);
}
/* 
.container {
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
