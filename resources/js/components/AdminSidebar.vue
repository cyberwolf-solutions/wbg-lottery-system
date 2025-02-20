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
                <li v-for="item in menuItems" :key="item.id" class="nav-item">
                    <a v-if="!item.subItems" :href="item.link" class="nav-link" :class="{ active: isActive(item.link) }"
                        :aria-label="'Go to ' + item.name">
                        {{ item.name }}
                    </a>
                    <a v-else href="#" @click="toggleDropdown(item.id)" class="nav-link"
                        :class="{ active: isActive(item.link) }" :aria-label="'Go to ' + item.name">
                        {{ item.name }}
                        <!-- Icon Toggle -->
                        <i v-if="!item.isOpen" class="fas fa-chevron-down float-end"></i>
                        <i v-if="item.isOpen" class="fas fa-chevron-up float-end"></i>
                    </a>

                    <!-- Submenu Items with smooth transition -->
                    <transition name="submenu-transition">
                        <ul v-if="item.isOpen && item.subItems && item.subItems.length" class="nav flex-column ms-3">
                            <li v-for="subItem in item.subItems" :key="subItem.id" class="nav-item">
                                <a :href="subItem.link" class="nav-link">{{ subItem.name }}</a>
                            </li>
                        </ul>
                    </transition>
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
            isSidebarVisible: true, // Sidebar visibility state
            menuItems: [
                {
                    id: 1,
                    name: "Dashboard",
                    link: "http://127.0.0.1:8000/api/adminDash",
                    isOpen: false,

                },
                {
                    id: 2,
                    name: "Users",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 3, name: "Users", link: "http://127.0.0.1:8000/api/users" },
                        { id: 4, name: "Roles", link: "http://127.0.0.1:8000/api/Roles" },
                        { id: 5, name: "Customers", link: "http://127.0.0.1:8000/api/customers" }
                    ],
                },
                {
                    id: 8,
                    name: "Lotteries",
                    link: "http://127.0.0.1:8000/api/admin/list",
                    isOpen: false,

                },
                {
                    id: 3,
                    name: "Lottery Dashboards",
                    link: "#",
                    isOpen: false,
                    subItems: [],  // Empty initially
                },
                {
                    id: 4,
                    name: "Winners",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 5, name: "Lottery 1", link: "http://127.0.0.1:8000/api/adminWin" },
                        { id: 6, name: "Lottery 2", link: "http://127.0.0.1:8000/api/adminWin" },
                    ],
                },
                {
                    id: 5,
                    name: "Credit Request",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 7, name: "Requests", link: "http://127.0.0.1:8000/api/creditReq" },
                        { id: 8, name: "Transactions", link: "http://127.0.0.1:8000/api/transactions" },
                    ],
                },
                {
                    id: 6,
                    name: "Purchase List",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 7, name: "Lottery 1", link: "http://127.0.0.1:8000/api/purchase" },
                        { id: 8, name: "Lottery 2", link: "http://127.0.0.1:8000/api/purchase" },
                    ],
                },
                {
                    id: 7,
                    name: "Wallet History",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 7, name: "Transaction History", link: "http://127.0.0.1:8000/api/walletHistory" },

                    ],
                },
            ],
            logoUrl: '/assets/images/profile.png',
        };
    },
    methods: {
        isActive(link) {
            return window.location.href.includes(link);
        },

        toggleDropdown(id) {
            const menuItem = this.menuItems.find((item) => item.id === id);
            menuItem.isOpen = !menuItem.isOpen;
        },
        toggleSidebar() {
            this.isSidebarVisible = !this.isSidebarVisible;
            this.$emit("sidebar-toggle", this.isSidebarVisible); // Emit event to update the main content layout
        },
        async fetchLotteryData() {
            try {
                const response = await axios.get("http://127.0.0.1:8000/api/admin/sidebar/lotteries");  // Your API endpoint for fetching lotteries
                const lotteries = response.data;  // Assuming the response is an array of lottery objects

                // Update the 'Lottery Dashboards' menu with fetched data
                const lotteryDashboard = this.menuItems.find(item => item.name === "Lottery Dashboards");
                lotteryDashboard.subItems = lotteries.map(lottery => ({
                    id: lottery.id,
                    name: lottery.name,
                    link: `http://127.0.0.1:8000/api/admin/lottery/dashboard/${lottery.id}`,
                }));
            } catch (error) {
                console.error("Error fetching lottery data:", error);
            }
        },
       

    },
    // Fetch lottery data when component is mounted
    mounted() {
        this.fetchLotteryData();
    },


};
</script>

<style scoped>
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
