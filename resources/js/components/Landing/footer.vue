<template>
    <footer class="footer bg-light py-5 mx-3" style="background-color: #F5F5DC; overflow-x: hidden;">
        <div class="container-fluid px-0">
            <div class="row text-center text-lg-start">
                <!-- Logo and About Section -->
                <div class="col-lg-3 mb-4">
                    <img src="#" alt="Logo" class="mb-3" />
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted nav-link">About us</a></li>
                        <li><a href="#" class="text-muted nav-link">How it Works</a></li>
                        <li><a href="#" class="text-muted nav-link">Our services</a></li>
                        <li><a href="#" class="text-muted nav-link">Blog</a></li>
                        <li><a href="#" class="text-muted nav-link">Contact us</a></li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-3 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted nav-link">My Account</a></li>
                        <li><a href="#" class="text-muted nav-link">Affiliate Program</a></li>
                        <li><a href="#" class="text-muted nav-link">Terms and Conditions</a></li>
                        <li><a href="#" class="text-muted nav-link">Privacy Policy</a></li>
                        <li><a href="#" class="text-muted nav-link">Sorteo Licenses</a></li>
                    </ul>
                </div>



                <!-- Newsletter -->
                <div class="col-lg-6 mb-4">
                    <h5 class="mb-3">Email Newsletters</h5>
                    <p class="text-muted">
                        Subscribe now and receive weekly newsletters for latest <br> draw and
                        offers!
                    </p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email address" />
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>

            <div class="container-fluid text-center mt-4">
                <div class="row">
                    <div class=" col-8">
                        <p class="text-muted mb-0 text-start">
                            © 2025 Sorteo - All Rights Reserved.
                        </p>
                    </div>

                    <div class="col-4">
                        <div class="mt-2">
                            <a href="#" class="text-muted me-3"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-muted me-3"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-muted"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </footer>
</template>

<script>

export default {
    name: "Footer",
}

</script>

<style>
.footer {
    background: #f9f9f9;
}

.footer ul li a {
    font-size: 0.9rem;
    color: #6c757d;
    transition: color 0.3s ease;
}

.footer ul li a:hover {
    color: #007bff;
}

.footer .input-group .form-control {
    border-radius: 0.5rem 0 0 0.5rem;
    border: 1px solid #ddd;
    padding: 0.8rem 1rem;
}

.footer .input-group .btn {
    border-radius: 0 0.5rem 0.5rem 0;
    padding: 0.8rem;
    font-size: 0.9rem;
}

.footer .bi {
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

.footer .bi:hover {
    color: #007bff;
}
</style>