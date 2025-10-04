<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';


interface Show_Type {
    id: number,
    name: string,
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Vytváranie typu predstavenia',
        href: `show_types/create`,
    },
];

const form = useForm({
    name: '',
});

const handleSubmit = () => {
    console.log(form);
    form.post(`/show_types/`);
};

</script>

<template>

    <Head title="Vytvorenie typu predstavenia" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="Show_Type name">Typ predstavenia</Label>
                    <Input v-model="form.name" type="text" placeholder="Typ predstavenia"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <Button type="submit" :disabled="form.processing">Vytvoriť typ predstavenia</Button>
            </form>
        </div>
    </AppLayout>
</template>
