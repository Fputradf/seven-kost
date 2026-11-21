<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMarketplaceItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'category' => 'required|string|in:kasur,kipas,rak,perabot,elektronik,buku,lainnya',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string|in:baru,bekas_seperti_baru,bekas_baik,bekas_cukup',
            'meetup_location' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'description' => 'required|string|max:500',
            'is_featured' => 'nullable|boolean',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Nama atau judul barang wajib diisi.',
            'category.required' => 'Pilih kategori barang yang sesuai.',
            'price.required' => 'Harga barang wajib diisi.',
            'price.numeric' => 'Harga barang harus berupa angka nominal yang valid.',
            'condition.required' => 'Pilih kondisi barang saat ini.',
            'meetup_location.required' => 'Tentukan lokasi titik temu aman (COD).',
            'description.required' => 'Deskripsi barang wajib diisi.',
            'description.max' => 'Deskripsi barang maksimal 500 karakter.',
            'images.max' => 'Maksimal 5 foto barang yang dapat diunggah.',
            'images.*.image' => 'File yang diunggah harus berupa gambar (JPG, PNG, atau WebP).',
            'images.*.max' => 'Ukuran setiap foto maksimal 5MB.',
        ];
    }
}
