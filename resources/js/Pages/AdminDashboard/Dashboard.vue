<template>
  <div id="app" class="d-flex dark-theme">
    <Sidebar @sidebar-toggle="handleSidebarToggle" />
    <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">

      <!-- Notification Bell -->
      <div class="notification-bell" @click.stop="toggleNotificationPanel">
        <i class="fas fa-bell"></i>
        <span v-if="unreadCount > 0" class="notification-badge">{{ unreadCount }}</span>
      </div>

      <!-- Notification Panel Overlay (click to close) -->
      <div v-if="showNotificationPanel" class="notification-overlay" @click="toggleNotificationPanel"></div>

      <!-- Notification Panel -->
      <transition name="slide">
        <div v-if="showNotificationPanel" class="notification-panel">
          <div class="notification-header">
            <h4>Notifications</h4>
            <div class="notification-actions">
              <button @click="clearNotifications" class="action-btn" title="Clear all">
                <i class="fas fa-trash"></i>
              </button>
              <button @click="toggleNotificationPanel" class="close-btn">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="notification-content">
            <div v-if="notifications.length === 0" class="empty-notifications">
              No new notifications
            </div>
            <div v-for="(notification, index) in notifications" :key="notification.id" class="notification-item" :class="{
              'unread': !notification.read && notification.type === 'message',
              'withdrawal-notification': notification.type === 'withdrawal',
              'deposit-notification': notification.type === 'deposit'
            }" @click="markNotificationAsRead(index)">
              <div class="notification-message">
                <template v-if="notification.type === 'message'">
                  <strong>New message from {{ notification.user.name }}:</strong>
                  <p>{{ notification.message }}</p>
                </template>

                <template v-if="notification.type === 'withdrawal'">
                  <strong>Withdrawal request from {{ notification.user.name }}</strong>
                  <p>Amount: {{ notification.amount }} | Status: {{ notification.status }}</p>
                </template>

                <template v-if="notification.type === 'deposit'">
                  <strong>Deposit from {{ notification.user.name }}</strong>
                  <p>Amount: {{ notification.amount }} | Status: {{ notification.status }}</p>
                </template>
              </div>
              <div class="notification-time">
                {{ formatTime(notification.created_at) }}
                <button @click.stop="removeNotification(index)" class="remove-btn" title="Remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>

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
                      class="number-ball small">{{
                        num }}</span>
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

        <div class="widget-row">
          <!-- Latest Messages -->
          <div class="widget medium-widget">
            <div class="widget-header">
              <h3>Latest Messages</h3>
            </div>
            <div class="widget-content">
              <ul class="notification-list">
                <li v-for="msg in latestMessages" :key="msg.id" class="notification-item">
                  <strong>{{ msg.user?.name || 'Unknown' }}</strong>: {{ msg.message }}
                  <!-- <small class="text-muted">{{ formatDate(msg.created_at) }}</small> -->
                </li>
              </ul>
            </div>
          </div>

          <!-- Latest Withdrawals -->
          <div class="widget medium-widget">
            <div class="widget-header">
              <h3>Recent Withdrawals</h3>
            </div>
            <div class="widget-content">
              <ul class="notification-list">
                <li v-for="withdraw in latestWithdraws" :key="withdraw.id" class="notification-item">
                  {{ withdraw.user?.name || 'Unknown' }} - {{ withdraw.amount }} 
                  <!-- <small class="text-muted">{{ formatDate(withdraw.created_at) }}</small> -->
                </li>
              </ul>
            </div>
          </div>

          <!-- Latest Deposits -->
          <div class="widget medium-widget">
            <div class="widget-header">
              <h3>Recent Deposits</h3>
            </div>
            <div class="widget-content">
              <ul class="notification-list">
                <li v-for="deposit in latestDeposits" :key="deposit.id" class="notification-item">
                  {{ deposit.user?.name || 'Unknown' }} - {{ deposit.amount }} 
                  <!-- <small class="text-muted">{{ formatDate(deposit.created_at) }}</small> -->
                </li>
              </ul>
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
import Echo from 'laravel-echo';

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
    },
    latestMessages: {
      type: Array,
      default: () => []
    },
    latestWithdraws: {
      type: Array,
      default: () => []
    },
    latestDeposits: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      isSidebarVisible: true,
      showNotificationPanel: false,
      notifications: [],
      unreadCount: 0,
      echo: null,
      removedNotificationIds: new Set()
    };
  },
  computed: {
    recentMessages() {
      return this.notifications
        .filter(n => n.type === 'message')
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 5);
    },
    recentWithdrawals() {
      return this.notifications
        .filter(n => n.type === 'withdrawal')
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 5);
    },
    recentDeposits() {
      return this.notifications
        .filter(n => n.type === 'deposit')
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 5);
    }
  },
  mounted() {
    // Load removed IDs first to ensure they're available for filtering
    const removedIds = localStorage.getItem('removedNotificationIds');
    if (removedIds) {
      this.removedNotificationIds = new Set(JSON.parse(removedIds));
    }
    this.loadLocalNotifications();
    this.fetchNotifications();
    this.setupWebSocket();
  },
  beforeDestroy() {
    document.removeEventListener('click', this.handleClickOutside);
    if (this.echo) {
      this.echo.disconnect();
    }
  },
  methods: {
    handleSidebarToggle(isVisible) {
      this.isSidebarVisible = isVisible;
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    fetchNotifications() {
      axios.get('/api/admin/notifications')
        .then(response => {
          // Filter out removed notifications from API response
          const newNotifications = response.data.filter(
            n => !this.removedNotificationIds.has(n.id)
          );

          // Merge with existing notifications, avoiding duplicates
          const existingIds = new Set(this.notifications.map(n => n.id));
          const mergedNotifications = [
            ...this.notifications,
            ...newNotifications.filter(n => !existingIds.has(n.id))
          ];

          this.notifications = mergedNotifications.filter(
            n => !this.removedNotificationIds.has(n.id)
          );
          this.saveLocalNotifications();
          this.updateUnreadCount();
        })
        .catch(error => {
          console.error('Error fetching notifications:', error);
        });
    },
    toggleNotificationPanel() {
      this.showNotificationPanel = !this.showNotificationPanel;
      if (this.showNotificationPanel) {
        this.markNotificationsAsRead();
        // Add click outside listener when panel opens
        document.addEventListener('click', this.handleClickOutside);
      } else {
        // Remove click outside listener when panel closes
        document.removeEventListener('click', this.handleClickOutside);
      }
    },
    handleClickOutside(event) {
      const panel = this.$el.querySelector('.notification-panel');
      const bell = this.$el.querySelector('.notification-bell');

      // Close panel if clicked outside of it and not on the bell
      if (panel && !panel.contains(event.target) &&
        bell && !bell.contains(event.target)) {
        this.toggleNotificationPanel();
      }
    },
    loadLocalNotifications() {
      const savedNotifications = localStorage.getItem('adminNotifications');
      if (savedNotifications) {
        const allNotifications = JSON.parse(savedNotifications);
        // Only load unread notifications that haven't been removed
        this.notifications = allNotifications.filter(
          notification => !notification.read && !this.removedNotificationIds.has(notification.id)
        );
        this.updateUnreadCount();
      }
    },
    saveLocalNotifications() {
      // Save only unread notifications that aren't removed
      const notificationsToSave = this.notifications.filter(
        n => !n.read && !this.removedNotificationIds.has(n.id)
      );
      localStorage.setItem('adminNotifications', JSON.stringify(notificationsToSave));
      localStorage.setItem('removedNotificationIds', JSON.stringify([...this.removedNotificationIds]));
      this.updateUnreadCount();
    },
    updateUnreadCount() {
      this.unreadCount = this.notifications.filter(n => !n.read).length;
    },
    addNotification(notificationData) {
      const newId = notificationData.id || Date.now();
      let newNotification;

      if (notificationData.type === 'message') {
        newNotification = {
          id: newId,
          type: 'message',
          message: notificationData.content,
          user: notificationData.user || { name: 'Unknown User' },
          created_at: notificationData.created_at || new Date().toISOString(),
          read: !!notificationData.read_at
        };
      } else if (notificationData.type === 'withdrawal') {
        newNotification = {
          id: newId,
          type: 'withdrawal',
          amount: notificationData.amount,
          user: notificationData.user || { name: 'Unknown User' },
          created_at: notificationData.created_at || new Date().toISOString(),
          status: notificationData.status
        };
      } else if (notificationData.type === 'deposit') {
        newNotification = {
          id: newId,
          type: 'deposit',
          amount: notificationData.amount,
          user: notificationData.user || { name: 'Unknown User' },
          created_at: notificationData.created_at || new Date().toISOString(),
          status: notificationData.status
        };
      }

      if (newNotification && !this.removedNotificationIds.has(newId)) {
        if (!this.notifications.some(n => n.id === newId)) {
          this.notifications.unshift(newNotification);
          this.saveLocalNotifications();

          let toastMessage = '';
          if (newNotification.type === 'message') {
            toastMessage = `New message from ${newNotification.user.name}`;
          } else if (newNotification.type === 'withdrawal') {
            toastMessage = `New withdrawal request from ${newNotification.user.name}`;
          } else if (newNotification.type === 'deposit') {
            toastMessage = `New deposit from ${newNotification.user.name}`;
          }

          this.$toast.info(toastMessage, {
            position: 'top-right',
            duration: 5000,
            onClick: () => {
              this.showNotificationPanel = true;
              this.markNotificationsAsRead();
            }
          });
        }
      }
    },
    markNotificationAsRead(index) {
      this.notifications[index].read = true;
      this.saveLocalNotifications();
    },
    markNotificationsAsRead() {
      this.notifications = this.notifications.map(n => ({ ...n, read: true }));
      this.saveLocalNotifications();
    },
    removeNotification(index) {
      this.removedNotificationIds.add(this.notifications[index].id);
      this.notifications.splice(index, 1);
      this.saveLocalNotifications();
    },
    clearNotifications() {
      this.notifications.forEach(notification => {
        this.removedNotificationIds.add(notification.id);
      });
      this.notifications = [];
      this.saveLocalNotifications();
    },
    setupWebSocket() {
      this.echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
          }
        }
      });

      this.echo.private(`admin.${this.$page.props.auth.user.id}`)
        .listen('NewMessage', (data) => {
          this.addNotification({ ...data.message, type: 'message' });
        })
        .listen('NewWithdrawal', (data) => {
          this.addNotification({ ...data.withdrawal, type: 'withdrawal' });
        })
        .listen('NewDeposit', (data) => {
          this.addNotification({ ...data.deposit, type: 'deposit' });
        });
    },
    formatTime(dateString) {
      return new Date(dateString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
  },
};
</script>

