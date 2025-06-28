<script lang="ts" setup>
import Header from '@/components/partials/Header.vue';
import { SidebarProvider } from '@ui/sidebar';
import LeftSidebar from '@/components/partials/LeftSidebar.vue';
</script>

<template>
    <div class="flex w-full">
        <SidebarProvider>
            <LeftSidebar />
            <main class="flex flex-col flex-grow max-w-full min-h-screen">
                <Header />
                <router-view />
            </main>
        </SidebarProvider>
    </div>
</template>