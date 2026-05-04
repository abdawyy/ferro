<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'contact.first_name' => ['required', 'string', 'max:120'],
            'contact.last_name' => ['required', 'string', 'max:120'],
            'contact.email' => ['required', 'email:rfc', 'max:255'],
            'contact.phone' => ['nullable', 'string', 'max:40'],
            'shipping.address' => ['required', 'string', 'max:500'],
            'shipping.city' => ['required', 'string', 'max:120'],
            'shipping.country' => ['required', 'string', 'size:2'],
            'shipping.method' => ['required', 'in:standard,express,overnight'],
            'marketing_consent' => ['sometimes', 'boolean'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
            'hear_about_us' => ['nullable', 'string', 'max:120'],
        ];
    }
}
