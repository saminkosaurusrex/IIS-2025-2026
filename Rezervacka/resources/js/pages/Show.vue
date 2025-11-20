<template>
    <NavMenu />
    <div class="flex h-[94vh]">
        <div class="h-full w-2/5 overflow-hidden">
            <img
                v-if="show.image"
                :src="show.image"
                alt="Obrázok predstavenia"
                class="h-full w-full object-cover"
            />
        </div>
        <div ref="scrollContainer" class="flex w-3/5 flex-col overflow-y-auto">
            <div class="px-10 py-10">
                <h1 class="mb-4 text-5xl font-bold text-gray-900">
                    {{ show.name }}
                </h1>

                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <div
                        class="rounded-full bg-amber-50 px-4 py-2 text-gray-900"
                        style="font-size: clamp(0.75rem, 1.2vw, 1rem)"
                    >
                        {{ show.show_type.name }}
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="tag in show.tags"
                            :key="tag.id"
                            class="rounded-full bg-white/80 px-3 py-1 text-gray-800"
                            style="font-size: clamp(0.7rem, 1vw, 0.875rem)"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                </div>

                <p class="mb-8 max-w-2xl text-lg leading-relaxed text-gray-700">
                    {{ show.description }}
                </p>

                <div class="mb-6 flex flex-wrap items-center gap-2">
                    <b>Účinkujú:</b>
                    <template
                        v-for="performer in show.performers"
                        :key="performer.id"
                    >
                        {{ performer.name }},
                    </template>
                </div>

                <div
                    v-if="$page.props.auth.user"
                    class="mb-6 flex flex-wrap items-center gap-2"
                >
                    Vaše hodnotenie:
                    <vue3-star-ratings
                        v-model="form.rating"
                        :disableClick="form.processing"
                    />
                </div>

                <div class="mb-6 border-t border-gray-300"></div>

                <div ref="filterSection" class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-gray-700"
                        >Zvoľte miesto:</label
                    >
                    <select
                        v-model="selectedHall"
                        @change="scrollToEvents"
                        class="w-64 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none"
                    >
                        <option value="">Zvoľte miesto</option>
                        <option
                            v-for="hall in uniqueHalls"
                            :key="hall.id"
                            :value="hall.id"
                        >
                            {{ hall.name }}
                        </option>
                    </select>
                </div>

                <div class="mb-6 border-t border-gray-300"></div>

                <div
                    v-if="selectedHall && groupedEvents.length > 0"
                    ref="eventsSection"
                    class="mb-8 overflow-x-auto"
                >
                    <table class="w-full">
                        <tbody>
                            <tr
                                v-for="group in groupedEvents"
                                :key="group.date"
                                class="border-b border-gray-200"
                            >
                                <td
                                    class="py-4 pr-8 align-top font-medium text-gray-900"
                                >
                                    {{ group.date }}
                                </td>
                                <td class="py-4">
                                    <div class="flex flex-wrap gap-3">
                                        <Link
                                            v-for="event in group.events"
                                            :key="event.id"
                                            :href="`/udalost/${event.id}`"
                                            class="rounded-lg border border-gray-300 bg-white px-6 py-2 text-gray-900 transition-colors hover:border-gray-400 hover:bg-gray-100"
                                        >
                                            <div
                                                class="flex flex-col items-center"
                                            >
                                                <span class="font-medium">{{
                                                    event.time
                                                }}</span>
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >{{
                                                        Number(
                                                            event.price,
                                                        ).toFixed(2)
                                                    }}
                                                    €</span
                                                >
                                            </div>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-else-if="!selectedHall"
                    class="py-8 text-center text-gray-500"
                >
                    Prosím, zvoľte miesto pre zobrazenie dostupných termínov.
                </div>

                <div v-else class="py-8 text-center text-gray-500">
                    Pre toto miesto momentálne nie sú dostupné žiadne termíny.
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch } from 'vue';
import NavMenu from '../components/NavBar.vue';

const props = defineProps<{
    show: any;
    user_rating: any;
}>();

const form = useForm({
    rating: props.user_rating?.rating / 2 || 0,
    show_id: props.show.id,
});

watch(
    () => form.rating,
    (newVal) => {
        const roundedValue = Math.round(newVal * 2) / 2;
        if (newVal !== roundedValue) {
            form.rating = roundedValue;
        }
        form.post('/rating');
    },
);

const selectedHall = ref('');
const scrollContainer = ref<HTMLElement | null>(null);
const eventsSection = ref<HTMLElement | null>(null);

const uniqueHalls = computed(() => {
    if (!props.show.events || props.show.events.length === 0) return [];

    const hallsMap = new Map();
    props.show.events.forEach((event: any) => {
        if (event.hall && !hallsMap.has(event.hall.id)) {
            hallsMap.set(event.hall.id, event.hall);
        }
    });

    return Array.from(hallsMap.values());
});

const groupedEvents = computed(() => {
    if (!selectedHall.value || !props.show.events) return [];

    const now = new Date();

    const filteredEvents = props.show.events.filter((event: any) => {
        if (event.hall.id !== Number(selectedHall.value)) return false;

        const eventDateTime = new Date(event.starting_at.replace(' ', 'T'));
        return eventDateTime > now;
    });

    const groups = new Map();

    filteredEvents.forEach((event: any) => {
        const dateTime = event.starting_at.replace(' ', 'T');
        const date = new Date(dateTime);

        const dateKey = date.toLocaleDateString('sk-SK', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });

        const timeStr = date.toLocaleTimeString('sk-SK', {
            hour: '2-digit',
            minute: '2-digit',
        });

        if (!groups.has(dateKey)) {
            groups.set(dateKey, {
                date: dateKey,
                events: [],
            });
        }

        groups.get(dateKey).events.push({
            id: event.id,
            time: timeStr,
            price: event.price,
            starting_at: event.starting_at,
            ending_at: event.ending_at,
        });
    });

    const result = Array.from(groups.values());
    result.forEach((group) => {
        group.events.sort((a: any, b: any) => {
            return (
                new Date(a.starting_at.replace(' ', 'T')).getTime() -
                new Date(b.starting_at.replace(' ', 'T')).getTime()
            );
        });
    });

    return result;
});

const scrollToEvents = () => {
    if (selectedHall.value && eventsSection.value && scrollContainer.value) {
        nextTick(() => {
            const elementTop = eventsSection.value!.offsetTop;
            scrollContainer.value!.scrollTo({
                top: elementTop - 20,
                behavior: 'smooth',
            });
        });
    }
};
</script>

<style src="../../css/app.css"></style>
