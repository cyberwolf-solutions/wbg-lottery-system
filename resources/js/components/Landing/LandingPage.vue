<template>
  <div class="landing-page container-fluid px-0">
    <div class="container-fluid mt-2 mb-3">
      <div class="row mx-5">
        <div class="py-5 col-12 col-md-6 align-content-center ">
          <h1 class="hero-title text-center text-md-start">Take the chance to <br> change your life</h1>
          <p class="hero-subtitle text-center text-md-start">Sorteo is an online lottery platform inspired by a few
            sorteo <br> lover's fantasy
            of
            the
            ultimate lottery platform.</p>
          <button class="custom-button">BUY TICKET NOW!</button>
        </div>
        <div class="hero-graphics d-flex justify-content-center mt-4 col-12 col-md-6">
          <img :src="image1" alt="Lottery Graphics" class="img-fluid" style="height: 400px; object-fit: cover;">

        </div>
      </div>

    </div>


    <div class="container-fluid mt-5">
      <div class="row align-items-center" style="background:#eef4f7;">
        <div class="col-12 col-md-4 px-md-5 mt-3">
          <h4 class="mb-4 fw-bold text-dark text-center text-md-start mt-3">Buy Lottery Tickets Online</h4>
          <p class="mb-4 text-center text-md-start">
            Buy lottery tickets online to the biggest lotteries <br>
            in the world offering huge jackpot prizes that you<br>
            can win when you play online lottery.
          </p>
        </div>

        <div class="col-12 col-md-4 mt-3">
          <div class="countdown-timer d-flex justify-content-center align-items-center gap-3">
            <div class="time-box text-center">
              <span class="time-number">10</span>
              <span class="time-label">days</span>
            </div>
            <div class="time-box text-center">
              <span class="time-number">44</span>
              <span class="time-label">hrs</span>
            </div>
            <div class="time-box text-center">
              <span class="time-number">15</span>
              <span class="time-label">mins</span>
            </div>
            <div class="time-box text-center">
              <span class="time-number">53</span>
              <span class="time-label">sec</span>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-3 mt-3">
          <div class="mt-4 align-content-center text-center">
            <a class="nav-link fw-bold text-secondary" href="#">Home</a>
            <a class="nav-link mt-2 text-secondary text-decoration-underline" href="#">View All Offers</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Countdown Section -->
    <section class="jackpot" id="jackpot">
      <div class="container-fluid">
        <div class="row">
          <div class="team-boxed">
            <div class="container ">
              <div class="intro mt-4">
                <h2 class="text-center">Lottery Jackpots </h2>
                <p class="text-center">Choose from the Powerball , Mega , Lotto or lucky Day <br> Lotto and
                  try for a chance to win a big cash prize.</p>
              </div>
              <div class="row people align-items-center justify-content-center">

                <div class="card-wrapper item col-md-4 col-lg-3" style="margin-left: 10px;" onclick="selectCard(this)">
                  <div class="card-box text-center py-4">
                    <img class="card-logo rounded-circle mb-3" :src="image2" style="height: 80px;" alt="Logo">
                    <h3 class="card-prize text-danger mb-2">€161,557,581</h3>
                    <p class="card-title title mb-2">US Powerball</p>
                    <p class="card-description text-muted mb-4">Next Draw: 3 days 4:21:11</p>
                  </div>
                  <button class="card-button rounded-pill w-50 mx-auto d-block">Play Now</button>
                </div>

                <div class="card-wrapper item col-md-4 col-lg-3" style="margin-left: 20px;" onclick="selectCard(this)">
                  <div class="card-box text-center py-4">
                    <img class="card-logo rounded-circle mb-3" :src="image3" style="height: 80px;" alt="Logo">
                    <h3 class="card-prize text-danger mb-2">€161,557,581</h3>
                    <p class="card-title title mb-2">US Powerball</p>
                    <p class="card-description text-muted mb-4">Next Draw: 3 days 4:21:11</p>
                  </div>
                  <button class="card-button rounded-pill w-50 mx-auto d-block">Play Now</button>
                </div>

                <div class="card-wrapper item col-md-4 col-lg-3" style="margin-left: 20px;" onclick="selectCard(this)">
                  <div class="card-box text-center py-4">
                    <img class="card-logo rounded-circle mb-3" :src="image4" style="height: 80px;" alt="Logo">
                    <h3 class="card-prize text-danger mb-2">€161,557,581</h3>
                    <p class="card-title title mb-2">US Powerball</p>
                    <p class="card-description text-muted mb-4">Next Draw: 3 days 4:21:11</p>
                  </div>
                  <button class="card-button rounded-pill w-50 mx-auto d-block">Play Now</button>
                </div>

              </div>
              <p class=" text-secondary text-center text-decoration-underline">See All Lotteries</p>
            </div>
          </div>

        </div>


        <section class="Results" id="Results">
          <div class=" container-fluid">
            <div class=" row align-content-center justify-content-center">
              <div class="col-12 col-md-12">
                <div class="intro mt-5">
                  <h2 class="text-center fw-bold">Latest Lotttery Results </h2>
                  <p class="text-center text-secondary">Check your lotto results online, find all the lotto winning
                    numbers
                    <br> and see if you won the latest lotto jackpots!
                  </p>
                </div>
              </div>


              <div class="row  justify-content-center">
                <div class="col-md-6 mt-4 d-flex">
                  <div class="card shadow flex-fill">
                    <div class="card-header bg-white">
                      <h5 class="fw-bold">Lottery Winning Numbers</h5>
                    </div>
                    <div class="card-body">
                      <table class="table align-middle" style="border-style: none;">
                        <thead>
                          <tr>
                            <th>Lottery</th>
                            <th>Draw Date</th>
                            <th>Winning Numbers</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr style="background-color: #EEEEEE;">
                            <td>
                              <i class="bi bi-heart-fill text-danger me-2"></i> Cancer Charity
                            </td>
                            <td>30/05/2018</td>
                            <td>
                              <span class="badge badgecol2  border">19</span>
                              <span class="badge badgecol2 border">31</span>
                              <span class="badge badgecol2 border">21</span>
                              <span class="badge badgecol1 border">69</span>
                              <span class="badge badgecol1 border">77</span>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <i class="bi bi-flag-fill text-primary me-2"></i> US Powerball
                            </td>
                            <td>30/05/2018</td>
                            <td>
                              <span class="badge badgecol1  border">19</span>
                              <span class="badge badgecol3 border">31</span>
                              <span class="badge badgecol1 border">21</span>
                              <span class="badge badgecol3 border">69</span>
                              <span class="badge badgecol3 border">77</span>
                            </td>
                          </tr>
                          <tr style="background-color: #EEEEEE;">
                            <td>
                              <i class="bi bi-flag-fill text-primary me-2"></i> US Powerball
                            </td>
                            <td>30/05/2018</td>
                            <td>
                              <span class="badge badgecol1  border">19</span>
                              <span class="badge badgecol1 border">31</span>
                              <span class="badge badgecol2 border">21</span>
                              <span class="badge badgecol1 border">69</span>
                              <span class="badge badgecol2 border">77</span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- Our Winners -->
                <div class="col-md-4 mt-4 d-flex">
                  <div class="card shadow flex-fill">
                    <div class="card-header bg-white">
                      <h5 class="fw-bold">Our Winners</h5>
                    </div>
                    <div class="card-body">
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                          style="background-color: #EEEEEE;">
                          <div>
                            <i class="bi bi-flag-fill text-primary me-2"></i> Viola Pitman
                            <small class="d-block text-muted">Cancer Charity, 30/05/2018</small>
                          </div>
                          <span class="text-success fw-bold">€500.00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <div>
                            <i class="bi bi-flag-fill text-primary me-2"></i> Clay Colon
                            <small class="d-block text-muted">Powerball, 30/05/2018</small>
                          </div>
                          <span class="text-success fw-bold">€3400.00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                          style="background-color: #EEEEEE;">
                          <div>
                            <i class="bi bi-flag-fill text-primary me-2"></i> Inez Newton
                            <small class="d-block text-muted">Powerball, 30/05/2018</small>
                          </div>
                          <span class="text-success fw-bold">€130.00</span>
                        </li>

                      </ul>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="text-center mt-5 mb-5">
              <a href="#" class="text-primary text-decoration-none text-decoration-underline">See All Results</a>
            </div>
          </div>

        </section>

      </div>
    </section>



    <section class="whychose " id="WhyChoose">
      <div class="container-fluid mt-5">
        <div class="row align-content-center justify-content-center">
          <div class="col-12">
            <div class="intro mt-4 mb-3">
              <h2 class="text-center fw-bold">Why Choose Us?</h2>
              <p class="text-center text-secondary">
                Sorteo makes playing the world's largest lotteries easy and fun.
              </p>
            </div>
          </div>
        </div>
        <div class="row align-items-center justify-content-end mt-4 ">
          <!-- Left Column -->
          <div class="col-md-6 mx-5">
            <div class="row g-3">
              <!-- Feature Box -->
              <div class="col-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-trophy text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Biggest lottery jackpots</p>
                </div>
              </div>
              <div class="col-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-coin text-primary fs-2"></i>
                  <p class="fw-bold mt-2">No commission on Winnings</p>
                </div>
              </div>
              <div class="col-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3" style=" background-color: #63b5f6;">

                  <p class="text-light">
                    One of the core advantages of playing an online lotto is that it is
                    both safe and secure. So, there is no need for you to go, queue, or
                    visit a retail store, to get tickets.
                  </p>
                </div>
              </div>
              <div class="col-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-cash-stack text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Instant payout system</p>
                </div>
              </div>
              <div class="col-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-star text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Performance Bonuses</p>
                </div>
              </div>
              <div class="col-4 " style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-headset text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Dedicated Support</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4 text-center my-1">

            <img :src="image5" alt="Lottery" class="img-fluid" />
          </div>
        </div>
      </div>
    </section>




    <section class="buy" id="buy" style="background-color: #EEEEEE;">
      <div class="container-fluid ">
        <div class="row align-content-center justify-content-center">
          <div class="col-12 mt-3">
            <div class="intro mt-5">
              <h2 class="text-center fw-bold">Buy Lottery Tickets Online.</h2>
              <p class="text-center text-secondary mt-3">
                Play the lottery online safely and securely at the Lotter , the leading lottery <br>
                ticket purchasing service in the world.
              </p>
            </div>
          </div>
        </div>

        <div class="row justify-content-center mt-4">
          <div class="col-12 col-md-10  rounded">
            <div class="table-responsive rounded">
              <table class="table table-bordered text-center align-middle bg-white shadow rounded">
                <thead class="bg-light  align-middle" style="height: 70px;">
                  <tr>
                    <th>Lottery</th>
                    <th>Jackpot</th>
                    <th>Price</th>
                    <th>Time to Draw</th>
                    <th>Sold</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="background-color: #EEEEEE;">
                    <td>
                      <img src="https://via.placeholder.com/30x20" alt="Flag" class="me-2">
                      Cancer Charity
                    </td>
                    <td>€53,000,000</td>
                    <td>€3.9</td>
                    <td>
                      <div class="row">

                        <div class="col-3" >
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border" >19</span></div>
                            <div class="col-12">days</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">hrs</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">mins</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">secs</div>
                          </div>
                        </div>
                        
                      </div>
                    </td>
                    <td>
                      <div class="progress-bar">
                        <div class="progress-fill" style="width: 50%;">
                          <span class="progress-text">50%</span>
                        </div>
                      </div>
                    </td>
                    <td><button class="btn btn-primary btn-sm">Buy Tickets</button></td>
                  </tr>
                  <tr>
                    <td>
                      <img src="https://via.placeholder.com/30x20" alt="Flag" class="me-2">
                      US Powerball
                    </td>
                    <td>€13,000,000</td>
                    <td>€3.9</td>
                    <td>
                      <div class="row">

                        <div class="col-3" >
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border" >19</span></div>
                            <div class="col-12">days</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">hrs</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">mins</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">secs</div>
                          </div>
                        </div>
                        
                      </div>
                    </td>
                    <td>
                      <div class="progress-bar">
                        <div class="progress-fill" style="width: 50%;">
                          <span class="progress-text">50%</span>
                        </div>
                      </div>
                    </td>
                    <td><button class="btn btn-primary btn-sm">Buy Tickets</button></td>
                  </tr>

                  <tr style="background-color: #EEEEEE;">
                    <td>
                      <img src="https://via.placeholder.com/30x20" alt="Flag" class="me-2">
                      Cancer Charity
                    </td>
                    <td>€53,000,000</td>
                    <td>€3.9</td>
                    <td>
                      <div class="row">

                        <div class="col-3" >
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border" >19</span></div>
                            <div class="col-12">days</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">hrs</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">mins</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">secs</div>
                          </div>
                        </div>
                        
                      </div>
                    </td>
                    <td>
                      <div class="progress-bar">
                        <div class="progress-fill" style="width: 50%;">
                          <span class="progress-text">50%</span>
                        </div>
                      </div>
                    </td>
                    <td><button class="btn btn-primary btn-sm">Buy Tickets</button></td>
                  </tr>
                  <tr>
                    <td>
                      <img src="https://via.placeholder.com/30x20" alt="Flag" class="me-2">
                      US Powerball
                    </td>
                    <td>€13,000,000</td>
                    <td>€3.9</td>
                    <td>
                      <div class="row">

                        <div class="col-3" >
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border" >19</span></div>
                            <div class="col-12">days</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">hrs</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">mins</div>
                          </div>
                        </div>

                        <div class="col-3">
                          <div class="row">
                            <div class="col-12"> <span class="badge badgecol2  border">19</span></div>
                            <div class="col-12">secs</div>
                          </div>
                        </div>
                        
                      </div>
                    </td>
                    <td>
                      <div class="progress-bar">
                        <div class="progress-fill" style="width: 50%;">
                          <span class="progress-text">50%</span>
                        </div>
                      </div>
                    </td>
                    <td><button class="btn btn-primary btn-sm">Buy Tickets</button></td>
                  </tr>
                 
                </tbody>
              </table>
            </div>
            <div class="text-center mt-5 mb-5">
              <a href="#" class="btn btn-link text-primary">View All Lotteries</a>
            </div>
          </div>
        </div>


      </div>
    </section>

    <section class="contact py-5" id="contact">
      <div class="container text-center">
        <div class="row">
          <div class="col-lg-4 mx-auto mt-4">
            <h4 class="mb-3">We answer all of your questions</h4>
            <h2 class="mb-4 fw-bold">CONTACT US</h2>
            <p class="mb-5">
              If you have any questions or queries our helpful support team will be more than happy to assist.
            </p>
            <form>
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Full Name" />
              </div>
              <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email Address" />
              </div>
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Phone No" />
              </div>
              <div class="mb-3">
                <textarea class="form-control" rows="4" placeholder="Message"></textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100">SEND MESSAGE</button>
            </form>
          </div>

          <div class="col-md-4 mt-4">
            <img :src="image6" style="height: auto; max-width: 100%;" alt="Lottery" class="img-fluid" />
          </div>
        </div>

        <div class="row mt-5">
          <div class="d-flex justify-content-center gap-4 flex-wrap">
            <img src="#" alt="EnvatoMarket" class="icon" style="width: 50px; height: auto;" />
            <img src="#" alt="CodeCanyon" class="icon" style="width: 50px; height: auto;" />
            <img src="#" alt="GraphicRiver" class="icon" style="width: 50px; height: auto;" />
            <img src="#" alt="3DOcean" class="icon" style="width: 50px; height: auto;" />
            <img src="#" alt="AudioJungle" class="icon" style="width: 50px; height: auto;" />
          </div>
        </div>
      </div>
    </section>




  </div>
