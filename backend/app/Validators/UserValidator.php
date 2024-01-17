<?php

namespace App\Validators;

class UserValidator extends ApiFormValidator
{
    protected array $rules = [
            'name' => ['required', 'numeric', 'digits:20'],
            'pin' => ['required', 'numeric', 'digits:4'],
            'activation_date' => ['required', 'datetime'],
            'expiry_date' => ['required', 'date'],
            'balance' => ['required', 'numeric'],
    ];
}
