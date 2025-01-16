<template>
    <img :src="logoUrl" alt="Logo"  />
</template>

<script>
export default {
    data() {
        return {
            logoUrl: '/assets/images/logo.png',
        };
    },
};
</script>
