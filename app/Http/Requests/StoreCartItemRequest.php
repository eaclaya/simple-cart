<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $product = Product::find($this->input('product_id'));
                if (! $product) {
                    return;
                }

                $cart = $this->user()->cart;
                $existingQuantity = 0;

                if ($cart) {
                    $existingItem = $cart->items()->where('product_id', $product->id)->first();
                    $existingQuantity = $existingItem?->quantity ?? 0;
                }

                $newQuantity = $existingQuantity + $this->input('quantity');

                if ($newQuantity > $product->stock_quantity) {
                    $validator->errors()->add(
                        'quantity',
                        'Requested quantity exceeds available stock.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'The selected product does not exist.',
            'quantity.required' => 'Please specify a quantity.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
