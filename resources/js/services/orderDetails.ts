import http from '.';

export default {
    async fetchOrderDetails(payload: Record<string, any>) {
        return await http.get('/order-details', { params: payload });
    },

    async fetchOrderDetail(id: number) {
        return await http.get(`/order-details/${id}`);
    },

    async createOrderDetail(payload: Record<string, any>) {
        return await http.post('/order-details', payload);
    },

    async updateOrderDetail(id: number, payload: Record<string, any>) {
        return await http.put(`/order-details/${id}`, payload);
    },

    async deleteOrderDetail(id: number) {
        return await http.delete(`/order-details/${id}`);
    }
};