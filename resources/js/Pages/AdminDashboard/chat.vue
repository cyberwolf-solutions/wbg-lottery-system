<template>
  <AdminLayout>
    <div id="app" class="d-flex dark-theme h-screen">
      <Sidebar @sidebar-toggle="handleSidebarToggle" />
      <div :class="['main-content', { 'sidebar-hidden': !isSidebarVisible }]" class="flex-fill flex flex-col">
        <h1 class="text-xl mb-4 font-bold p-4">User Messages</h1>
        <div class="flex flex-1 overflow-hidden">
          <!-- Sidebar -->
          <div class="w-1/4 border-r border-gray-700 flex flex-col h-full">
            <!-- Search Bar -->
            <div class="p-2 border-b border-gray-700">
              <input v-model="searchQuery" class="w-full border border-gray-600 bg-gray-800 text-white p-2 rounded"
                placeholder="Search users..." @input="handleSearch" />
            </div>

            <!-- User List -->
            <ul class="flex-1 overflow-y-auto">
              <li v-for="user in filteredUsers" :key="user.id"
                class="cursor-pointer p-2 border-b border-gray-700 hover:bg-gray-800 relative" :class="{
                  'bg-blue-900': selectedUserId === user.id,
                  'bg-gray-800/50': unreadCounts[user.id] > 0
                }" @click="openChat(user.id)">
                <div class="flex justify-between items-center">
                  <span>{{ user.id }} - {{ user.name }}</span>
                  <span v-if="unreadCounts[user.id] > 0" class="bg-red-500 text-white text-xs rounded-full px-2 py-1">
                    {{ unreadCounts[user.id] }}
                  </span>
                </div>
                <div class="text-xs text-gray-400 truncate flex justify-between items-center">
                  <span class="truncate max-w-[70%]">
                    {{ getLastMessagePreview(user.id) }}
                  </span>
                  <span class="ml-2 whitespace-nowrap text-gray-500">
                    {{ getLastMessageTime(user.id) }}
                  </span>
                </div>
              </li>

            </ul>
          </div>

          <!-- Chat Area -->
          <div class="flex-1 flex flex-col h-full">
            <!-- Messages -->
            <!-- Messages -->
            <div v-if="selectedUserId" ref="messagesContainer" class="flex-1 overflow-y-auto p-4">
              <div v-for="msg in selectedMessages" :key="msg.id" class="mb-3" :class="[
                msg.is_from_user ? 'flex justify-start' : 'flex justify-end',
                { 'animate-pulse': msg.isUnread }
              ]">
                <div :class="[
                  msg.is_from_user ? 'bg-gray-700' : 'bg-blue-600 text-white',
                  { 'ring-2 ring-yellow-400': msg.isUnread }
                ]" class="max-w-xs p-3 rounded-lg transition-all duration-200">
                  <div class="text-sm">{{ msg.message }}</div>
                  <div class="text-xs text-gray-400 mt-1 text-right">
                    {{ formatDate(msg.created_at) }}
                    <span v-if="msg.isUnread" class="ml-1 text-yellow-300">•</span>
                  </div>
                </div>
              </div>
              <div ref="scrollAnchor"></div>
            </div>
            <div v-else class="flex-1 flex items-center justify-center text-gray-500">
              Select a user to view messages
            </div>

            <!-- Message Input -->
            <div v-if="selectedUserId" class="p-4 border-t border-gray-700">
              <form @submit.prevent="sendReply" class="flex gap-2">
                <input v-model="newMessage" class="flex-1 border border-gray-600 bg-gray-800 text-white p-2 rounded"
                  placeholder="Reply..." required />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                  Send
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onUnmounted, onMounted, nextTick, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import Sidebar from '@/components/AdminSidebar.vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

const props = defineProps({
  groupedMessages: Object,
  users: Array,
  admin: Object,
})

const selectedUserId = ref(null)
const newMessage = ref('')
const isSidebarVisible = ref(true)
const echo = ref(null)
const searchQuery = ref('')
const messagesContainer = ref(null)
const scrollAnchor = ref(null)
const unreadCounts = ref({})
const lastSeenMessages = ref({})

const localGroupedMessages = ref(
  Object.fromEntries(
    Object.entries(props.groupedMessages).map(([userId, messages]) => {
      return [userId, messages.map(msg => ({
        ...msg,
        isUnread: false // All loaded messages should be marked as read initially
      }))]
    })
  )
)
const getLastMessageTime = (userId) => {
  const messages = localGroupedMessages.value[userId]
  if (!messages || messages.length === 0) return ''

  const sorted = [...messages].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
  const lastMsg = sorted[sorted.length - 1]

  const date = new Date(lastMsg.created_at)
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' ' +
    date.toLocaleDateString([], { month: 'short', day: 'numeric' })
}

const selectedMessages = computed(() => {
  if (!selectedUserId.value) return []
  return [...localGroupedMessages.value[selectedUserId.value]]
    .sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

})

