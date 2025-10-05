<template>
    <div class="reservation-card">
        <header class="header">
            <div class="header-top">
                <h1 class="name">{{ showName }}</h1>
                <div class="score">{{ rating }}</div>
            </div>
            <div class="details">
                <span>{{ ageRating }}</span>
                <span>{{ duration }} min</span>
                <span>{{ subtitles }}</span>
            </div>
            <div class="tags">
                <span v-for="genre in genres" :key="genre">{{ genre }}</span>
            </div>
        </header>

        <div class="content">
            <div class="info-box">
                <span>Kde:</span>
                <span>{{ location }}</span>
            </div>

            <div class="info-box datetime">
                <span>Kedy:</span>
                <span>{{ dateTime }}</span>
            </div>

            <div class="seats">
                <div class="seat">
                    <div class="seat-box empty"></div>
                    <span class="seat-count">{{ availableSeats }}</span>
                    <span class="seat-label">Voľné</span>
                </div>
                <div class="seat">
                    <div class="seat-box taken"></div>
                    <span class="seat-count">{{ occupiedSeats }}</span>
                    <span class="seat-label">Obsadené</span>
                </div>
            </div>

            <hr />

            <div class="tickets-section">
                <div class="ticket-row">
                    <span>Dospelý:</span>
                    <NumberField
                        :default-value="adultTickets"
                        :min="0"
                        @update:model-value="
                            (val) => $emit('update-adult', val)
                        "
                    >
                        <NumberFieldContent>
                            <NumberFieldDecrement />
                            <NumberFieldInput />
                            <NumberFieldIncrement />
                        </NumberFieldContent>
                    </NumberField>
                </div>
                <div class="ticket-row">
                    <span>Deti, ZTP, 60+:</span>
                    <NumberField
                        :default-value="discountedTickets"
                        :min="0"
                        @update:model-value="
                            (val) => $emit('update-discounted', val)
                        "
                    >
                        <NumberFieldContent>
                            <NumberFieldDecrement />
                            <NumberFieldInput />
                            <NumberFieldIncrement />
                        </NumberFieldContent>
                    </NumberField>
                </div>
            </div>

            <hr />

            <div class="sum">
                <span>Spolu:</span>
                <span>{{ totalPrice.toFixed(2) }} €</span>
            </div>

            <hr />

            <div class="email">
                <span class="email-label">Email:</span>
                <div class="email-input">
                    <input
                        type="email"
                        :value="email"
                        @input="
                            $emit(
                                'update-email',
                                ($event.target as HTMLInputElement).value,
                            )
                        "
                        class="w-full rounded-md border-2 border-gray-300 p-3 text-gray-900"
                    />
                </div>
            </div>

            <div class="checkbox-wrapper">
                <Checkbox
                    :checked="termsAccepted"
                    @update:checked="$emit('update-terms', $event)"
                />
                <label>Podmienky</label>
            </div>

            <button
                class="reserve-button"
                :disabled="true"
                @click="$emit('submit')"
            >
                Zaplatiť
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';

interface Props {
    showName: string;
    rating: string;
    ageRating: string;
    duration: number;
    subtitles: string;
    genres: string[];
    location: string;
    dateTime: string;
    availableSeats: number;
    occupiedSeats: number;
    adultTickets: number;
    discountedTickets: number;
    totalPrice: number;
    email: string;
    termsAccepted: boolean;
    canSubmit: boolean;
}

interface Emits {
    (e: 'update-adult', value: number): void;
    (e: 'update-discounted', value: number): void;
    (e: 'update-email', value: string): void;
    (e: 'update-terms', value: boolean): void;
    (e: 'submit'): void;
}

defineProps<Props>();
defineEmits<Emits>();
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

/* SEATS */
.seats {
    display: flex;
    justify-content: center;
    gap: clamp(1.5rem, 6vw, 4rem);
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
.seat-box.taken {
    background-color: #dc2626;
}

.seat-count {
    font-size: clamp(1.2rem, 3.5vw, 1.5rem);
    font-weight: 700;
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
