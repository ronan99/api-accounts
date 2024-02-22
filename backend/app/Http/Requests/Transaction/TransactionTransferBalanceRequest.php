<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseApiRequest;

class TransactionTransferBalanceRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "userTo" => ["required", "integer"],
            "amount" => ["required", "integer"]
        ];
    }
}
