<?php
// validation for query params
class RequestValidation
{
    public static function validateQuery(array $queryParams)
    {
        $email = isset($queryParams['email']) ? trim($queryParams['email']) : null;

        // Validate email format if it's set
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }
        return [
            'rooms' => self::sanitizeInt($queryParams['rooms'] ?? null),
            'district' => isset($queryParams['district']) ? htmlspecialchars($queryParams['district'], ENT_QUOTES, 'UTF-8') : null,
            'email' => $email,
            'm2_min' => self::sanitizeInt($queryParams['m2_min'] ?? null),
            'm2_max' => self::sanitizeInt($queryParams['m2_max'] ?? null),
            'price_min' => self::sanitizeInt($queryParams['price_min'] ?? null),
            'price_max' => self::sanitizeInt($queryParams['price_max'] ?? null),
            'floor_min' => self::sanitizeInt($queryParams['floor_min'] ?? null),
            'floor_max' => self::sanitizeInt($queryParams['floor_max'] ?? null),
        ];
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
