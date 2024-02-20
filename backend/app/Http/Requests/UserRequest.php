<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
class UserRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

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
            "password" => ['required', 'string', 'min:6']
        ];
    }
}
