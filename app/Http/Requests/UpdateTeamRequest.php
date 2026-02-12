<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','string','max:80'],
            'power' => ['sometimes','integer','min:1','max:200'],
        ];
    }
}
