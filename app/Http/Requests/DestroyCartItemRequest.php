<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $cartItem = $this->route('cartItem');

        return $this->user() !== null
            && $cartItem->cart?->user_id === $this->user()->id;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
