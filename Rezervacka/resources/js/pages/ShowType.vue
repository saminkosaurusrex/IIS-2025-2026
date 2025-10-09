<template>
    <div class="app-layout flex">

        <div class="main-content flex-1 flex flex-col">
            <NavMenu />

            <div class="content p-4">
                <div class="max-w-3xl mx-auto mt-8">
                    <h1 class="text-2xl font-bold mb-6 text-gray-900">Shows</h1>
                    <ul class="space-y-6">
                        <li v-for="show in props.shows" :key="show.id" class="p-4 border rounded-lg shadow hover:bg-gray-100 transition bg-white">
                            <div class="flex items-center gap-4">
                                <img
                                    v-if="show.image"
                                    :src="show.image"
                                    alt="Show image"
                                    class="w-16 h-16 object-cover rounded"
                                />
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">{{ show.name }}</p>
                                </div>
                            </div>

                            <!-- Eventy / časy registrácie -->
                            <div v-if="show.events.length" class="mt-3">
                                <h3 class="font-medium text-gray-800 mb-2">Dostupné termíny:</h3>
                                <ul class="space-y-1">
                                    <Link
                                        v-for="event in show.events"
                                         :key="event.id"
                                        :href="`/udalost/${event.id}`"
                                    >
                                        <li

                                            class="flex justify-between items-center p-2 bg-gray-50 rounded hover:bg-gray-200 transition"
                                        >
                                            <div>
                                                <span class="font-medium text-gray-900">{{ event.hall.name }}</span>:
                                                <span class="text-gray-800">{{ formatDateTime(event.starting_at) }} – {{ formatDateTime(event.ending_at) }}</span>
                                            </div>
                                            <div class="text-right font-semibold text-gray-900">
                                                {{ Number(event.price).toFixed(2) }} €
                                            </div>
                                        </li>
                                    </Link>
                                </ul>
                            </div>
                            <div v-else class="mt-2 text-gray-600">Momentálne žiadne termíny.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import NavMenu from '../components/NavBar.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    shows: Array
})

// Funkcia na pekné formátovanie dátumu a času
const formatDateTime = (datetime: string) => {
    // pridáme "T" medzi dátum a čas pre správny ISO formát
    const isoString = datetime.replace(' ', 'T')
    const date = new Date(isoString)
    return date.toLocaleString(undefined, {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<style src="../../css/app.css"></style>
