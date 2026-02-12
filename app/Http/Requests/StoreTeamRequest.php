<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'  => ['required','string','min:2','max:80', Rule::unique('teams', 'name')],
            'power' => ['required','integer','min:1','max:200'],
        ];
    }
    public function messages(): array
    {
        return [
            'power.max' => 'Team power must be 200 or less.',
        ];
    }
}
