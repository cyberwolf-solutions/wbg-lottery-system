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
                <!-- <img :src="logoUrl" alt="Logo" height="80" width="100" /> -->

            </div>

            <!-- Sidebar Menu -->
            <ul class="nav flex-column mt-5">
                <li v-for="item in menuItems" :key="item.id" class="nav-item">
                    <a href="#" @click="toggleDropdown(item.id)" class="nav-link" :class="{ active: isActive(item) }"
                        :aria-label="'Go to ' + item.name">
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
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 1, name: "Submenu 1", link: "#" },
                        { id: 2, name: "Submenu 2", link: "#" },
                    ],
                },
                {
                    id: 2,
                    name: "Profile",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 3, name: "Submenu 3", link: "#" },
                        { id: 4, name: "Submenu 4", link: "#" },
                    ],
                },
                {
                    id: 3,
                    name: "Settings",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 5, name: "Submenu 5", link: "#" },
                        { id: 6, name: "Submenu 6", link: "#" },
                    ],
                },
                {
                    id: 4,
                    name: "Reports",
                    link: "#",
                    isOpen: false,
                    subItems: [
                        { id: 7, name: "Submenu 7", link: "#" },
                        { id: 8, name: "Submenu 8", link: "#" },
                    ],
                },
            ],
            logoUrl: '/assets/images/profile.png',
        };
    },
    methods: {
        isActive(item) {
            return window.location.href.includes(item.link);
        },
        toggleDropdown(id) {
            const menuItem = this.menuItems.find((item) => item.id === id);
            menuItem.isOpen = !menuItem.isOpen;
        },
        toggleSidebar() {
            this.isSidebarVisible = !this.isSidebarVisible;
            this.$emit("sidebar-toggle", this.isSidebarVisible); // Emit event to update the main content layout
        },
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

.submenu-transition-enter, .submenu-transition-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
