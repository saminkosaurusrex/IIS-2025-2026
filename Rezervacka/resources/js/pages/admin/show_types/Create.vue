<script setup lang="ts">
import BaseEditCreate from '@/components/BaseEditCreate.vue';

</script>

<template>
    <BaseEditCreate 
        :name-props="{
            action: 'Vytvorenie',
            changeActionName: 'Vytvoriť',
            name: 'Typy predstavení',
            changeName: 'typ predstavenia',
            link: 'show_types',
            actionLink: 'create'
        }" 
    />
</template>
