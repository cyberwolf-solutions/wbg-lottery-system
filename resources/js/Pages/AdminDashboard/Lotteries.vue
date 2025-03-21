<template>
  <div id="app" class="d-flex dark-theme">
    <Sidebar @sidebar-toggle="handleSidebarToggle" />
    <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
      <!-- Dashboard Widgets -->
      <div class="dashboard-banner">
        <!-- Top Navbar Section -->
        <div class="navbar">
          <h2 class="lottery-name fw-bold text-danger">{{ lotteries.name }}</h2>
          <button @click="openModal(lotteries)" class="btn btn-primary">Create a Dashboard</button>

        </div>

        <div v-if="responseMessage" :class="responseClass" class="fixed w-full p-4 text-center z-50">
          <div class="bg-blue-500 text-white p-3 rounded-lg shadow-md">
            {{ responseMessage }}
          </div>
        </div>


        <!-- Lottery Table Section -->
        <div class="lottery-table">
          <h3 class="mt-4">{{ lotteries.name }} Dashboard Details</h3>
          <table>
            <thead>
              <tr>
                <th>Dashboard</th>
                <th>Price</th>
                <th>Draw Date</th>
                <th>Draw Number</th>
                <th>Actions</th>

              </tr>
            </thead>

            <tbody>
              <tr v-for="dashboard in dashboards" :key="dashboard.id">
                <td><a href="#">{{ dashboard.dashboard }}</a></td>
                <td>{{ dashboard.price }}</td>
                <td>{{ dashboard.date }}</td>
                <td>{{ dashboard.draw_number }}</td>
                <td>
                  <button @click="editDashboard(dashboard)" class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button @click="confirmDelete(dashboard)" class="btn btn-danger btn-sm mx-2">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>


          </table>
        </div>

        <!-- Modal for Creating a Dashboard -->
        <div v-if="isModalOpen" class="modal-overlay">
          <div class="modal-content">
            <h3>Create a New Dashboard</h3>
            <form @submit.prevent="createDashboard">


              <div class="form-group">
                <label for="lottery">Lottery Name:</label>
                <input type="text" :value="lotteries.name" id="lottery_name" readonly />
                <input type="text" v-model="newDashboard.lottery_id" id="id" readonly hidden />


              </div>

              <div class="form-group">
                <label for="dashboard">Dashboard Name:</label>
                <select v-model="newDashboard.dashboard" id="dashboard" class="form-control" required>
                  <option value="$1 Dashboard">$1 Dashboard</option>
                  <option value="$10 Dashboard">$10 Dashboard</option>
                  <option value="$100 Dashboard">$100 Dashboard</option>
                </select>
              </div>

              <div class="form-group">
                <label for="dashboardType">Dashboard Type:</label>
                <select v-model="newDashboard.dashboardType" id="dashboardType" class="form-control" required>
                  <option value="First Digits">First Digits</option>
                  <option value="Last Digits">Last Digits</option>
                </select>
              </div>

              <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" v-model="newDashboard.price" id="price" required />
              </div>



              <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" v-model="newDashboard.date" id="date" required />
              </div>

              <div class="form-group">
                <label for="draw">Draw:</label>
                <input type="text" v-model="newDashboard.draw" id="draw" required />
              </div>

              <div class="form-group">
                <label for="draw">Color: (#code)</label>
                <input type="text" v-model="newDashboard.color" id="draw" required />
              </div>

              <div class="form-group">
                <label for="draw-number">Draw Number:</label>
                <input type="number" v-model="newDashboard.drawNumber" id="draw-number" required />
              </div>

              <button type="submit" class="btn btn-success mx-3">Create Dashboard</button>
              <button type="button" @click="closeModal" class="btn btn-danger mx-4">Cancel</button>
            </form>
          </div>
        </div>


        <!-- Modal for Editing a Dashboard -->
        <div v-if="isEditModalOpen" class="modal-overlay">
          <div class="modal-content">
            <h3>Edit Dashboard</h3>
            <form @submit.prevent="updateDashboard">
              <div class="form-group">
                <label for="edit-price">Price:</label>
                <input type="number" v-model="editingDashboard.price" id="edit-price" required />
              </div>
              <div class="form-group">
                <label for="edit-date">Date:</label>
                <input type="date" v-model="editingDashboard.date" id="edit-date" required />
              </div>
              <div class="form-group">
                <label for="edit-draw">Draw:</label>
                <input type="text" v-model="editingDashboard.draw" id="edit-draw" required />
              </div>
              <div class="form-group">
                <label for="edit-draw-number">Draw Number:</label>
                <input type="number" v-model="editingDashboard.drawNumber" id="edit-draw-number" required />
              </div>
              <button type="submit" class="btn btn-success mx-3">Update Dashboard</button>
              <button type="button" @click="closeEditModal" class="btn btn-danger mx-4">Cancel</button>
            </form>
          </div>
        </div>

        <!-- Confirmation Modal for Deleting -->
        <div v-if="isDeleteModalOpen" class="modal-overlay">
          <div class="modal-content">
            <h3>Are you sure you want to delete this dashboard?</h3>
            <div class="row align-items-center justify-content-center">
              <button @click="deleteDashboard" class=" btn btn-danger mx-2 col-5">Yes, Delete</button>
              <button @click="closeDeleteModal" class="btn btn-secondary col-5">Cancel</button>
            </div>

          </div>
        </div>
      </div>
    </div>

    <router-view />
  </div>
</template>

<script>
import { ref } from 'vue';  // Import `ref` from Vue
import Sidebar from '@/components/AdminSidebar.vue';

export default {
  components: {
    Sidebar,
  },
  props: ["lotteries", "dashboards"],

  data() {
    return {
      lotteries: this.lotteries || [],
      dashboards: this.dashboards || [],
      isSidebarVisible: true,
      isModalOpen: false,
      isEditModalOpen: false,
      isDeleteModalOpen: false,
      newDashboard: {
        dashboard: '',
        price: '',
        date: '',
        draw: '',
        draw_number: '',
        lottery_id: '',
        id: '',
        dashboardType: ''

      },
      editingDashboard: {},
      responseMessage: ref(null),  // Use `ref` to make this reactive
      responseClass: ref('bottom-response'),
    };
  },
  mounted() {
    console.log("Lotteries received:", this.lotteries);
    console.log("Dashboards:", this.dashboards);
  },

  methods: {
    showResponse(message, position = 'bottom') {
      this.responseMessage = message;
      this.responseClass = position === 'bottom' ? 'top-response' : 'bottom-response';

      // Hide the message after 3 seconds
      setTimeout(() => {
        this.responseMessage = null;
      }, 3000);
    },

    handleSidebarToggle(isVisible) {
      this.isSidebarVisible = isVisible;
    },

    openModal(lottery) {
      if (lottery) {
        this.newDashboard.lottery_id = lottery.id;
      }
      this.isModalOpen = true;
    },

    closeModal() {
      this.isModalOpen = false;
    },

    editDashboard(dashboard) {
      this.editingDashboard = { ...dashboard };
      this.isEditModalOpen = true;
    },

    closeEditModal() {
      this.isEditModalOpen = false;
    },

    updateDashboard() {
      const index = this.dashboards.findIndex(d => d.id === this.editingDashboard.id);
      if (index !== -1) {
        this.dashboards.splice(index, 1, this.editingDashboard);
      }
      this.closeEditModal();
    },

    confirmDelete(dashboard) {
      this.editingDashboard = dashboard;
      this.isDeleteModalOpen = true;
    },

    closeDeleteModal() {
      this.isDeleteModalOpen = false;
    },

    deleteDashboard() {
      this.dashboards = this.dashboards.filter(d => d.id !== this.editingDashboard.id);
      this.closeDeleteModal();
    },

    async createDashboard() {
      try {
        const response = await axios.post('/api/admin/dashboard/create', this.newDashboard);
        this.dashboards.push(response.data); // Add the newly created dashboard
        this.closeModal();
        this.showResponse('Dashboard Created', 'bottom');  // Call showResponse method
        window.location.reload();
      } catch (error) {
        console.error("Error creating dashboard: ", error);
        this.showResponse('Failed to create dashboard. Please try again.', 'bottom'); // Call showResponse on error
      }
    }
  },
};
</script>

<style scoped>
.top-response {
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  position: fixed;
  z-index: 999;
}

.bottom-response {
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  position: fixed;
  z-index: 999;
}

.bg-blue-500 {
  background-color: #3b82f6;
}

.text-white {
  color: white;
}

.p-3 {
  padding: 12px;
}

.rounded-lg {
  border-radius: 8px;
}

.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
  background-color: #1a1a1a;
  border-radius: 8px;
  overflow: hidden;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  height: auto;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 20px;
  border-bottom: 2px solid #444;
}