<style scoped>
.widget-row {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.medium-widget {
  flex: 1;
  min-width: 0;
}

.notification-list {
  max-height: 300px;
  overflow-y: auto;
}

.notification-item {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  border-bottom: 1px solid #3a3a3a;
  transition: background-color 0.2s;
}

.notification-item:hover {
  background-color: #2a2a2a;
}

.notification-message {
  flex: 1;
}

.notification-message strong {
  font-size: 14px;
  color: #ffffff;
}

.notification-message p {
  margin: 5px 0 0;
  font-size: 13px;
  color: #b0b0b0;
}

.notification-time {
  font-size: 12px;
  color: #888;
  margin-left: 10px;
  align-self: center;
}

.empty-notifications {
  text-align: center;
  color: #888;
  padding: 20px;
  font-size: 14px;
}

/* Optional: Specific styling for each notification type */
.message-notification {
  background-color: rgba(0, 123, 255, 0.1);
}

.withdrawal-notification {
  background-color: rgba(255, 193, 7, 0.1);
}

.deposit-notification {
  background-color: rgba(40, 167, 69, 0.1);
}

/* Base Styles */
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

/* Notification System */
.notification-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 998;
}

.notification-panel {
  position: fixed;
  top: 0;
  right: 0;
  width: 400px;
  height: 100vh;
  background: #2c3e50;
  color: white;
  z-index: 999;
  box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}