const filteredUsers = computed(() => {
  if (!searchQuery.value) return props.users
  return props.users.filter(user =>
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    user.id.toString().includes(searchQuery.value)
  )
})


const openChat = (userId) => {
  // Mark all messages as read
  if (localGroupedMessages.value[userId]) {
    localGroupedMessages.value[userId].forEach(msg => {
      msg.isUnread = false;
    });
  }

  // Reset unread count for this user
  unreadCounts.value[userId] = 0;

  // Update last seen message
  if (localGroupedMessages.value[userId]?.length > 0) {
    const lastMsg = localGroupedMessages.value[userId][localGroupedMessages.value[userId].length - 1];
    lastSeenMessages.value[userId] = lastMsg.id;
  }

  selectedUserId.value = userId;
  nextTick(() => scrollToBottom());
}

const handleSidebarToggle = (isVisible) => {
  isSidebarVisible.value = isVisible
}

const handleSearch = () => {

  if (searchQuery.value) {
    selectedUserId.value = null
  }
}
const sendReply = async () => {
  try {
    const response = await axios.post('/api/admin/messages/store', {
      message: newMessage.value,
      user_id: selectedUserId.value
    })

    // Immediately add the message to local state
    if (!localGroupedMessages.value[selectedUserId.value]) {
      localGroupedMessages.value[selectedUserId.value] = []
    }

    localGroupedMessages.value[selectedUserId.value].push({
      id: Date.now(), // temporary ID until real one comes from server
      message: newMessage.value,
      user_id: selectedUserId.value,
      admin_id: props.admin.id,
      is_from_user: false,
      created_at: new Date().toISOString(),
      isUnread: false
    })

    newMessage.value = ''
    scrollToBottom()

  } catch (error) {
    console.error('Error sending message:', error)
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  const fullDate = date.toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' })
  return `${fullDate} ${time}` // Example: "May 7, 2025 03:45 PM"
}

const getLastMessagePreview = (userId) => {
  const messages = localGroupedMessages.value[userId]
  if (!messages || messages.length === 0) return 'No messages yet'

  const sorted = [...messages].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
  const lastMsg = sorted[sorted.length - 1]

  return lastMsg.message.length > 30
    ? lastMsg.message.substring(0, 30) + '...'
    : lastMsg.message
}

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      const isAtBottom =
        messagesContainer.value.scrollHeight -
          messagesContainer.value.scrollTop -
          messagesContainer.value.clientHeight <
        100; 
      if (!selectedUserId.value || isAtBottom) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
      }
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
    authEndpoint: '/broadcasting/auth',
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
      },
    },
  });

  const adminId = props.admin?.id;
  if (adminId) {

    window.Echo.private(`admin.${adminId}`)
      .listen('NewMessage', (data) => {
        const message = data.message;
        const userId = message.user_id;

        if (!localGroupedMessages.value[userId]) {
          localGroupedMessages.value[userId] = [];
        }

        const exists = localGroupedMessages.value[userId].some(
          m => m.id === message.id
        );

        if (!exists) {
          const isUnread = selectedUserId.value !== userId;

          // 👇 Replace created_at with client-side time to avoid sorting issue
          localGroupedMessages.value[userId].push({
            ...message,
            created_at: new Date().toISOString(), // override with current time
            isUnread: isUnread
          });

          if (isUnread) {
            unreadCounts.value[userId] = (unreadCounts.value[userId] || 0) + 1;
          }

          if (selectedUserId.value === userId) {
            nextTick(() => scrollToBottom());
          }
        }
      });

  }
};

unreadCounts.value = Object.fromEntries(
  props.users.map(user => [user.id, 0])
)

onMounted(() => {
  // if (window.Laravel.user) {
  setupEcho()
  initState()
  // }
})

onUnmounted(() => {
  if (window.Echo) {
    window.Echo.leave(`admin.${window.Laravel.user?.id}`);
    window.Echo.disconnect();
  }
});

watch(unreadCounts, (newVal) => {
  console.log('Unread counts updated:', newVal);
}, { deep: true });

</script>
<style scoped>
.top-response {
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  position: fixed;
  z-index: 999;
}

.bottom-response {
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  position: fixed;
  z-index: 999;
}

.bg-blue-500 {
  background-color: #3b82f6;
}

.text-white {
  color: white;
}

.p-3 {
  padding: 12px;
}

.rounded-lg {
  border-radius: 8px;
}

.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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


.flex.justify-start {
  justify-content: flex-start;
}

.flex.justify-end {
  justify-content: flex-end;
}

.max-w-xs {
  max-width: 20rem;
}

.bg-gray-700 {
  background-color: #374151;
}

.bg-blue-600 {
  background-color: #2563eb;
}

.rounded-lg {
  border-radius: 0.5rem;
}

.text-gray-400 {
  color: #9ca3af;
}
</style>