<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';

interface Show {
    id: number,
    name: string,
    show_type: { id: number, name: string },
    tags: { id: number, name: string }[],
    performers: { id: number, name: string }[],
    image: string,
    description: string,
}

interface Props {
    show_types: { id: number, name: string }[];
    tags: { id: number, name: string }[];
    performers: { id: number, name: string }[];
    show: Show;
}


const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Úprava predstavenia',
        href: `shows/${props.show.id}/edit`,
    },
];

const form = useForm({
    name: props.show.name,
    show_types: props.show.show_type.id,
    tags: props.show.tags.map(tag => tag.id),
    performers: props.show.performers.map(performer => performer.id),
    image: null as File | null,
    delete_image: false,
    description: props.show.description,
    _method: 'PUT'
});

const handleSubmit = () => {
    console.log(form);
    form.post(`/shows/${props.show.id}`, {
        forceFormData: true,
        preserveScroll: true,
    });
};


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

const removeImage = () => {
    form.delete_image = true;
};

const selectType = (type: number) => {
    form.show_types = type;
};

</script>

<template>

    <Head title="Upraviť predstavenia" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
                <div class="space-y-2">
                    <Label for="Shows name">Názov predstavenia</Label>
                    <Input v-model="form.name" type="text" placeholder="Názov predstavenia"></Input>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Shows name">Popisok</Label>
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
                    <Label for="Shows type">Vybrať typ predstavenia</Label>
                    <div class="flex space-x-2">
                        <Button v-for="show_type in props.show_types" type="button"
                            :class="form.show_types === show_type.id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:text-white'"
                            @click="selectType(show_type.id)"> {{ show_type.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.show_types">{{ form.errors.show_types }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="Show tags">Vybrať žánre</Label>
                    <div class="flex flex-wrap gap-3">
                        <Button v-for="tag in props.tags" type="button"
                            :class="form.tags.includes(tag.id) ? 'bg-blue-600 text-white hover:bg-blue-400' : 'bg-red-400 text-white hover:text-white hover:bg-red-600'"
                            @click="toggleTags(tag.id)"> {{ tag.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.tags">{{ form.errors.tags }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Show tags">Vybrať účinkujúcich</Label>
                    <div class="flex flex-wrap gap-3">
                        <Button v-for="performer in props.performers" type="button"
                            :class="form.performers.includes(performer.id) ? 'bg-blue-600 text-white hover:bg-blue-400' : 'bg-gray-400 text-white hover:text-white hover:bg-gray-600'"
                            @click="togglePerformers(performer.id)"> {{ performer.name }}
                        </Button>
                    </div>
                    <div class="text-sm text-red-600" v-if="form.errors.performers">{{ form.errors.performers }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="image">Obrázok</Label>
                    <div v-if="props.show.image != null" class="group">
                        <!-- Miniaturka -->
                        <img :src="props.show.image" alt="Image"
                            class="h-12 w-12 object-cover rounded cursor-pointer" />

                        <!-- Zväčšený obrázok cez celú tabuľku -->
                        <div
                            class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 pointer-events-none">
                            <img :src="props.show.image" alt="Image"
                                class="max-w-2xl max-h-[80vh] object-contain rounded-lg shadow-2xl" />
                        </div>
                        
                    </div>
                    <Button v-if="!form.delete_image && props.show.image" type="button" variant="destructive" size="sm" class="mt-2" @click="removeImage">
                            Odstrániť obrázok
                        </Button>
                    <Input v-else id="image" type="file" accept="image/*" @change="e => form.image = e.target.files[0]" />

                    <div class="text-sm text-red-600" v-if="form.errors.image">
                        {{ form.errors.image }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing">Upraviť predstavenie</Button>
            </form>
        </div>
    </AppLayout>
</template>
