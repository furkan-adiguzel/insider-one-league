<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class EditMatchScoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'home_score' => ['required','integer','min:0','max:20'],
            'away_score' => ['required','integer','min:0','max:20'],
        ];
    }
}
