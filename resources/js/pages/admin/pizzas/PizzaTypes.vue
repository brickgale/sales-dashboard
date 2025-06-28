<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@ui/table';
import Dialog from '@/components/partials/Dialog.vue';
import AlertDialog from '@/components/partials/AlertDialog.vue';
import Pagination from '@/components/partials/Pagination.vue';
import { Button } from '@ui/button';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import PizzaTypeForm from './PizzaTypeForm.vue';
import { usePizzaTypeStore } from '@/stores/pizzaTypes';
import { onMounted, ref } from 'vue';
import { handleError } from '@/lib/utils';
import { toast } from 'vue-sonner';

const pizzaTypeStore = usePizzaTypeStore();
const addDialogOpen = ref(false);
const editDialogOpen = ref<boolean[]>([]);

const columns: string[] = [
    'Name',
    'Slug',
    'Category',
    'Ingredients',
    'Actions',
];

const fetchPizzaTypes = async (page: number = 1) => {
    try {
        const res = await pizzaTypeStore.fetchPizzaTypes({ page });
        console.log('Fetched pizza types:', res);
    } catch (error) {
        console.error('Error fetching pizza types:', error);
    }
};

onMounted(() => {
    fetchPizzaTypes();
});


const successCreation = (message: string) => {
    addDialogOpen.value = false;
    toast.success(message)
    fetchPizzaTypes();
};

const deletePizzaType = async (pizzaType: any) => {
    try {
        await pizzaTypeStore.deletePizzaType(pizzaType.id);
        toast.success('Pizza type deleted successfully');
        fetchPizzaTypes();
    } catch (error) {
        handleError('Pizza type deletion unsuccessful',error);
    }
};

</script>

<template>
    <Card class="w-full max-w-4xl">
        <CardHeader>
            <div class="flex items-center justify-between h-[36px]">
                <h2 class="text-lg font-semibold">Pizzas Types</h2>

                <div class="flex gap-3">
                    <Dialog 
                        title="Add Pizza Type"
                        class="sm:max-w-[600px]"
                        :open="addDialogOpen"
                        @update:open="addDialogOpen = $event"
                    >
                        <PizzaTypeForm 
                            @error="handleError"
                            @success="successCreation"
                        />
                        <template #trigger>
                            <Button 
                                @click.prevent="addDialogOpen = true"
                            >
                                <Plus class="h-4 w-4" />
                                Add Pizza Type
                            </Button>
                        </template>
                    </Dialog>
                </div>
            </div>
        </CardHeader>
        <CardContent>
            <template v-if="!(pizzaTypeStore.loading) && pizzaTypeStore.pizzaTypes.length">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead v-for="column in columns" :key="column">
                                {{ column }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="pizzaType in pizzaTypeStore.pizzaTypes" :key="pizzaType.id">
                            <TableCell class="max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap">
                                {{ pizzaType.name }}
                            </TableCell>
                            <TableCell class="max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap">
                                {{ pizzaType.slug || 'N/A' }}
                            </TableCell>
                            <TableCell>{{ pizzaType.category }}</TableCell>
                            <TableCell class="max-w-[200px] overflow-hidden text-ellipsis whitespace-nowrap">
                                {{ pizzaType.ingredients }}
                            </TableCell>
                            <TableCell class="flex items-center gap-2">
                                <Button 
                                    variant="ghost-primary" 
                                    size="icon" 
                                    @click.prevent="() => $router.push({ name: 'admin.pizza-types.edit', params: { id: pizzaType.id } })"
                                ><SquarePen /></Button>
                                <AlertDialog 
                                    title="Delete Pizza Type"
                                    :description="`Are you sure you want to delete this pizza type: ${pizzaType.name} ? This action cannot be undone.`"
                                    @continue="deletePizzaType(pizzaType)"
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
                    :total="pizzaTypeStore.total"
                    :per-page="10"
                    :current-page="pizzaTypeStore.currentPage"
                    @update:page="fetchPizzaTypes"
                />
            </template>
            <div v-else class="flex items-center justify-center h-full">
                <p class="text-gray-500">
                    {{ pizzaTypeStore.loading ? 'Loading...' : 'No pizza types found.' }}
                </p>
            </div>
        </CardContent>
    </Card>
</template>