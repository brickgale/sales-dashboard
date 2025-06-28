import http from './';

export default {
    async fetchPizzaTypes(payload: Record<string, any>) {
        return await http.get('/pizza-types', { params: payload });
    },

    async fetchPizzaType(id: number) {
        return await http.get(`/pizza-types/${id}`);
    },

    async createPizzaType(payload: Record<string, any>) {
        return await http.post('/pizza-types', payload);
    },

    async updatePizzaType(id: number, payload: Record<string, any>) {
        return await http.put(`/pizza-types/${id}`, payload);
    },

    async deletePizzaType(id: number) {
        return await http.delete(`/pizza-types/${id}`);
    }
};
