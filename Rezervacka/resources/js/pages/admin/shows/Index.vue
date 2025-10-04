<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';

interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Meno', 'Typ', 'Žáner','Obrázok'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    shows: [];
}

const props = defineProps<Props>();
console.log(props.shows);
const tableValues = props.shows;

const nameProps: Name = {
    name: 'Predstavenia',
    changeName: 'predstavenie',
    link: '/shows',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
