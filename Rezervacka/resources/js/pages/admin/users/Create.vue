<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Vytvorenie uživateľa',
        href: 'users/create',
    },
];

type Role = 'admin' | 'editor' | 'cashier' | 'user';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: [] as Role[]
});

const handleSubmit = () => {
    if (form.password !== form.password_confirmation) {
        form.setError("password_confirmation", "Heslá sa musia zhodovať.");
        return;
    }else{
        form.clearErrors("password_confirmation");
    }
    console.log(form);
    form.post('/users');
};

const toggleRole = (role: Role): void => {
    const index = form.role.indexOf(role);
    if (index > -1) {
        form.role.splice(index, 1);
    } else {
        form.role.push(role);
    }
};

</script>

<template>

    <Head title="Vytvorenie uživateľa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="User name">Meno a priezvisko</Label>
                    <Input v-model="form.name" type="text" placeholder="Meno a priezvisko"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="User email">E-mail</Label>
                    <Input v-model="form.email" type="email" placeholder="E-mail"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>

                <div class="space-y-2"> <Label for="User role">Rola</Label>
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
                    <div class="text-sm text-red-600" v-if="form.errors.role">{{ form.errors.role }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="User password">Heslo</Label>
                    <Input v-model="form.password" type="password" placeholder="Heslo"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.password"></div>
                </div>
                <div class="space-y-2">
                    <Label for="User password confirmation">Potvrdenie hesla</Label>
                    <Input v-model="form.password_confirmation" type="password" placeholder="Zopakuj heslo" />
                    <div class="text-sm text-red-600" v-if="form.errors.password_confirmation">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing">Vytvoriť uživateľa</Button>
            </form>
        </div>
    </AppLayout>
</template>
