<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;

class HalfKanaRule implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /*$regex = '{^(
                (\xe3\x82[\xa1-\xbf]) # カタカナ
               |(\xe3\x83[\x80-\xbe]) # カタカナ
               |(\xef\xbc[\x90-\x99]) # 全角数字
               |(\xef\xbd[\xa6-\xbf]) # 半角カタカナ
               |(\xef\xbe[\x80-\x9f]) # 半角カタカナ
               |[0-9a-zA-Z ]     # 半角英数字空白
               |(\xe3\x80\x80)   # 全角スペース
            )+$}x';*/
        $regex = '{^(
               |(\xef\xbd[\xa6-\xbf]) # 半角カタカナ
               |(\xef\xbe[\x80-\x9f]) # 半角カタカナ
               |[0-9a-zA-Z ]     # 半角英数字空白
               |(\xe3\x80\x80)   # 全角スペース
            )+$}x';
        $result = preg_match( $regex, $value, $match );
        if ($result === 1) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '半角カタカナを入力してください。';
    }
}
