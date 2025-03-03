<template>
  <div id="app" class="d-flex dark-theme">
    <Sidebar @sidebar-toggle="handleSidebarToggle" />
    <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
      <!-- Adjusted main content class -->
      <!-- Dashboard Widgets -->
      <div class="dashboard-banner">
        <div class="banner-content">
          <div class="jackpot">
            <span>JACKPOT</span>
          </div>
          <div class="super-lotto">
            <span>SUPER</span>
            <span class="seven">7</span>
            <span>LOTTO</span>
          </div>
        </div>
        <button class="control-button left">&#x276C;</button>
        <button class="control-button right">&#x276D;</button>
      </div>


      <div class="dashboard-cards">
        <div class="card">
          <img src="/path/to/image1.jpg" alt="Eurojackpot" class="card-image" />
          <div class="card-content">
            <h3>Eurojackpot</h3>
            <p class="prize">€7,930,000</p>
            <button class="play-now">Visit</button>
          </div>
        </div>
        <div class="card">
          <img src="/path/to/image2.jpg" alt="Powerball" class="card-image" />
          <div class="card-content">
            <h3>Powerball</h3>
            <p class="prize">€7,930,000</p>
            <button class="play-now">Visit</button>
          </div>
        </div>
        <div class="card">
          <img src="/path/to/image3.jpg" alt="Megamillions" class="card-image" />
          <div class="card-content">
            <h3>Megamillions</h3>
            <p class="prize">€7,930,000</p>
            <button class="play-now">Visit</button>
          </div>
        </div>
        <div class="card">
          <img src="/path/to/image4.jpg" alt="UK Lotto" class="card-image" />
          <div class="card-content">
            <h3>UK Lotto</h3>
            <p class="prize">€7,930,000</p>
            <button class="play-now">Visit</button>
          </div>
        </div>
      </div>
      <router-view />
    </div>
  </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';

export default {
  components: {
    Sidebar,
  },
  data() {
    return {
      isSidebarVisible: true,
    };
  },
  methods: {
    handleSidebarToggle(isVisible) {
      this.isSidebarVisible = isVisible;
    },
  },
};
</script>

<style scoped>
#app.dark-theme {
  background-color: #121212;
  color: #e0e0e0;
  font-family: 'Arial', sans-serif;
  min-height: 100vh;
}

.main-content {
  margin-left: 250px;
  padding: 20px;
  background-color: #1e1e1e;
  transition: margin-left 0.3s ease;
}

.main-content.sidebar-hidden {
  margin-left: 0;
}

.dashboard-banner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  background-color: #1a1a1a;
  border-radius: 8px;
  overflow: hidden;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  height: 400px;
}

.banner-content {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 40px;
  width: 100%;
}

.jackpot span {
  font-size: 3rem;
  font-weight: bold;
  color: #fbc02d;
  text-shadow: 0 0 10px #fbc02d, 0 0 20px #ffeb3b;
}

.super-lotto {
  text-align: center;
  color: #7c4dff;
}

.super-lotto span {
  font-size: 1.5rem;
  font-weight: bold;
  text-shadow: 0 0 10px #7c4dff, 0 0 20px #651fff;
}

.super-lotto .seven {
  font-size: 2.5rem;
  color: #00e676;
}

.control-button {
  background-color: transparent;
  border: 2px solid #ff4081;
  color: #ff4081;
  border-radius: 50%;
  font-size: 1.5rem;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.control-button:hover {
  background-color: #ff4081;
  color: #ffffff;
  transform: scale(1.1);
}

.control-button.left {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
}

.control-button.right {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
}


.dashboard-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  /* 4 cards per row */
  gap: 20px;
  margin-bottom: 20px;
}

.card {
  background-color: #2c2c2c;
  /* Darker background for cards */
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  /* Subtle shadow for depth */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  /* Slight lift effect */
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
  /* Enhanced shadow on hover */
}

.card-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  /* Ensure image covers the area proportionally */
}

.card-content {
  padding: 15px;
  text-align: center;
}

.card-content h3 {
  color: #f1f1f1;
  /* Light color for card titles */
  margin: 0;
}

.card-content .prize {
  color: #ffb400;
  /* Gold-like color for prize */
  font-size: 1.2em;
  margin: 10px 0;
}

.card-content .play-now {
  background-color: #d32f2f;
  /* Red button */
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 0.9em;
  transition: background-color 0.3s ease;
}

.card-content .play-now:hover {
  background-color: #b71c1c;
  /* Darker red on hover */
}
</style>
