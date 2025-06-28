<script lang="ts" setup>
import { PropType } from 'vue';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuItem,
    SidebarMenuButton,
} from '@ui/sidebar';
import { Collapsible, CollapsibleTrigger, CollapsibleContent } from '@ui/collapsible';
import { ChevronDown } from 'lucide-vue-next';
import LucideIcon from './LucideIcon.vue';
import router from '@/router';

type Item = {
    title: string;
    icon: string;
    route: string;
};

export type MenuItemType = {
    title: string;
    icon: string;
    route: string;
    children?: Array<Item>;
};

defineProps<{
    title: string;
    items: MenuItemType[];
}>();

const currentRoute = router.currentRoute.value.name;
const hasActiveRoute = (items: Array<Item>) => {
    return items.some(item => {
        return item.route === currentRoute;
    });
};

</script>

<template>
    <SidebarGroup v-if="title">
        <SidebarGroupLabel class="h-6 text-zinc-400 dark:text-zinc-500 px-4">
            {{ title }}
        </SidebarGroupLabel>
    </SidebarGroup>

    <SidebarGroup v-for="item in items" :key="item.title" class="py-1">
        <Collapsible v-if="item.children?.length" :default-open="hasActiveRoute(item.children)" class="group/collapsible">
            <SidebarGroupLabel>
                <SidebarMenuButton asChild>
                    <CollapsibleTrigger>
                        <LucideIcon :name="item.icon" />
                        <span>{{ item.title }}</span>
                        <ChevronDown class="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-180" />
                    </CollapsibleTrigger>
                </SidebarMenuButton>
            </SidebarGroupLabel>
            <CollapsibleContent class="flex px-6 pt-2">
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="child in item.children" :key="child.title">
                            <SidebarMenuButton asChild>
                                <router-link :to="{ name: child.route }">
                                    <LucideIcon :name="child.icon" />
                                    <span>{{ child.title }}</span>
                                </router-link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </CollapsibleContent>
        </Collapsible>
        <SidebarGroupLabel v-else>
            <SidebarMenuButton asChild>
                <router-link :to="{ name: item.route }">
                    <LucideIcon :name="item.icon" />
                    <span>{{ item.title }}</span>
                </router-link>
            </SidebarMenuButton>
        </SidebarGroupLabel>
    </SidebarGroup>
</template>
