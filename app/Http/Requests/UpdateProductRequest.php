<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id'    => ['required', 'exists:categories,id'],
            'sku'            => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($this->product),
            ],
            'name'           => [
                'required',
                'string',
                'max:150',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sell_price'     => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'min_stock'      => ['nullable', 'integer', 'min:0'],
            'unit'           => ['required', 'string', 'max:20'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'      => ['boolean'],
            'variants'       => ['nullable', 'array'],
            'variants.*.name' => ['nullable', 'string', 'max:100'],
            'variants.*.additional_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',

            'sku.required' => 'SKU wajib diisi.',
            'sku.unique'   => 'SKU sudah digunakan.',
            'sku.max'      => 'SKU maksimal 50 karakter.',

            'name.required' => 'Nama produk wajib diisi.',
            'name.unique'   => 'Nama produk sudah digunakan.',
            'name.max'      => 'Nama produk maksimal 150 karakter.',

            'purchase_price.required' => 'Harga beli wajib diisi.',
            'purchase_price.numeric'  => 'Harga beli harus berupa angka.',
            'purchase_price.min'      => 'Harga beli tidak boleh kurang dari 0.',

            'sell_price.required' => 'Harga jual wajib diisi.',
            'sell_price.numeric'  => 'Harga jual harus berupa angka.',
            'sell_price.min'      => 'Harga jual tidak boleh kurang dari 0.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer'  => 'Stok harus berupa angka bulat.',
            'stock.min'      => 'Stok tidak boleh kurang dari 0.',

            'unit.required' => 'Satuan wajib diisi.',
            'unit.max'      => 'Satuan maksimal 20 karakter.',
        ];
    }
}