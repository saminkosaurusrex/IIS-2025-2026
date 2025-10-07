<template>
    <div class="reservation-card">
        <header class="header">
            <div class="header-top">
                <h1 class="name">{{ showName }}</h1>
                <div v-if="rating" class="score">{{ rating }}</div>
            </div>
            <div class="details">
                <span>{{ duration }} min</span>
            </div>
            <div class="tags">
                <span v-for="tag in tags" :key="tag.id">{{ tag.name }}</span>
            </div>
        </header>

        <div class="content">
            <div class="info-box">
                <span>Kde:</span>
                <span>{{ hall }}</span>

            </div>

            <div class="info-box">
                <span>Adresa:</span>
                <span style="font-size: clamp(0.75rem, 2vw, 0.875rem);font-weight: 500">
                    {{ location }}</span>
            </div>

            <div class="info-box datetime">
                <span>Kedy:</span>
                <span>{{ dateTime }}</span>
            </div>

            <div class="seats">

                <div v-if="availableSeats" class="seat">
                    <div class="seat-box empty"></div>
                    <span class="seat-count">{{ availableSeats }}</span>
                    <div class="seat-label">Voľné</div>
                </div>

                <div v-if="selectedSeats" class="seat">
                    <div class="seat-box selected"></div>
                    <span
                        class="seat-count"
                        :class="{ 'over-limit': selectedSeats > 6 }">
                        {{ selectedSeats > 6 ? selectedSeats + '/6' : selectedSeats }}
                    </span>
                    <div class="seat-label">Vybraté</div>
                </div>
                <div v-if="ownReservedSeats" class="seat items-center">
                    <div class="seat-box reserved own"></div>
                    <span class="seat-count">{{ ownReservedSeats }}</span>
                    <div class="seat-label">Vami </div>
                    <div class="seat-label"> Rezervované</div>
                </div>
                <div v-if="reservedSeats" class="seat items-center">
                    <div class="seat-box reserved "></div>
                    <span class="seat-count">{{ reservedSeats }}</span>
                    <div class="seat-label">Rezervované</div>
                </div>
                <div v-if="ownTakenSeats" class="seat">
                    <div class="seat-box taken own"></div>
                    <span class="seat-count">{{ ownTakenSeats }}</span>
                    <div class="seat-label">Vami </div>
                    <div class="seat-label"> Obsadené</div>
                </div>
                <div v-if="takenSeats" class="seat">
                    <div class="seat-box taken"></div>
                    <span class="seat-count">{{ takenSeats }}</span>
                    <div class="seat-label">Obsadené</div>
                </div>
            </div>

            <hr />



            <hr />

            <div class="sum">
                <span>Spolu:</span>
                <span>{{ totalPrice.toFixed(2) }} €</span>
            </div>
            <hr />

            <form @submit.prevent="emit('submit')" class="w-8/12 space-y-4">


                <div class="space-y-4">
                    <div v-if="!user">
                        <Label for="Name">*Meno</Label>
                        <Input v-model="form.name" type="text"/>
                        <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                    </div>

                    <div v-if="!user">
                        <Label for="Email">*Email</Label>
                        <Input v-model="form.email" type="email"/>
                        <div class="text-sm text-red-600" v-if="form.errors.email">{{ form.errors.email }}</div>
                    </div>

                    <div class="text-sm text-red-600" v-if="form.errors.event_id">{{ form.errors.event_id }}</div>
                    <div  class="text-sm text-red-600">
                        {{ form.errors.selectedSeats }}
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.selectedSeatsTooMany">{{ form.errors.selectedSeatsTooMany }}</div>



                    <Button type="submit" :disabled="form.processing">Rezervovať</Button>

                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import Label from './ui/label/Label.vue';
import { Button } from '@/components/ui/button';
import Input from './ui/input/Input.vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

interface Props {
    form: ReturnType<typeof useForm>;
    showName: string;
    rating: number | null;
    duration: number;
    tags: {
        id: number;
        name: string;
    }[];
    hall: string;
    location: string;
    dateTime: string;
    availableSeats: number;
    selectedSeats: number;
    takenSeats: number;
    reservedSeats: number;
    ownTakenSeats: number;
    ownReservedSeats: number;
    adultTickets: number;
    totalPrice: number;
    email: string;
    termsAccepted: boolean;
    canSubmit: boolean;
}
const page = usePage();
const user = page.props.auth.user;


interface Emits {
    (e: 'update-adult', value: number): void;
    (e: 'update-email', value: string): void;
    (e: 'update-terms', value: boolean): void;
    (e: 'submit'): void;
}
const emit = defineEmits<Emits>()

defineProps<Props>();

</script>

<style scoped>
.reservation-card {
    width: 100%;
    max-width: 600px;
    background-color: rgb(255, 255, 255);
    border-radius: 1rem;
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: 100%;
    align-items: stretch;
    min-height: 85vh;
}

/* HEADER */
.header {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    padding: 1.5rem;
    color: white;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.name {
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    font-weight: 700;
    line-height: 1.2;
}

.score {
    background-color: #dc2626;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: clamp(1rem, 3vw, 1.25rem);
}

.details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.tags span {
    background-color: #374151;
    padding: 0.4rem 0.8rem;
    border-radius: 9999px;
    font-size: clamp(0.75rem, 2vw, 0.875rem);
}

/* CONTENT */
.content {
    padding: 1.5rem;
}

.info-box {
    background-color: #dc2626;
    color: white;
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.info-box span:first-child {
    font-size: clamp(1rem, 3vw, 1.125rem);
}

.info-box span:last-child {
    font-size: clamp(1.25rem, 4vw, 1.5rem);
    font-weight: 700;
}


.seat {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* SEATS */
.seats {
    display: flex;
    justify-content: center;
    gap: clamp(1.5rem, 6vw, 2rem);
    padding: 1.5rem 0;
    flex-wrap: wrap;
}

.seat-box {
    width: clamp(48px, 10vw, 64px);
    height: clamp(48px, 10vw, 64px);
    border-radius: 0.5rem;
}

.seat-box.empty {
    background-color: #d1d5db;
}
.seat-box.selected {
    background-color: green;
}
.seat-box.reserved {
    background-color: #FF8000;
}
.seat-box.taken {
    background-color: #dc2626;
}

.seat-box.own {
    border: 6px solid green;
}

.seat-count {
    font-size: clamp(1.2rem, 3.5vw, 1.5rem);
    font-weight: 700;
}

.seat-count.over-limit {
    color: red;
    font-weight: bold;
}

/* SUM */
.sum {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    font-size: clamp(1.2rem, 4vw, 1.5rem);
    font-weight: 700;
}

/* EMAIL */
.email {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1em;
}

@media (min-width: 480px) {
    .email {
        flex-direction: row;
        align-items: center;
    }
}

.email-label {
    font-size: clamp(1.1rem, 3vw, 1.5rem);
    font-weight: 700;
}

/* CHECKBOX */
.checkbox-wrapper {
    display: flex;
    align-items: start;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
}

/* BUTTON */
.reserve-button {
    width: 100%;
    background-color: #2d3748;
    color: white;
    padding: 1rem 2rem;
    border-radius: 1rem;
    font-size: clamp(1rem, 3.5vw, 1.25rem);
    font-weight: 700;
    text-align: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.reserve-button:hover {
    background-color: #1a202c;
}

/* SMALLER SCREENS */
@media (max-width: 480px) {
    .content {
        padding: 1rem;
    }

    .info-box {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }

    .seats {
        gap: 2rem;
    }

    .ticket-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .sum {
        font-size: 1.2rem;
    }
}
</style>
