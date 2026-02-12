import type { ApiEnvelope } from './types'
import { ApiError } from './errors'

type HttpMethod = 'GET' | 'POST' | 'PATCH' | 'DELETE'

type LaravelValidationError = {
    message?: string
    errors?: Record<string, string[]>
}

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

        let parsed: any
        try {
            parsed = JSON.parse(raw)
        } catch {
            throw new ApiError({
                type: 'invalid_json',
                message: `API returned invalid JSON (${res.status}).`,
                code: res.status,
            })
        }

        if (parsed && typeof parsed === 'object' && 'ok' in parsed) {
            const env = parsed as ApiEnvelope<T>

            if (!env.ok) {
                if (env.error) throw new ApiError(env.error)
                throw new ApiError({ type: 'unknown_error', message: 'Unknown error', code: res.status })
            }

            return env.data
        }

        if (parsed && typeof parsed === 'object' && ('errors' in parsed || 'message' in parsed)) {
            const v = parsed as LaravelValidationError

            if (!res.ok) {
                throw new ApiError({
                    type: res.status === 422 ? 'validation_error' : 'http_error',
                    message: v.message ?? `Request failed (${res.status}).`,
                    code: res.status,
                    fields: v.errors,
                })
            }

            return parsed as T
        }

        if (!res.ok) {
            throw new ApiError({
                type: 'http_error',
                message: `Request failed (${res.status}).`,
                code: res.status,
            })
        }

        return parsed as T
    }

    get<T>(path: string) { return this.request<T>('GET', path) }
    post<T>(path: string, body?: unknown) { return this.request<T>('POST', path, body) }
    patch<T>(path: string, body?: unknown) { return this.request<T>('PATCH', path, body) }
    delete<T>(path: string) { return this.request<T>('DELETE', path) }
}

export const api = new ApiClient('')
