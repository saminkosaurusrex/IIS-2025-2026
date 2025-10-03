<template>
    <div class="app-layout flex">
        <div class="main-content flex flex-1 flex-col">
            <NavMenu />

            <div class="content p-4">
                <div class="grid grid-cols-2 gap-8">
                    <!-- Ľavá strana - Rezervácia -->
                    <ReservationSummary
                        :show-name="event.show.name"
                        :rating="event.show.rating"
                        :age-rating="event.show.age_rating"
                        :duration="event.show.duration"
                        :subtitles="event.show.subtitles"
                        :genres="event.show.genres"
                        :location="event.hall.name"
                        :date-time="formatDateTime(event.starting_at)"
                        :available-seats="availableSeatsCount"
                        :occupied-seats="occupiedSeatsCount"
                        :adult-tickets="adultTickets"
                        :discounted-tickets="discountedTickets"
                        :total-price="totalPrice"
                        :email="email"
                        :terms-accepted="termsAccepted"
                        :can-submit="canSubmit"
                        @update-adult="adultTickets = $event"
                        @update-discounted="discountedTickets = $event"
                        @update-email="email = $event"
                        @update-terms="termsAccepted = $event"
                        @submit="handleSubmit"
                    />

                    <!-- Pravá strana - Sedadlá -->
                    <SeatingChart
                        :rows="event.hall.rows"
                        :columns="event.hall.columns"
                        :reserved-seats="reservedSeats"
                        :selected-seats="selectedSeats"
                        @seat-click="handleSeatClick"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import ReservationSummary from '@/components/EventCard.vue';
import NavMenu from '@/components/NavBar.vue';
import SeatingChart from '@/components/SeatingChart.vue';
import { computed, ref } from 'vue';

// Props z backendu
interface Props {
    event: {
        show: {
            name: string;
            rating: string;
            age_rating: string;
            duration: number;
            subtitles: string;
            genres: string[];
        };
        hall: {
            name: string;
            rows: number;
            columns: number;
        };
        starting_at: string;
        price: number;
    };
    reservedSeats: Array<{ row: number; column: number }>;
}

const props = defineProps<Props>();

// Lokálny state
const selectedSeats = ref<Array<{ row: number; column: number }>>([]);
const adultTickets = ref(0);
const discountedTickets = ref(0);
const email = ref('');
const termsAccepted = ref(false);

// Computed
const totalSeats = computed(
    () => props.event.hall.rows * props.event.hall.columns,
);
const occupiedSeatsCount = computed(() => props.reservedSeats.length);
const availableSeatsCount = computed(
    () => totalSeats.value - occupiedSeatsCount.value,
);

const totalPrice = computed(() => {
    return (
        adultTickets.value * props.event.price +
        discountedTickets.value * (props.event.price * 0.8)
    );
});

const canSubmit = computed(() => {
    return (
        email.value !== '' &&
        termsAccepted.value &&
        (adultTickets.value > 0 || discountedTickets.value > 0)
    );
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

const formatDateTime = (datetime: string) => {
    // Tvoja formátovacia logika
    return datetime;
};

const handleSubmit = () => {
    // Odoslanie rezervácie
    console.log('Submitting reservation...', {
        selectedSeats: selectedSeats.value,
        adultTickets: adultTickets.value,
        discountedTickets: discountedTickets.value,
        email: email.value,
    });
};
</script>
