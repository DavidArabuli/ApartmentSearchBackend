<?php

class RequestValidation
{
    public function validateQuery(array $queryParams)
    {
        return [
            'istabas' => filter_var($queryParams['istabas'] ?? null, FILTER_VALIDATE_INT),
            'pagasts' => isset($queryParams['pagasts']) ? htmlspecialchars($queryParams['pagasts'], ENT_QUOTES, 'UTF-8') : null,
            'm2_min' => filter_var($queryParams['m2_min'] ?? null, FILTER_VALIDATE_INT),
            'm2_max' => filter_var($queryParams['m2_max'] ?? null, FILTER_VALIDATE_INT),
            'cena_min' => filter_var($queryParams['cena_min'] ?? null, FILTER_VALIDATE_INT),
            'cena_max' => filter_var($queryParams['cena_max'] ?? null, FILTER_VALIDATE_INT),
            'stavs_min' => filter_var($queryParams['stavs_min'] ?? null, FILTER_VALIDATE_INT),
            'stavs_max' => filter_var($queryParams['stavs_max'] ?? null, FILTER_VALIDATE_INT),
        ];
    }
    public static function pagination(array $queryParams): array
    {
        $limit = max(1, min(100, (int)($queryParams['page_limit'] ?? 4)));
        $page = max(1, (int)($queryParams['page'] ?? 1));

        return ['page_limit' => $limit, 'page' => $page];
    }
}
