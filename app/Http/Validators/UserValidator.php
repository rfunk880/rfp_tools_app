<?php

namespace App\Http\Validators;

use App\Models\UserType;
use Support\Contracts\ValidateRequest;
use Support\Validation\Laravel\LaravelValidator;
use Support\Validation\Validator;

class UserValidator  extends Validator implements ValidateRequest
{
    protected $rules = array(

        'default' => [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'confirmed',
            'user_type_id' => 'required'
        ],
        'edit' => [
            'name' => 'required',
            'password' => 'confirmed',
            'user_type_id' => 'required'
        ],
        'profile' => [
            'name' => 'required',
        ],
        'info' => [
            'first_name' => 'required',
            'last_name' => 'required'
        ],
        'change_password' => [
            'password' => 'required',
            'password_confirmation' => 'required'
        ],
        'msa_manual' => [
            'file' => 'required|mimes:jpg,png,gif,jpeg,xls,pdf,xlsx,csv'
        ]

    );
}
