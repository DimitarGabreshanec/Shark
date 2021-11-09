<?php

namespace App\Http\Requests\Store;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Store\WorkTimeRule;
use App\Rules\Store\PostDateRule;



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
        $rules['price'] = ['numeric', 'nullable'];
        $rules['product_name'] = ['required', 'string', 'max:256'];
        $rules['main_img_name'] = ['required', 'string'];
        $rules['restaurant_deposit'] = ['numeric', 'nullable']; 
        $rules['available_from'] = [new WorkTimeRule($this->input('available_from_hour'), $this->input('available_from_minute'), '引取可能時間(開)')];
        $rules['available_to'] = [new WorkTimeRule($this->input('available_to_hour'), $this->input('available_to_minute'), '引取可能時間(終)')];
        $rules['post_from'] = [new PostDateRule($this->input('post_from_date'), $this->input('post_from_hour'), $this->input('post_from_minute'), '前')];
        $rules['post_to'] = [new PostDateRule($this->input('post_to_date'), $this->input('post_to_hour'), $this->input('post_to_minute'), '後')];
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
            'main_img_name.required' => 'メイン画像を選択してください。',
        ];
    }
}
