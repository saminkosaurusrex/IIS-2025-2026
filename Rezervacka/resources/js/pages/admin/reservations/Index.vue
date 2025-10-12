<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';


interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Kód', 'Sála', 'Predstavenie', 'Začiatok','Cena', 'Stav'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    reservations: [];
}

const props = defineProps<Props>();
console.log(props.reservations);
const tableValues = props.reservations;

const nameProps: Name = {
    name: 'Rezervácie',
    changeName: 'rezerváciu',
    link: '/reservations',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