.lottery-name {
  font-size: 24px;
  color: #e0e0e0;
}

.create-dashboard-btn {
  background-color: #5a5a5a;
  color: #e0e0e0;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 5px;
}

.create-dashboard-btn:hover {
  background-color: #777;
}

.lottery-prices,
.dashboards {
  margin-top: 20px;
}

.lottery-prices h3,
.dashboards h3 {
  color: #e0e0e0;
  font-size: 20px;
}

.lottery-prices ul,
.dashboards ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.lottery-prices li,
.dashboards li {
  color: #b0b0b0;
  font-size: 16px;
}

.dashboards a {
  color: #1e90ff;
  text-decoration: none;
}

.dashboards a:hover {
  text-decoration: underline;
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
  background-color: #1a1a1a;
  border-radius: 8px;
  overflow: hidden;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  height: auto;
}

.lottery-table h3 {
  color: #e0e0e0;
  font-size: 20px;
  margin-bottom: 15px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: #2c2c2c;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

th,
td {
  padding: 12px 15px;
  text-align: left;
  color: #e0e0e0;
  border-bottom: 1px solid #444;
}

th {
  background-color: #333;
}

td a {
  color: #1e90ff;
  text-decoration: none;
}

td a:hover {
  text-decoration: underline;
}

tbody tr:hover {
  background-color: #444;
}
</style>
<style scoped>
/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

/* Modal Content */
.modal-content {
  background-color: #333;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  width: 100%;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  color: #e0e0e0;
}

h3 {
  margin-bottom: 15px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  font-size: 14px;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 8px;
  font-size: 14px;
  background-color: #444;
  color: #e0e0e0;
  border: 1px solid #555;
  border-radius: 5px;
}

button {
  padding: 10px 20px;
  margin-top: 10px;
  border-radius: 5px;
}

button.btn-success {
  background-color: #28a745;
  color: #fff;
}

button.btn-danger {
  background-color: #dc3545;
  color: #fff;
}

button:hover {
  opacity: 0.9;
}
</style>
