<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Textarea from '@/components/ui/textarea/Textarea.vue';


interface Hall {
    id: number,
    name: string,
    description: string,
    address: string,
    rows: number,
    columns: number
}



const props = defineProps<{hall: Hall}>();



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Úprava sály',
        href: `halls/${props.hall.id}/edit`,
    },
];

const form = useForm({
    name: props.hall.name,
    description: props.hall.description,
    address: props.hall.address,
    rows: props.hall.rows,
    columns: props.hall.columns
});

const handleSubmit = () => {
    console.log(form);
    form.put(`/halls/${props.hall.id}`);
};

</script>

<template>

    <Head title="Vytvorenie sály" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="Hall name">Názov sály</Label>
                    <Input v-model="form.name" type="text" placeholder="Názov sály"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Hall email">Adresa</Label>
                    <Input v-model="form.address" type="text" placeholder="Adresa"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.address">{{ form.errors.address }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Hall description">Popis</Label>
                    <Textarea v-model="form.description" type="text" placeholder="Popis"/>
                    <div class="text-sm text-red-600" v-if="form.errors.description">{{ form.errors.description }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Hall rows">Riadky</Label>
                    <Input v-model="form.rows" type="number" placeholder="Riadky"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.rows">{{ form.errors.rows }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Hall columns">Stĺpce</Label>
                    <Input v-model="form.columns" type="number" placeholder="Stĺpce"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.columns">{{ form.errors.columns }}</div>
                </div>
                <Button type="submit" :disabled="form.processing">Upraviť sálu</Button>
            </form>
        </div>
    </AppLayout>
</template>
