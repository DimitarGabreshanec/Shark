<?php

namespace App\Service;

use App\Models\User;
use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Hash;

use Auth;
use DB;

class UserService
{

    public static function doSearch($search_params)
    {
        $users = User::orderByDesc('updated_at');
        if (isset($search_params['member_no']) && !empty($search_params['member_no'])) {
            $users->where('member_no', 'like', '%' . $search_params['member_no'] . '%');
        }
        if (isset($search_params['email']) && !empty($search_params['email'])) {
            $users->where('email', 'like', '%' . $search_params['email'] . '%');
        }
        if (isset($search_params['name']) && !empty($search_params['name'])) {
            $users->where('name', 'like', '%' . $search_params['name'] . '%');
        }

        if (isset($search_params['status'])) {
            if ($search_params['status'] != 'all') {
                $users->where('status', $search_params['status']);
            }
        }

        return $users;
    }

    public static function doCreate($data)
    {
        $data['member_no'] = self::generateUserCode();
        $data['password'] = Hash::make('12345678'); //既定パスワード
        $data['position'] = self::getJsonPositionData($data);
        if($obj_user = User::create($data)) { 
            $obj_user->sendUserCreateNotification();
            return $obj_user;
        } else {
            return null;
        }
    }

    public static function doCreateFront($data)
    {
        $obj_user = new User();

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_user->create($data)) {
            return $obj_user;
        } else {
            return null;
        }
    }

    public static function doUpdate(User $obj_user, $data)
    {

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_user->update($data)) {
            return $obj_user;
        } else {
            return null;
        }
    }

    public static function doUpdateFront(User $obj_user, $data)
    {
        if(isset($data['password']) && !empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }

        $data['birthday'] = Carbon::parse("{$data['birth_year']}-{$data['birth_month']}-{$data['birth_day']}")->format('Y-m-d');

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_user->update($data)) {
            return $obj_user;
        } else {
            return null;
        }
    }

    public static function doDelete($obj_user)
    {
        if ($obj_user->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public static function generateUserCode()
    {
        $latest_code = User::orderByDesc('member_no')->first();
        if (is_object($latest_code)) {
            $latest_code_no = (int)str_replace('U', '', ltrim($latest_code->member_no, '0'));
            $new_number = $latest_code_no + 1;
            $number_length = strlen($new_number) > 8 ? strlen($new_number) : 8;
            $new_user_code = 'U' . str_pad($new_number, $number_length, "0", STR_PAD_LEFT);
        } else {
            $new_user_code = 'U00000001';
        }

        return $new_user_code;
    }

    public static function getAllUsers()
    {
        return User::orderBy('id')
            ->get();
    }

    public static function getJsonPositionData($params)
    {
        if (!empty($params['user_lat']) && !empty($params['user_lng'])) {
            $position['lat'] = $params['user_lat'];
            $position['lng'] = $params['user_lng'];
            return json_encode($position);
        } else {
            return null;
        }
    }

    public static function getFormatPosition($index_params)
    {
        return json_decode($index_params, true);
    }

    public static function getImasuguUsers($lat, $lng)
    {
        $users = User::orderBy('id')
                    ->where('status', '=', config('const.user_status_code.registered'))
                    ->get();
        $new_user=[];
        foreach($users as $user){
            $self_pos = $user->getPosition();
            $my_lat = isset($self_pos['lat']) ? $self_pos['lat'] : null;
            $my_lng = isset($self_pos['lng']) ? $self_pos['lng'] : null; 
            $distance = self::mile_distance($my_lat, $my_lng, $lat, $lng);  
            if($distance < 5){
                array_push($new_user, $user);
            }
            
        } 
        return $new_user;
    }

    public static function mile_distance($lat1, $lng1, $lat2, $lng2) {
        $R = 3958.8; // Radius of the Earth in miles
        $rlat1 = $lat1 * (3.141592654/180); // Convert degrees to radians
        $rlat2 = $lat2 * (3.141592654/180); // Convert degrees to radians
        $difflat = $rlat2-$rlat1; // Radian difference (latitudes)
        $difflon = ($lng2-$lng1) * (3.141592654/180); // Radian difference (longitudes)
        $d = 2 * $R * asin(sqrt(sin($difflat/2)*sin($difflat/2)+cos($rlat1)*cos($rlat2)*sin($difflon/2)*sin($difflon/2)));
        return $d;
    }
}
