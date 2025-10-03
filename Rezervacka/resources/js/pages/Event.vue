<template>
    <div class="app-layout flex">
        <div class="main-content flex-1 flex flex-col">
            <NavMenu />

            <div class="content p-4 text-black">
                <h1>{{ event.show.name }} - {{ event.hall.name }}</h1>

                <!-- Hlavná mriežka sedadiel s číslami -->
                <div class="flex">
                    <!-- Čísla riadkov -->
                    <div class="flex flex-col mr-1">
                        <div class="h-10"></div> <!-- prázdny roh pre horný ľavý roh -->
                        <div v-for="row in event.hall.rows" :key="'row-num-' + row"
                             class="mb-2 w-10 h-10 flex items-center justify-center">
                            {{ row }}
                        </div>
                    </div>

                    <!-- Sedadlá a čísla stĺpcov -->
                    <div>
                        <!-- Čísla stĺpcov -->
                        <div class="grid gap-2 "
                             :style="`grid-template-columns: repeat(${event.hall.columns}, 40px)`">
                            <div v-for="col in event.hall.columns" :key="'col-num-' + col"
                                 class="w-10 h-10 flex items-center justify-center font-bold">
                                {{ col }}
                            </div>
                        </div>

                        <!-- Sedadlá -->
                        <div
                            v-for="row in event.hall.rows"
                            :key="'row-' + row"
                            class="grid gap-2"
                            :style="`grid-template-columns: repeat(${event.hall.columns}, 40px)`"
                        >
                            <div
                                v-for="col in event.hall.columns"
                                :key="'seat-' + row + '-' + col"
                                class="mb-2 w-10 h-10 flex items-center justify-center border rounded overflow-hidden"
                                :class="isBooked(row, col) ? 'bg-red-500' : 'bg-gray-100'"
                            >
                                <img
                                    src="/images/seat.png"
                                    :alt="`Seat ${row}-${col}`"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import NavMenu from '../components/NavBar.vue';
import dayjs from 'dayjs';

interface ReservedSeat {
    row: number;
    column: number;
}

interface Hall {
    id: number;
    name: string;
    rows: number;
    columns: number;
}

interface Show {
    id: number;
    name: string;
}

interface Event {
    id: number;
    hall: Hall;
    show: Show;
    starting_at: string;
    ending_at: string;
    price: number;
}

interface Props {
    event: Event;
    reservedSeats: ReservedSeat[];
}

const props = defineProps<Props>();

const formatDateTime = (datetime: string) => dayjs(datetime).format('DD. MMM YYYY HH:mm')

// Funkcia na kontrolu, či je sedadlo obsadené rezerváciou
const isBooked = (row: number, col: number): boolean => {
    return props.reservedSeats.some(seat => seat.row === row && seat.column === col);
}
</script>

<style scoped>
.seat-grid {
    display: grid;
}
</style>
