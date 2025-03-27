<template>

  <div class="landing-page container-fluid px-0">
    <Nav />
    <div class="container-fluid mt-2 mb-3">
      <div class="row mx-5">
        <div
          class="py-5 col-12 col-md-6 align-content-center d-flex flex-column justify-content-center align-items-center align-items-md-start">
          <h1 class="hero-title text-center text-md-start">Take the chance to <br> change your life</h1>
          <p class="hero-subtitle text-center text-md-start">
            Get ready to experience the thrill of winning with <b>WinBoard Game</b>, the ultimate lottery system
            where your lucky numbers can turn into big rewards!
            BOOK YOUR NUMBER NOW

          </p>
          <a :href="route('landinglottery.index')" class="nav-link">
            <button class="custom-button mt-3">BOOK YOUR NUMBER NOW!</button>
          </a>


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
            Book your lucky number online
            Pick and book your favorite first or last two digits (00–99).
            Match your numbers with Sri Lanka’s official Lottery board’s draw.
            Win <b>70 times</b> your booking price if your numbers hit!
          </p>
        </div>

        <div class="col-12 col-md-4 mt-3">
          <div class="countdown-timer d-flex justify-content-center align-items-center gap-3">
            <div class="time-box text-center">
              <span class="time-number">{{ countdown.days }}</span>
              <span class="time-label">days</span>
            </div>
            <div class="time-box text-center">
              <span class="time-number">{{ countdown.hours }}</span>
              <span class="time-label">hrs</span>
            </div>
            <div class="time-box text-center">
              <span class="time-number">{{ countdown.minutes }}</span>
              <span class="time-label">mins</span>
            </div>
            <div class="time-box text-center">
              <span class="time-number">{{ countdown.seconds }}</span>
              <span class="time-label">sec</span>
            </div>
          </div>
        </div>


        <div class="col-12 col-md-3 mt-3">
          <div class="mt-4 align-content-center text-center">
            <!-- <a class="nav-link fw-bold text-secondary" href="#">Home</a>
            <a class="nav-link mt-2 text-secondary text-decoration-underline" href="#">View All Offers</a> -->
            <a :href="route('landinglottery.index')" class="nav-link">
              <button class="custom-button mt-3">Book your lucky number</button>
            </a>
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
                <h2 class="text-center">Winboard Lottery selection </h2>
                <p class="text-center">Think you’ve got the lucky number? Here’s your chance to turn your predictions
                  into BIG
                  rewards while earning extra with referrals! Choose your lottery and winboard to book your
                  number.
                </p>
              </div>
              <div class="row people align-items-center justify-content-center">

                <div v-for="lottery in lotteries" :key="lottery.id" class="card-wrapper item col-md-4 col-lg-3"
                  style="margin-left: 10px; margin-top: 10px; margin-bottom: 30px;" onclick="selectCard(this)">
                  <div class="card-box d-flex flex-column justify-content-center align-items-center text-center py-4">
                    <img class="card-logo rounded-circle mb-3" :src="image2" style="height: 80px;" alt="Logo">
                    <h3 class="card-prize text-danger mb-2">{{ lottery.prize }}</h3>
                    <p class="card-title title mb-2">{{ lottery.name }}</p>
                    <p class="card-description text-muted mb-4">
                      Next Draw: {{ formatCountdown(lottery.draw_time) }}
                    </p>
                  </div>
                  <button class="card-button rounded-pill w-50 mx-auto d-block">Play Now</button>
                </div>



              </div>
              <a :href="route('landinglottery.index')" class="nav-link">
                <p class=" text-secondary text-center text-decoration-underline">See All Lotteries</p>
              </a>

            </div>
          </div>

        </div>


        <section class="Results" id="Results">
          <div class=" container-fluid">
            <div class=" row align-content-center justify-content-center">
              <div class="col-12 col-md-12">
                <div class="intro mt-5">
                  <h2 class="text-center fw-bold">Latest Lotttery Results </h2>
                  <p class="text-center text-secondary">Lottery results will be updated as per the official web sites of
                    lottery boards.<br />find the latest
                    winning numbers and see if you won the particular winboard prize.
                    <br>
                  </p>
                </div>
              </div>


              <div class="row  justify-content-center">
                <div class="col-md-6 mt-4 d-flex">
                  <div class="card shadow flex-fill">
                    <div class="card-header bg-white">
                      <h5 class="fw-bold">This area will update as per new draw</h5>
                    </div>
                    <div class="card-body">
                      <div style="overflow-x: auto; white-space: nowrap;">
                        <table class="table align-middle" style="border-style: none;">
                          <thead>
                            <tr>
                              <th>Lottery</th>
                              <th>Draw Date</th>
                              <th style="text-align: center;">Winning Numbers</th> <!-- Centered -->
                            </tr>
                          </thead>
                          <tbody>

                            <!-- Iterate over each lottery result (winning_numbers) -->
                            <tr v-for="(winningResult, index) in winning_numbers" :key="index"
                              style="background-color: #EEEEEE;">
                              <td>
                                <i class="bi bi-heart-fill text-danger me-2"></i>{{ winningResult.lottery_name }}
                              </td>
                              <td>{{ winningResult.date }}</td>
                              <td colspan="3" style="text-align: center;">
                                <div v-for="(numbers, drawNumber) in winningResult.winning_numbers" :key="drawNumber">
                                  <span>{{ Object.values(numbers).join(' ') }}</span>
                                </div>
                              </td>
                            </tr>

                            <!-- Fallback if no winning numbers are available -->
                            <tr v-if="winning_numbers.length === 0">
                              <td colspan="3">No Winning Numbers Available</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>

                  </div>
                </div>

                <!-- Our Winners -->
                <div class="col-md-4 mt-4 d-flex">
                  <div class="card shadow flex-fill">
                    <div class="card-header bg-white">
                      <h5 class="fw-bold">Winners details and amounts</h5>
                    </div>
                    <div class="card-body">
                      <ul class="list-group list-group-flush">
                        <!-- Iterate through the winners passed from the controller -->
                        <li v-for="(winner, index) in winners" :key="index"
                          class="list-group-item d-flex justify-content-between align-items-center"
                          :style="index % 2 === 0 ? { backgroundColor: '#EEEEEE' } : {}">
                          <div>
                            <i class="bi bi-flag-fill text-primary me-2"></i> {{ winner.name }}
                            <small class="d-block text-muted">{{ winner.lottery }}</small>
                          </div>
                          <span class="text-success fw-bold">USDT {{ winner.amount }}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

              </div>

            </div>
            <div class="text-center mt-5 mb-5">
              <!-- <button class="custom-button mt-3">All results</button> -->
              <a :href="route('latest.index')" class="nav-link">
                <button class="custom-button mt-3" style="margin-left: 10px;">Latest results</button>
              </a>

              <!-- <a href="#" class="text-primary text-decoration-none text-decoration-underline">See All Results</a> -->
            </div>
          </div>

        </section>

      </div>
    </section>



    <section class="whychose" id="WhyChoose">
      <div class="container-fluid mt-5 px-3 px-md-5">
        <div class="row align-content-center justify-content-center">
          <div class="col-12">
            <div class="intro mt-4 mb-3">
              <h2 class="text-center fw-bold">Why Choose WinBoard Game?</h2>
              <p class="text-center text-secondary">

                Reliable, secure, exciting odds, fast payouts, and top-notch customer service.

              </p>
            </div>
          </div>
        </div>
        <div class="row align-items-center justify-content-end mt-4">
          <!-- Left Column -->
          <div class="col-md-6">
            <div class="row g-3">
              <!-- Feature Boxes -->
              <div class="col-6 col-md-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-trophy text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Biggest Rewards</p>
                </div>
              </div>
              <div class="col-6 col-md-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-coin text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Fair & Transparent</p>
                </div>
              </div>
              <div class="col-6 col-md-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-bar-chart text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Clear & Balanced</p>
                </div>
              </div>
              <div class="col-6 col-md-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-cash-stack text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Secure & Reliable wallet system</p>
                </div>
              </div>
              <div class="col-6 col-md-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-star text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Exciting Referral Reward</p>
                </div>
              </div>
              <div class="col-6 col-md-4" style="height: 250px;">
                <div class="feature-box text-center border rounded-3 p-3">
                  <i class="bi bi-headset text-primary fs-2"></i>
                  <p class="fw-bold mt-2">Instant support and assistance</p>
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
                Book your lucky number online
                Play Smart. Win Big. Be Part of the Community!
              </p>
            </div>
          </div>
        </div>

        <div class="row justify-content-center mt-4">
          <div class="col-12 col-md-10 rounded">
            <div class="table-responsive rounded">
              <div class="table-responsive">
                <table class="table table-bordered text-center align-middle bg-white shadow rounded">
                  <thead class="bg-light align-middle" style="height: 70px;">
                    <tr>
                      <th>Lottery</th>
                      <th>Prize</th>
                      <!-- <th>Time</th>
                      <th>Sold</th> -->
                      <th>Buy</th>
                      <!-- <th>Status</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(dashboard, index) in upcomingDraws" :key="dashboard.id"
                      :style="index % 2 === 0 ? { backgroundColor: '#EEEEEE' } : {}">
                      <td>
                        <img :src="image2" alt="Flag" class="me-2" style="width: 30px; height: 20px;">
                        {{ dashboard.name || 'Unknown Lottery' }}
                      </td>
                      <td>USDT{{ ((parseFloat(dashboard.prize) || 0) * 70).toLocaleString() }}</td>

                      <!-- <td>€{{ ((parseFloat(dashboard.prize) / 100) || 0).toFixed(2) }}</td> -->
                      <!-- <td>
                        <div class="d-flex justify-content-center">
                          <div class="text-center me-2">
                            <span class="badge badgecol2 border">{{ formatTimeRemaining(dashboard.date).days }}</span>
                            <div>days</div>
                          </div>
                          <div class="text-center me-2">
                            <span class="badge badgecol2 border">{{ formatTimeRemaining(dashboard.date).hours }}</span>
                            <div>hrs</div>
                          </div>
                          <div class="text-center me-2">
                            <span class="badge badgecol2 border">{{ formatTimeRemaining(dashboard.date).minutes
                              }}</span>
                            <div>mins</div>
                          </div>
                          <div class="text-center">
                            <span class="badge badgecol2 border">{{ formatTimeRemaining(dashboard.date).seconds
                              }}</span>
                            <div>secs</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="progress-bar">
                          <div class="progress-fill"
                            :style="{ width: ((dashboard.pickedNumbers?.length / 100) * 100 || 0) + '%' }">
                            <span class="progress-text">{{ Math.round((dashboard.pickedNumbers?.length / 100) *
                              100) }}%</span>
                          </div>
                        </div>
                        <small>{{ dashboard.pickedNumbers?.length || 0 }}/100 numbers sold</small>
                      </td> -->
                      <td><a :href="route('landinglottery.index')" class="nav-link"><button
                            class="btn btn-primary btn-sm">Buy Tickets</button></a>

                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="text-center mt-5 mb-5">
              <a :href="route('landinglottery.index')" class="nav-link">
                <p class=" text-secondary text-center text-decoration-underline">See All Lotteries</p>
              </a>

            </div>
          </div>
        </div>


      </div>
    </section>







    <Footer />
  </div>
