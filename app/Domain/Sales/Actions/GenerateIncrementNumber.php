<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions;

use Illuminate\Database\Eloquent\Model;

class GenerateIncrementNumber
{
    public function execute(string $modelClass, string $incrementNumberColumn): string
    {
        if (!is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("The provided class must be an Eloquent model.");
        }

        $currentYear = date('Y');
        $lastInstance = $modelClass::orderBy($incrementNumberColumn, 'desc')->first();

        if ($lastInstance) {
            $lastIncrementNumber = $lastInstance->$incrementNumberColumn;
            $lastYear = substr($lastIncrementNumber, 0, 4);
            $lastSequence = (int)substr($lastIncrementNumber, 4);

            if ($lastYear == $currentYear) {
                $newSequence = str_pad((string)($lastSequence + 1), 5, '0', STR_PAD_LEFT);
                return $currentYear . $newSequence;
            }
        }

        // If no instance exists or it's a new year, start a new sequence
        return $currentYear . '00001';
    }
}
