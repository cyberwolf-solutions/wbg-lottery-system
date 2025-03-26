<template>
  <div id="app" class="d-flex dark-theme">
    <Sidebar @sidebar-toggle="handleSidebarToggle" />
    <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
      <!-- Dashboard Widgets -->
      <div class="dashboard-widgets">
        <!-- Summary Stats Row -->
        <div class="widget-row">
          <div class="widget stat-widget">
            <div class="widget-header">
              <h3>Total Users</h3>
              <i class="fas fa-users"></i>
            </div>
            <div class="widget-content">
              <span class="stat-value">{{ userStats.total }}</span>
              <span class="stat-change positive">+{{ userStats.new_today }} today</span>
            </div>
          </div>

          <div class="widget stat-widget">
            <div class="widget-header">
              <h3>Active Players</h3>
              <i class="fas fa-user-check"></i>
            </div>
            <div class="widget-content">
              <span class="stat-value">{{ userStats.active }}</span>
              <span class="stat-change">{{ Math.round((userStats.active / userStats.total) * 100) || 0 }}% of
                total</span>
            </div>
          </div>

          <div class="widget stat-widget">
            <div class="widget-header">
              <h3>Total Lotteries</h3>
              <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="widget-content">
              <span class="stat-value">{{ lotteries.length }}</span>
              <span class="stat-change">{{lotteries.reduce((acc, curr) => acc + curr.dashboards_count, 0)}} active
                draws</span>
            </div>
          </div>
        </div>

        <!-- Jackpot Display -->
        <!-- <div class="widget large-widget jackpot-widget">
          <div class="widget-header">
            <h2>Current Jackpots</h2>
          </div>
          <div class="widget-content jackpots-grid">
            <div v-for="jackpot in jackpots" :key="jackpot.id" class="jackpot-item"
              :style="{ backgroundColor: jackpot.color }">
              <img :src="jackpot.image" :alt="jackpot.name" class="jackpot-image" />
              <div class="jackpot-details">
                <h3>{{ jackpot.name }}</h3>
                <div class="winning-number">
                  <span v-for="(num, index) in jackpot.winning_number.split('')" :key="index" class="number-ball">{{ num
                    }}</span>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <!-- Recent Results and Lottery Stats -->
        <div class="widget-row">
          <div class="widget medium-widget">
            <div class="widget-header">
              <h3>Recent Results</h3>
            </div>
            <div class="widget-content">
              <ul class="results-list">
                <li v-for="result in recentResults" :key="result.id" class="result-item">
                  <span class="lottery-name">{{ result.lottery.name }}</span>
                  <span class="winning-number">
                    <span v-for="(num, index) in result.winning_number.split('')" :key="index"
                      class="number-ball small">{{ num }}</span>
                  </span>
                  <span class="result-time">{{ formatDate(result.created_at) }}</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="widget medium-widget">
            <div class="widget-header">
              <h3>Lottery Statistics</h3>
            </div>
            <div class="widget-content">
              <div v-for="lottery in lotteries" :key="lottery.id" class="lottery-stat">
                <div class="lottery-info">
                  <span class="lottery-color" :style="{ backgroundColor: lottery.color }"></span>
                  <span class="lottery-name">{{ lottery.name }}</span>
                </div>
                <div class="lottery-numbers">
                  <span class="stat">
                    <i class="fas fa-chart-line"></i>
                    {{ lottery.dashboards_count }} Draws
                  </span>
                  <span class="stat">
                    <i class="fas fa-trophy"></i>
                    {{ lottery.winners_count }} Winners
                  </span>
                </div>
              </div>
            </div>
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
  props: {
    lotteries: {
      type: Array,
      default: () => []
    },
    userStats: {
      type: Object,
      default: () => ({})
    },
    recentResults: {
      type: Array,
      default: () => []
    },
    jackpots: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      isSidebarVisible: true,
    };
  },
  mounted() {
    console.log("Lotteries:", this.lotteries);
    console.log("User Stats:", this.userStats);
    console.log("Recent Results:", this.recentResults);
    console.log("Jackpots:", this.jackpots);
  },
  methods: {
    handleSidebarToggle(isVisible) {
      this.isSidebarVisible = isVisible;
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    }
  },
};
</script>


<style scoped>
.dashboard-widgets {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.widget-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.widget {
  background: #2a3042;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  flex: 1;
  min-width: 250px;
}

.large-widget {
  flex: 100%;
}

.medium-widget {
  flex: 1;
  min-width: 300px;
}

.widget-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  border-bottom: 1px solid #3a4055;
  padding-bottom: 10px;
}

.widget-header h2,
.widget-header h3 {
  margin: 0;
  color: #fff;
}

.widget-header i {
  font-size: 1.5rem;
  color: #6366f1;
}

.widget-content {
  color: #e2e8f0;
}

/* Stat Widget Styles */
.stat-widget {
  min-height: 120px;
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 2rem;
  font-weight: bold;
  color: #fff;
}

.stat-change {
  font-size: 0.9rem;
  color: #94a3b8;
}

.stat-change.positive {
  color: #10b981;
}

/* Jackpot Widget Styles */
.jackpots-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 15px;
}

.jackpot-item {
  border-radius: 8px;
  padding: 15px;
  display: flex;
  align-items: center;
  gap: 15px;
  color: white;
}

.jackpot-image {
  width: 50px;
  height: 50px;
  object-fit: contain;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  padding: 5px;
}

.jackpot-details {
  flex: 1;
}

.jackpot-details h3 {
  margin: 0 0 5px 0;
  font-size: 1.1rem;
}

.winning-number {
  display: flex;
  gap: 5px;
}

.number-ball {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #fff;
  color: #1e293b;
  font-weight: bold;
}

.number-ball.small {
  width: 22px;
  height: 22px;
  font-size: 0.8rem;
}

/* Results List Styles */
.results-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.result-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #3a4055;
}

.result-item:last-child {
  border-bottom: none;
}

.lottery-name {
  flex: 1;
  font-weight: 500;
}

.result-time {
  font-size: 0.8rem;
  color: #94a3b8;
  margin-left: 15px;
}

/* Lottery Stats Styles */
.lottery-stat {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #3a4055;
}

.lottery-stat:last-child {
  border-bottom: none;
}

.lottery-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.lottery-color {
  width: 15px;
  height: 15px;
  border-radius: 50%;
}

.lottery-numbers {
  display: flex;
  gap: 15px;
}

.stat {
  font-size: 0.9rem;
  color: #94a3b8;
  display: flex;
  align-items: center;
  gap: 5px;
}

.stat i {
  color: #6366f1;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .widget-row {
    flex-direction: column;
  }

  .widget {
    min-width: 100%;
  }

  .jackpots-grid {
    grid-template-columns: 1fr;
  }
}



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
