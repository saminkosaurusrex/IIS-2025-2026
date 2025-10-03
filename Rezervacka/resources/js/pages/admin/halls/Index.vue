<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';


interface TableValues {
    id: number;
    name: string;
    description: string;
    address: string;
    rows: number;
    columns: number;
}

interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Meno', 'Popis', 'Adresa', 'Riadky', 'Stĺpce'];


interface Hall {
    id: number,
    name: string,
    description: string,
    address: string,
    rows: number,
    columns: number
}

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    halls: Hall[];
}

const props = defineProps<Props>();
console.log(props.halls);
const tableValues: TableValues[] = props.halls;

const nameProps: Name = {
    name: 'Sály',
    changeName: 'sálu',
    link: '/halls',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