</template>


<script>
export default {
  data() {
    return {
      image1: '/assets/images/image1.png',
      image2: '/assets/images/1.png',
      image3: '/assets/images/2.png',
      image4: '/assets/images/3.png',
      image5: '/assets/images/why.png',
      image6: '/assets/images/contact.png',
    };
  },
};


</script>



<style scoped>
/* Base styles for card */
.card-wrapper {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
  /* Default black shadow */
  transition: box-shadow 0.3s ease, transform 0.3s ease;
  cursor: pointer;
}

.card-wrapper:hover {
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
  /* Slightly larger black shadow on hover */
  transform: scale(1.03);
}

/* Highlighted/Selected card */
.card-wrapper.selected-card {
  box-shadow: 0px 4px 15px rgba(60, 149, 244, 0.5);
  /* Blue glow shadow */
  transform: scale(1.05);
  /* Slight zoom effect */
}

/* Card content styles */
.card-box {
  padding: 20px;
}

.card-logo {
  height: 80px;
  width: 80px;
}

.card-prize {
  font-size: 1.5rem;
  font-weight: bold;
}

.card-title {
  font-size: 1rem;
  color: #333;
}

.card-description {
  font-size: 0.875rem;
  color: #6c757d;
}

/* Button styles */
.card-button {
  font-size: 1rem;
  padding: 10px 20px;
  font-weight: bold;
  text-transform: uppercase;
  background-color: #63b5f6;
  border: none;
  color: white;
  transition: background-color 0.3s ease;
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);

}

