<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserType;
class UserStoreRequest extends BaseApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
            "email" => ["required", "email", "unique:users,email"],
            "cpfCnpj" => ["required", "string", "unique:users,cpfCnpj"],
            "type" => ["required", new Enum(UserType::class)],
            "password" => ['required', 'string', 'min:6'],
            "balance" => ["integer", "min:0"]
        ];
    }
}
