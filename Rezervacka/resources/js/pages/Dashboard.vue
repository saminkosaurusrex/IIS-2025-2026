<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
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
const page = usePage();
console.log(page.props.auth.user.roles[0]);
const tableValues = props.reservations;

const nameProps: Name = {
    name: 'Moje rezervácie',
    changeName: 'rezerváciu',
    link: '/dashboard',
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Moje rezervácie',
        href: `/dashboard`,
    },
];

</script>

<template>
        <BaseTable v-if="props.reservations.length != 0"
            :table-header="tableHeader" 
            :table-values="tableValues" 
            :name-props="nameProps"
        />
        <Head :title="'Rezervácia'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-1/1 text-center text-gray-500 mt-40 text-2xl">Zatiaľ žiadne rezervácie</div>
    </AppLayout>
</template>
