<template>
    <Nav />

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Contact
            </h2>
        </template>

        <div class="container d-flex justify-content-center align-items-center" style="margin-bottom: 100px;">
            <div class="row contact-container" style="margin-top: 20px;margin-bottom: 30px;">
                <!-- Left Section -->
                <div class="col-md-6 contact-left">
                    <h3 style="margin-bottom: 20px;margin-top: 20px;font-weight: bolder;font-size: 20px;">Get In Touch
                    </h3>
                    <p style="margin-bottom: 30px;margin-top: 5px;font-weight: bolder;font-size: 14px;color: gray;">If
                        you have
                        any questions or queries, our helpful support team will be more than happy to assist.
                    </p>
                    <form @submit.prevent="sendMessage">
                        <div class="form-group">
                            <input v-model="fullName" type="text" class="form-control" placeholder="Full Name"
                                required />
                        </div>
                        <div class="form-group mt-1">
                            <input v-model="contactNumber" type="text" class="form-control" placeholder="Contact number"
                                required />
                        </div>
                        <div class="form-group mt-1">
                            <input v-model="email" type="email" class="form-control" placeholder="Email" required />
                        </div>
                        <div class="form-group mt-1">
                            <textarea v-model="message" class="form-control" rows="3" placeholder="Message"
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-2">SEND MESSAGE</button>
                    </form>
                </div>
                <!-- Right Section -->
                <div class="col-md-6 contact-right" style="position: relative;">
                    <!-- Background Image -->
                    <img :src="logoUrl3" alt="Backgroud" class="contact-bg" />

                    <!-- Content -->
                    <div class="contact-content">
                        <h4 style="margin-bottom: 20px; margin-top: 20px; font-weight: bolder; font-size: 20px;">We Are
                            Available</h4>
                        <p>24 Hours A Day, 365 Days A Year</p>
                        <hr style="border-color: rgba(255,255,255,0.2); margin-top: 30px; margin-bottom: 30px;" />
                        <p style="margin-bottom: 20px; margin-top: 20px; font-weight: bolder; font-size: 20px;">
                            <strong>Contact Us</strong>
                        </p>
                        <p>
                            <i class="fab fa-telegram-plane"></i>
                            <a href="https://t.me/+EA7YZi2ogEM3ZjE0" target="_blank"
                                style="text-decoration: none; color: inherit;" title="Contact us on Telegram via phone">
                                Telegram 
                            </a>
                        </p>

                        <p><i class="fas fa-envelope"></i> info@winboardgame.com</p>

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

            logoUrl3: '/assets/images/contact.jpg', // Path to your logo

            fullName: '',
            contactNumber: '',
            email: '',
            message: '',

        };
    },
    name: "contact",
    components: {
        Footer,
        Nav,

    },
    methods: {
        async sendMessage() {
            try {
                const response = await axios.post('/send-contact-email', {
                    full_name: this.fullName,
                    contact_number: this.contactNumber,
                    email: this.email,
                    message: this.message,
                });
                alert('Message sent successfully!');
                this.resetForm();
            } catch (error) {
                console.error('There was an error sending the email:', error);
            }
        },
        resetForm() {
            this.fullName = '';
            this.contactNumber = '';
            this.email = '';
            this.message = '';
        }
    }
};
</script>


<style>
.contact-right {
    position: relative;
    /* Ensures proper positioning for overlay */
    color: white;
    /* Make text stand out on a dark background */
    padding: 20px;
    overflow: hidden;
    /* Ensures the image doesn't overflow the container */
    border-radius: 10px;
    /* Optional: Rounded corners */
}

.contact-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* Ensures the image covers the entire container */
    filter: brightness(40%);
    /* Reduce brightness to darken the image */
    z-index: 0;
    /* Sends the image behind the text */
}

.contact-content {
    position: relative;
    z-index: 1;
    /* Brings the text in front of the image */
}


body {
    font-family: Arial, sans-serif;
    background-color: white;
    /* margin: 0; */
    /* padding: 20px; */
}

.contact-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.contact-left {
    padding: 30px;
}

.contact-right {
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 30px;
}

.contact-right h4 {
    font-size: 24px;
    margin-bottom: 10px;
}

.contact-right p {
    font-size: 14px;
}

.contact-right a {
    color: white;
    text-decoration: none;
}

.form-control {
    border-radius: 25px;
    padding: 10px 20px;
}

.btn-primary {
    background-color: rgb(96, 200, 242);
    border: none;
    border-radius: 25px;
    padding: 10px 20px;
}

.btn-primary:hover {
    background-color: rgb(96, 200, 242);
}

.map-footer {
    text-align: center;
    margin-top: 20px;
}
</style>