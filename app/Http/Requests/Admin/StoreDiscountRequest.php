<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id|unique:discounts,product_id',
            'type' => 'required|in:seasonal,volume',
            'discount_amount' => 'required|numeric|min:0',
            'min_quantity' => 'required_if:type,volume|nullable|integer|min:1',
            'start_date' => 'required_if:type,seasonal|nullable|date',
            'end_date' => 'required_if:type,seasonal|nullable|date|after_or_equal:start_date',
            'show_on_dashboard' => 'nullable|boolean',
        ];
    }
}
