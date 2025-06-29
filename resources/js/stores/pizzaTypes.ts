import pizzaTypeService from '@/services/pizzaTypes';
import { defineStore } from 'pinia';
import { defaultPaginationState } from '@/types/pagination';

export const usePizzaTypeStore = defineStore('pizzaTypes', {
    state: () => ({
        pizzaTypes: [] as Record<string, any>[],
        ...defaultPaginationState,
        pizzaType: {} as Record<string, any>,
        loading: false,
    }),

    actions: {
        async fetchPizzaTypes(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await pizzaTypeService.fetchPizzaTypes(payload);
                if (payload.getAll !== 'true') {
                    const { data, current_page, total } = response.data.pizza_types;
                    this.pizzaTypes = data;
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

        async fetchPizzaType(id: number) {
            this.loading = true;
            try {
                const response = await pizzaTypeService.fetchPizzaType(id);
                this.pizzaType = response.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createPizzaType(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await pizzaTypeService.createPizzaType(payload);
                this.pizzaTypes.push(response.data);
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updatePizzaType(id: number, payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await pizzaTypeService.updatePizzaType(id, payload);
                const index = this.pizzaTypes.findIndex(pt => pt.id === id);
                if (index !== -1) {
                    this.pizzaTypes[index] = response.data;
                }
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deletePizzaType(id: number) {
            this.loading = true;
            try {
                const response = await pizzaTypeService.deletePizzaType(id);
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },
    },

});