<?php

namespace App\Enums;

/**
 * Enum for Quiz classification
 */
enum QuizType: string
{
    case MULTIPLE_CHOICE = 'multiple_choice';

    case ESSAY = 'essay';

    case MIXED = 'mixed';

    /**
     * Get the translated label for the quiz type
     */
    public function label(): string
    {
        return match ($this) {
            self::MULTIPLE_CHOICE => __('Trắc nghiệm'),
            self::ESSAY => __('Tự luận'),
            self::MIXED => __('Hỗn hợp'),
        };
    }
}
