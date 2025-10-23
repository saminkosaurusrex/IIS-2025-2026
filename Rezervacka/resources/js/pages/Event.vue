<template>
    <div class="app-layout flex">
        <!-- Sidebar vľavo -->

        <!-- Hlavná časť stránky -->
        <div class="main-content flex flex-1 flex-col">
            <!-- TopBar hore -->
            <NavMenu />

            <!-- Obsah stránky -->
            <div class="content flex flex-col gap-4 p-4 lg:flex-row">
                <!-- Seating chart -->
                <ReservationSummary
                    class="w-full max-w-md"
                    :form="form"
                    :show-name="event.show.name"
                    :rating="roundedRating"
                    :duration="event.ending_at_human"
                    :tags="event.show.tags"
                    :hall="event.hall.name"
                    :location="event.hall.address"
                    :date-time="event.starting_at_human"
                    :selected-seats="selectedSeatsCount"
                    :available-seats="availableSeatsCount"
                    :reserved-seats="reservedSeatsCount"
                    :taken-seats="takenSeatsCount"
                    :own-reserved-seats="ownReservedSeatsCount"
                    :own-taken-seats="ownTakenSeatsCount"
                    :adult-tickets="adultTickets"
                    :total-price="totalPrice"
                    :email="email"
                    :terms-accepted="termsAccepted"
                    :can-submit="canSubmit"
                    @update-adult="adultTickets = $event"
                    @update-email="email = $event"
                    @update-terms="termsAccepted = $event"
                    @submit="handleSubmit"
                />

                <SeatingChart
                    :rows="event.hall.rows"
                    :columns="event.hall.columns"
                    :selected-seats="selectedSeats"
                    :reserved-seats="reservedSeats"
                    :taken-seats="takenSeats"
                    :own-reserved-seats="ownReservedSeats"
                    :own-taken-seats="ownTakenSeats"
                    @seat-click="handleSeatClick"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import ReservationSummary from '@/components/EventCard.vue';
import NavMenu from '@/components/NavBar.vue';
import SeatingChart from '@/components/SeatingChart.vue';
import { computed, ref } from 'vue';
import { useForm, usePage} from '@inertiajs/vue3';

// Props z backendu
interface Props {
    event: {
        show: {
            name: string;
            age_rating: number;
            duration: number;
            subtitles: string;
            average_rating: number | null;
            tags: {
                id: number;
                name: string;
            }[];
        };
        hall: {
            name: string;
            address: string
            rows: number;
            columns: number;
        };
        id: number;
        starting_at: string;
        starting_at_human: string,
        ending_at_human: number;
        price: number;
    };
    reservedSeats: Array<{ row: number; column: number }>;
    takenSeats: Array<{ row: number; column: number }>;
    ownReservedSeats: Array<{ row: number; column: number }>;
    ownTakenSeats: Array<{ row: number; column: number }>;
}

const props = defineProps<Props>();
const page = usePage();
const user = page.props.auth.user;



// Lokálny state
const selectedSeats = ref<Array<{ row: number; column: number }>>([]);
const adultTickets = ref(0);

const email = ref('');
const termsAccepted = ref(false);

// Computed
const totalSeats = computed(
    () => props.event.hall.rows * props.event.hall.columns,
);
const selectedSeatsCount = computed(() => selectedSeats.value.length);

const reservedSeatsCount = computed(() => props.reservedSeats.length);
const takenSeatsCount = computed(() => props.takenSeats.length);

const ownReservedSeatsCount = computed(() => props.ownReservedSeats.length);
const ownTakenSeatsCount = computed(() => props.ownTakenSeats.length);


const availableSeatsCount = computed(
    () => totalSeats.value - reservedSeatsCount.value - takenSeatsCount.value,
);

const totalPrice = computed(() => {
    return (
        selectedSeats.value.length * props.event.price
    );
});

const canSubmit = computed(() => {
    return (
        email.value !== '' &&
        termsAccepted.value &&
        (adultTickets.value > 0)
    );
});


const roundedRating = computed(() => {
    return props.event.show.average_rating !== null
        ? Math.round(props.event.show.average_rating * 10) / 10
        : null;
});

// Methods
const handleSeatClick = (row: number, col: number) => {
    const index = selectedSeats.value.findIndex(
        (s) => s.row === row && s.column === col,
    );
    if (index > -1) {
        selectedSeats.value.splice(index, 1);
    } else {
        selectedSeats.value.push({ row, column: col });
    }
};


const form = useForm({
    event_id: props.event.id,
    ...(user
        ? { }
        : {
            name: '',
            email: ''
        }),
    termsAccepted: '',
    selectedSeats: [] as Array<{ row: number; column: number }>
});



const handleSubmit = () => {
    form.selectedSeats = selectedSeats.value;
    form.post('/public/reservations', {
        onError: (errors) => {
            if (errors?.selectedSeats) {
                const message: string = errors.selectedSeats;
                const seatsPart = message.replace('Tieto miesta sú už zabraté: ', '').trim();
                const seatStrings = seatsPart.split(' ');
                const takenSeats: Array<{ row: number; column: number }> = seatStrings.map(s => {
                    const [row, column] = s.replace('[', '').replace(']', '').split(',');
                    return { row: parseInt(row, 10), column: parseInt(column, 10) };
                });
                selectedSeats.value = selectedSeats.value.filter(
                    s => !takenSeats.some(t => t.row === s.row && t.column === s.column)
                );

            }
        },
        onSuccess: () =>{
            selectedSeats.value.splice(0);
        }
    });
};
</script>

<style scoped>
.content {
    height: auto;
    gap: 1rem;
}
</style>
