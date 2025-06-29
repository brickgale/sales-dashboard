import pizzaService from '@/services/pizzas';
import { defineStore } from 'pinia';
import { defaultPaginationState } from '@/types/pagination';

export const usePizzaStore = defineStore('pizzas', {
    state: () => ({
        pizzas: [] as Record<string, any>[],
        ...defaultPaginationState,
        pizza: {} as Record<string, any>,
        loading: false,
    }),

    actions: {
        async fetchPizzas(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await pizzaService.fetchPizzas(payload);
                if (payload.getAll !== 'true') {
                    const { data, current_page, total } = response.data.pizzas;
                    this.pizzas = data;
                    this.currentPage = current_page;
                    this.total = total;
                }
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchPizza(id: number) {
            this.loading = true;
            try {
                const response = await pizzaService.fetchPizza(id);
                this.pizza = response.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createPizza(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await pizzaService.createPizza(payload);
                this.pizzas.push(response.data);
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updatePizza(id: number, payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await pizzaService.updatePizza(id, payload);
                const index = this.pizzas.findIndex(p => p.id === id);
                if (index !== -1) {
                    this.pizzas[index] = response.data;
                }
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deletePizza(id: number) {
            this.loading = true;
            try {
                const response = await pizzaService.deletePizza(id);
                return response;
            } catch (error) {
                throw error;
            } 
        },
    },

});
