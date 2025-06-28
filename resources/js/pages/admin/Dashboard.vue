<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { useAuthStore } from '@/stores/auth';
import { computed, ref } from 'vue';

const authStore = useAuthStore();

const name = computed(() => {
    return authStore.user?.name || 'Guest';
});

// Dashboard stats - these would typically come from an API
const stats = ref({
    totalPizzas: 24,
    totalOrders: 156,
    totalSales: 12340.50
});
</script>

<template>
    <div class="flex h-screen w-full p-6">
        <div class="w-full max-w-4xl mx-auto flex flex-col h-full">
            <h2 class="text-2xl font-semibold mb-6">Hello, {{ name }}!</h2>
            
            <!-- Stats Cards -->
            <div class="flex gap-6 mb-8">
                <!-- Pizza Count Card -->
                <Card class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Total Pizzas</p>
                                <p class="text-3xl font-bold">{{ stats.totalPizzas }}</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Orders Count Card -->
                <Card class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Orders</p>
                                <p class="text-3xl font-bold">{{ stats.totalOrders }}</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Total Sales Card -->
                <Card class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Total Sales</p>
                                <p class="text-3xl font-bold">${{ stats.totalSales.toLocaleString() }}</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

        </div>
    </div>
</template>