<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@ui/table';
import Dialog from '@/components/partials/Dialog.vue';
import AlertDialog from '@/components/partials/AlertDialog.vue';
import Pagination from '@/components/partials/Pagination.vue';
import { Button } from '@ui/button';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import PizzaForm from './PizzaForm.vue';
import PizzaTypes from './PizzaTypes.vue';
import { usePizzaStore } from '@/stores/pizza';
import { onMounted, ref } from 'vue';
import { handleError } from '@/lib/utils';
import { toast } from 'vue-sonner';

const pizzaStore = usePizzaStore();
const addDialogOpen = ref(false);
const editDialogOpen = ref<boolean[]>([]);

const columns: string[] = [
    'Pizza Type',
    'Slug',
    'Size',
    'Price',
    'Actions',
];

const fetchPizzas = async (page: number = 1) => {
    try {
        await pizzaStore.fetchPizzas({ page });
    } catch (error) {
        console.error('Error fetching pizzas:', error);
    }
};

onMounted(() => {
    fetchPizzas();
});

const successCreation = (message: string) => {
    addDialogOpen.value = false;
    toast.success(message);
    fetchPizzas();
};

const successEdit = (message: string, pizza: any, key: number) => {
    editDialogOpen.value[key] = false;
    toast.success(message);
    fetchPizzas();
};

const deletePizza = async (pizza: any) => {
    try {
        await pizzaStore.deletePizza(pizza.id);
        toast.success('Pizza deleted successfully');
        fetchPizzas();
    } catch (error) {
        handleError('Pizza deletion unsuccessful',error);
    }
};

</script>

<template>
    <div class="flex flex-col p-6 justify-center items-center gap-6">
        <Card class="w-full max-w-4xl">
            <CardHeader>
                <div class="flex items-center justify-between h-[36px]">
                    <h2 class="text-lg font-semibold">Pizzas</h2>

                    <div class="flex gap-3">
                        <Dialog 
                            title="Add Pizza"
                            class="sm:max-w-[600px]"
                            :open="addDialogOpen"
                            @update:open="addDialogOpen = $event"
                        >
                            <PizzaForm 
                                @error="handleError"
                                @success="successCreation"
                            />
                            <template #trigger>
                                <Button 
                                    @click.prevent="addDialogOpen = true"
                                >
                                    <Plus class="h-4 w-4" />
                                    Add Pizza
                                </Button>
                            </template>
                        </Dialog>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <template v-if="!(pizzaStore.loading) && pizzaStore.pizzas.length">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead v-for="column in columns" :key="column">
                                    {{ column }}
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(pizza, key) in pizzaStore.pizzas" :key="pizza.id">
                                <TableCell class="max-w-[250px] overflow-hidden text-ellipsis whitespace-nowrap">
                                    {{ pizza.pizza_type?.name || 'N/A' }}
                                </TableCell>
                                <TableCell class="max-w-[200px] overflow-hidden text-ellipsis whitespace-nowrap">
                                    {{ pizza.slug || 'N/A' }}
                                </TableCell>
                                <TableCell class="w-[100px]">{{ pizza.size }}</TableCell>
                                <TableCell class="w-[100px] overflow-hidden text-ellipsis whitespace-nowrap">${{ pizza.price }}</TableCell>
                                <TableCell class="flex items-center gap-2">
                                    <Dialog 
                                        title="Edit Property Unit"
                                        class="sm:max-w-[600px]"
                                        :open="editDialogOpen[key]"
                                        @update:open="editDialogOpen[key] = $event"
                                        :key="key"
                                    >
                                        <PizzaForm 
                                            :isEdit="true"
                                            :defaultValues="pizza"
                                            @error="handleError"
                                            @success="successEdit($event, pizza, key)"
                                            :key="key"
                                        />
                                        <template #trigger>
                                            <Button 
                                                variant="ghost-primary"
                                                @click.prevent="editDialogOpen[key] = true"
                                            >
                                                <SquarePen />
                                            </Button>
                                        </template>
                                    </Dialog>
                                    <AlertDialog 
                                        title="Delete Pizza"
                                        :description="`Are you sure you want to delete this pizza? This action cannot be undone.`"
                                        @continue="deletePizza(pizza)"
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
                        :total="pizzaStore.total"
                        :per-page="pizzaStore.perPage"
                        :current-page="pizzaStore.currentPage"
                        @update:page="fetchPizzas"
                    />
                </template>
                <div v-else class="flex items-center justify-center h-full">
                    <p class="text-gray-500">
                        {{ pizzaStore.loading ? 'Loading...' : 'No pizzas found.' }}
                    </p>
                </div>
            </CardContent>
        </Card>

        <PizzaTypes />
    </div>
</template>