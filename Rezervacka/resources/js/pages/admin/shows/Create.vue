<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Vytvorenie predstavenia',
        href: 'shows/create',
    },
];

const form = useForm({
    name: '',
    show_types: null as number | null,
    tags: [] as number[],
    performers: [] as number[],
    image: File,
    description: '',
});

const handleSubmit = () => {
    console.log(form);
    form.post('/shows');
};

interface Props {
    show_types: { id: number; name: string }[];
    tags: { id: number; name: string }[];
    performers: { id: number; name: string }[];
}

const toggleTags = (tag: number) => {
    const index = form.tags.indexOf(tag);
    if (index > -1) {
        form.tags.splice(index, 1);
    } else {
        form.tags.push(tag);
    }
};

const togglePerformers = (performer: number) => {
    const index = form.performers.indexOf(performer);
    if (index > -1) {
        form.performers.splice(index, 1);
    } else {
        form.performers.push(performer);
    }
};

const props = defineProps<Props>();

const selectType = (type: number) => {
    form.show_types = type;
};
</script>

<template>
    <Head title="Vytvorenie predstavenia" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="Shows name">*Názov predstavenia</Label>
                    <Input
                        v-model="form.name"
                        type="text"
                        placeholder="Názov predstavenia"
                    ></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Shows name">*Popisok</Label>
                    <Input
                        v-model="form.description"
                        type="text"
                        placeholder="Názov predstavenia"
                    ></Input>
                    <div
                        class="text-sm text-red-600"
                        v-if="form.errors.description"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Shows type">*Vybrať typ predstavenia</Label>
                    <div class="flex space-x-2">
                        <Button
                            v-for="show_type in props.show_types"
                            type="button"
                            :class="
                                form.show_types === show_type.id
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-200 text-gray-800 hover:text-white'
                            "
                            @click="selectType(show_type.id)"
                        >
                            {{ show_type.name }}
                        </Button>
                    </div>
                    <div
                        class="text-sm text-red-600"
                        v-if="form.errors.show_types"
                    >
                        {{ form.errors.show_types }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="Show tags">*Vybrať žánre</Label>
                    <div class="flex flex-wrap gap-3">
                        <Button
                            v-for="tag in props.tags"
                            type="button"
                            :class="
                                form.tags.includes(tag.id)
                                    ? 'bg-blue-600 text-white hover:bg-blue-400'
                                    : 'bg-red-400 text-white hover:bg-red-600 hover:text-white'
                            "
                            @click="toggleTags(tag.id)"
                        >
                            {{ tag.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.tags">
                        {{ form.errors.tags }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Show tags">*Vybrať účinkujúcich</Label>
                    <div class="flex flex-wrap gap-3">
                        <Button
                            v-for="performer in props.performers"
                            type="button"
                            :class="
                                form.performers.includes(performer.id)
                                    ? 'bg-blue-600 text-white hover:bg-blue-400'
                                    : 'bg-gray-400 text-white hover:bg-gray-600 hover:text-white'
                            "
                            @click="togglePerformers(performer.id)"
                        >
                            {{ performer.name }}
                        </Button>
                    </div>
                    <div
                        class="text-sm text-red-600"
                        v-if="form.errors.performers"
                    >
                        {{ form.errors.performers }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="image">Obrázok</Label>
                    <Input
                        id="image"
                        type="file"
                        accept="image/*"
                        @change="(e) => (form.image = e.target.files[0])"
                    />

                    <div class="text-sm text-red-600" v-if="form.errors.image">
                        {{ form.errors.image }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Vytvoriť predstavenie</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
