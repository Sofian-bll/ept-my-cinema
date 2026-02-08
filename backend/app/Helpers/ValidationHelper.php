<?php

namespace App\Helpers;

class ValidationHelper
{
    public static function required(array $data, array $requiredFields): ?array
    {
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $missingFields[] = $field;  // Ajt a array les fields manquant
            }
        }
        return $missingFields;

    }
}