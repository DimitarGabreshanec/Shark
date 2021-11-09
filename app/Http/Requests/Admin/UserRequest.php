<?php

namespace App\Http\Requests\Admin;
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
        if ($this->has('id')) {
            $rules['email'] = ['required', 'email', 'max:150', "unique:users,email,{$this->input('id')},id,deleted_at,NULL"];
        } else {
            $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'];
        }
        $rules['gender'] = ['required', 'numeric'];
        $rules['post_first'] = ['required', 'string'];
        $rules['post_second'] = ['required', 'string'];
        $rules['address'] = ['required', 'string'];
        
        //$rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['member_no'] = 'UID';
        $attributes['name'] = '名前'; 
        $attributes['email'] = 'メール';
        $attributes['birthday'] = '誕生日';
        $attributes['email_verify_token	'] = 'メール確認トークン';
        $attributes['password'] = 'パスワード';
        $attributes['gender'] = '性別';
        $attributes['status'] = '登録ステータス';
        $attributes['social'] = 'Socialアカウント';
        $attributes['last_login_at'] = '最終ログイン日時'; 
        $attributes['post_first'] = '郵便番号（後）'; 
        $attributes['post_second'] = '郵便番号（前）'; 
        $attributes['address'] = '住所詳細'; 
        return $attributes;
    }

    public function messages()
    {
        return [ 
            'name.required' => '名前を入力してください。', 
            'email.required' => 'メールアドレスを入力してください。',
            'gender.required' => '性別を選択してください。',
            'birthday.required' => '誕生日を選択してください。',
            'post_first.required' => '郵便番号（後）を入力してください。',
            'post_second.required' => '郵便番号（前）を入力してください。',
            'address.required' => '住所詳細を入力してください。',
        ];
    }
}
