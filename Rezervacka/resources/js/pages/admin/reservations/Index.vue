<script setup lang="ts">

import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import Button from '../../../components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '../../../components/ui/label/Label.vue';
import InputError from '@/components/InputError.vue';
import type { DateRange } from "reka-ui"
import type { Ref } from "vue"
import {CalendarDate, getLocalTimeZone, today, parseDateTime,now  } from "@internationalized/date"
import { ref,watch } from "vue"
import { RangeCalendar } from "@/components/ui/range-calendar"
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover'
import { Input } from '@/components/ui/input';

interface Props {
    reservations: any;
    halls: any;
    selectedHalls: {
        id: number;
        name: string;
    }[];
    shows:{
        id: number;
        name: string;
    }[];
    selectedShows:{
        events:{
            id: number;
            name: string;
        }
    }[];
    startDatetime: string|null;
    endDatetime: string|null;
    selectedRow: number|null;
    selectedCol: number|null;
}

const props = defineProps<Props>();


const tz = getLocalTimeZone();
const todayDate = today(tz);

const inputStart = props.startDatetime ? parseDateTime(props.startDatetime) : null;
const inputEnd = props.endDatetime ? parseDateTime(props.endDatetime) : null;

const start =
    inputStart &&
    inputStart.year >= todayDate.year - 100 &&
    inputStart.year <= todayDate.year + 100
        ? inputStart
        : todayDate;

const end =
    inputEnd &&
    inputEnd.year >= todayDate.year - 100 &&
    inputEnd.year <= todayDate.year + 100
        ? inputEnd
        : start.add({ days: 7 });


const value = ref({
    start,
    end,
}) as Ref<DateRange>

const updateOptions = {
    confirm:  1,
    confirmAndPay: 2,
    pay: 3,
    cancel: 4
}

const datePickerNames =
    [
        {   "id": 1,   name: "Budúce podujatia" },
        {   "id": 2,   name: "Všetky podujatia" },
        {   "id": 3,   name: "Vlastný rozsah" },

    ]
const showPopover= ref(-1)
const openCalendar = ref(false)
const selectedDatePickerNames = ref(
    !inputEnd ? 1 : inputEnd.year === 9999 ? 2 : 3);

const updateForm = useForm({
        reservation_id: "",
        action: "",
    }
);

const updateReservation = (
    reservation: Props['reservations'][number],
    action: (typeof updateOptions)[keyof typeof updateOptions]
) => {
    updateForm.reservation_id = reservation.id;
    updateForm.action = action.toString();
    updateForm.put(`reservations`,{preserveScroll: true})
};

const form = useForm({
    halls: props.selectedHalls
        ? props.selectedHalls.map(id => Number(id))
        : [] as number[],
    shows: props.selectedShows
        ? props.selectedShows.map(id => Number(id))
        : [] as number[],
    start_date: props.startDatetime ,
    end_date: props.endDatetime,
    selectedRow: props.selectedRow,
    selectedCol: props.selectedCol,});

const selectDate = (dateNameId: number) => {
    selectedDatePickerNames.value = dateNameId;
    switch (dateNameId) {
        case 1:{
            form.start_date = now(getLocalTimeZone()).toAbsoluteString();
            form.end_date= ""
            form.get("reservations")
            break
        }
        case 2:{
            form.start_date = new CalendarDate(1,0,0).toString()
            form.end_date= new CalendarDate(9999,0,0).toString()
            form.get("reservations")
            break
        }
        case 3:{
            openCalendar.value = true;
        }
    }

};

watch(() => form.selectedRow, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        form.get("reservations",{preserveState: true,})
    }
})

watch(() => form.selectedCol, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        form.get("reservations",{preserveState: true,})
    }
})
watch(openCalendar, (newValue, oldValue) => {
    if (oldValue === true && newValue === false) {
        form.start_date = value.value.start?.toString()
        form.end_date = value.value.end ? value.value.end.toString() : value.value.start?.toString()
        form.get("reservations")
    }
})
const selectHall = (hallId: number) => {
    if (!form.halls.includes(hallId)) {
        form.halls.push(hallId);
    }else {
        form.halls = form.halls.filter(id => id !== hallId);
    }
    form.get("reservations")
};

const selectShow = (showId: number) => {
    if (!form.shows.includes(showId)) {
        form.shows.push(showId);
    }else {
        form.shows = form.shows.filter(id => id !== showId);
    }
    form.get("reservations")
};


const getReservationStatus = (reservation: Props['reservations'][number]) => {
    if (reservation.canceled_at) return 'Zrušené';
    if (reservation.paid_at) return 'Zaplatené';
    if (reservation.confirmed_at) return 'Potvrdené';
    return 'Rezervované - čaká na potvrdenie';
};



</script>

