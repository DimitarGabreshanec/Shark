<?php

namespace App\Http\Requests\Store;
use App\Rules\Store\WorkTimeRule;
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
        $rules['store_name'] = ['required', 'string', 'max:256'];
        $rules['store_address'] = ['required', 'string', 'max:256'];
        $rules['tel'] = ['required', 'string', 'max:16'];
        $rules['prefecture'] = ['required'];
        //$rules['charger_name'] = ['required', 'string', 'max:32'];
        $rules['url'] = ['nullable', 'url', 'max:512'];
        $rules['detail'] = ['nullable', 'string', 'max:1000'];
        $rules['main_img_name'] = ['required', 'string'];
        $rules['work_from'] = [new WorkTimeRule($this->input('work_from_hour'), $this->input('work_from_minute'), '営業時間(前)')];
        $rules['work_to'] = [new WorkTimeRule($this->input('work_to_hour'), $this->input('work_to_minute'), '営業時間(次)')];

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
        $attributes['work_from'] = '営業時間(終)';
        $attributes['work_to'] = '営業時間(開)';
        $attributes['prefecture'] = '都道府県';

        return $attributes;
    }

    public function messages()
    {
        return [
            'category.required' => '１個以上の掲載カテゴリを選択してください。',
            'store_name.required' => '店舗名を入力してください。',
            'store_address.required' => '住所を入力してください。',
            'tel.required' => '電話番号を入力してください。',
            'prefecture.required' => '都道府県を選択してください。',
            'main_img_name.required' => 'メイン画像を選択してください。',
            'work_from.required' => '営業時間(開)を選択してください。',
            'work_to.required' => '営業時間(終)を選択してください。',

        ];
    }
}
