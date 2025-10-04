<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';

interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id','Žáner'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    tags: [];
}

const props = defineProps<Props>();
console.log(props.tags);
const tableValues = props.tags;

const nameProps: Name = {
    name: 'Žánre',
    changeName: 'žáner',
    link: '/tags',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
