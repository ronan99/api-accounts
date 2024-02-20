<?php

namespace App\Http\Requests;


class AuthRequest extends BaseApiRequest
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
            "email" => ["required", "email"],
            "password" => ["required", "min:6", "string"]
        ];
    }

    public function messages(): array{
        return [
            "email.required" => "O e-mail é obrigatório",
            "email.email" => "O e-mail está mal formatado",
            "password.required" => "A senha é obrigatória",
            "password.min" => "A senha deve ter no mínimo 6 caracteres"
        ];
    }
}
