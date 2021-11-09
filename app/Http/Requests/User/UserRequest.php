<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        $rules['password_confirmation'] = ['required', 'same:password'];   
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['name'] = 'お名前';
        $attributes['birthday'] = '生年月日';
        $attributes['gender'] = '性別';
        $attributes['email'] = 'ログインID'; 
        $attributes['password'] = 'パスワード';  
        $attributes['password_confirmation'] = 'パスワード確認';  
        return $attributes;
    }
  
    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください。', 
            'gender.required' => '性別を選択してください。',
            'birthday.required' => '生年月日を選択してください。',    
            'password.required' => 'パスワードを入力してください。',  
            'password_confirmation.required' => 'パスワード確認を入力してください。',      
        ];
    }
}
