<template>
    <footer class="footer" style="margin-top: 80px;">
      <div class="" style="background-color: transparent;border-style: none;padding-left: 150px;padding-right: 150px;">
        <div class="row text-center text-md-start">
          <div class="col-md-4">
            <h5 style="font-weight: bold;">About Sorteo</h5>
            <ul class="list-unstyled">
              <li><a href="#">About us</a></li>
              <li><a href="#">How it works</a></li>
              <li><a href="#">Our services</a></li>
              <li><a href="#">Blog</a></li>
              <li><a href="#">Contact us</a></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5 style="font-weight: bold;">Quick Links</h5>
            <ul class="list-unstyled" style="color: gray;">
              <li><a href="#">My Account</a></li>
              <li><a href="#">Affiliate Program</a></li>
              <li><a href="#">Terms and Conditions</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Sorteo Licenses</a></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5 style="font-weight: bold;">Email Newsletters</h5>
            <p style="color: gray;font-size: 12px;">Subscribe now and receive weekly newsletter for the latest draw and offer news and much more!</p>
            <form class="d-flex">
              <input type="email" class="form-control me-2" placeholder="Email address" 
              style="border-radius: 30px;border-color: gainsboro;" required>
              <button type="submit"  style="border-radius: 30px;background-color: rgb(96, 200, 242);border-style: none;" class="btn btn-primary">Subscribe</button>
            </form>
          </div>
        </div>
        <div class="row mt-4 text-left" >
          <div class="col-md-6" style="margin-top: 30px;">
            <p style="font-size: 12px;">© 2025 Sorteo - All Rights Reserved.</p>
          </div>
          <div class="col-md-6 text-center text-md-end">
            <div class="social-icons">
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-google"></i></a>
              <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'Footer',
  };
  </script>
  
  <style scoped>
  .footer {
    background-color: #f8f9fa;
    padding: 40px 0;
  }
  .footer h5 {
    font-size: 16px;
    margin-bottom: 20px;
  }
  .footer a {
    color: #6c757d;
    text-decoration: none;
  }
  .footer a:hover {
    text-decoration: underline;
  }
  .footer .social-icons a {
    margin-right: 10px;
    font-size: 20px;
    color: #6c757d;
  }
  .footer .social-icons a:hover {
    color: #007bff;
  }
  </style>
  