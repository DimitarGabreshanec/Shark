<?php

namespace App\Http\Requests\Store;
 
use Illuminate\Foundation\Http\FormRequest;

class StoreDataRequest extends FormRequest
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
        if ($this->has('id')) {
            $rules['email'] = ['required', 'email', 'max:32', "unique:stores,email,{$this->input('id')},id,deleted_at,NULL"];
        } else {
            $rules['email'] = ['required', 'email', 'max:32', 'unique:stores,email,NULL,id,deleted_at,NULL'];
        }  
        $rules['password'] = ['required'];   
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes(); 
        $attributes['password'] = 'ログインパスワード';  
        $attributes['email'] = 'メールを入力してください。'; 
        return $attributes;
    }
  
    public function messages()
    {
        return [ 
            'password.required' => 'ログインパスワードを入力してください。', 
            'email.required' => 'メールを入力してください。', 
        ];
    }
}
