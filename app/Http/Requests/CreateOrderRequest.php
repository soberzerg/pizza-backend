<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'address' => ['required', 'string'],
            'contact' => ['required', 'string'],
            'delivery_cost' => ['required', 'numeric'],
        ];
    }
}
