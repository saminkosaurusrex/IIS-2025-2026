<template>
    <div class="app-layout flex">
        <!-- Sidebar vľavo -->

        <!-- Hlavná časť stránky -->
        <div class="main-content flex flex-1 flex-col">
            <!-- TopBar hore -->
            <NavMenu />

            <div v-if="groupedUpcoming.length > 0">

                <h1 class="text-4xl font-bold px-6 pt-4">Budúce Udalosti</h1>



                <div class="grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-4  p-4" >
                    <template v-for="(group, index) in groupedUpcoming" :key="`upcoming-${index}`">
                    <!-- Left: reservation card -->
                    <div class="reservation-card border p-4 rounded shadow flex flex-col justify-center">
                        <header class="header">
                            <div class="header-top flex justify-between items-center">
                                <h1 class="name font-bold text-lg">{{ group[0].event.show.name }}</h1>
                                <div v-if="group[0].event.show.average_rating" class="score">
                                    {{ roundedRating(group) }}
                                </div>
                            </div>
                            <div class="details text-sm text-white">
                                <span>{{ group[0].event.ending_at_human }} min</span>
                            </div>
                            <div class="tags mt-1 flex flex-wrap gap-1 text-xs">
          <span v-for="tag in group[0].event.show.tags" :key="tag.id" class="bg-gray-200 px-2 py-1 rounded">
            {{ tag.name }}
          </span>
                            </div>
                        </header>
                        <div class="content mt-2">
                            <div class="info-box flex justify-between text-sm">
                                <span>Kde:</span>
                                <span>{{ group[0].event.hall.name }}</span>
                            </div>
                            <div class="info-box flex justify-between text-sm">
                                <span>Adresa:</span>
                                <span class="font-medium text-xs sm:text-sm">{{ group[0].event.hall.address }}</span>
                            </div>
                            <div class="info-box flex justify-between text-sm">
                                <span>Kedy:</span>
                                <span>{{ group[0].event.starting_at_human }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: table rows for this group -->
                    <div class="overflow-x-auto">
                        <Table class="max-w-xl bg-white border">
                            <TableBody class="relative">
                                    <TableRow class="bg-gray-100 font-bold ">
                                        <TableCell>Rada</TableCell>
                                        <TableCell>Stĺpec</TableCell>
                                        <TableCell>Cena</TableCell>
                                        <TableCell>Status</TableCell>
                                        <TableCell>Lístok</TableCell>

                                    </TableRow>
                                    <TableRow v-for="item in group" :key="item.id" >

                                        <TableCell>{{ item.row }}</TableCell>
                                        <TableCell>{{ item.column }}</TableCell>
                                        <TableCell>{{ item.event.price }} €</TableCell>
                                        <TableCell :class="getReservationStatusStyle">
                                            {{ getReservationStatus(item) }}</TableCell>


                                        <TableCell >
                                            <a :href="`/my-reservations/download?accessCode=${item.access_code}`" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                            </a>

                                        </TableCell>

                                    </TableRow>
                            </TableBody>
                        </Table>
                        <div class="mt-4">
                            <a :href="downloadAllReservationUrl(group)" target="_blank">
                                <Button>
                                    Stiahnuť všetky lístky
                                </Button>
                            </a>
                        </div>


                    </div>


                </template>
                </div>
            </div>

            <div v-if="groupedPast.length > 0" class="mt-6">
                <h2 class="text-4xl font-bold px-6 pt-4">Minulé události</h2>
                <div class="grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-4  p-4" >
                    <template v-for="(group, index) in groupedPast" :key="`past-${index}`">
                    <!-- Left: reservation card -->
                    <div class="reservation-card border p-4 rounded shadow flex flex-col justify-center">
                        <header class="header">
                            <div class="header-top flex justify-between items-center">
                                <h1 class="name font-bold text-lg">{{ group[0].event.show.name }}</h1>
                                <div v-if="group[0].event.show.average_rating" class="score">
                                    {{ roundedRating(group) }}
                                </div>
                            </div>
                            <div class="details text-sm text-white">
                                <span>{{ group[0].event.ending_at_human }} min</span>
                            </div>
                            <div class="tags mt-1 flex flex-wrap gap-1 text-xs">
          <span v-for="tag in group[0].event.show.tags" :key="tag.id" class="bg-gray-200 px-2 py-1 rounded">
            {{ tag.name }}
          </span>
                            </div>
                        </header>
                        <div class="content mt-2">
                            <div class="info-box flex justify-between text-sm">
                                <span>Kde:</span>
                                <span>{{ group[0].event.hall.name }}</span>
                            </div>
                            <div class="info-box flex justify-between text-sm">
                                <span>Adresa:</span>
                                <span class="font-medium text-xs sm:text-sm">{{ group[0].event.hall.address }}</span>
                            </div>
                            <div class="info-box flex justify-between text-sm">
                                <span>Kedy:</span>
                                <span>{{ group[0].event.starting_at_human }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: table rows for this group -->
                    <div class="overflow-x-auto">
                        <Table class="max-w-xl bg-white border">
                            <TableBody class="relative">
                                <TableRow class="bg-gray-100 font-bold ">
                                    <TableCell>Rada</TableCell>
                                    <TableCell>Stĺpec</TableCell>
                                    <TableCell>Cena</TableCell>
                                    <TableCell>Status</TableCell>


                                </TableRow>
                                <TableRow v-for="item in group" :key="item.id" >

                                    <TableCell>{{ item.row }}</TableCell>
                                    <TableCell>{{ item.column }}</TableCell>
                                    <TableCell>{{ item.event.price }} €</TableCell>
                                    <TableCell :class="getReservationStatusStyle">
                                        {{ getReservationStatus(item) }}</TableCell>


                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>


                </template>
                </div>
            </div>

            <h1 class="text-3xl p-8" v-if="groupedUpcoming.length == 0 && groupedPast.length == 0">
                Žiadne rezervácie
            </h1>

            <Link :href="loginUrl" class="p-4"
                  v-if="!$page.props.auth.user && (groupedUpcoming.length != 0 || groupedPast.length != 0)">
                <Button >
                    Dokončiť registráciu a spárovať rezervácie
                </Button>
            </Link>

        </div>
    </div>
</template>





<script setup lang="ts">
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import NavMenu from '@/components/NavBar.vue';
import { computed } from 'vue';
import {Button} from "@/components/ui/button";
import {Link} from "@inertiajs/vue3";

interface Props {
    reservations: {
        id: number
        access_code: string;
        row: number;
        column: number;
        reserved_at: string;
        confirmed_at: string | null;
        paid_at: string | null;
        canceled_at: string | null;
        name: string | null;
        email: string | null;

        event:{
            starting_at: string;
            starting_at_human: string,
            ending_at_human: number;
            price: number;
            show: {
                name: string;
                image: string;
                average_rating: number | null;
                tags:{
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
        }
    }[];
}

const now = new Date()

const upcomingReservations = computed(() =>
    props.reservations.filter(r => new Date(r.event.starting_at) > now)
)

const pastReservations = computed(() =>
    props.reservations.filter(r => new Date(r.event.starting_at) <= now)
)

const groupedUpcoming = computed(() => {
    const groups: Record<string, typeof props.reservations> = {};
    upcomingReservations.value.forEach((res) => {
        const key = `${res.event.hall.name}_${res.event.show.name}_${res.event.starting_at_human}`;
        if (!groups[key]) groups[key] = [];
        groups[key].push(res);
    });
    return Object.values(groups);
});

const groupedPast = computed(() => {
    const groups: Record<string, typeof props.reservations> = {};
    pastReservations.value.forEach((res) => {
        const key = `${res.event.hall.name}_${res.event.show.name}_${res.event.starting_at_human}`;
        if (!groups[key]) groups[key] = [];
        groups[key].push(res);
    });
    return Object.values(groups);
});

const props = defineProps<Props>();

const downloadAllReservationUrl = (group) => {
    const codes = group.map(r => r.access_code).join(',');
    const params = new URLSearchParams();
    params.append('accessCode', codes);
    return `/my-reservations/download?${params.toString()}`;
};


const loginUrl = computed(() => {
    const codes = props.reservations.map(r => r.access_code).join(',');
    const first = props.reservations[0];

    const params = new URLSearchParams();
    params.append('accessCode', codes);

    if (first?.name) {
        params.append('name', first.name);
    }
    if (first?.email) {
        params.append('email', first.email);
    }

    return `/register?${params.toString()}`;
});


const getReservationStatus = (reservation: Props['reservations'][number]) => {
    if (reservation.canceled_at) return 'Zrušené';
    if (reservation.confirmed_at) return 'Potvrdené';
    if (reservation.paid_at) return 'Zaplatené';
    return 'Rezervované - čaká na potvrdenie';
};

const getReservationStatusStyle = (reservation: Props['reservations'][number]) => {
    if (reservation.canceled_at) return 'Zrušené';
    if (reservation.confirmed_at) return 'Potvrdené';
    if (reservation.paid_at) return 'Zaplatené';
    return 'bg-gray-500';
};


const roundedRating = (group: typeof props.reservations) => {
    const rating = group[0].event.show.average_rating;
    return rating !== null ? Math.round(rating * 10) / 10 : null;
};



</script>
<style scoped>
/* CARD */
.reservation-card {
    width: 100%;
    max-width: 600px;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 6px 10px -3px rgba(0,0,0,0.1), 0 3px 3px -3px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* HEADER */
.header {
    background: linear-gradient(135deg, #1f2937, #111827);
    padding: 0.75rem;
    color: #fff;
}

.header-top {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.2rem;
}

.name {
    font-size: clamp(1rem, 2.5vw, 1.5rem);
    font-weight: 700;
    line-height: 1.1;
}

.score {
    background: #dc2626;
    color: #fff;
    padding: 0.2rem 0.5rem;
    border-radius: 0.4rem;
    font-weight: 700;
    font-size: clamp(0.75rem, 2vw, 0.85rem);
}

.details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.3rem;
    margin-bottom: 0.2rem;
    font-size: clamp(0.65rem, 1.8vw, 0.75rem);
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.2rem;
}

.tags span {
    background: #374151;
    padding: 0.2rem 0.5rem;
    border-radius: 9999px;
    font-size: clamp(0.6rem, 1.5vw, 0.7rem);
}

/* CONTENT */
.content {
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-box {
    background: #dc2626;
    color: #fff;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.2rem;
}

.info-box span:first-child {
    font-size: clamp(0.7rem, 1.8vw, 0.8rem);
}

.info-box span:last-child {
    font-size: clamp(0.85rem, 2.2vw, 1rem);
    font-weight: 700;
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


}
</style>