<template>
    <Head title="Rezervácie" />

    <AppLayout>
        <div class="p-4">



            <div>
                <Link  :href="'reservations/create'">
                    <Button class="mb-8" >
                        Vytvoriť Rezerváciu
                    </Button>
                </Link >
                <div class="space-y-2">

                    <div class="space-y-2 space-x-2">
                        <Label for="Event hall">Výber sály</Label>
                        <div class="space-x-2">
                            <Button v-for="hall in props.halls" :key="hall.id" type="button"
                                    :class="form.halls.includes(hall.id) ? 'bg-blue-600 text-white mt-2' : 'mt-2 bg-gray-200 text-gray-800 hover:text-white'"
                                    @click="selectHall(hall.id)"> {{ hall.name }}
                            </Button>
                        </div>
                        <div class="text-sm text-red-600" v-if="form.errors.halls">{{ form.errors.halls }}</div>

                        <Label for="Event hall">Výber predstavenia</Label>
                        <div class="space-x-2">
                            <Button v-for="show in props.shows" :key="show.id" type="button"
                                    :class="form.shows.includes(show.id) ? 'bg-blue-600 text-white mt-2' : ' mt-2 bg-gray-200 text-gray-800 hover:text-white'"
                                    @click="selectShow(show.id)"> {{ show.name }}
                            </Button>
                        </div>

                        <Label for="Event hall">Výber miesta</Label>
                        <div class="space-x-2">
                            <div class="flex items-center gap-2">
                                <label for="row" class="w-16 text-sm font-medium">Rada:</label>
                                <Input    min="1"
                                          max="10000" id="row" v-model="form.selectedRow" type="number"  class="w-24" />

                                <label for="column" class="w-16 text-sm font-medium">Miesto:</label>
                                <Input    min="1"
                                          max="10000" id="column" v-model="form.selectedCol" type="number" class="w-24" />
                            </div>

                            <div class="flex items-center gap-2">

                            </div>

                        </div>
                    </div>


                    <div class="space-y-2 space-x-2">
                        <Label for="Event hall">Výber dátumu podujatia</Label>
                        <div class="space-x-2">
                            <Button v-for="action in datePickerNames" :key="action.id" type="button"
                                    :class="selectedDatePickerNames == action.id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:text-white'"
                                    @click="selectDate(action.id)"> {{ action.name }}
                            </Button>

                            <Popover v-model:open="openCalendar">
                                <PopoverTrigger>
                                </PopoverTrigger>
                                <PopoverContent>
                                    <RangeCalendar
                                        v-model="value"
                                        class="p-3 rounded-md border bg-gray-200"
                                    />
                                </PopoverContent>
                            </Popover>
                        </div>


                    </div>
                    <div class="space-x-2 flex items-center justify-between p-4">
                        <div class="text-sm text-red-600" v-if="form.errors.halls">{{ form.errors.halls }}</div>


                    </div>


                </div>

                <div v-if="props.reservations.data.length > 0" >
                    <InputError :message="updateForm.errors.reservation_id" />
                    <Table >
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-left">Sála</TableHead>
                                <TableHead class="text-left">Predstavenie</TableHead>
                                <TableHead class="text-left">Začiatok</TableHead>
                                <TableHead class="text-left">Cena</TableHead>
                                <TableHead class="text-left">Rada</TableHead>
                                <TableHead class="text-left">Miesto</TableHead>
                                <TableHead class="text-left">Stav</TableHead>
                                <TableHead class="text-left">Akcie</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody class="relative">
                            <TableRow
                                v-for="reservation in props.reservations.data"
                                :key="reservation.id"
                            >
                                <TableCell>{{reservation.event.hall.name}}</TableCell>
                                <TableCell class="max-w-[150px] truncate text-left" >{{reservation.event.show.name}}</TableCell>
                                <TableCell >{{reservation.event.starting_at_human}}</TableCell>
                                <TableCell>{{reservation.event.price}} €</TableCell>
                                <TableCell>{{reservation.row}}</TableCell>
                                <TableCell>{{reservation.column}}</TableCell>
                                <TableCell >
                                    {{ getReservationStatus(reservation) }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-row items-center gap-x-2">
                                        <template v-if="!reservation.canceled_at">

                                            <Button @click="updateReservation(reservation,updateOptions.confirm)"
                                                    :disabled="updateForm.processing"
                                                    v-if="!reservation.confirmed_at" class="bg-green-600">
                                                Potvrdiť
                                            </Button>
                                            <Button
                                                @click="updateReservation(reservation,updateOptions.confirmAndPay)"
                                                :disabled="updateForm.processing"
                                                v-if="!reservation.confirmed_at" class="bg-green-700">
                                                Potvrdiť a Zaplatiť
                                            </Button>
                                            <Button
                                                @click="updateReservation(reservation,updateOptions.pay)"
                                                :disabled="updateForm.processing"
                                                v-if="!reservation.paid_at  && reservation.confirmed_at" class="bg-green-600">
                                                Zaplatiť
                                            </Button>
                                            <Button
                                                @click="updateReservation(reservation,updateOptions.cancel)"
                                                :disabled="updateForm.processing"
                                                class="bg-red-600">
                                                Zrušiť
                                            </Button>
                                            <div class="relative cursor-pointer"
                                                 @mouseenter="showPopover = reservation.id"
                                                 @mouseleave="showPopover = -1">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                </svg>
                                                <div v-if="showPopover === reservation.id"
                                                     class="absolute z-50 top-0 right-full mr-2 bg-gray-200 p-4 rounded-md shadow-lg w-auto">
                                                    <p :class="reservation.user ? 'text-green-600' : 'text-red-600'">
                                                        Registrovaný? : {{ reservation.user ? "Áno" : "Nie" }}
                                                    </p>
                                                    <p>Meno: {{ reservation.user?.name || reservation.name }}</p>
                                                    <p>Email: {{ reservation.user?.email || reservation.email }}</p>
                                                </div>
                                            </div>

                                        </template>
                                    </div>

                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div class="flex flex-row w-full items-center justify-center my-8 space-x-2 text-xl">
                        <Link
                            v-for="link in reservations.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-3 py-1 border rounded transition-colors duration-200"
                            :class="{
          'bg-blue-500 text-white pointer-events-none cursor-default': link.active,
          'text-gray-500 pointer-events-none cursor-not-allowed': !link.url,
          'hover:bg-blue-100': link.url && !link.active
        }"
                        >
                            {{
                                link.label.includes("Previous")
                                    ? 'Predchádzajúca'
                                    : link.label.includes("Next")
                                        ? 'Ďalšia'
                                        : link.label
                            }}
                        </Link>
                    </div>
                </div>
                <Label v-else>Žiadne rezervácie</Label>

            </div>


        </div>
    </AppLayout>
</template>
