import type { ApiEnvelope } from './types'
import { ApiError } from './errors'

type HttpMethod = 'GET' | 'POST' | 'PATCH' | 'DELETE'

export class ApiClient {
    constructor(private readonly baseUrl = '') {}

    async request<T>(method: HttpMethod, path: string, body?: unknown): Promise<T> {
        const res = await fetch(this.baseUrl + path, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-Request-ID': crypto.randomUUID?.() ?? String(Date.now()),
            },
            body: body ? JSON.stringify(body) : undefined,
        })

        // API her zaman json envelope dönüyor
        const json = (await res.json()) as ApiEnvelope<T>

        if (!json.ok) {
            if (json.error) throw new ApiError(json.error)
            throw new ApiError({ type: 'unknown_error', message: 'Unknown error', code: res.status })
        }

        return json.data
    }

    get<T>(path: string) { return this.request<T>('GET', path) }
    post<T>(path: string, body?: unknown) { return this.request<T>('POST', path, body) }
    patch<T>(path: string, body?: unknown) { return this.request<T>('PATCH', path, body) }
    delete<T>(path: string) { return this.request<T>('DELETE', path) }
}

// singleton
export const api = new ApiClient('')