.card-button:hover {
  background-color: #3d729e;
}


.item {
  position: relative;
  padding-bottom: 60px;
  /* Adds space for the button */
}

.item .box {
  text-align: center;
}

.item .play-now-btn {
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: bold;
}

.item img {
  height: 80px;
  width: 80px;
  object-fit: cover;
}

.item .name {
  font-size: 1.5rem;
  font-weight: bold;
}

.item .title {
  font-size: 1.2rem;
  color: #333;
}

.item .description {
  font-size: 0.9rem;
  color: #6c757d;
}

/* .item:hover {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease;
} */


.contact {
  background: #f9f9f9;
}

.contact h2 {
  font-size: 1.5rem;
  color: #555;
}

.contact h1 {
  font-size: 2.5rem;
  font-weight: bold;
  color: #333;
}

.contact p {
  color: #777;
  font-size: 1rem;
}

.contact form .form-control {
  border-radius: 0.5rem;
  border: 1px solid #ddd;
  padding: 0.8rem 1rem;
  font-size: 1rem;
}

.contact button {
  background: #63b5f6;
  border: none;
  border-radius: 0.5rem;
  padding: 0.8rem;
  color: white;
  font-weight: bold;
  font-size: 1rem;
  transition: background 0.3s ease;
}

.contact button:hover {
  background: #0056b3;
}

