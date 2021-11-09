<?php

namespace App\Http\Requests\Admin;
use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules['store_id'] = ['required'];
        $rules['price'] = ['numeric', 'nullable'];
        $rules['ship_price'] = ['numeric', 'nullable'];
        $rules['restaurant_deposit'] = ['numeric', 'nullable']; 
        $rules['quantity'] = ['numeric', 'nullable'];
        $rules['product_name'] = ['required', 'string', 'max:256'];
        $rules['main_img_name'] = ['required', 'string'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['type'] = '商品種類';
        $attributes['product_no'] = '商品番号';
        $attributes['product_name'] = '商品名';
        $attributes['list_reason'] = '出品理由';
        $attributes['available_from'] = '引取可能時間(from)';
        $attributes['available_to'] = '引取可能時間(to)';
        $attributes['price'] = '出品価格';
        $attributes['ship_price'] = '送料';
        $attributes['restaurant_deposit'] = '予約金';
        $attributes['post_from'] = '掲載期間(from)';
        $attributes['post_to'] = '掲載期間(to)';
        $attributes['introduction'] = '商品紹介';
        $attributes['quantity'] = '在庫数';
        $attributes['note'] = '備考';
        $attributes['main_img_name'] = 'メイン画像';
        return $attributes;
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名を入力してください。',
            'list_reason.required' => '出品理由を入力してください。',
            'store_id.required' => '店舗を選択してください。',
            'main_img_name.required' => 'メイン画像を選択してください。',
        ];
    }
}
