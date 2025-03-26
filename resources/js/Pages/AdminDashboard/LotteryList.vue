<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <!-- Dashboard Widgets -->
            <div class="dashboard-banner">
                <!-- Top Navbar Section -->
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Win Board Game</h2>
                    <button @click="openModal" class="btn btn-primary">Create a Lottery</button>
                </div>

                <!-- Lottery Table Section -->
                <div class="lottery-table">
                    <h3 class="mt-4">Lottery Details:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Actions</th> <!-- New header for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(lottery, index) in lottery" :key="index">
                                <td><a href="#">{{ lottery.id }}</a></td>
                                <td><a href="#">{{ lottery.name }}</a></td>
                                <td>{{ lottery.description }}</td>
                                <td> <img :src="`public/${lottery.image}`" alt="Lottery Image" width="50" height="50" />
                                </td>

                                <td>
                                    <!-- Edit Button -->
                                    <button @click="editLottery(lottery)" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button @click="confirmDelete(lottery)" class="btn btn-danger btn-sm mx-2">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <!-- Modal for Creating a Lottery -->
                <div v-if="isModalOpen" class="modal-overlay">
                    <div class="modal-content">
                        <h3>Create a New Lottery</h3>
                        <form @submit.prevent="createLottery">
                            <div class="form-group">
                                <label for="name">Lottery Name:</label>
                                <input type="text" v-model="newLottery.name" id="name" required />
                            </div>
                            <div class="form-group">
                                <label for="description" class="font-weight-bold ">Description:</label>
                                <textarea v-model="newLottery.description" id="description" class="form-control"
                                    rows="4" placeholder="Enter description here..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="colorPicker">Pick a Color:</label>
                                <input type="color" id="colorPicker" v-model="newLottery.color">
                                <span>{{ newLottery.color }}</span>
                            </div>


                            <div class="form-group">
                                <label for="image">Lottery Image:</label>
                                <input type="file" @change="handleImageUpload" id="image" accept="image/*" required />
                            </div>


                            <button type="submit" class="btn btn-success mx-3">Create Lottery</button>
                            <button type="button" @click="closeModal" class="btn btn-danger mx-4">Cancel</button>
                        </form>
                    </div>
                </div>

                <!-- Modal for Editing a Lottery -->

                <div v-if="isEditModalOpen" class="modal-overlay">
                    <div class="modal-content">
                        <h3>Edit Lottery</h3>
                        <form @submit.prevent="updateLottery">
                            <div class="form-group">
                                <label for="ename">Lottery Name:</label>
                                <input type="text" v-model="editingLottery.name" id="name" required />
                                <!-- Display validation error if name is missing -->
                                <span v-if="validationErrors.name" class="text-danger">{{ validationErrors.name[0]
                                }}</span>
                            </div>
                            <div class="form-group">
                                <label for="edescription">Description:</label>
                                <textarea v-model="editingLottery.description" id="description" required></textarea>
                                <!-- Display validation error if description is missing -->
                                <span v-if="validationErrors.description" class="text-danger">{{
                                    validationErrors.description[0] }}</span>
                            </div>
                            <div class="form-group">
                                <label for="color">Lottery Color:</label>
                                <input type="color" v-model="editingLottery.color" id="color" />
                            </div>


                            <div class="form-group">
                                <label for="eimage">Lottery Image URL:</label>
                                <input type="text" v-model="editingLottery.image" id="edit-image" />
                            </div>
                            <div class="form-group">
                                <label for="image">Lottery Image:</label>
                                <input type="file" @change="handleImageUpload" id="image" accept="image/*" />
                            </div>

                            <button type="submit" class="btn btn-success mx-3">Update Lottery</button>
                            <button type="button" @click="closeEditModal" class="btn btn-danger mx-4">Cancel</button>
                        </form>
                    </div>
                </div>



                <!-- Confirmation Modal for Deleting -->
                <div v-if="isDeleteModalOpen" class="modal-overlay">
                    <div class="modal-content">
                        <h3>Are you sure you want to delete this lottery?</h3>
                        <div class="row align-items-center justify-content-center">
                            <button @click="deleteLottery" class=" btn btn-danger mx-2 col-5">Yes, Delete</button>
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
import Sidebar from '@/components/AdminSidebar.vue';

import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');



