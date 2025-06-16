<?php

namespace App\Traits;

use DateTime;
use App\Models\User;
use App\Models\UserType;

trait UserHelperTrait
{
    public function resetVerifyCode($code)
    {
        $this->verify_code = $code;
        return $this->save();
    }

    public static function verifyUserWithCode($code)
    {
        $user = User::where('verify_code', 'LIKE', $code)->first();
        if ($user) {
            $user->status = 1;
            $user->verify_code = null;
            $user->last_login_at = date('Y-m-d H:i:s');
            $user->save();
            return $user;
        }
        return false;
    }

    public function getFullNameAttribute($val)
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasFilledInfo()
    {
        return $this->info->completed_status;
    }

    public static function mapToGroup($labelAr)
    {
        return collect($labelAr)->map(function ($item) {
            return self::mapLabelToId($item);
        })->toArray();
    }

    public static function mapLabelToId($label)
    {
        switch (strtolower($label)) {
            case 'admin':
            case 'administrator':
                return UserType::ADMIN;

            case 'user':
            case 'users':
                return UserType::USER;
        }

        return null;
    }

  

    public function scopeFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            if (@$params['keyword'] != '') {
                $keyword = urldecode($params['keyword']);
                $q->where(function ($q) use ($keyword) {
                    $q
                        ->orWhere('name', 'LIKE', $keyword . '%')
                        ->orWhere('email', 'LIKE', $keyword . '%')
                        // ->orWhere('company_name', 'LIKE', $keyword . '%')
                        /* ->orWhere('phone', 'LIKE', $keyword . '%') */;
                });
            }
        });
    }


    public function scopeFilterCustomer($q, $params = [])
    {
        return $q->select('users.*')
            ->leftJoin('patient_information', 'patient_information.user_id', '=', 'users.id')
            ->where(function ($q) use ($params) {
                if (@$params['keyword'] != '') {
                    $keyword = urldecode($params['keyword']);
                    $q->where(function ($q) use ($keyword) {
                        $q
                            ->orWhere('name', 'LIKE', $keyword . '%')
                            ->orWhere('email', 'LIKE', $keyword . '%')
                            ->orWhere('phone', 'LIKE', $keyword . '%')
                            ->orWhere('patient_information.age', 'LIKE', $keyword . '%')
                            ->orWhere('patient_information.full_address', 'LIKE', $keyword . '%');
                    });
                }
            });
    }


    public function getProfileThumbUrlAttribute(){
        return $this->profile_photo_path ? url('img/storage/'.$this->profile_photo_path.'?w=100') : dummyImagePath();
    }


    public function getInitialLettersAttribute(){
        return initialLetters($this->name);
    }


    public function getProfilePicture(){
        $avatar = $this->getMedia('avatar')->first();
        if($avatar){
            return $avatar->getThumbUrl();
        }

        return asset('img/avatar.png');

    }
}
