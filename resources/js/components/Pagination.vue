<!-- components/Pagination.vue -->
<template>
    <div class="flex justify-between items-center mt-4">
        <button 
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
        >
            Previous
        </button>

        <span>Page {{ currentPage }} of {{ totalPages }}</span>

        <button 
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
        >
            Next
        </button>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from "vue";

const props = defineProps({
    currentPage: Number,
    totalPages: Number
});

const emit = defineEmits(["update:currentPage"]);

const prevPage = () => {
    if (props.currentPage > 1) emit("update:currentPage", props.currentPage - 1);
};

const nextPage = () => {
    if (props.currentPage < props.totalPages) emit("update:currentPage", props.currentPage + 1);
};
</script>
