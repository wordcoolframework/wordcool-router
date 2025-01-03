<?php

namespace Router;

class RateLimiter {

    private static array $requestCounts = [];
    private static array $rateLimits = [];

    public static function addLimit(string $key, int $maxAttempts, int $decaySeconds): void {
        self::$rateLimits[$key] = [
            'maxAttempts'   => $maxAttempts,
            'decaySeconds'  => $decaySeconds,
        ];
    }

    public static function hit(string $key): bool {
        $currentTime = time();

        if (!isset(self::$requestCounts[$key])) {
            self::$requestCounts[$key] = [
                'attempts' => 0,
                'expiresAt' => $currentTime + self::$rateLimits[$key]['decaySeconds'],
            ];
        }

        $rateLimit = self::$rateLimits[$key];
        $requestInfo = &self::$requestCounts[$key];

        if ($currentTime > $requestInfo['expiresAt']) {
            $requestInfo['attempts'] = 0;
            $requestInfo['expiresAt'] = $currentTime + $rateLimit['decaySeconds'];
        }

        if ($requestInfo['attempts'] < $rateLimit['maxAttempts']) {
            $requestInfo['attempts']++;
            return true; // Allow the request
        }

        return false; // Rate limit exceeded
    }

    public static function remaining(string $key): int {
        return self::$rateLimits[$key]['maxAttempts'] - (self::$requestCounts[$key]['attempts'] ?? 0);
    }

    public static function reset(string $key): void {
        unset(self::$requestCounts[$key]);
    }

}