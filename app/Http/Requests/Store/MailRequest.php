<?php

namespace App\Http\Requests\Store;
 
use Illuminate\Foundation\Http\FormRequest;

class MailRequest extends FormRequest
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
        $rules['email_confirmation'] = ['required', 'string', 'max:32', 'same:email'];  
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['email'] = 'メールアドレス'; 
        $attributes['email_confirmation'] = 'メールアドレス確認'; 
        return $attributes;
    }
  
    public function messages()
    {
        return [ 
            'email.required' => 'メールアドレスを入力してください。', 
            'email_confirmation.required' => 'メールアドレス確認を入力してください。',  
            'email.email' => '無効なメールアドレス。'
        ];
    }
}
