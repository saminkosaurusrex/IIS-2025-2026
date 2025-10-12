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
                <SeatingChart
                    :rows="rows"
                    :columns="columns"
                    :reserved-seats="reservedSeats"
                    :selected-seats="selectedSeats"
                    @seat-click="handleSeatClick"
                    class="flex-1"
                />

                <!-- Reservation card -->
                <ReservationCard
                    :show-name="showName"
                    :rating="rating"
                    :age-rating="ageRating"
                    :duration="duration"
                    :subtitles="subtitles"
                    :genres="genres"
                    :location="location"
                    :date-time="dateTime"
                    :available-seats="availableSeats"
                    :occupied-seats="occupiedSeats"
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
                    class="flex-1"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import ReservationCard from '../components/EventCard.vue';
import NavMenu from '../components/NavBar.vue';
import SeatingChart from '../components/SeatingChart.vue';

// Seating chart
const rows = 6;
const columns = 6;
const reservedSeats = ref([
    { row: 1, column: 2 },
    { row: 2, column: 3 },
]);
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
};

// Reservation card
const showName = ref('The Great Show');
const rating = ref('8.5');
const ageRating = ref('12+');
const duration = ref(120);
const subtitles = ref('SK, EN');
const genres = ref(['Drama', 'Action']);
const location = ref('Cinema City');
const dateTime = ref('05.10.2025 19:00');
const availableSeats = computed(
    () => rows * columns - reservedSeats.value.length,
);
const occupiedSeats = computed(() => reservedSeats.value.length);

const adultTickets = ref(0);
const discountedTickets = ref(0);
const totalPrice = computed(
    () => adultTickets.value * 10 + discountedTickets.value * 7,
);
const email = ref('');
const termsAccepted = ref(false);
const canSubmit = computed(
    () =>
        termsAccepted.value && adultTickets.value + discountedTickets.value > 0,
);

const handleSubmit = () => {
    alert(
        `Rezervácia odoslaná pre ${adultTickets.value + discountedTickets.value} osôb na ${showName.value}`,
    );
};
</script>

<style scoped>
.content {
    height: 94vh;
    gap: 1rem;
}
</style>
