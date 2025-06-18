<template>
    <Nav />

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="landing-page container-fluid px-0 mb-0">
            <div class="d-flex justify-content-center align-items-center mt-4"
                style="height: auto; flex-direction: column; text-align: center;">
                <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px; margin-top: 70px;">Lotteries Page</p>
                <p style="margin-bottom: 50px; font-size: smaller; color: gray; margin-top: 0;">
                    Explore exciting lottery games and book your lucky numbers to win big prizes with WinBoard Game
                </p>
            </div>

            <div class="container py-5">
                <div class="row g-4 d-flex justify-content-center align-items-center">
                    <!-- Loop through lotteries -->
                    <div v-for="lottery in lotteries" :key="lottery.id" class="col-md-6 col-lg-5">
                        <div class="card shadow" style="border-radius: 15px; border: none; overflow: hidden;">
                            <div class="card-body text-center">
                                <div class="row" style="height: 70px;">
                                    <div class="col-6">
                                        <!-- Display lottery image -->
                                        <img :src="`/${lottery.image}` || logoUrl1" alt="Lottery Image"
                                            class="card-img-top" style="height: 100px; width: auto;">
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <button class="btn text-white"
                                            style="border-radius: 50px; padding: 8px 20px; font-size: 14px; background-color: rgb(96, 200, 242);"
                                            @click="handlePlayNow(lottery.id)">
                                            PLAY NOW!
                                        </button>
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 30px 0;" />

                                <!-- Display lottery name -->
                                <p style="font-size: 14px; color: #555; margin-bottom: 20px;">{{ lottery.name }}</p>

                                <!-- Display winning numbers or countdown -->
                                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                                    <template v-if="lottery.dashboards && lottery.dashboards.length > 0">
                                        <!-- Display countdown if draw date is in the future -->
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <p style="font-size: 12px; color: #555; margin-bottom: 5px;">
                                                Next Draw: {{ formatDrawDate(lottery.dashboards[0].date) }} at 8:00 PM
                                            </p>
                                            <p style="font-size: 12px; color: #555; margin-bottom: 5px;">
                                                Time Remaining:
                                            </p>
                                            <div style="display: flex; gap: 5px;">
                                                <span
                                                    style="display: flex; flex-direction: column; align-items: center;">
                                                    <span style="font-size: 16px; font-weight: bold;">
                                                        {{ countdowns[lottery.id]?.days || '00' }}
                                                    </span>
                                                    <span style="font-size: 10px;">Days</span>
                                                </span>
                                                <span
                                                    style="display: flex; flex-direction: column; align-items: center;">
                                                    <span style="font-size: 16px; font-weight: bold;">
                                                        {{ countdowns[lottery.id]?.hours || '00' }}
                                                    </span>
                                                    <span style="font-size: 10px;">Hours</span>
                                                </span>
                                                <span
                                                    style="display: flex; flex-direction: column; align-items: center;">
                                                    <span style="font-size: 16px; font-weight: bold;">
                                                        {{ countdowns[lottery.id]?.minutes || '00' }}
                                                    </span>
                                                    <span style="font-size: 10px;">Minutes</span>
                                                </span>
                                                <span
                                                    style="display: flex; flex-direction: column; align-items: center;">
                                                    <span style="font-size: 16px; font-weight: bold;">
                                                        {{ countdowns[lottery.id]?.seconds || '00' }}
                                                    </span>
                                                    <span style="font-size: 10px;">Seconds</span>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Display winning numbers if draw date has passed -->

                                    </template>

                                    <!-- Fallback if no dashboard data -->
                                    <template v-else>
                                        <span style="font-size: 14px; color: #888;">No upcoming draws</span>
                                    </template>
                                </div>
                            </div>
                            <!-- Bottom Blue Section -->
                            <div :style="{ backgroundColor: lottery.color }" style="padding: 10px; text-align: center; 
                                border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                            </div>
                        </div>
                    </div>
                    <!-- End Loop -->
                </div>
            </div>

            <div class="row d-flex justify-content-center align-items-center mb-0"
                style="margin-bottom: 50px; margin-top: 150px; border-top-color: aqua; border-style: solid;">
                <div class="col-12" style="background-color: #EAF4FC;">
                    <div class="row d-flex mt-4">
                        <div class="col-10 text-center">
                            <div class="row">
                                <div class="col-6 d-flex justify-content-center align-items-center">
                                    <i class="bi bi-patch-question-fill"
                                        style="color: rgb(96, 200, 242); font-size: 150px;"></i>
                                </div>
                                <div class="col-6">
                                    <h1
                                        style="margin-top: 50px; color: #333; margin-bottom: 20px; font-size: 30px; font-weight: bold;">
                                        If you have any questions</h1>
                                    <div style="width: 50%; margin-left: auto; margin-right: auto; align-items: left;">
                                        <p style="color: #555; font-size: 14px; margin-bottom: 40px; text-align: left;">
                                            Winners updated frequently. Prize value listed as won may not reflect actual
                                            net claims payment amount in photo due to combined prize claim amounts and
                                            other
                                            adjustments. All Prizes are 100% commission-FREE.
                                        </p>
                                        <div class="text-center mt-4" style="margin-bottom: 30px;">
                                            <a :href="route('faq')" :active="route().current('faq')">
                                                <button class="btn"
                                                    style="background-color: rgb(96, 200, 242); border-radius: 50px; padding: 10px 30px; color: white;">
                                                    Check FAQs
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
        </div>

        <!-- Register Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <RegisterForm />
                    </div>
                </div>
            </div>
        </div>

        <Footer />
    </AuthenticatedLayout>
