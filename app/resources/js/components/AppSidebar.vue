<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, PlaySquare, History, Trophy } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.is_admin === true);

const mainNavItemsRaw: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'Simulate Match',
        href: route('simulate'),
        icon: PlaySquare,
    },
    {
        title: 'Match History',
        href: route('matches'),
        icon: History,
    },
    {
        title: 'Rankings',
        href: route('rank'),
        icon: Trophy,
    },
    {
        title: 'Players',
        href: route('players.index'),
        icon: Trophy,
    },
    {
        title: 'Games',
        href: route('games.index'),
        icon: Trophy,
        children: [
            { title: 'View Games', href: route('games.index') },
            { title: 'Create Game', href: route('games.create'), adminOnly: true },
        ],
    },
];

const mainNavItems = computed(() => {
    return mainNavItemsRaw.map(item => {
        if (!item.children) {
            return item;
        }
        return {
            ...item,
            children: item.children.filter(child => {
                if (child.adminOnly && !isAdmin.value) {
                    return false;
                }
                return true;
            }),
        };
    });
});

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
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
