<template>
  <div>
    <!-- Toggle Button for Small Screens -->
    <button @click="toggleSidebar" class="sidebar-toggle-btn">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div :class="['sidebar', { 'sidebar-hidden': !isSidebarVisible }]">
      <!-- Sidebar Header with Profile -->
      <div class="sidebar-header">
        <h2 class="fs-6 ms-5">Hello! Admin</h2>
        <img :src="logoUrl" alt="Profile Photo" class="profile-photo" />
      </div>

      <!-- Sidebar Menu -->
      <ul class="nav flex-column mt-5" v-if="!loading.permissions">
        <template v-for="item in visibleMenuItems" :key="item.id">
          <li class="nav-item">
            <!-- Main Menu Item -->
            <a v-if="!item.subItems || !item.subItems.length" :href="item.link" class="nav-link"
              :class="{ active: isActive(item.link) }" :aria-label="'Go to ' + item.name">
              {{ item.name }}
            </a>

            <!-- Dropdown Menu Item -->
            <template v-else>
              <a href="#" @click.prevent="toggleDropdown(item.id)" class="nav-link"
                :class="{ active: isDropdownActive(item) }" :aria-label="'Go to ' + item.name">
                {{ item.name }}
                <i :class="['fas float-end', item.isOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
              </a>

              <!-- Submenu Items -->
              <transition name="submenu-transition">
                <ul v-if="item.isOpen" class="nav flex-column ms-3">
                  <li v-for="subItem in item.subItems" :key="subItem.id" class="nav-item">
                    <a :href="subItem.link" class="nav-link" :class="{ active: isActive(subItem.link) }">
                      {{ subItem.name }}
                    </a>
                  </li>
                </ul>
              </transition>
            </template>
          </li>
        </template>

        <!-- Logout Button -->
        <li class="nav-item">
          <button @click="logout" class="nav-link logout-btn" aria-label="Logout">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </button>
        </li>
      </ul>

      <!-- Loading State -->
      <div v-if="loading.permissions" class="text-center p-4">
        <i class="fas fa-spinner fa-spin"></i> Loading menu...
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Sidebar",
  data() {
    return {
      isSidebarVisible: true,
      userPermissions: [],
      loading: {
        permissions: true,
        lotteries: false
      },
      menuItems: [
        {
          id: 1,
          name: "Dashboard",
          link: "/api/admin/dashboard",
          isOpen: false,
          permission: "view dashboard"
        },
        {
          id: 2,
          name: "Users",
          link: "#",
          isOpen: false,
          permission: "manage users",
          subItems: [
            { id: 3, name: "Users", link: "/api/admin/users", permission: "manage users" },
            { id: 4, name: "Roles", link: "/api/admin/Roles", permission: "manage roles" },
            // { id: 5, name: "Players", link: "/api/admin/players", permission: "manage players" },
            // { id: 6, name: "Affiliates", link: "/api/admin/affiliate", permission: "manage affiliates" },
          ],
        },
        {
          id: 12,
          name: "Affiliates",
          link: "/api/admin/affiliate",
          isOpen: false,
          permission: "manage affiliates"
        },
        {
          id: 11,
          name: "Players",
          link: "/api/admin/players",
          isOpen: false,
          permission: "manage players"
        },
        {
          id: 10,
          name: "Notices",
          link: "/api/admin/notices",
          isOpen: false,
          permission: "view notices"
        },
        {
          id: 8,
          name: "Lotteries",
          link: "/api/admin/list",
          isOpen: false,
          permission: "manage lotteries"
        },
        {
          id: 3,
          name: "Lottery Dashboards",
          link: "#",
          isOpen: false,
          permission: "manage dashboards",
          subItems: []
        },
        
        {
          id: 9,
          name: "Results",
          link: "/api/admin/results",
          isOpen: false,
          permission: "view results"
        },
        {
          id: 4,
          name: "Winners",
          link: "#",
          isOpen: false,
          permission: "manage winners",
          subItems: [
            { id: 6, name: "Lottery 2", link: "/api/adminWin", permission: "manage winners" },
          ],
        },
        {
          id: 5,
          name: "Credit Request",
          link: "#",
          isOpen: false,
          permission: "manage credits",
          subItems: [
            { id: 7, name: "Credit Requests", link: "/api/admin/creditReq", permission: "manage credits" },
            { id: 8, name: "Withdrawal Requests", link: "/api/admin/transactions", permission: "manage withdrawals" },
          ],
        },
        {
          id: 6,
          name: "Purchase List",
          link: "#",
          isOpen: false,
          permission: "view purchases",
          subItems: [
            { id: 7, name: "Lottery 1", link: "/api/purchase", permission: "view purchases" },
          ],
        },

        {
          id: 7,
          name: "Wallet History",
          link: "/api/admin/walletHistory",
          isOpen: false,
          permission: "view wallet history"
        },
        // {
        //   id: 7,
        //   name: "Wallet History",
        //   link: "#",
        //   isOpen: false,
        //   permission: "view wallet history",
        //   subItems: [
        //     { id: 7, name: "Transaction History", link: "/api/admin/walletHistory", permission: "view transactions" },
        //   ],
        // },
        {
          id: 13,
          name: "Reports",
          link: "#",
          isOpen: false,
          permission: "manage reports",
          subItems: [
            { id: 3, name: "Players", link: "/api/admin/player-reports", permission: "manage reports" },
            { id: 4, name: "Admins", link: "/api/admin/Admin-reports", permission: "manage reports" },
            { id: 5, name: "Lotteries", link: "/api/admin/Lottery-reports", permission: "manage reports" },
            { id: 6, name: "Expired Dashboards", link: "/api/admin/Lottery-Expired", permission: "manage reports" },
            { id: 6, name: "Cancelled Dashboards", link: "/api/admin/Lottery-Cancelled", permission: "manage reports" },
        
            
          ],
        },
      ],
      logoUrl: '/assets/images/profile.png',
    };
  },
  computed: {
    visibleMenuItems() {
      return this.menuItems
        .map(item => {
          // Create a shallow copy of the item to avoid direct mutation
          const menuItem = { ...item };
          
          // Process subItems if they exist
          if (menuItem.subItems && menuItem.subItems.length) {
            menuItem.subItems = menuItem.subItems.filter(subItem => 
              this.hasPermission(subItem.permission)
            );
          }
          
          return menuItem;
        })
        .filter(item => {
          // Show menu item if:
          // 1. It has no subItems AND user has permission
          // OR
          // 2. It has subItems AND at least one subItem is visible
          const hasPermission = this.hasPermission(item.permission);
          const hasVisibleSubItems = item.subItems && item.subItems.length > 0;
          
          return (!item.subItems || !item.subItems.length) 
            ? hasPermission 
            : hasVisibleSubItems;
        });
    }
  },
  methods: {
    hasPermission(requiredPermission) {
      if (!requiredPermission) return true; // No permission required
      
      // Normalize the required permission
      const requiredPerm = requiredPermission.trim().toLowerCase();
      
      // Check against user permissions
      return this.userPermissions.some(userPerm => {
        const userPermNormalized = userPerm.trim().toLowerCase();
        
        // Exact match
        if (userPermNormalized === requiredPerm) return true;
        
        // Wildcard permissions
        if (userPermNormalized === '*.*') return true; // Super admin
        
        // Partial wildcard (e.g., manage.*)
        if (userPermNormalized.endsWith('.*')) {
          const permPrefix = userPermNormalized.split('.')[0];
          return requiredPerm.startsWith(permPrefix);
        }
        
        return false;
      });
    },

    isActive(link) {
      return window.location.pathname === link;
    },
    
    isDropdownActive(item) {
      return item.subItems?.some(subItem => this.isActive(subItem.link));
    },
    
    toggleDropdown(id) {
      const menuItem = this.menuItems.find(item => item.id === id);
      if (menuItem) {
        menuItem.isOpen = !menuItem.isOpen;
        this.saveSidebarState();
      }
    },
    
    toggleSidebar() {
      this.isSidebarVisible = !this.isSidebarVisible;
      this.$emit("sidebar-toggle", this.isSidebarVisible);
    },
    
    saveSidebarState() {
      localStorage.setItem('sidebarMenuState', JSON.stringify(this.menuItems));
    },
    
    loadSidebarState() {
      const savedState = localStorage.getItem('sidebarMenuState');
      if (savedState) {
        try {
          const parsedState = JSON.parse(savedState);
          this.menuItems = this.menuItems.map(item => {
            const savedItem = parsedState.find(si => si.id === item.id);
            return savedItem ? { ...item, isOpen: savedItem.isOpen } : item;
          });
        } catch (e) {
          console.error('Failed to parse saved sidebar state', e);
        }
      }
    },
    
    async fetchUserPermissions() {
      try {
        const response = await axios.get('/api/admin/permissions');
        this.userPermissions = response.data.permissions || [];
      } catch (error) {
        console.error('Error fetching permissions:', error);
      } finally {
        this.loading.permissions = false;
      }
    },
    
    async fetchLotteryData() {
      this.loading.lotteries = true;
      try {
        const response = await axios.get("/api/admin/sidebar/lotteries");
        const lotteries = response.data;
        this.updateMenuWithLotteries(lotteries);
      } catch (error) {
        console.error("Error fetching lottery data:", error);
      } finally {
        this.loading.lotteries = false;
      }
    },
    
    updateMenuWithLotteries(lotteries) {
      const lotteryMenuUpdates = [
        {
          name: "Lottery Dashboards",
          permission: "manage dashboards",
          linkTemplate: "/api/admin/lottery/dashboard/{id}"
        },
        {
          name: "Purchase List",
          permission: "view purchases",
          linkTemplate: "/api/admin/purchase/{id}"
        },
        {
          name: "Winners",
          permission: "manage winners",
          linkTemplate: "/api/admin/adminWin/{id}"
        }
      ];

      lotteryMenuUpdates.forEach(menuUpdate => {
        const menuItem = this.menuItems.find(item => item.name === menuUpdate.name);
        if (menuItem) {
          menuItem.subItems = lotteries.map(lottery => ({
            id: lottery.id,
            name: lottery.name,
            link: menuUpdate.linkTemplate.replace('{id}', lottery.id),
            permission: menuUpdate.permission
          }));
        }
      });
    },
    
    async logout() {
      try {
        const response = await axios.post('/api/admin/logout');
        if (response.status === 200 && response.data.redirect_url) {
          window.location.href = response.data.redirect_url;
        }
      } catch (error) {
        console.error('Logout failed:', error);
      }
    },
  },
  async created() {
    try {
      await this.fetchUserPermissions();
      this.loadSidebarState();
      
      // Only fetch lottery data if we have relevant permissions
      if (this.hasPermission('manage dashboards') || 
          this.hasPermission('view purchases') || 
          this.hasPermission('manage winners')) {
        this.fetchLotteryData();
      }
    } catch (error) {
      console.error('Initialization error:', error);
      this.loading.permissions = false;
    }
  }
};
</script>
<style scoped>
.logout-btn {
  background: none;
  border: none;
  color: inherit;
  cursor: pointer;
  width: 100%;
  text-align: left;
  padding: 0.5rem 1rem;
}

