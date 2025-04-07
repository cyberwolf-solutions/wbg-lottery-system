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

const submit = async () => {
    errorMessage.value = '';
    isLoading.value = true;

    try {
        const response = await axios.post('/api/admin/login', form, {
            withCredentials: true
        });
        window.location.href = "/api/admin/dashboard";
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
                    <input 
                        type="email" 
                        id="email" 
                        v-model="form.email" 
                        placeholder="Enter your email" 
                        required 
                    />
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        v-model="form.password" 
                        placeholder="Enter your password" 
                        required 
                    />
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember_me" v-model="form.remember_me" />
                    <label for="remember_me">Remember Me</label>
                </div>

                <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
                <button 
                    type="submit" 
                    class="btn-submit" 
                    :disabled="isLoading"
                >
                    {{ isLoading ? "Logging in..." : "Login" }}
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* Base styles */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f0f2f5;
    padding: 20px;
}

.login-box {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    width: 100%;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

h2 {
    text-align: center;
    font-size: 1.75rem;
    color: #333;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.input-group {
    margin-bottom: 1.25rem;
}

.input-group label {
    display: block;
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.input-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.input-group input:focus {
    outline: none;
    border-color: #00A045;
    box-shadow: 0 0 0 3px rgba(0, 160, 69, 0.1);
}

.error-message {
    color: #d32f2f;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    text-align: center;
}

.btn-submit {
    width: 100%;
    padding: 0.875rem;
    background-color: #00A045;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #007a2f;
}

.btn-submit:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.remember-me {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.remember-me input {
    margin-right: 0.5rem;
    accent-color: #00A045;
}

.remember-me label {
    font-size: 0.9rem;
    color: #555;
}

/* Responsive Design */
@media (max-width: 480px) {
    .login-box {
        padding: 1.5rem;
        max-width: 90vw; /* Takes up most of the viewport width */
    }

    h2 {
        font-size: 1.5rem;
    }

    .input-group input {
        padding: 0.875rem;
        font-size: 1.1rem; /* Larger text for mobile */
    }

    .btn-submit {
        padding: 1rem;
        font-size: 1.1rem;
    }
}

@media (min-width: 481px) and (max-width: 768px) {
    .login-box {
        max-width: 450px;
        padding: 2rem;
    }
}

@media (min-width: 769px) {
    .login-box {
        max-width: 480px;
        padding: 2.5rem;
    }
}

/* Animation for smooth loading */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.login-box {
    animation: fadeIn 0.5s ease-in;
}
</style>