<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    // ->mixedCase()
                    // ->numbers()
                    // ->symbols()
                    ->numbers()

            ],
            // 'password_confirmation' => ['required', 'same:password'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'email.email' => 'El email debe ser un correo electronico',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'email.unique' => 'El email ya esta registrado',
            'password.confirmed' => 'La contraseña no coincide',
            'password.mixedCase' => 'La contraseña debe tener al menos una mayuscula y una minuscula',
            'password.numbers' => 'La contraseña debe tener al menos un numero',
            'password.symbols' => 'La contraseña debe tener al menos un simbolo',
            'password.letters' => 'La contraseña debe tener al menos una letra',

        ];
    }
}
