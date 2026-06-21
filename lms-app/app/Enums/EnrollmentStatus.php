<?php

namespace App\Enums;

/**
 * Enum for Enrollment status
 */
enum EnrollmentStatus: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case COMPLETED = 2;
    case CANCELLED = 3;

    /**
     * Get the translated label for the enrollment status
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Chờ duyệt'),
            self::ACTIVE => __('Đang học'),
            self::COMPLETED => __('Đã hoàn thành'),
            self::CANCELLED => __('Đã hủy'),
        };
    }
}
