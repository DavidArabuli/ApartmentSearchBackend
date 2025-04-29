<?php

class RequestValidation
{
    public static function validateQuery(array $queryParams)
    {
        return [
            'rooms' => self::sanitizeInt($queryParams['rooms'] ?? null),
            'district' => isset($queryParams['district']) ? htmlspecialchars($queryParams['district'], ENT_QUOTES, 'UTF-8') : null,
            'm2_min' => self::sanitizeInt($queryParams['m2_min'] ?? null),
            'm2_max' => self::sanitizeInt($queryParams['m2_max'] ?? null),
            'price_min' => self::sanitizeInt($queryParams['price_min'] ?? null),
            'price_max' => self::sanitizeInt($queryParams['price_max'] ?? null),
            'floor_min' => self::sanitizeInt($queryParams['floor_min'] ?? null),
            'floor_max' => self::sanitizeInt($queryParams['floor_max'] ?? null),
        ];
        // return [
        //     'rooms' => filter_var($queryParams['rooms'] ?? null, FILTER_VALIDATE_INT),
        //     'district' => isset($queryParams['district']) ? htmlspecialchars($queryParams['district'], ENT_QUOTES, 'UTF-8') : null,
        //     'm2_min' => filter_var($queryParams['m2_min'] ?? null, FILTER_VALIDATE_INT),
        //     'm2_max' => filter_var($queryParams['m2_max'] ?? null, FILTER_VALIDATE_INT),
        //     'price_min' => filter_var($queryParams['price_min'] ?? null, FILTER_VALIDATE_INT),
        //     'price_max' => filter_var($queryParams['price_max'] ?? null, FILTER_VALIDATE_INT),
        //     'floor_min' => filter_var($queryParams['floor_min'] ?? null, FILTER_VALIDATE_INT),
        //     'floor_max' => filter_var($queryParams['floor_max'] ?? null, FILTER_VALIDATE_INT),
        // ];
    }
    public static function validatePagination(array $queryParams): array
    {
        $limit = max(1, min(100, (int)($queryParams['page_limit'] ?? 20)));
        $page = max(1, (int)($queryParams['page'] ?? 1));

        return ['page_limit' => $limit, 'page' => $page];
    }
    public static function sanitizeInt($value)
    {
        if ($value === '' || $value === null) return null;
        $int = filter_var($value, FILTER_VALIDATE_INT);
        return $int === false ? null : $int;
    }
}
