<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'category_id'=>'required|exists:categories,id',
            'sub_category_id'=>'nullable|exists:sub_categories,id',
            'amount'=>'required',
            'payer'=>'required|exists:users,id',
            'due_on'=>'required|date',
            'vat'=>'required',
            'is_vat_inclusive'=>'required',
        ];
    }
}