.logout-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .logout-btn {
    text-align: center;
  }
}


.sidebar {
  width: 250px;
  position: fixed;
  height: 100vh;
  padding: 20px;
  background-color: #343a40;
  color: #fff;
  transition: transform 0.3s ease;
  z-index: 1000;
  /* Add these properties for scrollable behavior */
  overflow-y: auto;
  overflow-x: hidden;
  top: 0;
  left: 0;
}

/* Optional: Style the scrollbar to match your design */
.sidebar::-webkit-scrollbar {
  width: 5px;
}

.sidebar::-webkit-scrollbar-track {
  background: #2c2c2c;
}

.sidebar::-webkit-scrollbar-thumb {
  background: #555;
  border-radius: 5px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background: #777;
}

.sidebar.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.profile-photo {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-left: auto;
}

.sidebar-header h2 {
  font-size: 1.5em;
  margin: 0;
}

.nav-item .nav-link {
  color: #fff;
  padding: 10px 15px;
  display: block;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.nav-item .nav-link:hover {
  background-color: #444;
}

.nav-item .nav-link.active {
  background-color: #555;
}

.nav .nav-link {
  padding-left: 30px;
}

.sidebar-toggle-btn {
  position: absolute;
  top: 20px;
  left: 20px;
  background: transparent;
  border: none;
  color: #7e3030;
  font-size: 1.5em;
  cursor: pointer;
  z-index: 1100;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    position: fixed;
  }

  .sidebar.sidebar-hidden {
    transform: translateX(-100%);
  }
}

/* Smooth Dropdown Animation */
.submenu-transition-enter-active,
.submenu-transition-leave-active {
  transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.submenu-transition-enter,
.submenu-transition-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
