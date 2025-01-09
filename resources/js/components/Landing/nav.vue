<template>
    <div>
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
                <div class="collapse navbar-collapse" id="navbarNav">
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
                        
                        <li class="nav-item mx-5">
                            <a class="btn btn-primary text-white px-4 rounded-pill shadow" href="#">Join Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>


<script>
export default {
    data() {
        return {
            logoUrl: '/assets/images/logo.png', // Path to your logo
        };
    },
};
</script>

<style>
/* Top Header Styling */

.hover-blue:hover {
    color: blue; /* Change this to your desired shade of blue */
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