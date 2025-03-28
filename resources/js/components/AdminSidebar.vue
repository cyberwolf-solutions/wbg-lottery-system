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
            <ul class="nav flex-column mt-5">
                <li v-for="item in filteredMenuItems" :key="item.id" class="nav-item">
                    <a v-if="!item.subItems" :href="item.link" class="nav-link" :class="{ active: isActive(item.link) }"
                        :aria-label="'Go to ' + item.name" v-show="hasPermission(item.permission)">
                        {{ item.name }}
                    </a>
                    <a v-else href="#" @click="toggleDropdown(item.id)" class="nav-link"
                        :class="{ active: isActive(item.link) }" :aria-label="'Go to ' + item.name"
                        v-show="hasPermission(item.permission)">
                        {{ item.name }}
                        <!-- Icon Toggle -->
                        <i v-if="!item.isOpen" class="fas fa-chevron-down float-end"></i>
                        <i v-if="item.isOpen" class="fas fa-chevron-up float-end"></i>
                    </a>

                    <!-- Submenu Items with smooth transition -->
                    <transition name="submenu-transition">
                        <ul v-if="item.isOpen && item.subItems && item.subItems.length" class="nav flex-column ms-3">
                            <li v-for="subItem in item.subItems" :key="subItem.id" class="nav-item">
                                <a :href="subItem.link" class="nav-link" v-show="hasPermission(subItem.permission)">{{
                                    subItem.name }}</a>
                            </li>
                        </ul>
                    </transition>
                </li>
                <li class="nav-item">
                    <button @click="logout" class="nav-link logout-btn" aria-label="Logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </li>
            </ul>
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
                        // { id: 5, name: "Customers", link: "/api/customers", permission: "manage customers" }
                    ],
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
                    link: "#",
                    isOpen: false,
                    permission: "view wallet history",
                    subItems: [
                        { id: 7, name: "Transaction History", link: "/api/admin/walletHistory", permission: "view transactions" },
                    ],
                },
            ],
            logoUrl: '/assets/images/profile.png',
        };
    },
    computed: {
        filteredMenuItems() {
            return this.menuItems.map(item => {
                if (item.subItems) {
                    return {
                        ...item,
                        subItems: item.subItems.filter(subItem => this.hasPermission(subItem.permission))
                    };
                }
                return item;
            });
        }
    },
    methods: {
        hasPermission(permission) {
            if (!permission) return true; // Show if no permission required
            return this.userPermissions.includes(permission);
        },
        isActive(link) {
            return window.location.href.includes(link);
        },
        toggleDropdown(id) {
            const menuItem = this.menuItems.find((item) => item.id === id);
            menuItem.isOpen = !menuItem.isOpen;

            // Save the updated state to localStorage
            localStorage.setItem('sidebarMenuState', JSON.stringify(this.menuItems));
        }
,
        toggleSidebar() {
            this.isSidebarVisible = !this.isSidebarVisible;
            this.$emit("sidebar-toggle", this.isSidebarVisible);
        },
        async fetchUserPermissions() {
            try {
                const response = await axios.get('/api/admin/permissions');
                this.userPermissions = response.data.permissions || [];
                console.log('User Permissions:', this.userPermissions);

            } catch (error) {
                console.error('Error fetching permissions:', error);
            }
        },
        async fetchLotteryData() {
            try {
                const response = await axios.get("/api/admin/sidebar/lotteries");
                const lotteries = response.data;

                const lotteryDashboard = this.menuItems.find(item => item.name === "Lottery Dashboards");
                lotteryDashboard.subItems = lotteries.map(lottery => ({
                    id: lottery.id,
                    name: lottery.name,
                    link: `/api/admin/lottery/dashboard/${lottery.id}`,
                    permission: "manage dashboards"
                }));

                const purchaseList = this.menuItems.find(item => item.name === "Purchase List");
                purchaseList.subItems = lotteries.map(lottery => ({
                    id: lottery.id,
                    name: lottery.name,
                    link: `/api/admin/purchase/${lottery.id}`,
                    permission: "view purchases"
                }));

                const winnersSection = this.menuItems.find(item => item.name === "Winners");
                winnersSection.subItems = lotteries.map(lottery => ({
                    id: lottery.id,
                    name: lottery.name,
                    link: `/api/admin/adminWin/${lottery.id}`,
                    permission: "manage winners"
                }));
            } catch (error) {
                console.error("Error fetching lottery data:", error);
            }
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
    async mounted() {
    await this.fetchUserPermissions();
    this.fetchLotteryData();

    // Load the sidebar state from localStorage if it exists
    const savedState = localStorage.getItem('sidebarMenuState');
    if (savedState) {
        this.menuItems = JSON.parse(savedState);
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
