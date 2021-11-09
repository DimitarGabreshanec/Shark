<?php

namespace App\Http\Requests\Store;

use App\Rules\Share\HalfKanaRule;
use Illuminate\Foundation\Http\FormRequest;

class BankInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['bank'] = ['required', 'numeric'];
        $rules['bank_branch'] = ['required', 'numeric'];
        $rules['account_type'] = ['required', 'numeric'];
        $rules['account_no'] = ['required', 'string', 'max:126'];
        $rules['account_name'] = ['required', new HalfKanaRule(), 'max:126'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['bank'] = '銀行名';
        $attributes['bank_branch'] = '支店名';
        $attributes['account_type'] = '口座種別';
        $attributes['account_no'] = '口座番号';
        $attributes['account_name'] = '口座名(半角カタカナ)';

        return $attributes;
    }

    public function messages()
    {
        return [
            'bank.required' => '銀行名を選択してください。',
            'bank_branch.required' => '支店名を選択してください。',
            'account_type.required' => '口座種別を選択してください。',
            'account_no.required' => '口座番号を入力してください。',
            'account_name.required' => '口座名(半角カタカナ)を入力してください。',
        ];
    }
}
