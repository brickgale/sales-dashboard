import orderService from '@/services/orders';
import { defineStore } from 'pinia';
import { defaultPaginationState } from '@/types/pagination';

export const useOrderStore = defineStore('orders', {
    state: () => ({
        orders: [] as Record<string, any>[],
        ...defaultPaginationState,
        order: {} as Record<string, any>,
        loading: false,
    }),

    actions: {
        async fetchOrders(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await orderService.fetchOrders(payload);
                this.orders = response.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchOrder(id: number) {
            this.loading = true;
            try {
                const response = await orderService.fetchOrder(id);
                this.order = response.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createOrder(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await orderService.createOrder(payload);
                this.orders.push(response.data);
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateOrder(id: number, payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await orderService.updateOrder(id, payload);
                const index = this.orders.findIndex(o => o.id === id);
                if (index !== -1) {
                    this.orders[index] = response.data;
                }
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteOrder(id: number) {
            this.loading = true;
            try {
                const response = await orderService.deleteOrder(id);
                return response;
            } catch (error) {
                throw error;
            } 
        },
    },

});
