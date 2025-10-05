<script setup lang="ts">
import BaseEditCreate from '@/components/BaseEditCreate.vue';

interface Tags {
    id: number,
    name: string,
}

const props = defineProps<{tags: Tags}>();

</script>

<template>
    <BaseEditCreate
        :table-values="props.tags"
        :name-props="{ action: 'Úprava' ,changeActionName: 'Upraviť' ,name: 'Žáner', changeName: 'žánru', link: 'tags/', actionLink: 'edit' }"
    />
</template>
