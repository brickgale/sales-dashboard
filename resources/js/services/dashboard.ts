import http from '.';

export default {
    async getStats(params?: Record<string, any>) {
        return await http.get('/dashboard/stats', { params });
    },

    async getSalesByMonth(params?: Record<string, any>) {
        return await http.get('/dashboard/sales-by-month', { params });
    }
};
