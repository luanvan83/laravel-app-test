import { defineStore } from 'pinia';
import { fetchWrapper } from '@/helpers/fetch-wrapper';
import type { IRegisterDto } from '@/interfaces/user-register';

const baseUrl = `${import.meta.env.VITE_API_URL}/employees`;

export const useEmployeesStore = defineStore({
    id: 'employees',
    state: () => ({
        employees: {},
        employee: {}
    }),
    actions: {
        async register(employee : IRegisterDto) {
            await fetchWrapper.post(`${baseUrl}`, employee);
        },
        async getAll() {
            this.employees = { loading: true };
            try {
                const apiData = await fetchWrapper.get(baseUrl);
                this.employees = apiData.data;
            } catch (error) {
                this.users = { error };
            }
        },
        async getById(id) {
            this.employee = { loading: true };
            try {
                this.employee = await fetchWrapper.get(`${baseUrl}/${id}`);
            } catch (error) {
                this.employee = { error };
            }
        },
        async update(id, params) {
            await fetchWrapper.put(`${baseUrl}/${id}`, params);
        },
        async delete(id) {
            // add isDeleting prop to user being deleted
            this.employees.find(x => x.id === id).isDeleting = true;

            await fetchWrapper.delete(`${baseUrl}/${id}`);

            // remove user from list after deleted
            this.employees = this.employees.filter(x => x.id !== id);
        }
    }
});
