<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { Button } from '@ui/button';
import { Plus } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { ref } from 'vue';

import { useOrderStore } from '@/stores/orders';

const ordersStore = useOrderStore();

const handleAddOrder = () => {
    // TODO: Navigate to add order page or open modal
    console.log('Add new order');
    

};
</script>

<template>
    <div class="flex p-6 justify-center">
        <Card class="w-full max-w-4xl">
            <CardHeader>
                <div class="flex items-center justify-between h-[36px]">
                    <h2 class="text-lg font-semibold">Orders</h2>
                    <div class="flex gap-3">
                        <Button
                            @click="handleAddOrder"
                            class="flex items-center gap-2"
                        >
                            <Plus class="h-4 w-4" />
                            Add Order
                        </Button>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <div class="flex items-center justify-center h-full">
                    <p class="text-gray-500">No orders found.</p>
                </div>
            </CardContent>
        </Card>
    </div>
</template>