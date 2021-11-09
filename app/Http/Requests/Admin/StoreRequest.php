<?php

namespace App\Http\Requests\Admin;
use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $rules['category'] = ['required', 'array'];

        $rules['type'] = ['required', 'numeric'];

        if ($this->has('id')) {
            $rules['email'] = ['required', 'email', 'max:150', "unique:stores,email,{$this->input('id')},id,deleted_at,NULL"];
        } else {
            $rules['email'] = ['required', 'email', 'max:255', 'unique:stores,email,NULL,id,deleted_at,NULL'];
        }

        $rules['store_name'] = ['required', 'string', 'max:256'];
        $rules['store_address'] = ['required', 'string', 'max:256'];
        $rules['tel'] = ['required', 'string', 'max:16'];
        $rules['charger_name'] = ['required', 'string', 'max:32'];
        $rules['url'] = ['nullable', 'url', 'max:512'];
        $rules['detail'] = ['nullable', 'string', 'max:1000'];
        $rules['post_first'] = ['required', 'string'];
        $rules['post_second'] = ['required', 'string'];
        
        $rules['main_img_name'] = ['required'];
        
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['category'] = '掲載カテゴリ';
        $attributes['type'] = '種別';
        $attributes['email'] = 'ログインID';
        $attributes['store_name'] = '店舗名';
        $attributes['store_address'] = '店舗所在地';
        $attributes['tel'] = '電話番号';
        $attributes['charger_name'] = '担当者名';
        $attributes['url'] = 'URL';
        $attributes['detail'] = '店舗紹介文';
        $attributes['main_img_name'] = 'メイン画像';
        $attributes['post_first'] = '郵便番号（後）'; 
        $attributes['post_second'] = '郵便番号（前）'; 
        return $attributes;
    }

    public function messages()
    {
        return [
            'category.required' => '１個以上の掲載カテゴリを選択してください。',
            'type.required' => '種別を選択してください。',
            'email.required' => 'ログインIDを入力してください。',
            'store_name.required' => '店舗名を入力してください。',
            'store_address.required' => '店舗所在地を入力してください。',
            'tel.required' => '電話番号を入力してください。',
            'charger_name.required' => '担当者名を入力してください。',
            'main_img_name.required' => 'メイン画像を選択してください。',
            'post_first.required' => '郵便番号（後）を入力してください。',
            'post_second.required' => '郵便番号（前）を入力してください。',
        ];
    }
}
