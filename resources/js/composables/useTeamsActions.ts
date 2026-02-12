type ToastFn = (type: 'success' | 'error' | 'info', message: string) => void

export function useTeamsActions(opts: {
    create: (name: string, power: number) => Promise<unknown>
    update: (id: number, payload: { name: string; power: number }) => Promise<unknown>
    remove: (id: number) => Promise<unknown>
    toast: ToastFn
}) {
    async function onCreate(payload: { name: string; power: number }) {
        try {
            await opts.create(payload.name, payload.power)
            opts.toast('success', 'Team created.')
        } catch (e: any) {
            opts.toast('error', e?.message ?? 'Create failed.')
        }
    }

    async function onUpdate(payload: { teamId: number; name: string; power: number }) {
        try {
            await opts.update(payload.teamId, { name: payload.name, power: payload.power })
            opts.toast('success', 'Team updated.')
        } catch (e: any) {
            opts.toast('error', e?.message ?? 'Update failed.')
        }
    }

    async function onDelete(teamId: number) {
        if (!confirm('Delete this team?')) return
        try {
            await opts.remove(teamId)
            opts.toast('success', 'Team deleted.')
        } catch (e: any) {
            opts.toast('error', e?.message ?? 'Delete failed.')
        }
    }

    return { onCreate, onUpdate, onDelete }
}
