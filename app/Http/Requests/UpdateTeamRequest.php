<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $teamId = (int) ($this->route('teamId') ?? ($this->route('team')?->id ?? 0));
        return [
            'name'  => ['required','string','min:2','max:80', Rule::unique('teams', 'name')->ignore($teamId)],
            'power' => ['sometimes','integer','min:1','max:200'],
        ];
    }
    public function messages(): array
    {
        return [
            'power.max' => 'Team power must be 200 or less.',
        ];
    }
}
