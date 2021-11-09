<?php

namespace App\Http\Requests\Admin; 
use Illuminate\Foundation\Http\FormRequest;

class ConfigurationRequest extends FormRequest
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
        $rules['tax_rate'] = ['numeric', 'nullable'];
        $rules['fee_fix'] = ['numeric', 'nullable'];
        $rules['fee_percent'] = ['numeric', 'nullable']; 
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['tax_rate'] = '消費税';
        $attributes['fee_fix'] = '手数料(月額)';
        $attributes['fee_percent'] = '手数料'; 
        return $attributes;
    }

    public function messages()
    {
        return [ 
        ];
    }
}
