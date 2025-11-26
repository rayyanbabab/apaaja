<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
        $rules = [
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'type' => 'required|in:stok,peminjaman',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:item_images,id',
        ];
        if ($this->input('type') === 'stok') {
            $rules['category_id'] = 'required|exists:categories,id';
            $rules['supplier_id'] = 'required|exists:suppliers,id';
            $rules['harga'] = 'required|numeric|min:0|max:999999999';
        } else {
            $rules['category_id'] = 'nullable|exists:categories,id';
            $rules['supplier_id'] = 'nullable|exists:suppliers,id';
            $rules['harga'] = 'nullable|numeric|min:0|max:999999999';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'harga.max' => 'Harga tidak boleh lebih dari Rp 999.999.999',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
        ];
    }
}
