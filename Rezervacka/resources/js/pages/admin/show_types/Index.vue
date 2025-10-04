<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';


interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Typ predstavenia'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    show_types: [];
}

const props = defineProps<Props>();
console.log(props.show_types);
const tableValues = props.show_types;

const nameProps: Name = {
    name: 'Typy predstavení',
    changeName: 'typ predstavenia',
    link: '/show_types',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
