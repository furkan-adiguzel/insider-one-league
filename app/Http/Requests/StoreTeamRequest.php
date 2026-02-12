<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:80'],
            'power' => ['required','integer','min:1','max:200'],
        ];
    }
}
