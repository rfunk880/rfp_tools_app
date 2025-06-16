<?php

namespace App\Http\Validators;

use Themightysapien\Core\Services\Validation\AbstractValidator;
use Themightysapien\Core\Services\Validation\Laravel\LaravelValidator;
use Themightysapien\Core\Services\Validation\ValidationService;

class GeneralValidator extends LaravelValidator implements ValidationService
{
    protected $rules = [
        'folder-upload' => [
            'file' => 'required'
        ],
        'persons_create_single' => [
            'id_number' => 'required',
            'first_name' => 'required'
        ],
        'notes_create' => [
            'notes' => 'required'
        ]

    ];
}
