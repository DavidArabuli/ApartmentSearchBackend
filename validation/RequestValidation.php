<?php

class RequestValidation
{
    public static function validateQuery(array $queryParams)
    {
        return [
            'rooms' => filter_var($queryParams['rooms'] ?? null, FILTER_VALIDATE_INT),
            'district' => isset($queryParams['district']) ? htmlspecialchars($queryParams['district'], ENT_QUOTES, 'UTF-8') : null,
            'm2_min' => filter_var($queryParams['m2_min'] ?? null, FILTER_VALIDATE_INT),
            'm2_max' => filter_var($queryParams['m2_max'] ?? null, FILTER_VALIDATE_INT),
            'price_min' => filter_var($queryParams['price_min'] ?? null, FILTER_VALIDATE_INT),
            'price_max' => filter_var($queryParams['price_max'] ?? null, FILTER_VALIDATE_INT),
            'floor_min' => filter_var($queryParams['floor_min'] ?? null, FILTER_VALIDATE_INT),
            'floor_max' => filter_var($queryParams['floor_max'] ?? null, FILTER_VALIDATE_INT),
        ];
    }
    public static function validatePagination(array $queryParams): array
    {
        $limit = max(1, min(100, (int)($queryParams['page_limit'] ?? 4)));
        $page = max(1, (int)($queryParams['page'] ?? 1));

        return ['page_limit' => $limit, 'page' => $page];
    }
}
