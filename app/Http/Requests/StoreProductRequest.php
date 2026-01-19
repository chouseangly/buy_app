<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'discount'     => 'nullable|numeric|min:0|max:100',
            'viewer'       => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',

            // Image validation: allows up to 5 images, each max 2MB
            'images'       => 'nullable|array|max:5',
            'images.*'     => 'image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    //Custom error messages

    public function messages(): array
    {
        return [
            'product_name.required' => 'Please provide a name for the product.',
            'price.numeric' => 'The price must be a valid number.',
            'images.max' => 'You cannot upload more than 5 images.',
        ];
    }
}
