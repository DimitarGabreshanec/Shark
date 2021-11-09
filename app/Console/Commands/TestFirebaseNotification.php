<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestFirebaseNotification extends Command
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
    public function handle()
    {
        $firebaseToken = User::whereNotNull('firebase_token')->pluck('firebase_token')->all();
        $SERVER_API_KEY = 'AAAAjsvoMrY:APA91bFp7_RkdtRwDfDjjJfXRW3gFfern7HqAG9JifIoEupasU-GdwU55l6h4MhR2VpnAmfm_a-MCtKb90YFyt0ylhdtnt8pHQ_t4DLe4L_w_mjARHguX81aAi2q9VoMFNyN_4S1Yx8E';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "新しい商品が登録されました。",
                "body" => "XXXに新しい商品が登録されました。",
            ]
        ];
        $dataString = json_encode($data);

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
