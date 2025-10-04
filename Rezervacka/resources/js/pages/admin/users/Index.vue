<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';

interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
const tableHeader: string[] = ['Id', 'Meno', 'E-mail', 'Rola'];

interface Props {
    // musi sa to volat rovnako ako to co ide do compact
    users: [];
}

const props = defineProps<Props>();
console.log(props.users);
const tableValues = props.users;

const nameProps: Name = {
    name: 'Užívatelia',
    changeName: 'užívateľa',
    link: '/users',
};

</script>

<template>
    <BaseTable 
        :table-header="tableHeader" 
        :table-values="tableValues" 
        :name-props="nameProps"
      />
</template>
