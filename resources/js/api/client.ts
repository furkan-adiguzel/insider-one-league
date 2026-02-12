import type { ApiEnvelope } from './types'
import { ApiError } from './errors'

type HttpMethod = 'GET' | 'POST' | 'PATCH' | 'DELETE'

export class ApiClient {
    constructor(private readonly baseUrl = '') {}

    async request<T>(method: HttpMethod, path: string, body?: unknown): Promise<T> {
        const res = await fetch(this.baseUrl + path, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Request-ID': crypto.randomUUID?.() ?? String(Date.now()),
            },
            credentials: 'same-origin',
            body: body !== undefined ? JSON.stringify(body) : undefined,
        })

        const contentType = res.headers.get('content-type') ?? ''
        const raw = await res.text()

        const isJson = contentType.includes('application/json')

        if (!isJson) {
            const looksLikeHtml = raw.trim().startsWith('<!DOCTYPE html') || raw.trim().startsWith('<html')
            const msg = looksLikeHtml
                ? 'API returned HTML instead of JSON. (Add Accept: application/json) or check middleware/redirects.'
                : `API returned non-JSON response (${res.status}).`

            throw new ApiError({
                type: 'invalid_response',
                message: msg,
                code: res.status,
            })
        }

        let json: ApiEnvelope<T> | null = null
        try {
            json = JSON.parse(raw) as ApiEnvelope<T>
        } catch {
            throw new ApiError({
                type: 'invalid_json',
                message: `API returned invalid JSON (${res.status}).`,
                code: res.status,
            })
        }

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

export const api = new ApiClient('')
