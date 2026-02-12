import type { ApiErrorPayload } from './types'

export class ApiError extends Error {
    public readonly payload: ApiErrorPayload
    public readonly status: number

    constructor(payload: ApiErrorPayload) {
        super(payload.message)
        this.payload = payload
        this.status = payload.code
    }
}
