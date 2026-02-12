import { reactive } from 'vue'
import type { FixtureMatch } from '../api/types'

type ToastFn = (type: 'success' | 'error' | 'info', message: string) => void

export function useMatchEdit(opts: {
    toast: ToastFn
    editMatch: (matchId: number, home: number, away: number) => Promise<any>
    reloadAll: () => Promise<void>
    getError: () => string | null
}) {
    const edit = reactive<{ open: boolean; match: FixtureMatch | null }>({
        open: false,
        match: null,
    })

    function openEdit(m: FixtureMatch) {
        edit.open = true
        edit.match = m
    }

    function closeEdit() {
        edit.open = false
        edit.match = null
    }

    async function saveEdit(payload: { matchId: number; home: number; away: number }) {
        try {
            await opts.editMatch(payload.matchId, payload.home, payload.away)
            opts.toast('success', 'Match updated.')
            closeEdit()
            await opts.reloadAll()
        } catch {
            opts.toast('error', opts.getError() ?? 'Failed to update match.')
        }
    }

    return { edit, openEdit, closeEdit, saveEdit }
}
