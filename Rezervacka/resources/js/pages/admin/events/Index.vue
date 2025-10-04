<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';

interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Sála', 'Podujatie', 'Začiatok', 'Koniec', 'Cena'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    events: [];
}

const props = defineProps<Props>();
console.log(props.events);
const tableValues = props.events;

const nameProps: Name = {
    name: 'Kultúrna udalosť',
    changeName: 'kultúrne udalosti',
    link: '/events',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
