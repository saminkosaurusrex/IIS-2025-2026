<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import dayjs from "dayjs";
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Vytvorenie kultúrnej udalosti',
        href: 'event/create',
    },
];

const form = useForm({
    hall: null as number | null,
    show: null as number | null,
    price: undefined,
    date: '',
    start_time: '',
    end_time: ''
});

const handleSubmit = () => {
    console.log(form);
    form.post('/events');
};


interface Props {
    shows: { id: number, name: string }[];
    halls: { id: number, name: string }[];
    events: { hall_id: number, show_id: number, starting_at: string, ending_at: string }[];
}
const props = defineProps<Props>();

const selectHall = (hall: number) => {
    form.date = '';
    form.start_time = '';
    form.end_time = '';
    form.hall = hall;
    if (form.hall && form.show) {

        selectBlockedTimes();
    }
};

const selectShow = (show: number) => {
    form.date = '';
    form.start_time = '';
    form.end_time = '';
    form.show = show;
    if (form.hall && form.show) {

        selectBlockedTimes();
    }
};

interface BlockedTimeRange {
    start_at: string;
    end_at: string;
}

const blockedTimes: Record<string, BlockedTimeRange[]> = {};
function selectBlockedTimes() {
    Object.keys(blockedTimes).forEach(key => {
        delete blockedTimes[key];
    });
    const blocked = props.events.filter(event => event.hall_id === form.hall
    );

    blocked.forEach(event => {
        const parsedStartTime = dayjs(event.starting_at, "YYYY-MM-DD HH:mm:ss");
        const parsedEndTime = dayjs(event.ending_at, "YYYY-MM-DD HH:mm:ss");
        const dateKey = parsedStartTime.format("YYYY-MM-DD")
        if (!blockedTimes[dateKey]) {
            blockedTimes[dateKey] = [];
        }
        blockedTimes[dateKey].push({
            start_at: parsedStartTime.format("HH:mm"),
            end_at: parsedEndTime.format("HH:mm")
        })
    })
    console.log(blockedTimes)
}

const isBlocked = (hour: string) => {

    const dateKey = form.date;

    if (!dateKey) return false;

    const blockedForDate = blockedTimes[dateKey] || [];

    return blockedForDate.some(({ start_at, end_at }) => hour >= start_at && hour < end_at);
}

const isBlockedEnd = (hour: string) => {
    const dateKey = form.date;
    const timeKey = form.start_time;

    if (!dateKey) return false;

    const blockedForDate = blockedTimes[dateKey] || [];
    const firstBlocked = blockedForDate
        .slice()
        .sort((a, b) => a.start_at.localeCompare(b.start_at))
        .find(bt => bt.start_at > timeKey)?.start_at;
    console.log(firstBlocked);
    if (!firstBlocked) {
        return hour <= timeKey;
    }
    return hour <= timeKey || hour > firstBlocked;
}

</script>

<template>

    <Head title="Vytvorenie kulturnej udalosti" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="Event hall">Výber sály pre udalosť</Label>
                    <div class="flex space-x-2">
                        <Button v-for="hall in props.halls" type="button"
                            :class="form.hall === hall.id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:text-white'"
                            @click="selectHall(hall.id)"> {{ hall.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.hall">{{ form.errors.hall }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Event hall">Výber udalosti</Label>
                    <div class="flex space-x-2">
                        <Button v-for="show in props.shows" type="button"
                            :class="form.show === show.id ? 'bg-blue-600 text-white hover:bg-blue-400' : 'bg-red-400 text-white hover:text-white hover:bg-red-600'"
                            @click="selectShow(show.id)"> {{ show.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.show">{{ form.errors.show }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <Label>Dátum</Label>
                        <Input v-model="form.date" type="date" :min="new Date().toISOString().split('T')[0]"
                            :disabled="!form.hall || !form.show" />
                        <div class="text-sm text-red-600" v-if="form.errors.date">{{ form.errors.date }}</div>
                    </div>

                    <div>
                        <Label>Čas začiatku</Label>
                        <select v-model="form.start_time" class="w-full p-2 border rounded" :disabled="!form.date">
                            <option value="">Výber času</option>
                            <option v-for="hour in 24" :key="hour"
                                :value="(hour - 1).toString().padStart(2, '0') + ':00'"
                                :disabled="isBlocked((hour - 1).toString().padStart(2, '0') + ':00')">
                                {{ (hour - 1).toString().padStart(2, '0') }}:00
                            </option>
                        </select>
                        <div class="text-sm text-red-600" v-if="form.errors.start_time">{{ form.errors.start_time }}
                        </div>
                    </div>

                    <div>
                        <Label>Čas konca</Label>
                        <select v-model="form.end_time" class="w-full p-2 border rounded" :disabled="!form.start_time">
                            <option value="">Vyberte čas</option>
                            <option v-for="hour in 24" :key="hour"
                                :value="(hour - 1).toString().padStart(2, '0') + ':00'"
                                :disabled="isBlockedEnd((hour - 1).toString().padStart(2, '0') + ':00')">
                                {{ (hour - 1).toString().padStart(2, '0') }}:00
                            </option>
                        </select>
                        <div class="text-sm text-red-600" v-if="form.errors.end_time">{{ form.errors.end_time }}</div>
                    </div>
                    <div class="space-y-2">
                        <Label for="Shows name">Cena lístka</Label>
                        <Input v-model="form.price" type="number" placeholder="Cena lístka"></Input>
                        <div class="text-sm text-red-600" v-if="form.errors.price">{{ form.errors.price }}</div>
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing">Vytvoriť kultúrnu udalosť</Button>
            </form>
        </div>
    </AppLayout>
</template>