</template>

<script>
import Footer from "@/components/Landing/footer.vue";
import Nav from "@/components/Landing/nav.vue";
import axios from "axios";

export default {
  props: {
    latestDraw: String,
    lotteries: Array,
    winning_numbers: Array,
    winners: Array,
    upcomingDraws: Array
  },

  data() {
    return {
      image1: "/assets/images/image1.png",
      image2: "/assets/images/1.png",
      image3: "/assets/images/2.png",
      image4: "/assets/images/3.png",
      image5: "/assets/images/why.png",
      image6: "/assets/images/contact.png",

      landingPageData: {},
      countdown: {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
      },
      countdownInterval: null,
      now: new Date(),
      loading: false
    };
  },

  created() {
    this.fetchLandingPageData();
    if (this.latestDraw) {
      this.startCountdown(this.latestDraw);
    }
  },

  methods: {
    formatTimeRemaining(drawDate) {
      if (!drawDate) return { days: 0, hours: 0, minutes: 0, seconds: 0 };

      try {
        const now = new Date();
        // Set the draw time to 8 PM (20:00:00) on the draw date
        const drawDateTime = new Date(drawDate);
        drawDateTime.setHours(20, 0, 0, 0);
        const diff = drawDateTime - now;

        if (diff <= 0) return { days: 0, hours: 0, minutes: 0, seconds: 0 };

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((diff / (1000 * 60)) % 60);
        const seconds = Math.floor((diff / 1000) % 60);

        return { days, hours, minutes, seconds };
      } catch (e) {
        return { days: 0, hours: 0, minutes: 0, seconds: 0 };
      }
    },

    startCountdown() {
      // Clear any existing interval
      if (this.countdownInterval) {
        clearInterval(this.countdownInterval);
      }

      // Update the countdown every second
      this.countdownInterval = setInterval(() => {
        // Force Vue to re-render by updating a reactive property
        this.now = new Date();
      }, 1000);
    },
    async fetchLandingPageData() {
      try {
        const response = await axios.get("/api/landing-page-data");
        this.landingPageData = response.data;
      } catch (error) {
        console.error("Error fetching landing page data:", error);
      }
    },

    startCountdown(drawDate) {
      // Convert drawDate to a timestamp
      const targetDate = new Date(drawDate).getTime();

      this.countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance <= 0) {
          clearInterval(this.countdownInterval);
          this.countdown = { days: 0, hours: 0, minutes: 0, seconds: 0 };
          return;
        }

        this.countdown.days = Math.floor(distance / (1000 * 60 * 60 * 24));
        this.countdown.hours = Math.floor(
          (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        );
        this.countdown.minutes = Math.floor(
          (distance % (1000 * 60 * 60)) / (1000 * 60)
        );
        this.countdown.seconds = Math.floor((distance % (1000 * 60)) / 1000);
      }, 1000);
    },
    formatCountdown(drawTime) {
      if (!drawTime) return "N/A";

      const now = new Date();
      const drawDate = new Date(drawTime);
      const diff = drawDate - now;

      if (diff <= 0) return "Expired";

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
      const minutes = Math.floor((diff / (1000 * 60)) % 60);
      const seconds = Math.floor((diff / 1000) % 60);

      return `${days} days ${hours}:${minutes}:${seconds}`;
    },

    methods: {
      extractHighlightedNumbers(numbers) {
        if (!numbers || numbers.length < 4) return numbers; // Ensure we have enough numbers
        return [...numbers.slice(0, 2), ...numbers.slice(-2)];
      }
    }


  },

  mounted() {
    setInterval(() => {
      this.now = new Date();
    }, 1000);
  },
  beforeUnmount() {
    clearInterval(this.countdownInterval);
  },


  components: {
    Footer,
    Nav,
  },
};
</script>


``

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