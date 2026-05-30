<?php

namespace App\Enums;

/**
 * Enum for Question types in a quiz
 */
enum QuestionType: string
{
    case MULTIPLE_CHOICE = 'multiple_choice';

    case TRUE_FALSE = 'true_false';

    case ESSAY = 'essay';

    /**
     * Get the translated label for the question type
     */
    public function label(): string
    {
        return match ($this) {
            self::MULTIPLE_CHOICE => __('Trắc nghiệm'),
            self::TRUE_FALSE => __('Đúng/Sai'),
            self::ESSAY => __('Tự luận'),
        };
    }
}
