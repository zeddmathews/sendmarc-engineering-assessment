<script setup lang="ts">
import { useAdmin } from '@/composables/useAdmin';
const { isAdmin } = useAdmin();
</script>
<template>
  <slot v-if="isAdmin" />
</template>
