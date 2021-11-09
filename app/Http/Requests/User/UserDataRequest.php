<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class UserDataRequest extends FormRequest
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
        $rules['name'] = ['required', 'string', 'max:32'];
        $rules['birthday'] = [new BirthdayRule($this->input('birth_year'), $this->input('birth_month'), $this->input('birth_day'))];
        $rules['gender'] = ['required', 'numeric']; 
        $rules['post_first'] = ['required', 'string'];
        $rules['post_second'] = ['required', 'string'];
        $rules['address'] = ['required', 'string'];
        $rules['prefecture'] = ['required'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['name'] = 'お名前';
        $attributes['birthday'] = '生年月日';
        $attributes['gender'] = '性別';
        $attributes['email'] = 'ログインID'; 
        $attributes['password'] = 'ログインパスワード'; 
        $attributes['current_password'] = '先パスワード';
        $attributes['password_confirmation'] = '確認パスワード'; 
        $attributes['email_confirmation'] = '確認メール';
        $attributes['post_first'] = '郵便番号（後）'; 
        $attributes['post_second'] = '郵便番号（前）'; 
        $attributes['address'] = '住所詳細'; 
        $attributes['prefecture'] = '都道府県'; 
        return $attributes;
    }
  
    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください。', 
            'gender.required' => '性別を選択してください。',
            'birthday.required' => '生年月日を選択してください。',      
            'post_first.required' => '郵便番号（後）を入力してください。',
            'post_second.required' => '郵便番号（前）を入力してください。',
            'address.required' => '住所を入力してください。',
            'prefecture.required' => '都道府県を選択してください。',
        ];
    }
}
