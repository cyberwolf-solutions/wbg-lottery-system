<template>
  <div>
    <button @click="toggleChat"
      class="fixed bottom-4 right-4 p-3 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
    </button>

    <div v-if="showChat"
      class="fixed bottom-16 right-4 bg-white w-80 h-96 shadow-lg rounded-lg flex flex-col border border-gray-200">
      <div class="p-3 border-b font-bold bg-blue-600 text-white rounded-t-lg flex justify-between items-center">
        <span>Customer Support</span>
        <button @click="toggleChat" class="text-white hover:text-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <div ref="messagesContainer" class="flex-1 overflow-y-auto p-3 space-y-2">
        <div v-for="msg in messages" :key="msg.id" :class="msg.is_from_user ? 'justify-end' : 'justify-start'"
          class="flex">
          <div :class="msg.is_from_user ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="max-w-xs p-2 rounded-lg">
            {{ msg.message }}
            <div :class="msg.is_from_user ? 'text-blue-100' : 'text-gray-500'" class="text-xs mt-1">
              {{ formatTime(msg.created_at) }}
              <span v-if="!msg.is_from_user"> • Admin</span>
            </div>
          </div>
        </div>
      </div>

      <div class="p-3 border-t">
        <form @submit.prevent="sendMessage" class="flex space-x-2">
          <input v-model="newMessage"
            class="flex-1 border p-2 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
            placeholder="Type message..." required />
          <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">
            Send
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted, watch, onUnmounted } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const showChat = ref(false);
const newMessage = ref('');
const messagesContainer = ref(null);
const messages = ref([]);
const echo = ref(null);
const fetchInterval = ref(null);

const props = defineProps({
  user: Object
});

const toggleChat = () => {
  showChat.value = !showChat.value;
  if (showChat.value) {
    startMessagePolling();
    setupEcho();
  } else {
    stopMessagePolling();
  }
};

// Start polling messages only if needed (no need to poll if Echo is already listening)
const startMessagePolling = () => {
  fetchMessages(); // Initial fetch
  fetchInterval.value = setInterval(fetchMessages, 1000); // Fetch every second
};

// Stop polling when chat is closed or if Echo is enabled
const stopMessagePolling = () => {
  if (fetchInterval.value) {
    clearInterval(fetchInterval.value);
    fetchInterval.value = null;
  }
};

const fetchMessages = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie');
    const response = await axios.get('/api/messages', {
      withCredentials: true,
    });

    // Only update if messages have changed
    if (JSON.stringify(messages.value) !== JSON.stringify(response.data.messages)) {
      messages.value = response.data.messages;
      scrollToBottom();
    }
  } catch (error) {
    console.error('Error fetching messages:', error.response?.data || error.message);
  }
};

const sendMessage = async () => {
  try {
    const response = await axios.post('/api/messages/store', {
      message: newMessage.value,
    });

    newMessage.value = '';
    // No need to fetch messages manually, Echo will handle it
    scrollToBottom();
  } catch (error) {
    console.error('Error sending message:', error);
  }
};

const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
};

const setupEcho = () => {
  window.Pusher = Pusher;
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authEndpoint: '/user/broadcasting/auth',
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    },
  });

  const userId = props.user?.id;

  if (userId) {
    window.Echo.private(`user.${userId}`)
      .listen('NewMessage', (data) => {
        console.log('New message received:', data);
        const exists = messages.value.some(m => m.id === data.message.id);
        if (!exists) {
          messages.value.push({
            ...data.message,
            is_from_user: data.message.user_id === userId
          });
          scrollToBottom();
        }
      })
      .error((error) => {
        console.error('Echo subscription error:', error);
      });
  }
};

onMounted(() => {
  if (showChat.value) {
    startMessagePolling();
    setupEcho();
  }
});

onUnmounted(() => {
  stopMessagePolling();
  if (window.Echo) {
    window.Echo.leave(`user.${window.Laravel.user.id}`);
  }
});
</script>
