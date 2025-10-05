<script setup lang="ts">
import BaseEditCreate from '@/components/BaseEditCreate.vue';

interface Show_Type {
    id: number,
    name: string,
}

const props = defineProps<{show_types: Show_Type}>();

</script>

<template>
    <BaseEditCreate
        :table-values="props.show_types"
        :name-props="{ action: 'Úprava' ,changeActionName: 'Upraviť' ,name: 'Typ predstavenia', changeName: 'typu predstavenia', link: 'show_types/', actionLink: 'edit' }"
    />
</template>
