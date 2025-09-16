<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />

        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Send Email</h2>
                </div>

                <div class="email-form-card">
                    <div class="form-group">
                        <label>Select Recipient</label>
                        <input type="text" v-model="searchQuery" class="form-control"
                            placeholder="Search users by name or email" />
                        <select v-model="selectedUser" class="form-control mt-2">
                            <option value="all">All Users</option>
                            <option v-for="user in filteredUsers" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.email }})
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Email Header</label>
                        <input type="text" v-model="emailHeader" class="form-control"
                            placeholder="Enter email header" />
                    </div>

                    <div class="form-group">
                        <label>Email Body</label>
                        <textarea v-model="emailBody" class="form-control" rows="6"
                            placeholder="Enter email body"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Attach Image/Video</label>
                        <input type="file" @change="handleFileUpload" class="form-control" />
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" @click="sendEmail" :disabled="isSubmitting">
                            <span v-if="isSubmitting" class="spinner-border spinner-border-sm"></span>
                            {{ isSubmitting ? 'Sending...' : 'Send Email' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Sidebar from '@/components/AdminSidebar.vue'
import axios from 'axios'

const props = defineProps({
    users: Array
})

const isSidebarVisible = ref(true)
const selectedUser = ref('all')
const emailHeader = ref('')
const emailBody = ref('')
const file = ref(null)
const isSubmitting = ref(false)
const searchQuery = ref('')

const handleSidebarToggle = (visible) => {
    isSidebarVisible.value = visible
}

const handleFileUpload = (event) => {
    file.value = event.target.files[0]
}

const filteredUsers = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.users
    }
    const query = searchQuery.value.toLowerCase()
    return props.users.filter(user =>
        user.name.toLowerCase().includes(query) ||
        user.email.toLowerCase().includes(query)
    )
})

const sendEmail = async () => {
    if (!emailHeader.value || !emailBody.value) {
        alert('Please fill in the header and body.')
        return
    }

    isSubmitting.value = true

    // Collect recipients
    let recipients = []
    if (selectedUser.value === 'all') {
        recipients = props.users.map(u => u.id) // or use u.email if your backend expects emails
    } else {
        recipients = [selectedUser.value]
    }

    const formData = new FormData()
    formData.append('recipients', JSON.stringify(recipients))
    formData.append('header', emailHeader.value)
    formData.append('body', emailBody.value)
    if (file.value) {
        formData.append('file', file.value)
    }

    try {
        await axios.post('/api/admin/send-email', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        alert('Email(s) sent successfully!')
        // Reset form if you want
        emailHeader.value = ''
        emailBody.value = ''
        file.value = null
        selectedUser.value = 'all'
    } catch (error) {
        console.error(error)
        alert('Failed to send email')
    } finally {
        isSubmitting.value = false
    }
}
</script>


<style scoped>
#app.dark-theme {
    background-color: #121212;
    color: #e0e0e0;
    min-height: 100vh;
    font-family: 'Arial', sans-serif;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    background-color: #1e1e1e;
    transition: margin-left 0.3s ease, width 0.3s ease;
    width: calc(100% - 250px);
}

.main-content.sidebar-hidden {
    margin-left: 0;
    width: 100%;
}

.dashboard-banner {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 2px solid #444;
}

.email-form-card {
    background-color: #2c2c2c;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: none;
    background-color: #333;
    color: #e0e0e0;
}

.form-control:focus {
    outline: none;
    border: 1px solid #007bff;
}

.mt-2 {
    margin-top: 8px;
}

.form-control::placeholder {
    color: #f3f2f2;
    /* White placeholder color */
    opacity: 1;
    /* Ensure full opacity for consistency */
}
</style>