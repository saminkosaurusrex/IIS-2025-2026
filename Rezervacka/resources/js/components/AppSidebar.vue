<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, User, Theater, Drama, Clapperboard, Tags, PersonStanding, TicketCheck } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage<{ auth: { user: { roles: string[] } | null } }>()
const userRoles = page.props.auth.user.roles

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Užívatelia',
        href: '/users',
        icon: User,
        roles: ['admin'],
    },
    {
        title: 'Sály',
        href: '/halls',
        icon: Theater,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Predstavenia',
        href: '/shows',
        icon: Drama,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Kultúrne udalosti',
        href: '/events',
        icon: Clapperboard,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Typy predstavení',
        href: '/show-types',
        icon: LayoutGrid,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Žánre',
        href: '/tags',
        icon: Tags,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Účinkujúci',
        href: '/performers',
        icon: PersonStanding,
        roles: ['admin', 'editor'],
    },
    {
        title: 'Registrácie',
        href: '/registrations',
        icon: TicketCheck,
        roles: ['admin', 'editor', 'cashier'],
    },
];

const filteredNavItems: NavItem[] = mainNavItems.filter(item =>
  !item.roles || item.roles.some(role => userRoles.includes(role))
)

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="filteredNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
