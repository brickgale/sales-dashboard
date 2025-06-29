<script lang="ts" setup>
import { Button } from '@ui/button';
import { Input } from '@ui/input';
import { Label } from '@ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@ui/select';
import { usePizzaStore } from '@/stores/pizza';
import { usePizzaTypeStore } from '@/stores/pizzaTypes';
import { ref, onMounted } from 'vue';
import { PizzaType } from '@/types/pizzas';

const emit = defineEmits<{
    success: [message: string];
    error: [msg: string, error: typeof Error | string];
}>();

const pizzaStore = usePizzaStore();
const pizzaTypeStore = usePizzaTypeStore();

const form = ref({
    pizza_type_id: '',
    size: '',
    price: '',
    slug: '',
});

const pizzaTypes: PizzaType[] = [];

const loading = ref(false);

const sizeOptions = [
    { value: 'small', label: 'Small' },
    { value: 'medium', label: 'Medium' },
    { value: 'large', label: 'Large' },
    { value: 'extra_large', label: 'Extra Large' },
    { value: 'extra_extra_large', label: 'Extra Extra Large' },
];

interface Props {
    isEdit?: boolean;
    pizzaId?: string | number;
    defaultValues?: PizzaType;
}

const props = withDefaults(defineProps<Props>(), {
    isEdit: false,
    pizzaId: undefined,
    defaultValues: undefined,
});

onMounted(async () => {
    try {
        const res = await pizzaTypeStore.fetchPizzaTypes({ getAll: 'true' });
        pizzaTypes.push(...res.data.pizza_types);

        // Set default values if provided
        if (props.defaultValues) {
            const { pizza_type_id, size, price, slug } = props.defaultValues;
            form.value = { pizza_type_id, size, price, slug: slug || '' };
        }

        // If editing, fetch the pizza data (this will override defaultValues)
        if (props.isEdit && props.pizzaId) {
            await pizzaStore.fetchPizza(Number(props.pizzaId));
            const pizza = pizzaStore.pizza;
            
            // Set form values with proper defaults
            form.value = {
                pizza_type_id: pizza.pizza_type_id?.toString() || '',
                size: pizza.size || '',
                price: pizza.price?.toString() || '0',
                slug: pizza.slug || '',
            };
        }
    } catch (error: any) {
        emit('error', 'Error occurred', error);
    }
});

const submitForm = async () => {
    loading.value = true;
    try {
        if (props.isEdit && props.pizzaId) {
            await pizzaStore.updatePizza(props.pizzaId, {
                pizza_type_id: form.value.pizza_type_id,
                size: form.value.size,
                price: parseFloat(form.value.price),
                slug: form.value.slug,
            });
            emit('success', 'Pizza updated successfully');
        } else {
            await pizzaStore.createPizza({
                pizza_type_id: form.value.pizza_type_id,
                size: form.value.size,
                price: parseFloat(form.value.price),
                slug: form.value.slug,
            });
            emit('success', 'Pizza created successfully');
            
            // Reset form only for create mode
            form.value = {
                pizza_type_id: '',
                size: '',
                price: '',
                slug: '',
            };
        }
    } catch (error: any) {
        emit('error', error?.data?.message || error?.message || 'Error occurred');
    } finally {
        loading.value = false;
    }
};

</script>

<template>
    <form @submit.prevent="submitForm" class="space-y-4">
        <div class="space-y-2">
            <Label for="pizza_type_id">Pizza Type</Label>
            <Select v-model="form.pizza_type_id" required>
                <SelectTrigger>
                    <SelectValue placeholder="Select pizza type" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem 
                        v-for="pizzaType in pizzaTypeStore.pizzaTypes" 
                        :key="pizzaType.id" 
                        :value="pizzaType.id"
                    >
                        {{ pizzaType.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="space-y-2">
            <Label for="size">Size</Label>
            <Select v-model="form.size" required>
                <SelectTrigger>
                    <SelectValue placeholder="Select size" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem 
                        v-for="size in sizeOptions" 
                        :key="size.value" 
                        :value="size.value"
                    >
                        {{ size.label }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="space-y-2">
            <Label for="slug">Slug</Label>
            <Input 
                id="slug" 
                v-model="form.slug" 
                type="text" 
                required 
                placeholder="Enter slug"
            />
        </div>

        <div class="space-y-2">
            <Label for="price">Price</Label>
            <Input 
                id="price" 
                v-model="form.price" 
                type="number" 
                step="0.01"
                min="0"
                required 
                placeholder="Enter price"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <Button 
                type="submit" 
                :disabled="loading || pizzaStore.loading"
            >
                {{ loading || pizzaStore.loading ? 'Loading...' : (props.isEdit ? 'Update Pizza' : 'Create Pizza') }}
            </Button>
        </div>
    </form>
</template>