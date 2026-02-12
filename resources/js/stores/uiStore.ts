import { defineStore } from 'pinia'

export type Toast = { id: string; type: 'success' | 'error' | 'info'; message: string }

export const useUiStore = defineStore('ui', {
    state: () => ({
        toasts: [] as Toast[],
    }),
    actions: {
        toast(type: Toast['type'], message: string) {
            const id = crypto.randomUUID?.() ?? String(Date.now())
            this.toasts.push({ id, type, message })
            setTimeout(() => this.dismiss(id), 3000)
        },
        dismiss(id: string) {
            this.toasts = this.toasts.filter(t => t.id !== id)
        },
    },
})
