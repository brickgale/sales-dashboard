import http from '.';

export default {
    async fetchOrders(payload: Record<string, any>) {
        return await http.get('/orders', { params: payload });
    },

    async fetchOrder(id: number) {
        return await http.get(`/orders/${id}`);
    },

    async createOrder(payload: Record<string, any>) {
        return await http.post('/orders', payload);
    },

    async updateOrder(id: number, payload: Record<string, any>) {
        return await http.put(`/orders/${id}`, payload);
    },

    async deleteOrder(id: number) {
        return await http.delete(`/orders/${id}`);
    }
};