<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'status' => ['string', 'in:draft,published'],
            'product_name' => ['string', 'max:255'],
            'quantity' => ['string', 'max:255'],
            'brands' => ['string', 'max:255'],
            'categories' => ['string', 'max:255'],
            'labels' => ['string', 'max:255'],
            'cities' => ['string', 'max:255'],
            'purchase_places' => ['string', 'max:255'],
            'stores' => ['string', 'max:255'],
            'ingredients_text' => ['string', 'max:255'],
            'traces' => ['string', 'max:255'],
        ];
    }
}