export default {
    components: {
        Sidebar,
    },
    data() {
        return {
            isSidebarVisible: true,
            isModalOpen: false,
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            lottery: [],
            newLottery: {
                name: '',
                description: '',
                image: null,
                price: '',
                color: '#000000'
            },

            editingLottery: {},
            csrfToken: document.querySelector('meta[name="csrf-token"]') ?
                document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            validationErrors: {},
        };
    },
    mounted() {
        this.lottery = this.$page.props.lotteries;
    }

    ,
    methods: {
        updateColor(color) {
            document.getElementById("selectedColorCode").textContent = color;
            console.log("Selected Color:", color);
        },
        handleSidebarToggle(isVisible) {
            this.isSidebarVisible = isVisible;
        },
        openModal() {
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.newLottery.image = file;
            }
        },
        async createLottery() {
            try {
                const formData = new FormData();
                formData.append('name', this.newLottery.name);
                formData.append('description', this.newLottery.description);
                formData.append('image', this.newLottery.image);
                formData.append('color', this.newLottery.color);

                // Check for null or undefined values and alert if any are found
                if (!this.newLottery.name) {
                    alert("Name is null or undefined");
                    return;
                }

                if (!this.newLottery.description) {
                    alert("Description is null or undefined");
                    return;
                }

                if (!this.newLottery.image) {
                    alert("Image is null or undefined");
                    return;
                }

                if (!this.newLottery.color) {
                    alert("Color is null or undefined");
                    return;
                }

                // Get the CSRF token directly from the meta tag (document object)
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Correcting the CSRF Token Reference
                console.log(csrfToken);  // Log to verify if CSRF token exists
                console.log(document.cookie);  // Log cookies to check if CSRF token is stored there

                const response = await axios.post('/api/admin/lottery/create', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': csrfToken,  // Passing the CSRF token directly from the meta tag
                    },
                    withCredentials: true,  // Ensuring that cookies are sent with the request
                });

                console.log(response.data);


                if (response.status === 201) {

                    this.lottery.push(response.data);

                    this.isModalOpen = false;
                    window.location.reload();
                } else {
                    alert("Failed to create lottery. Please try again.");
                }
            } catch (error) {
                console.error('Error creating lottery:', error);
            }
        }

        ,
        editLottery(lottery) {
            this.editingLottery = { ...lottery };
            this.isEditModalOpen = true;
        },
        closeEditModal() {
            this.isEditModalOpen = false;
        },
        updateLottery() {
            const index = this.lotteries.findIndex(l => l.id === this.editingLottery.id);
            if (index !== -1) {
                this.lotteries.splice(index, 1, this.editingLottery);
            }
            this.closeEditModal();
        },
        confirmDelete(lottery) {
            this.editingLottery = lottery;
            this.isDeleteModalOpen = true;
        },
        closeDeleteModal() {
            this.isDeleteModalOpen = false;
        },
        deleteLottery() {
            this.lotteries = this.lotteries.filter(l => l.id !== this.editingLottery.id);
            this.closeDeleteModal();
        },
        async updateLottery() {
            // Frontend validation
            if (!this.editingLottery.name || !this.editingLottery.description) {
                alert("Please fill out both name and description.");
                return;
            }

            try {
                const formData = new FormData();
                formData.append('name', this.editingLottery.name);
                formData.append('description', this.editingLottery.description);
                formData.append('image', this.editingLottery.image);
                formData.append('color', this.editingLottery.color);

                // Make the update request
                const csrfToken = this.csrfToken;
                const response = await axios.put(`http://127.0.0.1:8000/api/admin/lottery/update/${this.editingLottery.id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    withCredentials: true,
                });

                if (response.status === 200) {
                    const index = this.lottery.findIndex(l => l.id === this.editingLottery.id);
                    if (index !== -1) {
                        this.lottery.splice(index, 1, response.data);
                    }
                    this.closeEditModal();
                } else {
                    alert("Failed to update lottery. Please try again.");
                }
            } catch (error) {
                console.error('Error updating lottery:', error);
                if (error.response && error.response.data) {
                    // Display validation errors directly in the modal
                    if (error.response.data.errors) {
                        this.validationErrors = error.response.data.errors;
                    }
                    console.error('Validation errors:', error.response.data.errors);
                    alert("Validation error occurred: " + JSON.stringify(error.response.data.errors));
                }
            }

        },
        async deleteLottery() {
            try {
                const response = await axios.delete(`http://127.0.0.1:8000/api/admin/lottery/delete/${this.editingLottery.id}`, {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                });

                if (response.status === 200) {
                    this.lottery = this.lottery.filter(l => l.id !== this.editingLottery.id); // Update frontend data
                    this.closeDeleteModal();
                } else {
                    alert("Failed to delete lottery. Please try again.");
                }
            } catch (error) {
                console.error('Error deleting lottery:', error);
            }
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