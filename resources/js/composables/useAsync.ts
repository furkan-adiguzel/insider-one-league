import { ref } from 'vue'
import { ApiError } from '../api/errors'

export function useAsync() {
    const loading = ref(false)
    const error = ref<string | null>(null)

    async function run<T>(fn: () => Promise<T>): Promise<T | null> {
        loading.value = true
        error.value = null
        try {
            return await fn()
        } catch (e: any) {
            if (e instanceof ApiError) error.value = e.payload.message
            else error.value = e?.message ?? 'Unexpected error'
            return null
        } finally {
            loading.value = false
        }
    }

    return { loading, error, run }
}
