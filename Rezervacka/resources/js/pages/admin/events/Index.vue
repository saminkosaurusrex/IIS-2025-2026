<script setup lang="ts">
import BaseTable from '@/components/BaseTable.vue';
import { Link } from '@inertiajs/vue3';

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
const tableValues = props.events.data;

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

    <div class="flex flex-row w-full items-center justify-center my-8 space-x-2 text-xl">
        <Link
            v-for="link in events.links"
            :key="link.label"
            :href="link.url || '#'"
            class="px-3 py-1 border rounded transition-colors duration-200"
            :class="{
          'bg-blue-500 text-white pointer-events-none cursor-default': link.active,
          'text-gray-500 pointer-events-none cursor-not-allowed': !link.url,
          'hover:bg-blue-100': link.url && !link.active
        }"
        >
            {{
                link.label.includes("Previous")
                    ? 'Predchádzajúca'
                    : link.label.includes("Next")
                        ? 'Ďalšia'
                        : link.label
            }}
        </Link>
    </div>

</template>
