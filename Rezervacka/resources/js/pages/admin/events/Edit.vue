<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import dayjs from "dayjs";
import { watch } from 'vue';


interface Props {
  shows: { id: number, name: string }[];
  halls: { id: number, name: string }[];
  events: { hall_id: number, show_id: number, starting_at: string, ending_at: string }[];
  event: { id: number, hall_id: number, show_id: number, starting_at: string, ending_at: string, price: number };
}
const props = defineProps<Props>();
const parsedTime = dayjs(props.event.starting_at, "YYYY-MM-DD HH:mm:ss");
const eventDate = parsedTime.format("YYYY-MM-DD");
const eventStart = parsedTime.format("HH:mm");
const eventEnd = dayjs(props.event.ending_at, "HH:mm").format("HH:mm");
console.log(eventDate, eventStart, eventEnd)
const form = useForm({
  hall: props.event.hall_id,
  show: props.event.show_id,
  price: props.event.price,
  date: eventDate,
  start_time: eventStart,
  end_time: eventEnd
});

const handleSubmit = () => {
  console.log(form);
  form.put(`/events/${props.event.id}`);
};

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Úprava kultúrnej udalosti',
    href: `event/${props.event.id}/edit`,
  },
];

const selectHall = (hall: number) => {
  form.date = '';
  form.start_time = '';
  form.end_time = '';
  form.hall = hall;
  selectBlockedTimes();
};

const selectShow = (show: number) => {
  form.date = '';
  form.start_time = '';
  form.end_time = '';
  form.show = show;
  selectBlockedTimes();
};


interface BlockedTimeRange {
  start_at: string;
  end_at: string;
}

const blockedTimes: Record<string, BlockedTimeRange[]> = {};
function selectBlockedTimes() {
  Object.keys(blockedTimes).forEach(key => {
    delete blockedTimes[key];
  });
  const blocked = props.events.filter(event => event.hall_id === form.hall && !(event.hall_id == props.event.hall_id && event.show_id == props.event.show_id));

  blocked.forEach(event => {
    const parsedStartTime = dayjs(event.starting_at, "YYYY-MM-DD HH:mm:ss");
    const parsedEndTime = dayjs(event.ending_at, "YYYY-MM-DD HH:mm:ss");
    const dateKey = parsedStartTime.format("YYYY-MM-DD")
    if (!blockedTimes[dateKey]) {
      blockedTimes[dateKey] = [];
    }
    blockedTimes[dateKey].push({
      start_at: parsedStartTime.format("HH:mm"),
      end_at: parsedEndTime.format("HH:mm")
    })
  })
  console.log(blockedTimes)
}

const isBlocked = (hour: string) => {

  const dateKey = form.date;

  if (!dateKey) return false;

  const blockedForDate = blockedTimes[dateKey] || [];

  return blockedForDate.some(({ start_at, end_at }) => hour >= start_at && hour < end_at);
}

const isBlockedEnd = (hour: string) => {
  const dateKey = form.date;
  const timeKey = form.start_time;

  if (!dateKey) return false;

  const blockedForDate = blockedTimes[dateKey] || [];
  const firstBlocked= blockedForDate
        .slice()
        .sort((a, b) => a.start_at.localeCompare(b.start_at))
        .find(bt => bt.start_at > timeKey)?.start_at;
  if (!firstBlocked) {
    return hour <= timeKey;
  }
  return hour <= timeKey || hour > firstBlocked;
}

watch(
  () => form.hall,
  (newHall, oldHall) => {
    console.log('Hall sa zmenilo z', oldHall, 'na', newHall);
    selectBlockedTimes();
  },
  { immediate: true }
);


</script>

<template>

  <Head title="Vytvorenie kulturnej udalosti" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
        <div class="space-y-2">
          <Label for="Event hall">Výber sály pre udalosť</Label>
          <div class="flex space-x-2">
            <Button v-for="hall in props.halls" type="button"
              :class="form.hall === hall.id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:text-white'"
              @click="selectHall(hall.id)"> {{ hall.name }}
            </Button>
          </div>
          <div class="text-sm text-red-600" v-if="form.errors.hall">{{ form.errors.hall }}</div>
        </div>
        <div class="space-y-2">
          <Label for="Event hall">Výber udalosti</Label>
          <div class="flex space-x-2">
            <Button v-for="show in props.shows" type="button"
              :class="form.show === show.id ? 'bg-blue-600 text-white hover:bg-blue-400' : 'bg-red-400 text-white hover:text-white hover:bg-red-600'"
              @click="selectShow(show.id)"> {{ show.name }}
            </Button>
          </div>
          <div class="text-sm text-red-600" v-if="form.errors.show">{{ form.errors.show }}
          </div>
        </div>
        <div class="space-y-4">
          <div>
            <Label>Dátum</Label>
            <Input v-model="form.date" type="date" :min="new Date().toISOString().split('T')[0]"
              :disabled="!form.hall" />
            <div class="text-sm text-red-600" v-if="form.errors.date">{{ form.errors.date }}</div>
          </div>

          <div>
            <Label>Čas začiatku</Label>
            <select v-model="form.start_time" class="w-full p-2 border rounded" :disabled="!form.date">
              <option value="">Výber času</option>
              <option v-for="hour in 24" :key="hour" :value="(hour - 1).toString().padStart(2, '0') + ':00'"
                :disabled="isBlocked((hour - 1).toString().padStart(2, '0') + ':00')">
                {{ (hour - 1).toString().padStart(2, '0') }}:00
              </option>
            </select>
            <div class="text-sm text-red-600" v-if="form.errors.start_time">{{ form.errors.start_time }}</div>
          </div>

          <div>
            <Label>Čas konca</Label>
            <select v-model="form.end_time" class="w-full p-2 border rounded" :disabled="!form.start_time">
              <option value="">Vyberte čas</option>
              <option v-for="hour in 24" :key="hour" :value="(hour - 1).toString().padStart(2, '0') + ':00'"
                :disabled="isBlockedEnd((hour - 1).toString().padStart(2, '0') + ':00')">
                {{ (hour - 1).toString().padStart(2, '0') }}:00
              </option>
            </select>
            <div class="text-sm text-red-600" v-if="form.errors.end_time">{{ form.errors.end_time }}</div>
          </div>
          <div class="space-y-2">
            <Label for="Shows name">Cena lístka</Label>
            <Input v-model="form.price" type="number" placeholder="Cena lístka"></Input>
            <div class="text-sm text-red-600" v-if="form.errors.price">{{ form.errors.price }}</div>
          </div>
        </div>
        <Button type="submit" :disabled="form.processing">Vytvoriť kultúrnu udalosť</Button>
      </form>
    </div>
  </AppLayout>
</template>
