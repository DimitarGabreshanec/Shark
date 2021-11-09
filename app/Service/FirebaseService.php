<?php

namespace App\Service;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Console\Command;

class FirebaseService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public static function handle($firebaseTokens, Store $store, Product $product, $image_url, $new_flag)
    {
        //$firebaseTokens = $user->whereNotNull('firebase_token')->pluck('firebase_token')->all();

        if(!empty($firebaseTokens)){
            $SERVER_API_KEY = 'AAAAjsvoMrY:APA91bFp7_RkdtRwDfDjjJfXRW3gFfern7HqAG9JifIoEupasU-GdwU55l6h4MhR2VpnAmfm_a-MCtKb90YFyt0ylhdtnt8pHQ_t4DLe4L_w_mjARHguX81aAi2q9VoMFNyN_4S1Yx8E';

        $title = "【Campbell】新しい商品の通知";
        if($new_flag == 1){
            $messages = $store->store_name . "に新しい商品【" . $product->product_name . "】が更新しました。";
        } else {
            $messages = $store->store_name . "に新しい商品【" . $product->product_name . "】が登録しました。";
        }
        $url = env('APP_URL') . '/shop/product_info/' . $store->id . '/' . $product->id;  
        $postdata = [
            "registration_ids" => $firebaseTokens,
            "notification" => [
                "title" => $title,
                "body" => $messages,
                'image' => $image_url,
                "icon"=> "favicon",
            ],
            "data" => [
                "notification_body" =>  $messages,
                "notification_title"=> $title,
                "notification_foreground"=> "true",
                "notification_android_channel_id"=> "fcm_default_channel",
                "notification_android_priority"=> "2",
                "notification_android_visibility"=> "1",
                "notification_android_color"=> "#ff0000",
                "notification_android_icon"=> "favicon",
                "notification_android_sound"=> "crystal",
                "notification_android_vibrate"=> "500, 200, 500",
                "notification_android_lights"=> "#ffff0000, 250, 250",
                'store_id'=> $store->id,
                'product_id'=> $product->id,
                'image' => $image_url,
                'url'=> $url,
            ],
        ];
        $dataString = json_encode($postdata);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        curl_close($ch);
        }

    }

    public static function handleCreateUser($firebaseTokens, $mail, $url)
    {
        //$firebaseTokens = $user->whereNotNull('firebase_token')->pluck('firebase_token')->all();

        if(!empty($firebaseTokens)){
            $SERVER_API_KEY = 'AAAAjsvoMrY:APA91bFp7_RkdtRwDfDjjJfXRW3gFfern7HqAG9JifIoEupasU-GdwU55l6h4MhR2VpnAmfm_a-MCtKb90YFyt0ylhdtnt8pHQ_t4DLe4L_w_mjARHguX81aAi2q9VoMFNyN_4S1Yx8E';

        $title = "【Campbell】アカウント仮登録";
        $messages = $mail . "サイトへのアカウント仮登録が完了しました。";
        $postdata = [
            "registration_ids" => $firebaseTokens,
            "notification" => [
                "title" => $title,
                "body" => $messages,
                "icon"=> "favicon",
            ],
            "data" => [
                "notification_body" =>  $messages,
                "notification_title"=> $title,
                "notification_foreground"=> "true",
                "notification_android_channel_id"=> "fcm_default_channel",
                "notification_android_priority"=> "2",
                "notification_android_visibility"=> "1",
                "notification_android_color"=> "#ff0000",
                "notification_android_icon"=> "favicon",
                "notification_android_sound"=> "crystal",
                "notification_android_vibrate"=> "500, 200, 500",
                "notification_android_lights"=> "#ffff0000, 250, 250",
                'url'=> $url,
            ],
        ];
        $dataString = json_encode($postdata);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        curl_close($ch);
        }

    }
}
