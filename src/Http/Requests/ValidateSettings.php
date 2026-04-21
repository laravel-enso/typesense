<?php

namespace LaravelEnso\Typesense\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateSettings extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return ['enabled' => 'required|boolean'];
    }
}
