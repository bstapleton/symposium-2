<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

class TitleOrText implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = Arr::get(request()->all(), $attribute);

        if (empty($data['title']) && empty($data['text'])) {
            $fail('Either title or text must be provided.');
        }
    }
}
