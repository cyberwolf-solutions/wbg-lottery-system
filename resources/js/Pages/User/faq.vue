<!-- <script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


</script> -->

<template>


    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="container my-5">
        <div class="faq-container">
            <h2 class="text-center fw-bold" style="font-size: 30px;">Frequently Asked Questions</h2>
            <p class="text-center" style="margin-bottom: 20px;margin-top: 20px;color:gray">Choose a category below for immediate online lottery help! If our FAQ section has not answered your inquiry, please contact us via email, live chat, or telephone.</p>
            
            <div class="category-buttons">
          
                <button class="custom-button mt-3" style=" background-color: #63b5f6;">SorteoTickets</button>
                <button class="custom-button mt-3" style=" background-color: #63b5f6;">Banking</button>
                <button class="custom-button mt-3" style=" background-color: #63b5f6;">Winning</button>
                <button class="custom-button mt-3" style=" background-color: #63b5f6;">Results & Alerts</button>
                <button class="custom-button mt-3" style=" background-color: #63b5f6;">About</button>
                <button class="custom-button mt-3" style=" background-color: #63b5f6;">General</button>
               
            </div>

            <div class="accordion" id="faqAccordion">
                <div class="accordion-item" style="border-style: none;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            What are lottery results alerts?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Lottery results alerts notify you when the latest results are available.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" style="border-style: none;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            What are jackpot alerts?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Jackpot alerts notify you when the jackpot amount reaches a significant level.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" style="border-style: none;">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;" data-bs-toggle="collapse" data-bs-target="#faq3">
                            How do I sign up to receive SMS winning alerts?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            To subscribe to FREE SMS winning alerts, please fill in your mobile phone number in the ‘SMS Number’ field in the Notification settings and save the changes. To unsubscribe, uncheck the box next to the ‘SMS Number’ field and save the changes.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" style="border-style: none;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;" data-bs-toggle="collapse" data-bs-target="#faq4">
                            When are lottery results updated on the site?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Lottery results are updated shortly after each draw.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" style="border-style: none;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;" data-bs-toggle="collapse" data-bs-target="#faq5">
                            How do I read the lottery results?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Lottery results are displayed in an easy-to-read format on our results page.
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
            logoUrl: '/assets/images/s.png', // Path to your logo
            logoUrl1: '/assets/images/2.png', // Path to your logo
            logoUrl2: '/assets/images/3.png', // Path to your logo
        };
    },
};
</script>

<style>
body {
    background-color: #f8f9fa;
}
.faq-container {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.category-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}
.category-buttons button {
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    background: #eaeaea;
    cursor: pointer;
}
.category-buttons .active {
    background: #007bff;
    color: white;
}
.accordion-button {
    background: #f1f1f1;
    color: #333;
    border-radius: 5px;
    font-weight: bold;
}
.accordion-button::after {
    filter: brightness(0.5);
}
</style>
