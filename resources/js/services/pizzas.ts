import http from '.';

export default {
    async fetchPizzas(payload: Record<string, any>) {
        return await http.get('/pizzas', { params: payload });
    },

    async fetchPizza(id: number) {
        return await http.get(`/pizzas/${id}`);
    },

    async createPizza(payload: Record<string, any>) {
        return await http.post('/pizzas', payload);
    },

    async updatePizza(id: number, payload: Record<string, any>) {
        return await http.put(`/pizzas/${id}`, payload);
    },

    async deletePizza(id: number) {
        return await http.delete(`/pizzas/${id}`);
    }
};