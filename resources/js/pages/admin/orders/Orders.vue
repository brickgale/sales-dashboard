<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@ui/table';
import Dialog from '@/components/partials/Dialog.vue';
import AlertDialog from '@/components/partials/AlertDialog.vue';
import Pagination from '@/components/partials/Pagination.vue';
import { Button } from '@ui/button';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import OrderForm from './Form.vue';
import { useOrderStore } from '@/stores/orders';
import { onMounted, ref } from 'vue';
import { handleError } from '@/lib/utils';
import { toast } from 'vue-sonner';

const orderStore = useOrderStore();
const addDialogOpen = ref(false);
const editDialogOpen = ref<boolean[]>([]);

const columns: string[] = [
    'Order ID',
    'Date',
    'Time',
    'Items Ordered',
    'Actions',
];

const fetchOrders = async (page: number = 1) => {
    try {
        await orderStore.fetchOrders({ page });
    } catch (error) {
        console.error('Error fetching orders:', error);
    }
};

onMounted(() => {
    fetchOrders();
});

const successCreation = (message: string) => {
    addDialogOpen.value = false;
    toast.success(message);
    fetchOrders();
};

const deleteOrder = async (order: any) => {
    try {
        await orderStore.deleteOrder(order.id);
        toast.success('Order deleted successfully');
        fetchOrders();
    } catch (error) {
        handleError('Order deletion unsuccessful', error);
    }
};

</script>

<template>
    <div class="flex p-6 justify-center">
        <Card class="w-full max-w-4xl">
            <CardHeader>
                <div class="flex items-center justify-between h-[36px]">
                    <h2 class="text-lg font-semibold">Orders</h2>
                    <div class="flex gap-3">
                        <Dialog 
                            title="Add Order"
                            class="sm:max-w-[600px]"
                            :open="addDialogOpen"
                            @update:open="addDialogOpen = $event"
                        >
                            <OrderForm 
                                @error="handleError"
                                @success="successCreation"
                            />
                            <template #trigger>
                                <Button
                                    @click.prevent="addDialogOpen = true"
                                >
                                    <Plus class="h-4 w-4" />
                                    Add Order
                                </Button>
                            </template>
                        </Dialog>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <template v-if="!(orderStore.loading) && orderStore.orders.length">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead v-for="column in columns" :key="column">
                                    {{ column }}
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="order in orderStore.orders" :key="order.id">
                                <TableCell>{{ order.id }}</TableCell>
                                <TableCell>{{ new Date(order.created_at).toLocaleDateString() }}</TableCell>
                                <TableCell>{{ new Date(order.created_at).toLocaleTimeString() }}</TableCell>
                                <TableCell>{{ order.order_details?.length || 0 }} items</TableCell>
                                <TableCell class="flex items-center gap-2">
                                    <Button 
                                        variant="ghost-primary" 
                                        size="icon" 
                                        @click.prevent="() => $router.push({ name: 'admin.orders.edit', params: { id: order.id } })"
                                    ><SquarePen /></Button>
                                    <AlertDialog 
                                        title="Delete Order"
                                        :description="`Are you sure you want to delete this order? This action cannot be undone.`"
                                        @continue="deleteOrder(order)"
                                    >
                                        <template #trigger>
                                            <Button 
                                                variant="ghost-destructive" 
                                                size="icon" 
                                            ><Trash /></Button>
                                        </template>
                                    </AlertDialog>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <Pagination
                        class="pt-2"
                        :total="orderStore.total"
                        :per-page="orderStore.perPage"
                        :current-page="orderStore.currentPage"
                        @update:page="fetchOrders"
                    />
                </template>
                <div v-else class="flex items-center justify-center h-full">
                    <p class="text-gray-500">
                        {{ orderStore.loading ? 'Loading...' : 'No orders found.' }}
                    </p>
                </div>
            </CardContent>
        </Card>
    </div>
</template>