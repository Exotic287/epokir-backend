<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|in:admin,setwan,dewan',
            'dapil_id' => 'required_if:role,dewan|nullable|integer',
            'fraksi_id' => 'required_if:role,dewan|nullable|integer',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role tidak valid.',
            'dapil_id.required_if' => 'Dapil wajib diisi untuk anggota dewan.',
            'fraksi_id.required_if' => 'Fraksi wajib diisi untuk anggota dewan.',
        ];
    }
}
