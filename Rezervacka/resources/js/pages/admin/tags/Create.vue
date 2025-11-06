<script setup lang="ts">
import BaseEditCreate from '@/components/BaseEditCreate.vue';

</script>

<template>
    <BaseEditCreate 
        :name-props="{
            action: 'Vytvorenie',
            changeActionName: 'Vytvoriť',
            name: 'Žáner',
            changeName: 'žánru',
            link: 'tags/',
            actionLink: 'create'
        }" 
    />
</template>
