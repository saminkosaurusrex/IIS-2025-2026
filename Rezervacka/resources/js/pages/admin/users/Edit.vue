<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive } from "vue"


type Role = 'admin' | 'editor' | 'cashier' | 'user';

interface User {
    id: number,
    name: string,
    email: string,
    role: Role[],
    halls_user: number[]
}

interface Props {
    user: User,
    halls: { id: number, name: string }[]
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Úprava uživateľa',
        href: `users/${props.user.id}/edit`,
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role as Role[],
    halls: props.user.halls_user ? (props.user.halls_user as number[]) : [] as number[]
});

const handleSubmit = () => {
    if(!form.halls[0]){
        console.log(form.halls);
        form.halls.splice(0, 1);
    }
    form.put(`/users/${props.user.id}`);
};

const toggleRole = (role: Role): void => {
    const index = form.role.indexOf(role);
    if (index > -1) {
        form.role.splice(index, 1);
    } else {
        form.role.push(role);
    }
};

const toggleHall = (hall: number): void => {
    const index = form.halls.indexOf(hall);
    if (index > -1) {
        form.halls.splice(index, 1);
    } else {
        form.halls.push(hall);
    }
};

</script>

<template>

    <Head title="Úprava uživateľa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="User name">*Meno a priezvisko</Label>
                    <Input v-model="form.name" type="text" placeholder="Meno a priezvisko"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="User email">*E-mail</Label>
                    <Input v-model="form.email" type="email" placeholder="E-mail"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>

                <div class="space-y-2"> <Label for="User role">*Rola</Label>
                    <div class="flex space-x-2">
                        <Button type="button"
                            :class="form.role.includes('admin') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                            @click="toggleRole('admin')"> Administrátor
                        </Button>
                        <Button type="button"
                            :class="form.role.includes('editor') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                            @click="toggleRole('editor')"> Redaktor
                        </Button>
                        <Button type="button"
                            :class="form.role.includes('cashier') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                            @click="toggleRole('cashier')"> Pokladník </Button>
                        <Button type="button"
                            :class="form.role.includes('user') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                            @click="toggleRole('user')"> Používateľ
                        </Button>
                    </div>
                    <div v-if="form.role.includes('cashier')" class="space-y-2"> <Label for="User role">Pre vybrané
                            sály</Label>
                        <div class="flex space-x-2">
                            <Button v-for="hall in props.halls" type="button"
                                :class="form.halls.includes(hall.id) ? 'bg-blue-600 text-white hover:bg-blue-400' : 'bg-red-400 text-white hover:text-white hover:bg-red-600'"
                                @click="toggleHall(hall.id)"> {{ hall.name }}
                            </Button>
                        </div>
                        <div class="text-sm text-red-600" v-if="form.errors.halls">{{ form.errors.halls }}
                        </div>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.role">{{ form.errors.email }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing">Upraviť uživateľa</Button>
            </form>
        </div>
    </AppLayout>
</template>
