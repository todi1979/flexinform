<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value && request()->card_number) {
                        $fail('Csak az egyik mezőt töltse ki!');
                    }
                }
            ],
            'card_number' => [
                'nullable',
                'string',
                'alpha_num',
                function ($attribute, $value, $fail) {
                    if ($value && request()->name) {
                        $fail('Csak az egyik mezőt töltse ki!');
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required_without' => 'Kérem, töltse ki az ügyfél nevét vagy az okmányazonosítóját!',
            'card_number.required_without' => 'Kérem, töltse ki az ügyfél nevét vagy az okmányazonosítóját!',
            'name.prohibited_if' => 'Csak az egyik mezőt töltse ki!',
            'card_number.prohibited_if' => 'Csak az egyik mezőt töltse ki!',
            'card_number.alpha_num' => 'Az okmányazonosító csak betűkből és számokból állhat!',
        ];
    }
}
