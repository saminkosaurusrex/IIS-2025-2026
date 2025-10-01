<template>
  <div class="app-layout flex">
    <!-- Sidebar vľavo -->

    <!-- Hlavná časť stránky -->
    <div class="main-content flex-1 flex flex-col">
      <!-- TopBar hore -->
      <NavMenu />

      <!-- Obsah stránky -->
      <div class="content p-4">
        <h1>content</h1>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import NavMenu from '../components/NavBar.vue';
</script>

<style src="../../css/app.css"></style>
