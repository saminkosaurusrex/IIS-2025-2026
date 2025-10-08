<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';

interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Sála', 'Predstavenie', 'Začiatok', 'Obrázok', 'Stav'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    reservations: [];
}

const props = defineProps<Props>();
console.log(props.reservations);
const tableValues = props.reservations;

const nameProps: Name = {
    name: 'Moje rezervácie',
    changeName: 'rezerváciu',
    link: '/dashboard',
};

</script>

<template>
        <BaseTable
            :table-header="tableHeader" 
            :table-values="tableValues" 
            :name-props="nameProps"
        />
</template>
