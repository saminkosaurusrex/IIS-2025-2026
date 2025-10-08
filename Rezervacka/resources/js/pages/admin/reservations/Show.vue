<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
interface Name {
    name: string;
    changeName: string;
    link: string;
}

// Lokálne dáta
interface Reservation {
    showName: string,
    hallName: string,
    starting_at: string
    address: string
    code: string
    price: number
    confirmed_at: string
    canceled_at: string
    row: number
    column: number 
}

const props = defineProps<{ reservation: Reservation }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rezervácia',
        href: `/dashboard/${props.reservation}`,
    },
];

</script>

<template>

    <Head :title="'Rezervácia'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div>Názov: {{ props.reservation.showName }}</div>
        <div>Adresa: {{ props.reservation.address }}</div>
        <div>Sála: {{ props.reservation.hallName }}</div>
        <div>Kedy: {{ props.reservation.starting_at }}</div>
        <div>Cena: {{ props.reservation.price }}</div>
        <div>Sedadlo: {{ props.reservation.row }}/{{ props.reservation.column }}</div>
        <div>Kód: {{ props.reservation.code }}</div>
        <div>Potvrdenie:
            <span v-if="props.reservation.canceled_at">Zrušené</span>
            <span v-else-if="props.reservation.confirmed_at">Potvrdené</span>
            <span v-else>Čakajúce</span>
        </div>
        <Link :href="'/dashboard'">
        <Button class="bg-slate-600">Späť</Button>
        </Link>
    </AppLayout>
</template>