</template>
<script>
import { onMounted, onBeforeUnmount } from 'vue';
import Footer from "@/components/Landing/footer.vue";
import Nav from "@/components/Landing/nav.vue";
import RegisterForm from '@/Pages/Auth/Register.vue';

export default {
    components: { Footer, Nav, RegisterForm },
    props: {
        lotteries: Array,
    },
    data() {
        return {
            logoUrl: '/assets/images/1.png',
            logoUrl1: '/assets/images/2.png',
            logoUrl2: '/assets/images/3.png',
            countdowns: {},
            intervals: {}, // Store intervals for each lottery
        };
    },
    methods: {
        getImageUrl(image) {
            return image.startsWith("http") ? image : `/storage/${image}`;
        },
        handlePlayNow(lotteryId) {
            // Your play handler here
        },
        formatDrawDate(date) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        calculateCountdown(drawDate) {
            const dateParts = drawDate.split('-');

            // alert()

            const sriLankaTimeUTC = new Date(Date.UTC(
                parseInt(dateParts[0]),
                parseInt(dateParts[1]) - 1,
                parseInt(dateParts[2]),
                14,
                30
            ));

            const targetDate = sriLankaTimeUTC.getTime();
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance <= 0) {
                return { days: '00', hours: '00', minutes: '00', seconds: '00' };
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            return {
                days: String(days).padStart(2, '0'),
                hours: String(hours).padStart(2, '0'),
                minutes: String(minutes).padStart(2, '0'),
                seconds: String(seconds).padStart(2, '0'),
            };
        }
        ,
        startCountdowns() {
            this.lotteries.forEach(lottery => {
                const dashboard = lottery.dashboards?.[0];
                if (!dashboard) return;

                // Parse date and create 8:00 PM Sri Lanka time (UTC+5:30 = 14:30 UTC)
                const dateParts = dashboard.date.split('-');
                const drawDateTimeUTC = new Date(Date.UTC(
                    parseInt(dateParts[0]),
                    parseInt(dateParts[1]) - 1,
                    parseInt(dateParts[2]),
                    14, // 8 PM in UTC+5:30 is 14:30 UTC
                    30
                ));

                const now = new Date();

                // Start countdown if the draw date-time is still in the future
                if (drawDateTimeUTC.getTime() > now.getTime()) {
                    this.updateCountdown(lottery.id, dashboard.date);

                    this.intervals[lottery.id] = setInterval(() => {
                        const updatedCountdown = this.calculateCountdown(dashboard.date);
                        this.countdowns[lottery.id] = updatedCountdown;

                        // Optional: stop interval when countdown hits 0
                        if (
                            updatedCountdown.days === '00' &&
                            updatedCountdown.hours === '00' &&
                            updatedCountdown.minutes === '00' &&
                            updatedCountdown.seconds === '00'
                        ) {
                            clearInterval(this.intervals[lottery.id]);
                        }
                    }, 1000);
                }
            });
        }
        ,
        updateCountdown(lotteryId, date) {
            this.countdowns[lotteryId] = this.calculateCountdown(date);
        },
        clearCountdowns() {
            Object.values(this.intervals).forEach(interval => clearInterval(interval));
        }
    },
    mounted() {
        this.startCountdowns();
    },
    beforeUnmount() {
        this.clearCountdowns();
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
