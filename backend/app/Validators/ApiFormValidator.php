<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
abstract class ApiFormValidator
{
    protected array $rules = [];

    public function validate(Request $request): ?array
    {
        try {
            return $request->validate($this->rules);
        } catch (ValidationException) {
            return null;
        }
    }
}