.contact .icon {
  height: 50px;
  object-fit: contain;
  filter: grayscale(100%);
  transition: filter 0.3s ease;
}

.contact .icon:hover {
  filter: grayscale(0);
}




.row.g-3 {
  display: flex;
  flex-wrap: wrap;
}

.row.g-3>.col-4 {
  display: flex;
}

.feature-box {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;

}

.feature-box p {
  margin-top: auto;
}



.whychose {
  background-color: #f9f9f9;
  padding: 40px 0;
}

.feature-box {
  transition: all 0.3s ease-in-out;
}

.feature-box:hover {
  background-color: #f1f8ff;
  border-color: #63b5f6;
  transform: translateY(-5px);
}

.info-box {
  font-size: 14px;
  line-height: 1.6;
}




.card {
  border-radius: 8px;
}

.badge {
  display: inline-flex;
  /* Centers content */
  justify-content: center;
  align-items: center;
  width: 40px;
  /* Fixed width for circle */
  height: 40px;
  /* Fixed height for circle */
  border-radius: 50%;
  /* Makes it a circle */
  font-size: 0.85rem;
  font-weight: bold;
  text-align: center;
  line-height: 1;
  /* Aligns number vertically */
  padding: 0;
  /* Removes extra padding */
  border: 2px solid #ccc;
  /* Default border color */
}

