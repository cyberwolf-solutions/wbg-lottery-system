<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

// Define form with Inertia.js
const form = useForm({
    email: '',
    password: '',
    remember_me: false,
});

const errorMessage = ref('');
const isLoading = ref(false);

// Redirect to dashboard if the user is already authenticated (via cookie)
// axios.get('/api/admin/check-auth')
//     .then(() => window.location.href = "/api/admin/dashboard")
//     .catch(() => { /* Do nothing, allow login */ });
// axios.get('/api/admin/check-auth', { withCredentials: true })
//     .then(() => window.location.href = "/api/admin/dashboard")
//     .catch(() => { /* Do nothing, allow login */ });


const submit = async () => {
    errorMessage.value = '';
    isLoading.value = true;

    try {
        const response = await axios.post('/api/admin/login', form, {
            withCredentials: true // Ensures cookies are sent
        });

        window.location.href = "/api/admin/dashboard"; // Redirect on success
    } catch (error) {
        if (error.response?.status === 401) {
            errorMessage.value = "Invalid credentials. Please try again.";
        } else {
            errorMessage.value = "An unexpected error occurred. Please try later.";
        }
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <form @submit.prevent="submit">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" v-model="form.email" placeholder="Enter your email" required />
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" v-model="form.password" placeholder="Enter your password" required />
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember_me" v-model="form.remember_me" />
                    <label for="remember_me">Remember Me</label>
                </div>

                <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
                <button type="submit" class="btn-submit" :disabled="isLoading">
                    {{ isLoading ? "Logging in..." : "Login" }}
                </button>
            </form>
        </div>
    </div>
</template>


<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f0f2f5;
}

.login-box {
    background: white;
    border-radius: 8px;
    padding: 30px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    font-size: 14px;
    color: #555;
}

.input-group input {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.input-group input:focus {
    outline: none;
    border-color: #00A045;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-bottom: 15px;
}

.btn-submit {
    width: 100%;
    padding: 12px;
    background-color: #00A045;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #007a2f;
}

/* Add styling for remember me */
.remember-me {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.remember-me input {
    margin-right: 8px;
}
</style>
