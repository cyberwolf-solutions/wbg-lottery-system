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
            <p style="font-size: 50px; font-weight: bold; margin-bottom: 10px;margin-top:70px;">List of lottery winners
            </p>
            <p style="font-size: smaller; color: gray; margin-top: 0;margin-bottom: 50px;">Our biggest winners have won
                70 times
                of the initial board booking value.. Read their story below.
            </p>
        </div>




        <div class="winners-container">
            <div v-for="lottery in lotteries" :key="lottery.id" class="lottery-section">
                <h2 class="lottery-title">{{ lottery.name }}</h2>

                <!-- Check if the lottery has dashboards with winners -->
                <div v-if="lottery.dashboards && lottery.dashboards.length > 0">
                    <div class="winners-grid">
                        <div v-for="dashboard in lottery.dashboards" :key="dashboard.id">
                            <!-- Ensure that only winners belonging to this lottery are displayed -->
                            <div v-if="dashboard.winners && dashboard.winners.length > 0">
                                <div v-for="winner in dashboard.winners" :key="winner.id" class="winner-card">
                                    <div class="winner-image-container">
                                        <img :src="`/${winner.user.image}`|| logoUrl1"  alt="Winner" class="winner-image">
                                        <div class="winner-badge">USDT {{ winner.price }}</div>
                                    </div>
                                    <div class="winner-details">
                                        <h3>{{ winner.user.name }}</h3>
                                        <div class="winner-meta">
                                            <p><i class="bi bi-calendar"></i> {{ dashboard.date }}</p>
                                            <p><i class="bi bi-ticket"></i> Draw #{{ dashboard.draw_number }}</p>
                                            <p><i class="bi bi-trophy"></i> {{ dashboard.dashboard }}</p>
                                            <p v-if="dashboard.dashboardType"><i class="bi bi-info-circle"></i> {{
                                                dashboard.dashboardType }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="no-winners">
                    No winners yet for this lottery
                </div>
            </div>
        </div>

            <!-- ------ -->

            <div class="row d-flex justify-content-center align-items-center mb-0"
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
                                    How to be a winner from every board
                                    Simple & Exciting: Easy to play, with massive winning potential!
                                    Refer & Earn: Invite friends and earn 10% of their winnings!
                                    Example: If your friend wins 70,000, you earn 7,000!
                                    Secure Payments: All transactions are in USDT for fast and secure
                                    deposits/withdrawals.
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
                                        :Help your friends to join and win
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
                                        .Earn 10% of their prize if they win!
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>



                </div>
            </div>



            <Footer   />
    </AuthenticatedLayout>
</template>

    <script>
    import Footer from "@/components/Landing/footer.vue";
    import Nav from "@/components/Landing/nav.vue";
    import { Swiper, SwiperSlide } from 'swiper/vue';
    import 'swiper/swiper-bundle.css';
    import 'bootstrap/dist/js/bootstrap.bundle.min.js';

    export default {
        components: {
            Footer,
            Nav,
            Swiper,
            SwiperSlide,
        },
        props: {
            lotteries: Array,
        },
        data() {
            return {
                swiperOptions: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                },
                logoUrl: '/assets/winners/a.jpeg',
            };
        },
    };
</script>

    <style>

    .winners-header {
        text-align: center;
        padding: 80px 20px 40px;
        max-width: 800px;
        margin: 0 auto;
    }

    .winners-header h1 {
        font-size: 2.8rem;
        font-weight: 800;
        color: #2c2c2c;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .winners-header .subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 0;
    }

    .winners-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }

    .lottery-section {
        margin-bottom: 60px;
    }

    .lottery-title {
        text-align: center;
        font-size: 1.8rem;
        color: #2c2c2c;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
    }

    .lottery-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #60c8f2, #4d9539);
    }

    .winners-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .winner-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
    }

    .winner-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .winner-image-container {
        position: relative;
        height: 300px;
        overflow: hidden;
    }

    .winner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1s ease;
    }

    .winner-card:hover .winner-image {
        transform: scale(1.05);
    }

    .winner-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #4d9539, #60c8f2);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .winner-details {
        padding: 20px;
        text-align: center;
    }

    .winner-details h3 {
        margin: 0 0 10px;
        color: #333;
        font-size: 1.2rem;
    }

    .winner-meta {
        font-size: 0.9rem;
        color: #666;
    }

    .winner-meta p {
        margin: 5px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .winner-meta i {
        color: #60c8f2;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .winners-header h1 {
            font-size: 2rem;
        }

        .winners-grid {
            grid-template-columns: 1fr;
        }
    }


    .carousel-item {
        padding: 20px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: 10px;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }

    .card {
        margin: 0 auto;
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