import { defineStore } from 'pinia';
import { fetchWrapper } from '@/helpers/fetch-wrapper';

const baseUrl = `${import.meta.env.VITE_API_URL}/restaurants`;

export const useRestaurantsStore = defineStore({
    id: 'restaurants',
    state: () => ({
        restaurants: {}
    }),
    actions: {
        async getAll() {
            this.restaurants = { loading: true };
            try {
                const apiData = await fetchWrapper.get(baseUrl);
                this.restaurants = apiData.data;
            } catch (error) {
                this.restaurants = { error };
            }
        }
    }
});
