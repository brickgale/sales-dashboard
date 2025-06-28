<script lang="ts" setup>
import { 
    Sidebar,
    SidebarContent,
} from '@ui/sidebar';
import SBMenuItem from './SBMenuItem.vue';

const menu = [
    {
        title: 'Main Menu',
        menu: [
            { title: 'Dashboard', icon: 'ChartLine', route: 'dashboard' },
            { title: 'Pizzas', icon: 'Pizza', route: 'pizzas' },
            { title: 'Orders', icon: 'ArrowLeftRight', route: 'orders' },
        ],
    },
    {
        title: 'Settings',
        menu: [
            { title: 'Edit Profile', icon: 'UserRoundPen', route: 'edit-profile' },
        ],
    },
];
</script>

<template>
    <Sidebar class="app-menu">
        <SidebarContent class="gap-0">
            <div class="flex p-2 h-[50px] items-center">
                <h2 class="w-full text-lg font-semibold text-center">Sales Dashboard</h2>
            </div>
            <SBMenuItem v-for="(item, key) in menu" :key="key" :title="item.title" :items="item.menu" />
        </SidebarContent>
    </Sidebar> 
</template>