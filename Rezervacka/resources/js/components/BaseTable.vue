<script setup lang="ts">
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { Head, Link, router, usePage} from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert/';
import { Rocket } from 'lucide-vue-next';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'


interface Name {
    name: string,
    changeName: string,
    link: string
}

interface TableValues {
    id?: number,
    name?: string,
    access_code?: string,
    hallName?: string,
    showName?: string,
    starting_at?: string,
    ending_at?: string,
    price?: number,
    email?: string,
    role?: string,
    description?: string,
    type?: string,
    address?: string,
    image?: string,
    rows?: number,
    columns?: number
    tags?: string
    performers?: string[]
    confirmed_at?: string
    canceled_at?: string
}
interface Props {
    tableHeader: string[];       // názvy stĺpcov
    tableValues: TableValues[];  // riadky
    nameProps: Name;             // názov, zmena názvu, link
}

const page = usePage();

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.nameProps.name,
        href: props.nameProps.link,
    },
];

const deleteHandle = (id: number) => {
    if(confirm('Chceš vymazať záynam ' + props.tableValues.find(u => u.id === id)?.name + '?')){
        router.delete(`${props.nameProps.link}/${id}`);
    }
};

</script>
<template>

    <Head :title="props.nameProps.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash?.success" class="mb-4">
                <Alert>
                    <Rocket class="h-4 w-4" />
                    <AlertTitle>Notifikácia!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash.success }}
                    </AlertDescription>
                </Alert>
            </div>
            <Link v-if="props.nameProps.link != '/dashboard'" :href="`${props.nameProps.link}/create`"><Button>Vytvoriť {{ props.nameProps.changeName }}</Button>
            </Link>
            <div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead v-for="hName in props.tableHeader">
                                {{ hName }}
                            </TableHead>
                            <TableHead class="text-center">Činnosti</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody class="relative">
                        <TableRow v-for="tValue in props.tableValues" :key="tValue.id">
                            <TableCell v-if="tValue.id && props.nameProps.link != '/dashboard'" >{{ tValue.id }}</TableCell>
                            <TableCell v-if="tValue.name" class="font-medium">{{ tValue.name }}</TableCell>
                            <TableCell v-if="tValue.email">{{ tValue.email }}</TableCell>
                            <TableCell v-if="tValue.access_code" class="font-medium">Skrytý</TableCell>
                            <TableCell v-if="tValue.hallName" class="font-medium">{{ tValue.hallName }}</TableCell>
                            <TableCell v-if="tValue.showName" class="font-medium">{{ tValue.showName }}</TableCell>
                            <TableCell v-if="tValue.description" class="overflow-x-auto max-w-xs whitespace-nowrap">{{
                                tValue.description }}
                            </TableCell>
                            <TableCell v-if="tValue.address" class="overflow-x-auto max-w-xs whitespace-nowrap">{{
                                tValue.address }}
                            </TableCell>
                            <TableCell v-if="tValue.type">{{ tValue.type }}</TableCell>
                            <TableCell v-if="tValue.starting_at">{{ tValue.starting_at }}</TableCell>
                            <TableCell v-if="tValue.ending_at">{{ tValue.ending_at }}</TableCell>
                            <TableCell v-if="tValue.price">{{ tValue.price }} €</TableCell>
                            <TableCell v-if="tValue.tags">{{ tValue.tags }} </TableCell>
                            <TableCell v-if="tValue.role">{{ tValue.role }}</TableCell>
                            <TableCell v-if="tValue.performers" class="relative group">
                                <span class="underline cursor-pointer">Zobraziť účinkujúcich</span>

                                <!-- Hover list -->
                                <div
                                    class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 pointer-events-none">
                                    <ul class="pointer-events-none bg-white text-black p-3 m-3 rounded shadow">
                                        <li v-for="performer in tValue.performers" :key="performer"
                                            >
                                            {{ performer }}
                                        </li>
                                    </ul>
                                </div>
                            </TableCell>
                            <TableCell v-if="tValue.image">
                                <div class="group">
                                    <!-- Miniaturka -->
                                    <img :src="tValue.image" alt="Image"
                                        class="h-12 w-12 object-cover rounded cursor-pointer" />

                                    <!-- Zväčšený obrázok cez celú tabuľku -->
                                    <div
                                        class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 pointer-events-none">
                                        <img :src="tValue.image" alt="Image"
                                            class="max-w-2xl max-h-[80vh] object-contain rounded-lg shadow-2xl" />
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell v-else-if="!tValue.image && props.nameProps.name === 'Predstavenia'">
                                <div class="group center">
                                    Bez obrázku
                                </div>
                            </TableCell>
                            <TableCell v-if="tValue.rows">{{ tValue.rows }}</TableCell>
                            <TableCell v-if="tValue.columns">{{ tValue.columns }}</TableCell>
                            <TableCell v-if="tValue.canceled_at" class="bg-red-400 rounded-full text-center text-white">Zrušené</TableCell>
                            <TableCell v-else-if="tValue.confirmed_at" class="bg-green-400 rounded-full text-center text-white">Potvrdené</TableCell>
                            <TableCell v-else-if="props.nameProps.link === '/dashboard'" class="bg-orange-400 rounded-full text-center text-white">Čakajúce</TableCell>
                            <TableCell v-if="props.nameProps.link != '/dashboard'" class="text-center space-x-2">
                                <Link :href="`${props.nameProps.link}/${tValue.id}/edit`">
                                <Button class="bg-slate-600">Upraviť</Button>
                                </Link>
                                <Button @click="deleteHandle(tValue.id)" class="bg-red-600">Vymazať</Button>
                            </TableCell>
                            <TableCell v-else class="text-center">
                                <Link :href="`${props.nameProps.link}/${tValue.id}/show`">
                                <Button class="bg-slate-600">Zobraziť</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

            </div>

        </div>
    </AppLayout>
</template>
