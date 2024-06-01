import { defineStore } from 'pinia';
import { fetchWrapper } from '@/helpers/fetch-wrapper';
import { useAlertStore } from '@/stores/alert.store';
import router from '@/router';
import type { IResponseData } from '@/interfaces/response-data';

const baseUrl = `${import.meta.env.VITE_API_URL}`;

export const useAuthStore = defineStore({
    id: 'auth',
    state: () => {
        const storedUser = localStorage.getItem('user');
        const user = storedUser ? JSON.parse(storedUser) : null;
        return {
            user: user,
            returnUrl: null as string | null
        }
    },
    actions: {
        async login(email: string, password: string) {
            try {
                const responseData : IResponseData = await fetchWrapper.post(
                    `${baseUrl}/login`,
                    { email, password }
                );
                console.log('responseData', responseData);
                if (responseData.data) {
                    // update pinia state
                    this.user = responseData.data;

                    // store user details and token in local storage to keep user logged in between page refreshes
                    localStorage.setItem('user', JSON.stringify(this.user));

                    // redirect to previous url or default to home page
                    router.push(this.returnUrl || '/');
                } else {
                    throw new Error('Data not found');
                }
            } catch (error) {
                const alertStore = useAlertStore();
                alertStore.error(error);
                return Promise.reject(error);                
            }
        },
        logout() {
            this.user = null;
            localStorage.removeItem('user');
            router.push('/login');
        }
    }
});
