<template>
    <div id="app" class="d-flex dark-theme">
        <Sidebar @sidebar-toggle="handleSidebarToggle" />
        <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill">
            <div class="dashboard-banner">
                <div class="navbar">
                    <h2 class="lottery-name fw-bold text-danger">Database Backup</h2>
                </div>

                <div class="lottery-table">
                    <button @click="backupDatabase" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">
                            <i class="fas fa-spinner fa-spin"></i> Processing...
                        </span>
                        <span v-else>
                            <i class="fas fa-database"></i> Backup & Download Database
                        </span>
                    </button>
                    <div v-if="error" class="alert alert-danger mt-3">
                        {{ error }}
                        <div v-if="errorDetails" class="mt-2 small">{{ errorDetails }}</div>
                    </div>
                    <div v-if="success" class="alert alert-success mt-3">{{ success }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/components/AdminSidebar.vue';
import axios from 'axios';

export default {
    components: {
        Sidebar,
    },
    data() {
        return {
            isSidebarVisible: true,
            loading: false,
            error: null,
            errorDetails: null,
            success: null
        };
    },
    methods: {
    handleSidebarToggle(isVisible) {
        this.isSidebarVisible = isVisible;
    },
    
    async backupDatabase() {
        this.loading = true;
        this.error = null;
        this.errorDetails = null;
        this.success = null;
        
        // Clear any existing timeouts
        if (this.messageTimeout) {
            clearTimeout(this.messageTimeout);
        }
        
        try {
           const response = await axios({
            url: route('admin.backup'),
            method: 'GET',
            responseType: 'blob',
            withCredentials: true, // Add this line
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                // If using token auth:
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        });

            if (response.data instanceof Blob) {
                const url = window.URL.createObjectURL(response.data);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `database-backup-${new Date().toISOString().split('T')[0]}.sql`);
                document.body.appendChild(link);
                link.click();
                
                window.URL.revokeObjectURL(url);
                link.remove();
                
                this.success = 'Database backup downloaded successfully!';
                
                // Set timeout to clear success message after 3 seconds
                this.messageTimeout = setTimeout(() => {
                    this.success = null;
                }, 3000);
            } else {
                const errorData = JSON.parse(await new Response(response.data).text());
                throw new Error(errorData.message || 'Backup failed');
            }
            
        } catch (error) {
            console.error('Backup failed:', error);
            this.error = 'Backup failed. Please try again.';
            
            if (error.response) {
                try {
                    const errorData = JSON.parse(await error.response.data.text());
                    this.error = errorData.message || this.error;
                    this.errorDetails = errorData.error || null;
                } catch (e) {
                    // If we can't parse the error, use the default message
                }
            } else if (error.message) {
                this.error = error.message;
            }
            
            // Set timeout to clear error messages after 3 seconds
            this.messageTimeout = setTimeout(() => {
                this.error = null;
                this.errorDetails = null;
            }, 3000);
        } finally {
            this.loading = false;
        }
    }
}
};
</script>

<style scoped>
/* Your existing styles... */
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

.lottery-table {
    margin-top: 20px;
}

.btn-primary {
    background-color: #0d6efd;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background-color: #0b5ed7;
}

.btn-primary:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

.alert {
    padding: 0.75rem 1.25rem;
    border-radius: 0.25rem;
    margin-top: 1rem;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.small {
    font-size: 0.875rem;
}
</style>