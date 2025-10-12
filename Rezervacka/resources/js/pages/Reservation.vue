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
                    <div class="reservation-card">
                        <header class="header">
                            <div class="header-top">
                                <h1 class="name">{{ event.show.name }}</h1>
                                <div v-if="event.show.average_rating" class="score">{{ roundedRating }}</div>
                            </div>
                            <div class="details">
                                <span>{{ event.ending_at_human }} min</span>
                            </div>
                            <div class="tags">
                                <span v-for="tag in event.show.tags" :key="tag.id">{{ tag.name }}</span>
                            </div>
                        </header>

                        <div class="content">
                            <div class="info-box">
                                <span>Kde:</span>
                                <span>{{ event.hall.name }}</span>

                            </div>

                            <div class="info-box">
                                <span>Adresa:</span>
                                <span style="font-size: clamp(0.75rem, 2vw, 0.875rem);font-weight: 500">
                    {{ event.hall.address }}</span>
                            </div>

                            <div class="info-box datetime">
                                <span>Kedy:</span>
                                <span>{{ event.starting_at_human }}</span>
                            </div>
                            <hr />



                            <hr />

                            <div class="sum">
                                <span>Spolu:</span>
                                <span>{{ totalPrice.toFixed(2) }} €</span>
                            </div>
                            <hr />

                            <div  class="w-8/12 space-y-4">


                                <div class="space-y-4">
                                    <div>
                                        <Label for="Name">Meno</Label>
                                        <div>{{props.event.reservations[0].name}}</div>

                                    </div>

                                    <div>
                                        <Label for="Email">Email</Label>
                                        <div>{{props.event.reservations[0].email}}</div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                <div class="reservation-table-container overflow-x-auto">
                    <table class="reservation-table w-full border-collapse">
                        <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 text-left">Meno</th>
                            <th class="p-2 text-left">Email</th>
                            <th class="p-2 text-left">Rada</th>
                            <th class="p-2 text-left">Stĺpec</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Dátum</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="reservation in event.reservations" :key="reservation.id"
                            :style="{
                    backgroundColor: reservation.canceled_at ? '#f8d7da' :
                                     reservation.paid_at ? '#d4edda' :
                                     reservation.confirmed_at ? '#cce5ff' :
                                     reservation.reserved_at ? '#fff3cd' :
                                     '#e2e3e5'
                }"
                        >
                            <td class="p-2">{{ reservation.name }}</td>
                            <td class="p-2">{{ reservation.email }}</td>
                            <td class="p-2">{{ reservation.row }}</td>
                            <td class="p-2">{{ reservation.column }}</td>
                            <td class="p-2">
                                <span v-if="reservation.canceled_at">Zrušená</span>
                                <span v-else-if="reservation.paid_at">Zaplatená</span>
                                <span v-else-if="reservation.confirmed_at">Potvrdená</span>
                                <span v-else-if="reservation.reserved_at">Vytvorená</span>
                                <span v-else>Neznámy</span>
                            </td>
                            <td class="p-2">{{ reservation.reserved_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <Link :href="loginUrl">
                        <Button>
                            Pokračovať s registráciou
                        </Button>
                    </Link>

                </div>





            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import NavMenu from '@/components/NavBar.vue';
import { computed } from 'vue';
import Label from '../components/ui/label/Label.vue';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

// Props z backendu
interface Props {
    event: {
        show: {
            name: string;
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
        reservations: {
            id: number
            name: string;
            email: string;
            access_code: string;
            row: number;
            column: number;
            reserved_at: string;
            confirmed_at: string | null;
            paid_at: string | null;
            canceled_at: string | null;
        }[];
        id: number;
        starting_at: string;
        starting_at_human: string,
        ending_at_human: number;
        price: number;
    };

}

const props = defineProps<Props>();



const totalPrice = computed(() => {
    return (
        props.event.reservations.length * props.event.price
    );
});

const loginUrl = computed(() => {
    const accessCodes = props.event.reservations.map(r =>
        `accessCode=${encodeURIComponent(r.access_code)}`
    );
    const first = props.event.reservations[0];

    accessCodes.push(`name=${encodeURIComponent(first.name)}`);
    accessCodes.push(`email=${encodeURIComponent(first.email)}`);

    return `/register?${accessCodes.join('&')}`;
});

const roundedRating = computed(() => {
    return props.event.show.average_rating !== null
        ? Math.round(props.event.show.average_rating * 10) / 10
        : null;
});



</script>

<style scoped>
.content {
    height: 94vh;
    gap: 1rem;
}
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

