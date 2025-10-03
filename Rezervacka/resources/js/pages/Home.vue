<template>
    <div class="app-layout flex">
        <!-- Sidebar vľavo -->

        <!-- Hlavná časť stránky -->
        <div class="main-content flex flex-1 flex-col">
            <!-- TopBar hore -->
            <NavMenu />

            <!-- Obsah stránky -->
            <div class="content p-4"></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import NavMenu from '../components/NavBar.vue';
</script>

<style src="../../css/app.css"></style>