/* Dynamic colors */
.badgecol1 {
  background-color: #63b5f6;
  /* Light gray */
  color: #ececec;
  /* Dark text */
  margin-left: 10px;
}

.badgecol2 {
  background-color: #EEEEEE;
  /* Light gray */
  color: #5d5d5d;
  /* Dark text */
  border-style: solid;
  border-color: #525252;
  margin-left: 10px;
}

.badgecol3 {
  background-color: white;
  /* Light gray */
  color: #5d5d5d;
  /* Dark text */
  border-style: solid;
  border-color: #525252;
  margin-left: 10px;
}

.middle-button {
  position: absolute;
  bottom: -15px;
  /* Adjust to position the button */
  left: 50%;
  transform: translateX(-50%);
}


/*********************** General Styles ***********************/
.landing-page {
  font-family: 'Arial', sans-serif;
  color: #333;
}

/*********************** Hero Section ***********************/
.hero {
  background: #f9f9f9;
  padding: 5rem 0;
}

.hero-title {
  font-size: 2.5rem;
  font-weight: bold;
  color: #000;
}

.hero-subtitle {
  font-size: 1.2rem;
  color: #555;
  margin: 1rem 0 2rem;
}

.hero-graphics img {
  max-width: 80%;
  height: auto;
}

/*********************** Countdown Section ***********************/
.countdown-section {
  background: #f1f7ff;
  color: #333;
  text-align: center;
}

