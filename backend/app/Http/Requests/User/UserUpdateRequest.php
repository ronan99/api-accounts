<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserType;
class UserUpdateRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => ["required", "integer"],
            "name" => ["required", "string"],
            "email" => ["required", "email"],
            "cpfCnpj" => ["required", "string"],
            "type" => ["required", new Enum(UserType::class)],
            "balance" => ["integer", "min:0"]
        ];
    }
}
