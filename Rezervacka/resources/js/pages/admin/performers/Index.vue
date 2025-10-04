<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';


interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Meno'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    performers: [];
}

const props = defineProps<Props>();
console.log(props.performers);
const tableValues = props.performers;

const nameProps: Name = {
    name: 'Účinkujúci',
    changeName: 'účikujúceho',
    link: '/performers',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
