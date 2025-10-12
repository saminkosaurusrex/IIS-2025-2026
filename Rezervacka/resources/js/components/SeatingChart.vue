<template>
    <div class="seating-chart">
        <div class="header flex flex-col items-center justify-center">
            <FontAwesomeIcon icon="arrow-up" class="text-2xl" />
            <div class="text-center text-sm leading-tight">
                Smer Pozorovania
            </div>
        </div>

        <div class="seats-grid" :style="gridStyle">
            <div
                v-for="seatNumber in totalSeats"
                :key="'seat-' + seatNumber"
                class="seat"
                :class="
                    getSeatClass(
                        Math.floor((seatNumber - 1) / props.columns) + 1,
                        ((seatNumber - 1) % props.columns) + 1,
                    )
                "
                @click="
                    handleSeatClick(
                        Math.floor((seatNumber - 1) / props.columns) + 1,
                        ((seatNumber - 1) % props.columns) + 1,
                    )
                "
            >
                {{ seatNumber }}
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed } from 'vue';

interface Props {
    rows: number;
    columns: number;
    reservedSeats: Array<{ row: number; column: number }>;
    takenSeats: Array<{ row: number; column: number }>;
    ownReservedSeats?: Array<{ row: number; column: number }>;
    ownTakenSeats?: Array<{ row: number; column: number }>;
    selectedSeats?: Array<{ row: number; column: number }>;
}

interface Emits {
    (e: 'seat-click', row: number, col: number): void;
}

const props = withDefaults(defineProps<Props>(), {
    selectedSeats: () => [],
});

const emit = defineEmits<Emits>();

// Celkový počet sedadiel
const totalSeats = props.rows * props.columns;

// Dynamický grid štýl
const gridStyle = computed(() => ({
    display: 'grid',
    gridTemplateRows: `repeat(${props.rows}, 1fr)`,
    gridTemplateColumns: `repeat(${props.columns}, 1fr)`,
    gap: '5px',
    width: '100%',
    height: '80vh', // maximálna výška gridu
}));


const isOwnReserved= (row: number, col: number): boolean =>
    props.ownReservedSeats?.some((seat) => seat.row === row && seat.column === col) ?? false;
const isOwnTaken= (row: number, col: number): boolean =>
    props.ownTakenSeats?.some((seat) => seat.row === row && seat.column === col) ?? false;


const isReserved = (row: number, col: number): boolean =>
    props.reservedSeats.some((seat) => seat.row === row && seat.column === col);

const isTaken = (row: number, col: number): boolean =>
    props.takenSeats.some((seat) => seat.row === row && seat.column === col);

const isSelected = (row: number, col: number): boolean =>
    props.selectedSeats.some((seat) => seat.row === row && seat.column === col);

const getSeatClass = (row: number, col: number): string => {
    let border_style = "";
    if(isOwnReserved(row, col) || isOwnTaken(row, col)) {
        border_style += " own "
    }
    if (isReserved(row, col)) return 'seat-reserved' + border_style;
    if (isTaken(row, col)) return 'seat-taken' + border_style;
    if (isSelected(row, col)) return 'seat-selected'+ border_style;
    return 'seat-available';
};

const handleSeatClick = (row: number, col: number): void => {
    if (!isReserved(row, col) && !isTaken(row,col)) {
        emit('seat-click', row, col);
    }
};
</script>

<style scoped>
.seating-chart {
    background-color: white;
    display: grid;
    justify-content: center;
    justify-items: center;
    border-radius: 15px;
}

.seats-grid {
    display: grid;
    justify-content: center;
}

.seat {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    cursor: pointer;
    background-color: #8b9db5;
    color: white;
    border-radius: 4px;
    transition: 0.2s;
    aspect-ratio: 1 / 1; /* štvorcové bunky */
    width: 100%;
    height: 100%;
}

.own{
    border: 6px solid green;
}
.seat-reserved {
    background-color: #FF8000;
}
.seat-taken{
    background-color: #dc2626;
}

.seat-selected {
    background-color: green;
}

.seat-available {
    background-color: #8b9db5;
}
</style>
