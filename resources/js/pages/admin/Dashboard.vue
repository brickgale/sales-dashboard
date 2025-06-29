<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { Button } from '@ui/button';
import { Input } from '@ui/input';
import { Label } from '@ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@ui/select';
import { useAuthStore } from '@/stores/auth';
import { useDashboardStore } from '@/stores/dashboard';
import { computed, onMounted, ref } from 'vue';
import { handleError, months } from '@/lib/utils';
import { Pizza, ShoppingCart, DollarSign } from 'lucide-vue-next';

const authStore = useAuthStore();
const dashboardStore = useDashboardStore();

const selectedMonth = ref("1"); // January as string
const selectedYear = ref(2015);

const name = computed(() => {
    return authStore.user?.name || 'Guest';
});

const fetchDashboardData = async () => {
    try {
        await dashboardStore.fetchStats({
            month: selectedMonth.value ? parseInt(selectedMonth.value) : undefined,
            year: selectedYear.value
        });
    } catch (error) {
        handleError('Error fetching dashboard stats', error);
    }
};

const applyFilter = () => {
    fetchDashboardData();
};

onMounted(() => {
    fetchDashboardData();
});

</script>

<template>
    <div class="flex h-screen w-full p-6">
        <div class="w-full max-w-4xl mx-auto flex flex-col h-full">
            <h2 class="text-2xl font-semibold mb-6">Hello, {{ name }}!</h2>
            
            <!-- Stats Cards -->
            <div class="flex flex-col md:flex-row gap-6 mb-8">
                <!-- Pizza Count Card -->
                <Card class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Total Ordered Pizzas</p>
                                <p class="text-3xl font-bold">
                                    {{ dashboardStore.loading ? '...' : dashboardStore.stats.total_pizzas }}
                                </p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                <Pizza class="w-8 h-8 text-orange-500" />
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
                                <p class="text-3xl font-bold">
                                    {{ dashboardStore.loading ? '...' : dashboardStore.stats.total_orders }}
                                </p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                <ShoppingCart class="w-8 h-8 text-blue-500" />
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
                                <p class="text-3xl font-bold">
                                    ${{ dashboardStore.loading ? '...' : dashboardStore.stats.total_sales.toLocaleString() }}
                                </p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                <DollarSign class="w-8 h-8 text-green-500" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filter Section -->
            <Card class="mb-6 p-1">
                <CardContent class="p-4">
                    <div class="flex flex-col md:flex-row items-end gap-4">
                        <div class="flex-1 w-full">
                            <Label for="month">Month</Label>
                            <Select v-model="selectedMonth" class="w-full">
                                <SelectTrigger class="mt-1 w-full">
                                    <SelectValue placeholder="Select month" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="month in months" :key="month.value" :value="month.value">
                                        {{ month.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex-1 w-full">
                            <Label for="year">Year</Label>
                            <Input 
                                id="year" 
                                type="number" 
                                v-model="selectedYear"
                                min="2015" 
                                max="2030"
                                class="mt-1"
                            />
                        </div>
                        <Button @click="applyFilter" :disabled="dashboardStore.loading" class="w-full sm:w-auto">
                            {{ dashboardStore.loading ? 'Loading...' : 'Apply Filter' }}
                        </Button>
                    </div>
                </CardContent>
            </Card>

        </div>
    </div>
</template>