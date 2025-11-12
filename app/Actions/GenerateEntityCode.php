<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GenerateEntityCode
{
    public function execute(string $modelClass, string $codeColumn, string $name): string
    {
        $baseCode = Str::substr(Str::slug($name), 0, 10);
        $baseCode = rtrim($baseCode, '-');
        $code = $baseCode;
        $counter = 1;

        if (!is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("The provided class must be an Eloquent model.");
        }

        while ($modelClass::where($codeColumn, $code)->exists()) {
            $code = $baseCode . '-' . $counter;
            $counter++;
        }

        return $code;
    }
}