.time-box {
  display: inline-block;
  padding: 1rem;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  width: 80px;
}

.time-number {
  font-size: 1.5rem;
  font-weight: bold;
  color: #63b5f6;
}

.time-label {
  display: block;
  font-size: 0.85rem;
  color: #777;
}

.btn-primary {
  background-color: #63b5f6;
  border: none;
  color: #fff;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  border-radius: 5px;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-secondary {
  background-color: #6c757d;
  border: none;
  color: #fff;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  border-radius: 5px;
}

.btn-outline-primary {
  border: 1px solid #63b5f6;
  color: #007bff;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  border-radius: 5px;
  background: none;
}

.btn-outline-primary:hover {
  background-color: #007bff;
  color: #fff;
}

.team-boxed {
  color: #313437;
  background-color: #fff;
}

.team-boxed p {
  color: #7d8285;
}

.team-boxed h2 {
  font-weight: bold;
  margin-bottom: 40px;
  padding-top: 40px;
  color: inherit;
}

@media (max-width:767px) {
  .team-boxed h2 {
    margin-bottom: 25px;
    padding-top: 25px;
    font-size: 24px;
  }
}

.team-boxed .intro {
  font-size: 16px;
  max-width: 500px;
  margin: 0 auto;
}

.team-boxed .intro p {
  margin-bottom: 0;
}

.team-boxed .people {
  padding: 50px 0;
}

.team-boxed .item {
  text-align: center;
}

.team-boxed .item .box {
  text-align: center;
  padding: 30px;
  background-color: #fff;
  margin-bottom: 30px;
}

.team-boxed .item .name {
  font-weight: bold;
  margin-top: 28px;
  margin-bottom: 8px;
  color: inherit;
}

.team-boxed .item .title {
  text-transform: uppercase;
  font-weight: bold;
  color: #d0d0d0;
  letter-spacing: 2px;
  font-size: 13px;
}

.team-boxed .item .description {
  font-size: 15px;
  margin-top: 15px;
  margin-bottom: 20px;
}

.team-boxed .item img {
  max-width: 160px;
}

.team-boxed .social {
  font-size: 18px;
  color: #a2a8ae;
}

.team-boxed .social a {
  color: inherit;
  margin: 0 10px;
  display: inline-block;
  opacity: 0.7;
}

.team-boxed .social a:hover {
  opacity: 1;
}
</style>