<script lang="ts" setup>
import { SidebarTrigger } from '@ui/sidebar';
import { Button } from '@ui/button';
import { Sun, Moon, LogOut } from 'lucide-vue-next';
import router from '@/router';
import { useColorMode } from '@vueuse/core';
import { useAuthStore } from '@/stores/auth';
import { handleError } from '@/lib/utils';
import { toast } from 'vue-sonner';

const authStore = useAuthStore();
const mode = useColorMode();

const toggleMode = () => mode.value = mode.value === 'dark' ? 'light' : 'dark';

const logout = () => {
    authStore.logout()
        .then(() => {
            router.push({ name: 'login' });
            toast.success('Logged out Successfully');
        }, (err) => {
            handleError('Logout Failed', err);
        });
};
</script>

<template>
    <header class="app-header h-[50px] max-w-full flex items-center gap-2 p-4 border border-l-0 justify-between">
        <SidebarTrigger />
        <div class="flex gap-2">
            <Button variant="ghost" size="icon" @click="toggleMode">
                <Sun v-if="mode == 'dark'" class="w-4 h-4" />
                <Moon v-else class="w-4 h-4" />
            </Button>
        <Button 
            variant="ghost"
            @click="logout"
        >
            <LogOut class="w-4 h-4" />
        </Button>
        </div>
    </header>
</template>