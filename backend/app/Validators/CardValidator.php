<?php

namespace App\Validators;

class CardValidator extends ApiFormValidator
{
    protected array $rules =
        [
            'card_number' => ['required', 'numeric', 'digits:20'],
            'pin' => ['required', 'numeric', 'digits:4'],
            'activation_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date'],
            'balance' => ['required', 'numeric'],
        ];




}
