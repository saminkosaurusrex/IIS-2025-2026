<template>
    <div class="seating-chart">
        <!-- Hlavná mriežka sedadiel -->
        <div class="flex">
            <!-- Čísla riadkov -->
            <div class="mr-4 flex flex-col">
                <div class="mb-2 flex flex-col items-center gap-1">
                    <FontAwesomeIcon icon="arrow-up" class="text-2xl" />
                    <div class="text-center text-sm leading-tight">
                        Smer Pozorovania
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <div
                        v-for="row in rows"
                        :key="'row-num-' + row"
                        class="mb-4 flex h-16 w-12 items-center justify-center text-lg font-bold"
                    >
                        {{ row }}
                    </div>
                </div>
            </div>

            <!-- Sedadlá a čísla stĺpcov -->
            <div>
                <!-- Čísla stĺpcov -->
                <div
                    class="mb-2 grid gap-4"
                    :style="`grid-template-columns: repeat(${columns}, 70px)`"
                >
                    <div
                        v-for="col in columns"
                        :key="'col-num-' + col"
                        class="flex h-10 items-center justify-center text-lg font-bold"
                    >
                        {{ col }}
                    </div>
                </div>

                <!-- Sedadlá -->
                <div
                    v-for="row in rows"
                    :key="'row-' + row"
                    class="mb-4 grid gap-4"
                    :style="`grid-template-columns: repeat(${columns}, 70px)`"
                >
                    <div
                        v-for="col in columns"
                        :key="'seat-' + row + '-' + col"
                        class="seat flex h-16 cursor-pointer items-center justify-center transition-all duration-200"
                        :class="getSeatClass(row, col)"
                        @click="handleSeatClick(row, col)"
                    >
                        {{ col }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

interface Props {
    rows: number;
    columns: number;
    reservedSeats: Array<{ row: number; column: number }>;
    selectedSeats?: Array<{ row: number; column: number }>;
}

interface Emits {
    (e: 'seat-click', row: number, col: number): void;
}

const props = withDefaults(defineProps<Props>(), {
    selectedSeats: () => [],
});

const emit = defineEmits<Emits>();

const isReserved = (row: number, col: number): boolean => {
    return props.reservedSeats.some(
        (seat) => seat.row === row && seat.column === col,
    );
};

const isSelected = (row: number, col: number): boolean => {
    return props.selectedSeats.some(
        (seat) => seat.row === row && seat.column === col,
    );
};

const getSeatClass = (row: number, col: number): string => {
    if (isReserved(row, col)) return 'seat-occupied';
    if (isSelected(row, col)) return 'seat-selected';
    return 'seat-available';
};

const handleSeatClick = (row: number, col: number): void => {
    if (!isReserved(row, col)) {
        emit('seat-click', row, col);
    }
};
</script>

<style scoped>
.seating-chart {
    display: flex;
    justify-content: center; /* centrovanie horizontálne */
    align-items: center; /* centrovanie vertikálne */
    width: 100%;
    min-height: 80vh; /* aby bolo stredne vysoko */
    background-color: white;
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
    padding: 2em;
    box-sizing: border-box;
    border-radius: 1.5rem;
}
.seat {
    border-radius: 8px 8px 0 0;
    font-weight: bold;
    font-size: 0.9rem;
}

.seat-available {
    background-color: #8b9db5;
    color: #1a1a1a;
}

.seat-available:hover {
    background-color: #6b7d95;
    transform: translateY(-2px);
}

.seat-selected {
    background-color: #9333ea;
    color: white;
}

.seat-occupied {
    background-color: #dc2626;
    color: white;
    cursor: not-allowed;
}

.seat-occupied:hover {
    background-color: #b91c1c;
}
</style>
