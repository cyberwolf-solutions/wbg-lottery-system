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
            <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">Terms and conditions</p>

        </div>



        <div class="container">
            <h1>Terms and Conditions</h1>
            <p>Welcome to WinBoard Game! By using our app, you agree to the following terms and conditions. Please read
                them
                carefully.</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By accessing or using the WinBoard Game app, you agree to be bound by these terms and conditions. If you
                do not
                agree, please do not use the app.</p>

            <h2>2. Eligibility</h2>
            <ul>
                <li>You must be at least 18 years old to use this app.</li>
                <li>You agree to comply with all applicable laws and regulations.</li>
            </ul>

            <h2>3. Game Rules</h2>
            <ul>
                <li>Users can pre-book numbers ranging from 00 to 99 before the draw of the Sri Lanka National Lottery
                    Board and
                    Development Lottery Board.</li>
                <li>The winning numbers are based on the last or first two digits of the official lottery draw.</li>
                <li>If your pre-booked number matches the winning number, you will receive 70% of the total earnings
                    from the
                    100-number bookings for that round.</li>
                <li>No refunds or cancellations allowed other than booking threshold rule.</li>
            </ul>

            <h2>4. Booking Threshold</h2>
            <ul>
                <li>A minimum of 80% of numbers (00–99) must be booked for a draw to proceed.</li>
                <li>If the threshold is not met, bookings will be refunded in full to the users’ wallets before the draw
                    time.
                </li>
                <li>Refunds will be processed by 8:00 PM Sri Lanka Time on the draw date.</li>
                <li>Users can use the refunded funds for future bookings.</li>
            </ul>

            <h2>5. Referral Program</h2>
            <ul>
                <li>Users can refer others to join WinBoard Game.</li>
                <li>Referrers will earn 10% of the prize won by their referred users.</li>
                <li>Example: If your referral wins a prize of 70,000, you will receive 7,000 as a referral bonus.</li>
            </ul>

            <h2>6. Deposits and Withdrawals</h2>
            <ul>
                <li>All financial transactions, including deposits and withdrawals, will be conducted in USDT (Tether).
                </li>
                <li>Users are responsible for ensuring that their deposit and withdrawal information is accurate.</li>
            </ul>

            <h2>7. User Responsibilities</h2>
            <ul>
                <li>Users must provide accurate and up-to-date information.</li>
                <li>Users are responsible for maintaining the confidentiality of their account details.</li>
            </ul>

            <h2>8. Prohibited Activities</h2>
            <p>Engaging in fraudulent activities or attempting to manipulate the game’s outcome is strictly prohibited.
                Any
                violation may result in account suspension or termination.</p>

            <h2>9. Limitation of Liability</h2>
            <p>WinBoard Game is not responsible for any losses incurred due to technical issues, delays, or errors in
                the
                lottery results. The app is provided "as is," and we make no warranties of any kind, express or implied.
            </p>

            <h2>10. Privacy Policy</h2>
            <p>WinBoard Game respects your privacy and is committed to protecting your personal data. Please refer to
                our <a href="privacy-policy.html">Privacy Policy</a> for detailed information.</p>

            <h2>11. Changes to Terms</h2>
            <p>WinBoard Game reserves the right to modify these terms at any time. Continued use of the app after
                changes are
                posted constitutes acceptance of the new terms.</p>

            <h2>12. Governing Law</h2>
            <p><strong>Legal Compliance Notice:</strong> Before participating in any gaming activities through WinBoard
                Game,
                please ensure that you comply with the laws and regulations governing such activities in your
                jurisdiction. It
                is your responsibility to understand and adhere to any applicable legal requirements. WinBoard Game
                assumes no
                responsibility for any legal consequences resulting from your participation if it violates the laws of
                your
                location.</p>

            <h2>13. Contact Information</h2>
            <p class="contact">For any questions or concerns regarding these terms, please contact us at <a
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
    name: "terms",
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
    background-color: #ffffff;
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
