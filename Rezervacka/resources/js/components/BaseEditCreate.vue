<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Textarea from '@/components/ui/textarea/Textarea.vue';


interface Type {
    id: number,
    name: string,
}

interface Name {
    action: string,
    changeActionName: string,
    name: string,
    changeName: string,
    link: string,
    actionLink: string
}

interface Props {
    tableValues?: Type;  // riadky
    nameProps: Name;    // názov, zmena názvu, link
}


const props = defineProps<Props>();



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `${props.nameProps.action} ${props.nameProps.changeName}`,
        href: `/${props.nameProps.link}${props.tableValues?.id}/${props.nameProps.actionLink}`,
    },
];

const form = useForm({
    name: props.tableValues?.name ?? '',
});

const handleSubmit = () => {
    console.log(form);
    props.nameProps.actionLink === 'create' ?
    form.post(`/${props.nameProps.link}`) :
    form.put(`/${props.nameProps.link}${props.tableValues?.id}`);
};

</script>

<template>

    <Head :title="$props.nameProps.action + ' ' + props.nameProps.changeName" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label>*{{ props.nameProps.name }}</Label>
                    <Input v-model="form.name" type="text" :placeholder="props.nameProps.name"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <Button type="submit" :disabled="form.processing">{{ props.nameProps.changeActionName }} {{props.nameProps.name}}</Button>
            </form>
        </div>
    </AppLayout>
</template>
