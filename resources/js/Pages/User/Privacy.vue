<!-- <script setup>
import { ref } from 'vue';
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
            <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">Privacy policy</p>






        </div>

        <div class="container">
            <!-- <h1>Privacy Policy</h1> -->
            <p style="color:silver;">At WinBoard Game, your privacy is important to us. This Privacy Policy outlines how
                we
                collect, use,
                and
                protect your personal information.</p>

            <h2>1. Information We Collect</h2>
            <ul>
                <li><strong>Personal Information:</strong> Name, email, phone number, and payment details (USDT
                    wallet
                    information).</li>
                <li><strong>Usage Information:</strong> Game activity, preferences, and interactions.</li>
                <li><strong>Device Information:</strong> Device type, operating system, and IP address.</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <ul>
                <li><strong>To Provide Services:</strong> Manage account, process bookings, and facilitate
                    transactions.
                </li>
                <li><strong>To Improve Our Services:</strong> Analyze usage patterns and enhance user experience.
                </li>
                <li><strong>To Communicate with You:</strong> Send updates, promotions, and important
                    account-related
                    messages.</li>
                <li><strong>For Security and Fraud Prevention:</strong> Prevent unauthorized access and fraudulent
                    activities.</li>
            </ul>

            <h2>3. Sharing Your Information</h2>
            <ul>
                <li><strong>We Do Not Sell Your Data:</strong> We do not trade or rent your personal information.
                </li>
                <li><strong>Service Providers:</strong> Third-party vendors assisting in operations (e.g., payment
                    processors).</li>
                <li><strong>Legal Requirements:</strong> Compliance with legal obligations if necessary.</li>
            </ul>

            <h2>4. Data Security</h2>
            <p>We implement encryption, secure servers, and access controls to protect your data.</p>

            <h2>5. Your Rights</h2>
            <ul>
                <li><strong>Access and Correction:</strong> Update your personal details via account settings.</li>
                <li><strong>Deletion:</strong> Request deletion of data, subject to legal obligations.</li>
                <li><strong>Opt-Out:</strong> Unsubscribe from promotional communications.</li>
            </ul>

            <h2>6. Cookies and Tracking Technologies</h2>
            <p>We use cookies to enhance your experience, personalize content, and improve our services.</p>

            <h2>7. Children's Privacy</h2>
            <p>WinBoard Game is not intended for children under 18, and we do not knowingly collect their data.</p>

            <h2>8. Changes to This Privacy Policy</h2>
            <p>We may update this Privacy Policy periodically. Changes will be posted with an updated effective
                date.</p>

            <h2>9. Contact Us</h2>
            <p class="contact">If you have any questions, please contact us at <a
                    href="mailto:info@winboardgame.com">info@winboardgame.com</a>.</p>
        </div>




        <!-- ------ -->

        <div class="row d-flex justify-content-center align-items-center"
            style="margin-bottom: 50px;margin-top: 150px;border-top-color: aqua;border-style: solid;">
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

                        <div
                            style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-bottom: 100px;">

                            <!-- Win Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;"> -->
                                <i class="bi bi-trophy-fill"
                                    style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;font-size: 100px;color: rgb(96, 200, 242);"></i>
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;"> -->
                                <h3 style="color: #333; margin-bottom: 10px;font-weight: bolder;">Win</h3>
                                <p style="color: #555; font-size: 12px;">
                                    Win any prize while playing your favourite lotteries online at WBG.
                                </p>
                            </div>

                            <!-- Notification Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;"> -->
                                <i class="bi bi-bell"
                                    style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;font-size: 100px;color: rgb(96, 200, 242);"></i>

                                <h3 style="color: #333; margin-bottom: 10px;">Notification</h3>
                                <p style="color: #555; font-size: 14px;">
                                    Receive an instant notification by email or SMS!
                                </p>
                            </div>

                            <!-- Collect Prize Section -->
                            <div
                                style="background-color:#EAF4FC; border: 1px solid rgb(96, 200, 242); border-radius: 10px; padding: 20px; width: 300px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <i class="bi bi-gift-fill"
                                    style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;font-size: 100px;color: rgb(96, 200, 242);"></i>
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;border-radius: 100px;height: 150px;width: 150px;"> -->
                                <!-- <img :src="logoUrl" alt="Logo" style="margin-bottom: 15px;margin-left: auto;margin-right: auto;"> -->
                                <h3 style="color: #333; margin-bottom: 10px;">Collect Your Prize</h3>
                                <p style="color: #555; font-size: 14px;">
                                    Your win will be directly transferred to your account on WBG!
                                </p>
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
            logoUrl: '/assets/winners/a.jpeg', // Path to your logo
            logoUrl1: '/assets/winners/b.jpeg', // Path to your logo
            logoUrl2: '/assets/winners/c.jpeg', // Path to your logo
        };
    },
    name: "pr",
    components: {
        Footer,
        Nav,

    },
};
</script>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
    color: #333;
}



h1,
h2 {
    color: #000000;
    font-weight: bold;
}

h2 {
    margin-top: 20px;
}

ul {
    list-style-type: disc;
    padding-left: 20px;
}

.contact {
    margin-top: 20px;
    font-weight: bold;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #ffffff;
}

a:hover {
    transform: scale(1.1);
}

.container {
    /* width: 90%; */
    max-width: 1200px;
    /* margin: 20px auto; */
    background: #fff;
    /* border-radius: 10px; */
    /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
    /* padding: 20px; */
    /* max-width: 800px; */
    /* margin: 0 auto; */
    background: white;
    /* padding: 20px; */
    /* border-radius: 8px; */
    /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
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
