import { defineStore } from 'pinia';
import { fetchWrapper } from '@/helpers/fetch-wrapper';
import { useAuthStore } from '@/stores/auth.store';
import type { IRegisterDto } from '@/interfaces/user-register';

const baseUrl = `${import.meta.env.VITE_API_URL}`;

export const useUsersStore = defineStore({
  id: 'users',
  state: () => ({
    users: {},
    user: {}
  }),
  actions: {
    async register(user : IRegisterDto) {
      await fetchWrapper.post(`${baseUrl}/register`, user);
    },
  }
});
