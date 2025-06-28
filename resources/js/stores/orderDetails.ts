import orderDetailService from '@/services/orderDetails';
import { defineStore } from 'pinia';
import { defaultPaginationState } from '@/types/pagination';

export const useOrderDetailStore = defineStore('orderDetails', {
    state: () => ({
        orderDetails: [] as Record<string, any>[],
        ...defaultPaginationState,
        orderDetail: {} as Record<string, any>,
        loading: false,
    }),

    actions: {
        async fetchOrderDetails(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await orderDetailService.fetchOrderDetails(payload);
                this.orderDetails = response.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchOrderDetail(id: number) {
            this.loading = true;
            try {
                const response = await orderDetailService.fetchOrderDetail(id);
                this.orderDetail = response.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createOrderDetail(payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await orderDetailService.createOrderDetail(payload);
                this.orderDetails.push(response.data);
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateOrderDetail(id: number, payload: Record<string, any>) {
            this.loading = true;
            try {
                const response = await orderDetailService.updateOrderDetail(id, payload);
                const index = this.orderDetails.findIndex(od => od.id === id);
                if (index !== -1) {
                    this.orderDetails[index] = response.data;
                }
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteOrderDetail(id: number) {
            this.loading = true;
            try {
                const response = await orderDetailService.deleteOrderDetail(id);
                return response;
            } catch (error) {
                throw error;
            } 
        },
    },

});
