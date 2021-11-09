<?php

namespace App\Http\Requests\Admin; 
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules['sequence'] = ['required', 'numeric']; 
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['name'] = 'カテゴリー名';
        $attributes['sequence'] = '表示順序'; 
        return $attributes;
    }

    public function messages()
    {
        return [
            'name.required' => 'カテゴリー名を入力してください。',
            'sequence.required' => '表示順序入力してください。', 
        ];
    }
}
