import { defineStore } from 'pinia';

interface IAlert
{
    message: string,
    type: string
}
export const useAlertStore = defineStore({
    id: 'alert',
    state: () => ({
        alert: null as IAlert | null
    }),
    actions: {
        success(message : string) {
            const newAlert : IAlert = { message, type: 'alert-success' };
            this.alert = newAlert;
        },
        error(message : string) {
            const newAlert : IAlert = { message, type: 'alert-danger' };
            this.alert = newAlert;
        },
        clear() {
            this.alert = null;
        }
    }
});
