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

        <div class="container my-5">
            <div class="faq-container">
                <h2 class="text-center fw-bold" style="font-size: 30px;">Frequently Asked Questions</h2>
                <p class="text-center" style="margin-bottom: 20px;margin-top: 20px;color:gray">Choose a category below
                    for
                    immediate online lottery help! If our FAQ section has not answered your inquiry, please contact us
                    via
                    email, live chat, or telephone.</p>

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
                            <button class="accordion-button collapsed"
                                style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                What is WinBoard Game?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">

                            <div class="accordion-body">
                                WinBoard Game is a unique lottery matching game where users pre-book numbers ranging
                                from 00 to
                                99. If the number you booked matches the Last two digits or First two digits of the
                                official Sri
                                Lanka National Lottery Board or Development Lottery Board draw, you win 70% of the total
                                earnings from that round’s bookings.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="border-style: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How can I book a number?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Booking is simple:
                                <ul>
                                    <li>Select any number between 00 to 99 from the board.</li>
                                    <li>Make the booking payment using your wallet balance in USDT.</li>
                                    <li>Once booked, you’ll receive confirmation, and your number will be locked for
                                        that round.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="border-style: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Can I book multiple boards or numbers?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! You can book:
                                <ul>
                                    <li>Multiple numbers on the same board.</li>
                                    <li>Numbers on multiple boards for different draws.</li>
                                </ul>
                                This increases your chances of winning!
                            </div>
                        </div>
                    </div>

                    <!-- Additional questions added below -->

                    <div class="accordion-item" style="border-style: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Can I win multiple times?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Absolutely! If you book multiple numbers and more than one matches the winning number
                                for
                                different boards, you will win the respective prizes for each match.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="border-style: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                What is the 80% rule for the board?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                For a draw to proceed, at least 80% of the numbers (80 out of 100) on a board must be
                                booked. If
                                the 80% threshold is not met, the board is canceled, and a full refund is issued to your
                                wallet
                                before the draw.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="border-style: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                style="border-radius: 100px;margin-top: 10px;background-color: whitesmoke;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Is WinBoard Game legal?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Before joining WinBoard Game, please ensure participation in lottery or gaming
                                activities is
                                legal in your jurisdiction. WinBoard Game is not responsible for any legal issues
                                arising from
                                non-compliance with local laws.
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
import { onMounted } from 'vue';
import Footer from "@/components/Landing/footer.vue";
import Nav from "@/components/Landing/nav.vue";

export default {
    components: { Footer, Nav },
    setup() {
        onMounted(() => {
            import('bootstrap/dist/js/bootstrap.bundle.min.js').then(() => {
                console.log('Bootstrap initialized');
            });
        });
    }
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
