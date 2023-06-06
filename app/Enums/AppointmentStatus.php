<?php

namespace App\Enums;

final class AppointmentStatus
{
    public const SCHEDULED = 1;
    public const CONFIRMED = 2;
    public const CANCELLED = 3;

    public static function getStatusName(int $status): string
    {
        switch ($status) {
            case self::SCHEDULED:
                return 'SCHEDULED';
            case self::CONFIRMED:
                return 'CONFIRMED';
            case self::CANCELLED:
                return 'CANCELLED';
            default:
                return 'Unknown';
        }
    }

    public static function color(int $status): string
    {
        switch ($status) {
            case self::SCHEDULED:
                return 'primary';
            case self::CONFIRMED:
                return 'success';
            case self::CANCELLED:
                return 'danger';
            default:
                return 'default';
        }
    }

    
    public static function getStatusValues(): array
    {
        return [
            self::SCHEDULED,
            self::CONFIRMED,
            self::CANCELLED,
        ];
    }
}
