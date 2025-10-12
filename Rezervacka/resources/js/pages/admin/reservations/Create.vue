<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import dayjs from "dayjs";
import { ref } from 'vue';
import SeatingChart from '@/components/SeatingChart.vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Vytvoriť rezerváciu',
        href: 'reservations/create',
    },
];

interface SeatData {
    event: {
        hall: {
            rows: number;
            columns: number;
        };
        price: number;
    };
    reservedSeats: Array<{ row: number; column: number }>;
    takenSeats: Array<{ row: number; column: number }>;
}


interface Event {
    id: number;
    hall_id: number;
    show_id: number;
    starting_at: string;
    ending_at: string;
}

interface Props {
    shows: { id: number, name: string }[];
    halls: { id: number, name: string }[];
    events: Event[];
}
const props = defineProps<Props>();

console.log(props.halls[0]);

interface Seat {
    row: number;
    column: number;
}

const form = useForm({
    event_id: null as number | null,
    hall: props.halls.length == 1 ? props.halls[0].id : null as number | null,
    show: null as number | null,
    price: null as number | null,
    date: '',
    time: '',
    seats: [] as Seat[]
});



const selectHall = (hall: number) => {
    form.date = '';
    form.hall = hall;
};

const selectDate = () => {
    form.time = '';
}

const selectShow = (show: number) => {
    form.date = '';
    form.show = show;

};

let seats = ref<SeatData | null>(null);

const selectTime = async (time: string) => {
    form.time = time;
    const eventId = props.events.filter(event => event.hall_id === form.hall
        && event.show_id === form.show && event.starting_at === `${form.date} ${form.time}`)
    form.event_id = eventId[0].id;
    try {
        const res = await fetch(`/api/reservations/${eventId[0].id}`);
        if (!res.ok) throw new Error('Chyba pri získavaní sedadiel');

        seats.value = await res.json();
        console.log(seats);
    } catch (error) {
        console.error(error);
    }
}

const selectedSeats = ref<Array<{ row: number; column: number }>>([]);

const handleSeatClick = (row: number, col: number) => {
    const index = selectedSeats.value.findIndex(
        (s) => s.row === row && s.column === col,
    );
    if (index > -1) {
        selectedSeats.value.splice(index, 1);
    } else {
        selectedSeats.value.push({ row, column: col });
    }
    if(seats.value){
        form.price = seats.value?.event.price * selectedSeats.value.length;
    }

};

const selectedTimes = (): Event[] => {
    const reservationTimes = props.events.filter(event => event.hall_id === form.hall
        && event.show_id === form.show && event.starting_at.startsWith(form.date));
    return reservationTimes;
}

const handleSubmit = () => {
    form.seats = selectedSeats.value;
    console.log(form);
    form.post('/reservations');
};

</script>

<template>

    <Head title="Vytvorenie kulturnej udalosti" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="Event hall">Výber udalosti</Label>
                    <div class="space-x-2 space-y-2">
                        <Button v-for="show in props.shows" type="button"
                            :class="form.show === show.id ? 'bg-blue-600 text-white hover:bg-blue-400' : 'bg-red-400 text-white hover:text-white hover:bg-red-600'"
                            @click="selectShow(show.id)"> {{ show.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.show">{{ form.errors.show }}
                    </div>
                </div>
                <div class="space-y-2 space-x-2">
                    <Label for="Event hall">Výber sály</Label>
                    <div class="space-x-2">
                        <Button v-for="hall in props.halls" type="button"
                            :class="form.hall === hall.id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:text-white'"
                            @click="selectHall(hall.id)"> {{ hall.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.hall">{{ form.errors.hall }}</div>
                </div>

                <div class="space-y-4">
                    <div>
                        <Label>Dátum</Label>
                        <Input v-model="form.date" type="date" :min="new Date().toISOString().split('T')[0]"
                            :disabled="!form.hall || !form.show" @click="selectDate()" />
                        <div class="text-sm text-red-600" v-if="form.errors.date">{{ form.errors.date }}</div>
                    </div>
                </div>
                <div v-if="form.date" class="space-y-2">
                    <Label for="Event hall">Výber času</Label>
                    <div v-if="selectedTimes().length > 0" class="space-x-2 ">
                        <Button v-for="event in selectedTimes()" type="button"
                            :class="form.time == event.starting_at.slice(11, 19) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:text-white'"
                            @click="selectTime(event.starting_at.slice(11, 19))"> {{ dayjs(event.starting_at,
                            "YYYY-MM-DD HH:mm:ss").format("HH:mm") }}
                        </Button>
                        <div class="text-sm text-red-600" v-if="form.errors.time && form.date">{{ form.errors.time }}</div>
                    </div>
                    <div v-else>
                        Neexistujú časy
                    </div>
                </div>
                <div class="space-y-2">
                    <Label v-if="seats" for="Reservation price">Počet lístkov: {{ selectedSeats.length }}</Label>
                </div>
                <div class="space-y-2">
                    <Label v-if="seats" for="Reservation price">Spolu: {{ form.price }}€</Label>
                </div>
                <Button  type="submit" :disabled="form.processing">Rezervovať</Button>
                <SeatingChart class="scale-85" v-if="seats" :rows="seats.event.hall.rows" :columns="seats.event.hall.columns"
                    :selected-seats="selectedSeats" :reserved-seats="seats.reservedSeats"
                    :taken-seats="seats.takenSeats" @seat-click="handleSeatClick" />
                <div class="text-sm text-red-600" v-if="form.errors.seats && seats">{{ form.errors.seats }}</div>
            </form>
        </div>
    </AppLayout>
</template>
