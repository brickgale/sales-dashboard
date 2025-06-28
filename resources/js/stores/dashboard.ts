import dashboardService from '@/services/dashboard';
import { defineStore } from 'pinia';

export const useDashboardStore = defineStore('dashboard', {
    state: () => ({
        stats: {
            total_pizzas: 0,
            total_orders: 0,
            total_sales: 0,
            period: {
                month: null as number | null,
                year: new Date().getFullYear(),
            }
        },
        salesByMonth: [] as Record<string, any>[],
        loading: false,
    }),

    actions: {
        async fetchStats(params?: Record<string, any>) {
            this.loading = true;
            try {
                const response = await dashboardService.getStats(params);
                this.stats = response.data.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchSalesByMonth(params?: Record<string, any>) {
            this.loading = true;
            try {
                const response = await dashboardService.getSalesByMonth(params);
                this.salesByMonth = response.data.data;
                return response;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },
    },
});
