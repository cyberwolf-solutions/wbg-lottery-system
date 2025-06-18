<template v-if="!$page.props.auth.user">
    <div class="container-fluid px-0">
        <!-- Top Header -->
        <div class="top-header bg-light py-2 mb-1">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left Section: Language and Support -->
                    <div class="col-md-6 col-12 text-center text-md-start mb-2 mb-md-0">
                        <i class="fas fa-globe me-2"></i>
                        <select class="form-select form-select-sm text-secondary me-2 d-inline-block"
                            style="border: none; width: auto;" aria-label="Language select">
                            <option>EN</option>
                            <option value="si">සිංහල (Sinhala)</option>
                        </select>

                        <span class="text-secondary mx-2 d-none d-md-inline">|</span>

                        <a href="#" class="text-decoration-none text-secondary d-inline-block">
                            <i class="fas fa-headset me-1"></i> Support
                        </a>
                    </div>

                    <!-- Right Section: Email -->
                    <div class="col-md-6 col-12 text-center text-md-end">
                        <a href="mailto:info@winboardgame.com" class="text-decoration-none text-secondary">
                            <i class="bi bi-envelope"></i> info@winboardgame.com
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img :src="logoUrl" alt="Logo" height="80" width="100" />
                </a>


                <template v-if="!$page.props.auth.user">
                    <button class="btn btn-primary text-white px-4 rounded-pill shadow d-lg-none me-2"
                        data-bs-toggle="modal" data-bs-target="#registerModal">
                        Join Us
                    </button>
                </template>
                <template v-else>
                    <a class="btn btn-primary text-white px-4 rounded-pill shadow d-lg-none me-2"
                        :href="route('login')">Log in</a>
                </template>

                <button class="navbar-toggler" type="button" @click="toggleSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-2">
                            <a :href="route('landing')" :active="route().current('landing')" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a :href="route('hiw.index')" :active="route().current('hiw.index')" class="nav-link">How it
                                works</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a :href="route('latest.index')" :active="route().current('latest.index')"

                                class="nav-link">Latest
                                results</a>

                        </li>
                        <li class="nav-item mx-2">
                            <a :href="route('landinglottery.index')" :active="route().current('landinglottery.index')"
                                class="nav-link">Lotteries</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a :href="route('winner.index')" :active="route().current('winner.index')"
                                class="nav-link">Winners</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a :href="route('contact.index')" :active="route().current('contact.index')"
                                class="nav-link">Contact</a>
                        </li>
                        <li class="nav-item mx-5">
                            <template v-if="!$page.props.auth.user">
                                <button class="btn btn-primary text-white px-4 rounded-pill shadow"
                                    data-bs-toggle="modal" data-bs-target="#registerModal">
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

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar" :class="{ 'show': sidebarOpen }">
            <div class="sidebar-header">
                <button class="close-btn" @click="toggleSidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ul class="sidebar-nav">
                <li>
                    <a :href="route('landing')" :active="route().current('landing')" class="nav-link">Home</a>
                </li>
                <li>
                    <a :href="route('hiw.index')" :active="route().current('hiw.index')" class="nav-link">How it
                        works</a>
                </li>
                <li>
                    <a :href="route('latest.index')" :active="route().current('latest.index')" class="nav-link">Latest
                        results</a>
                </li>
                <li>
                    <a :href="route('landinglottery.index')" :active="route().current('landinglottery.index')"
                        class="nav-link">Lotteries</a>
                </li>
                <li>
                    <a :href="route('winner.index')" :active="route().current('winner.index')"
                        class="nav-link">Winners</a>
                </li>
                <li>
                    <a :href="route('contact.index')" :active="route().current('contact.index')"
                        class="nav-link">Contact</a>
                </li>
                <li class="mt-3">
                    <template v-if="!$page.props.auth.user">
                        <button class="btn btn-primary text-white px-4 rounded-pill shadow w-100" data-bs-toggle="modal"
                            data-bs-target="#registerModal" @click="toggleSidebar">
                            Join Us
                        </button>
                    </template>
                    <template v-else>
                        <a class="btn btn-primary text-white px-4 rounded-pill shadow w-100" :href="route('login')">Log
                            in</a>
                    </template>
                </li>
            </ul>
        </div>
        <div class="sidebar-overlay" :class="{ 'show': sidebarOpen }" @click="toggleSidebar"></div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <RegisterForm :referral="referral" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import RegisterForm from '@/Pages/Auth/Register.vue';
