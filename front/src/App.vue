<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import { authStore } from '@/stores/auth.store'
import Alert from './components/Alert.vue'
</script>

<template>
  <div class="app-container">
    <div class="container pt-4 pb-4">
      <Alert />
      <RouterView />
    </div>
  </div>
</template>

<style scoped>

</style>
