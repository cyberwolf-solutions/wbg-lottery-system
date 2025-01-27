<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Dashboard Widgets -->
            <div class="dashboard-widgets">
                <div class="widget">
                    <h3>Widget 1</h3>
                    <p>Widget content goes here.</p>
                </div>
                <div class="widget">
                    <h3>Widget 2</h3>
                    <p>Widget content goes here.</p>
                </div>
                <div class="widget">
                    <h3>Widget 3</h3>
                    <p>Widget content goes here.</p>
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
      isSidebarVisible: true, // This controls whether the sidebar is visible or hidden
    };
  },
  methods: {
    handleSidebarToggle(isVisible) {
      this.isSidebarVisible = isVisible; // Update the visibility based on the sidebar toggle
    },
  },
};
</script>

<style scoped>
/* Apply dark theme styles to the main div with id="app" */
#app.dark-theme {
  background-color: #121212; /* Dark background */
  color: #e0e0e0; /* Light text color for readability */
  font-family: 'Arial', sans-serif;
  min-height: 100vh; /* Full height for the app */
}

/* Main Content Styles */
.main-content {
  margin-left: 250px; /* Default margin for sidebar visible */
  padding: 20px;
  background-color: #1e1e1e; /* Dark background for main content */
  transition: margin-left 0.3s ease;
}

.main-content.sidebar-hidden {
  margin-left: 0; /* Remove margin when sidebar is hidden */
}

/* Dashboard Widgets */
.dashboard-widgets {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.widget {
  background-color: #2c2c2c; /* Darker background for widgets */
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
}

.widget h3 {
  color: #f1f1f1; /* Light color for widget titles */
  margin-top: 0;
}

.widget p {
  color: #b0b0b0; /* Slightly dimmed color for widget content */
}

/* Sidebar Styles */
.sidebar {
  width: 250px;
  background-color: #1f1f1f; /* Dark background for sidebar */
  color: #e0e0e0; /* Light text color for sidebar */
  padding: 20px;
  position: fixed;
  height: 100vh;
  transition: transform 0.3s ease;
  z-index: 1000;
}

.sidebar.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar .nav-link {
  color: #e0e0e0; /* Light text color for links */
  padding: 10px 15px;
  transition: background-color 0.3s ease;
}

.sidebar .nav-link:hover {
  background-color: #333; /* Darker hover effect */
}

/* Responsive Layout */
@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
  }

  .dashboard-widgets {
    grid-template-columns: 1fr; /* Single column on small screens */
  }
}
</style>