import LoginForm from '@/Pages/Auth/Login.vue';
import axios from 'axios';

export default {
    data() {
        return {
            lotteries: [],
            logoUrl: '/assets/images/logo.png',
            sidebarOpen: false
        };
    },
    props: {

        referral: String
    },

    name: "Nav",
    components: {
        RegisterForm,
        LoginForm
    },
    mounted() {
        this.fetchLotteries();
    },
    // In Nav component
    mounted() {
        if (this.referral) {
            this.$nextTick(() => {
                const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            });
        }
    },
    methods: {
        async fetchLotteries() {
            try {
                const response = await axios.get();
                this.lotteries = response.data;
            } catch (error) {
                console.error('error fetching lotteries:', error);
            }
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            if (this.sidebarOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        },
        openRegisterModal() {
            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            registerModal.show();
        }
    }
};
</script>

<style>
/* Modal Styling for Small Screens */
.modal-content {
    border-radius: 8px;
    padding: 10px;
}

/* Make modal full-width and taller on small screens */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0;
        width: 100%;
        max-width: 100%;
        height: 100vh;
        /* Full height of the viewport */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        /* Allow scrolling if content overflows */
        padding: 20px;
        border-radius: 12px;
    }

    /* Form styling inside the modal */
    .modal-body {
        padding: 20px;
    }

    /* Style the form elements */
    .modal-body form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        /* Space between form fields */
    }

    .modal-body label {
        font-size: 1.1rem;
        /* Larger font for readability */
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .modal-body input {
        font-size: 1rem;
        /* Larger font for input text */
        padding: 12px;
        /* More padding for touch targets */
        height: 48px;
        /* Taller input fields */
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
    }

    .modal-body input::placeholder {
        font-size: 1rem;
        color: #999;
    }

    /* Style the buttons */
    .modal-body button {
        font-size: 1.1rem;
        padding: 12px;
        height: 48px;
        border-radius: 6px;
        margin-top: 10px;
    }

    /* Adjust checkbox or smaller elements */
    .modal-body .form-check-label {
        font-size: 0.95rem;
        /* Slightly smaller for secondary text */
    }

    /* Close button */
    .modal-header .btn-close {
        font-size: 1.2rem;
    }
}

/* General modal styling for larger screens */
@media (min-width: 577px) {
    .modal-dialog {
        max-width: 500px;
        /* Default Bootstrap modal width */
    }

    .modal-body label {
        font-size: 1rem;
    }

    .modal-body input {
        font-size: 0.9rem;
        padding: 10px;
        /* height: 40px; */
    }

    .modal-body button {
        font-size: 1rem;
        padding: 10px;
        height: 40px;
    }
}

body {
    overflow-x: hidden;
}

html,
body {
    height: 100%;
}

/* Top Header Styling */
.hover-blue:hover {
    color: blue;
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

/* Mobile Sidebar Styles */
.mobile-sidebar {
    position: fixed;
    top: 0;
    right: -300px;
    width: 280px;
    max-height: 100vh;
    background-color: white;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1050;
    transition: right 0.3s ease;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}


.mobile-sidebar.show {
    right: 0;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}

.sidebar-header {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #555;
}

.sidebar-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav li {
    margin-bottom: 15px;
}

.sidebar-nav a {
    display: block;
    padding: 10px;
    color: #555;
    text-decoration: none;
    font-weight: 500;
    border-radius: 4px;
}

.sidebar-nav a:hover {
    color: #007bff;
    background-color: #f8f9fa;
}

/* Hide regular navbar items on mobile */
@media (max-width: 991.98px) {
    .navbar-collapse {
        display: none;
    }
}

/* Show regular navbar items on desktop */
@media (min-width: 992px) {

    .mobile-sidebar,
    .sidebar-overlay {
        display: none;
    }

    .navbar-collapse {
        display: flex !important;
    }
}
</style>