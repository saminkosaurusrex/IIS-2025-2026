<script setup lang="ts">
import BaseEditCreate from '@/components/BaseEditCreate.vue';

</script>

<template>
    <BaseEditCreate 
        :name-props="{
            action: 'Vytvorenie',
            changeActionName: 'Vytvoriť',
            name: 'Účinkujúci',
            changeName: 'účinkujúceho',
            link: 'performers/',
            actionLink: 'create'
        }" 
    />
</template>
