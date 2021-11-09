<?php

namespace App\Http\Requests\Store;
 
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Store\MatchOldPassword; 

class PasswordRequest extends FormRequest
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
        $rules['current_password'] = ['required', new MatchOldPassword];
        $rules['password'] = ['required', 'string', 'min:8', 'confirmed']; 
        $rules['password_confirmation'] = ['required', 'same:password'];  
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['current_password'] = '先パスワード'; 
        $attributes['password'] = 'パスワード'; 
        $attributes['password_confirmation'] = 'パスワード確認'; 
        return $attributes;
    }
  
    public function messages()
    {
        return [ 
            'current_password.required' => '先パスワードを入力してください。', 
            'password.required' => 'パスワードを入力してください。',  
            'password_confirmation.required' => 'パスワード確認を入力してください。',  
        ];
    }
}
