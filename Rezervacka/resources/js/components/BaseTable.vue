<script setup lang="ts">
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { Head, Link, } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
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

interface TableHeaderValues {
    id: number,
    name: string,
    description: string,
    address: string,
    rows: number,
    columns: number
}

interface TableValues {
    id: number,
    name: string,
    description: string,
    address: string,
    rows: number,
    columns: number
}
interface Props {
    tableHeader: string[];       // názvy stĺpcov
    tableValues: TableValues[];  // riadky
    nameProps: Name;             // názov, zmena názvu, link
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.nameProps.name,
        href: props.nameProps.link,
    },
];

</script>
<template>

    <Head :title="props.nameProps.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <Link :href="`/halls/create`"><Button>Vytvoriť {{ props.nameProps.changeName }}</Button></Link>
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
                    <TableBody>
                        <TableRow v-for="tValue in props.tableValues">
                            <TableCell>{{ tValue.id }}</TableCell>
                            <TableCell class="font-medium">{{ tValue.name }}</TableCell>
                            <TableCell class="overflow-x-auto max-w-xs whitespace-nowrap">{{ tValue.description }}
                            </TableCell>
                            <TableCell class="overflow-x-auto max-w-xs whitespace-nowrap">{{ tValue.address }}
                            </TableCell>
                            <TableCell>{{ tValue.rows }}</TableCell>
                            <TableCell>{{ tValue.columns }}</TableCell>
                            <TableCell class="text-center space-x-2">
                                <Link :href="`/halls/${tValue.id}/edit`">
                                <Button class="bg-slate-600">Edit</Button>
                                </Link>
                                <Button class="bg-red-600">Delete</Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

            </div>

        </div>
    </AppLayout>
</template>