/* Slide transition */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

.slide-enter,
.slide-leave-to {
  transform: translateX(100%);
}

.notification-header {
  padding: 15px;
  background: #1a252f;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 1;
}

.notification-header h4 {
  margin: 0;
}

.notification-actions {
  display: flex;
  gap: 10px;
}

.action-btn,
.close-btn {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 1rem;
  padding: 5px;
}

.action-btn:hover,
.close-btn:hover {
  color: #ddd;
}

.notification-content {
  flex: 1;
  overflow-y: auto;
  padding: 15px;
}

.empty-notifications {
  text-align: center;
  padding: 20px;
  color: #95a5a6;
}

.notification-item {
  padding: 10px;
  margin-bottom: 10px;
  background: #34495e;
  border-radius: 5px;
  transition: background 0.2s;
  cursor: pointer;
}

.notification-item:hover {
  background: #3d566e;
}

.notification-message {
  margin-bottom: 5px;
}

.notification-time {
  font-size: 0.8rem;
  color: #95a5a6;
  text-align: right;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.remove-btn {
  background: none;
  border: none;
  color: #95a5a6;
  cursor: pointer;
  padding: 0 5px;
}

.remove-btn:hover {
  color: #fff;
}

/* Notification type styles */
.withdrawal-notification {
  border-left: 4px solid #f39c12;
  background-color: rgba(243, 156, 18, 0.1);
}

.deposit-notification {
  border-left: 4px solid #2ecc71;
  background-color: rgba(46, 204, 113, 0.1);
}

.unread {
  border-left: 4px solid #3498db;
  background-color: rgba(52, 152, 219, 0.1);
}

/* Notification Bell */
.notification-bell {
  position: fixed;
  top: 20px;
  right: 20px;
  font-size: 1.5rem;
  color: #fff;
  cursor: pointer;
  z-index: 1000;
  background: rgba(0, 0, 0, 0.5);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.notification-bell:hover {
  background: rgba(0, 0, 0, 0.7);
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #ff4757;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  font-size: 0.7rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Dashboard Widgets */
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

  .notification-panel {
    width: 100%;
  }
}
</style>