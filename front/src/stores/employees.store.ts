import { defineStore } from 'pinia';
import { fetchWrapper } from '@/helpers/fetch-wrapper';
import type { IEmployeeRegisterDto } from '@/interfaces/employee-register';

const baseUrl = `${import.meta.env.VITE_API_URL}/employees`;

export const useEmployeesStore = defineStore({
    id: 'employees',
    state: () => ({
        employees: {} as any,
        employee: {} as any
    }),
    actions: {
        async register(employee : IEmployeeRegisterDto) {
            await fetchWrapper.post(`${baseUrl}`, employee);
        },
        async getAll() {
            this.employees = { loading: true };
            try {
                const apiData = await fetchWrapper.get(baseUrl);
                this.employees = apiData.data;
            } catch (error) {
                this.employees = { error };
            }
        },
        async getById(id: number) {
            this.employee = { loading: true };
            try {
                this.employee = await fetchWrapper.get(`${baseUrl}/${id}`);
            } catch (error) {
                this.employee = { error };
            }
        },
        async update(id: number, params: any) {
            await fetchWrapper.put(`${baseUrl}/${id}`, params);
        },
        async delete(id: number) {
            // add isDeleting prop to user being deleted
            this.employees.find((x : any) => x.id === id).isDeleting = true;

            await fetchWrapper.delete(`${baseUrl}/${id}`);

            // remove employees from list after deleted
            this.employees = this.employees.filter((x : any) => x.id !== id);
        }
    }
});
