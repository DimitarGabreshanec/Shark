<?php

namespace App\Http\Requests\Store;
 
use Illuminate\Foundation\Http\FormRequest;

class LoginStoreRequest extends FormRequest
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
        $rules['email'] = ['required', 'string', 'max:32', 'email'];
        $rules['password'] = ['required'];   
        $rules['password_confirmation'] = ['required'];
        $rules['password_confirmation'] = ['required', 'same:password']; 
        //$rules['main_img'] = ['required', 'file'];
        $rules['type'] = ['required', 'numeric']; 
        $rules['store_name'] = ['required', 'string', 'max:256']; 
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes(); 
        $attributes['password'] = 'ログインパスワード';  
        $attributes['password_confirmation'] = '確認パスワード'; 
        $attributes['email'] = 'メールを入力してください。'; 
        $attributes['main_img'] = 'メイン画像';
        $attributes['type'] = '種別';
        return $attributes;
    }
  
    public function messages()
    {
        return [ 
            'password.required' => 'ログインパスワードを入力してください。', 
            'password_confirmation.required' => '確認パスワードを入力してください。',  
            'email.required' => 'メールを入力してください。', 
            'main_img.required' => 'メイン画像を選択してください。',
            'type.required' => '種別を選択してください。',
            'store_name.required' => '店舗名を入力してください。',
        ];
    }
}
