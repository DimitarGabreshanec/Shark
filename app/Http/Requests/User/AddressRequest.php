<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class AddressRequest extends FormRequest
{
    protected $action;

    public function __construct(Request $request, Factory $factory)
    {
        $this->action = !empty($request->route()->getName()) ? $request->route()->getName() : '';
    }

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
            $rules['address.last_name'] = ['required', 'string', 'max:128'];
            $rules['address.first_name'] = ['required', 'string', 'max:128'];
            $rules['address.last_name_kana'] = ['nullable', 'string', 'max:128', 'regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u'];
            $rules['address.first_name_kana'] = ['nullable', 'string', 'max:128', 'regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u'];
            $rules['address.post_first'] = ['nullable', 'string', 'max:3'];
            $rules['address.post_second'] = ['nullable', 'string', 'max:4'];

            $rules['address.prefecture'] = ['required', 'numeric'];
            $rules['address.address1'] = ['required', 'max:128'];
            $rules['address.address2'] = ['required', 'max:128'];
            $rules['address.address3'] = ['nullable', 'max:128'];
            $rules['address.tel'] = ['required', 'string', 'max:32'];

            return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['address.last_name'] = 'お名前（氏）';
        $attributes['address.first_name'] = 'お名前（名）';
        $attributes['address.last_name_kana'] = 'フリガナ（氏）';
        $attributes['address.first_name_kana'] = 'フリガナ（名）';

        $attributes['address.post_first'] = '郵便番号１';
        $attributes['address.post_second'] = '郵便番号２';

        $attributes['address.prefecture'] = '都道府県';

        $attributes['address.address1'] = '市区郡町村';
        $attributes['address.address2'] = '番地';
        $attributes['address.address3'] = 'ビル名';

        $attributes['address.tel'] = '電話番号';
        return $attributes;
    }

    public function messages()
    {
        return [
            'address.last_name.required' => 'お名前（氏）を入力してください。',
            'address.first_name.required' => 'お名前（名）を入力してください。',

            'address.last_name_kana.regex' => 'フリガナ（氏）に全角カタカナを入力してください。',
            'address.first_name_kana.regex' => 'フリガナ（名）に全角カタカナを入力してください。',

            'address.post_first.required' => '郵便番号を入力してください。',
            'address.post_second.required' => '郵便番号を入力してください。',

            'address.prefecture.required' => '都道府県を選択してください。',
            'address.address1.required' => '市区郡町村を入力してください。',
            'address.address2.required' => '番地を入力してください。',
            'address.tel.required' => '電話番号を入力してください。',
        ];
    }
}
