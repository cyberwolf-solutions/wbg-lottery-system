<template v-if="!$page.props.auth.user">
    <div class="container-fluid px-0">
        <!-- Top Header -->
        <div class="top-header bg-light py-2 mb-1">
            <div class="container d-flex justify-content-between align-items-center">
                <div class=" mx-5">
                    <a href="#" class="text-decoration-none text-secondary me-3">
                        <i class="fas fa-globe me-1"></i> EN
                    </a>
                    <span class="text-secondary">|</span>
                    <a href="#" class="text-decoration-none text-secondary ms-3"><i
                            class="fas fa-headset me-1"></i>Support</a>
                </div>
                <div class="mx-5">
                    <a href="mailto:example@gmai.com" class="text-decoration-none text-secondary">
                        <i class="bi bi-envelope"></i> example@gmail.com
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img :src="logoUrl" alt="Logo" height="80" width="100" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-2  ">
                            <a class="nav-link " href="#">Home</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="#jackpot">Jackpot</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="#Results">Results</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="#WhyChoose">Why Choose Us</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="#buy">Lotteries</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>

                        <!-- Join Us & Log In Buttons -->
                        <li class="nav-item mx-5">
                            <template v-if="!$page.props.auth.user">
                                <button
                                    class="btn btn-primary text-white px-4 rounded-pill shadow"
                                    data-bs-toggle="modal"
                                    data-bs-target="#registerModal"
                                >
                                    Join Us
                                </button>
                            </template>
                            <template v-else>
                                <a class="btn btn-primary text-white px-4 rounded-pill shadow"
                                    :href="route('login')">Log in</a>
                            </template>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="registerModalLabel">Join Us</h5> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <RegisterForm />
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <!-- <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <LoginForm />
                </div>
            </div>
        </div>
    </div> -->
    
</template>


<script>

import { Link } from '@inertiajs/vue3';
import RegisterForm from '@/Pages/Auth/Register.vue';
import LoginForm from '@/Pages/Auth/Login.vue';

export default {
    data() {
        return {
            logoUrl: '/assets/images/logo.png', // Path to your logo
        };
    },
    name: "Nav",
    components: {
        RegisterForm,
        LoginForm
  },
};
</script>

<style>
/* Top Header Styling */

.hover-blue:hover {
    color: blue;
    /* Change this to your desired shade of blue */
}



.top-header {
    font-size: 14px;
}

.top-header a {
    font-weight: 500;
}

.top-header i {
    margin-right: 5px;
}

/* Navbar Styling */
.navbar {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar a.nav-link {
    font-weight: 500;
    color: #555;
}

.navbar a.nav-link:hover {
    color: #007bff;
}

.navbar .btn-primary {
    background-color: #007bff;
    border: none;
    font-weight: 500;
}

.navbar .btn-primary:hover {
    background-color: #0056b3;
}
</style>