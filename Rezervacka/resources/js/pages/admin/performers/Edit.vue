<script setup lang="ts">
import BaseEditCreate from '@/components/BaseEditCreate.vue';

interface Performers {
    id: number,
    name: string,
}

const props = defineProps<{performers: Performers}>();

</script>

<template>
    <BaseEditCreate
        :table-values="props.performers"
        :name-props="{ action: 'Úprava' ,changeActionName: 'Upraviť' ,name: 'Účinkujúci', changeName: 'účinkujúceho', link: 'performers/', actionLink: 'edit' }"
    />
</template>
