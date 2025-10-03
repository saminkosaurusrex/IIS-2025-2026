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
.reservation-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    padding: 3rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.reservation-card {
    max-width: 700px;
    width: 100%;
    background-color: white;
    border-radius: 1.5rem;
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}
.reservation-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    padding: 3rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    padding: 2rem;
    color: white;
    position: relative;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.name {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.score {
    background-color: #dc2626;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 1.25rem;
}

.details {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.details span:first-child {
    background-color: #dc2626;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-weight: 700;
    font-size: 0.875rem;
}

.details span:nth-child(2) {
    font-weight: 600;
    font-size: 0.875rem;
}

.details span:nth-child(3) {
    color: #d1d5db;
    font-size: 0.875rem;
}

.tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.tags span {
    background-color: #374151;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
}

/* Content */
.content {
    padding: 2rem;
}

.info-box {
    background-color: #dc2626;
    color: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.info-box span:first-child {
    font-size: 1.125rem;
    opacity: 0.9;
}

.info-box span:last-child {
    font-size: 1.25rem;
    font-weight: 700;
}

.info-box.datetime span:last-child {
    font-size: 1.5rem;
}

/* Seats */
.seats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4rem;
    padding: 2rem 0;
    margin-bottom: 1rem;
}

.seat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.seat-box {
    width: 64px;
    height: 64px;
    border-radius: 0.5rem;
}

.seat-box.empty {
    background-color: #d1d5db;
}

.seat-box.taken {
    background-color: #dc2626;
}

.seat-count {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
}

.seat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Divider */
hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 1.5rem 0;
}

/* Tickets */
.tickets-section {
    padding: 0.5rem 0;
}

.ticket-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    font-size: 1.25rem;
}

.ticket-row span:first-child {
    font-weight: 600;
    color: #111827;
}

.ticket-row .count {
    font-weight: 700;
    font-size: 1.5rem;
    color: #111827;
}

/* Sum */
.sum {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
}

.sum span:last-child {
    font-size: 1.75rem;
}

/* Checkbox */
.checkbox-wrapper {
    display: flex;
    align-items: start;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.checkbox-wrapper label {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
    cursor: pointer;
}

/* Button */
.reserve-button {
    width: 100%;
    background-color: #2d3748;
    color: white;
    padding: 1rem 2rem;
    border-radius: 1rem;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.reserve-button:hover {
    background-color: #1a202c;
}

.email {
    display: flex;
    align-items: center;
    gap: 1rem; /* medzera medzi label a input */
    margin-bottom: 1em;
}

.email-label {
    flex-shrink: 0; /* label nemení veľkosť */
    font-size: 1.5rem;
    font-weight: 700;
}

.email-input {
    flex-grow: 1; /* input vyplní zvyšok riadku */
    border-radius: 0.75rem;
    font-size: 1rem;
    color: #111827;
}
</style>
