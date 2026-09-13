<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mục tiêu EXP mỗi ngày (Daily Goal EXP)
    |--------------------------------------------------------------------------
    |
    | Số điểm kinh nghiệm tối thiểu học viên cần đạt được trong một ngày
    | để duy trì hoặc tăng chuỗi học tập (Streak).
    |
    */
    'daily_goal_exp' => 50,

    /*
    |--------------------------------------------------------------------------
    | Minimum EXP to maintain daily learning streak
    |--------------------------------------------------------------------------
    |
    | The minimum experience points a learner must earn in a day to
    | maintain (or start) their streak. This is intentionally kept low
    | so that even a short 2-3 minute study session counts as "studied today".
    |
    */
    'streak_min_exp' => 10,

    /*
    |--------------------------------------------------------------------------
    | Múi giờ hệ thống tính ngày học (Timezone)
    |--------------------------------------------------------------------------
    |
    | Múi giờ mặc định cho tất cả các phép tính ngày học và reset Streak.
    |
    */
    'timezone' => 'Asia/Ho_Chi_Minh',

    /*
    |--------------------------------------------------------------------------
    | Cấu hình điểm EXP và giới hạn theo từng hành động học tập
    |--------------------------------------------------------------------------
    |
    | exp: Số EXP nhận được cho mỗi lần hoàn thành.
    | daily_cap: Giới hạn EXP tối đa nhận được từ hành động này trong 1 ngày (null là không giới hạn).
    | one_time: Nếu là true, chỉ nhận EXP 1 lần duy nhất cho mỗi reference_id (ví dụ từng tab bài học).
    |
    */
    'actions' => [

        // --- 1. THI THỬ HSK ---
        'hsk_mock_exam' => [
            'exp'       => 50,
            'daily_cap' => null,
            'one_time'  => false,
        ],

        // --- 2. CÁC PHẦN TRONG BÀI HỌC KHÓA HỌC (COURSE V2) ---
        'course_vocab' => [
            'exp'       => 10,
            'daily_cap' => null,
            'one_time'  => true,
        ],
        'course_dialogue' => [
            'exp'       => 15,
            'daily_cap' => null,
            'one_time'  => true,
        ],
        'course_grammar' => [
            'exp'       => 15,
            'daily_cap' => null,
            'one_time'  => true,
        ],
        'course_practice' => [
            'exp'       => 20,
            'daily_cap' => null,
            'one_time'  => true,
        ],

        // --- 3. THẺ GHI NHỚ TỰ DO (FLASHCARD) ---
        'flashcard_remember' => [
            'exp'       => 1,
            'daily_cap' => 20, // Tối đa 20 EXP/ngày tránh spam click
            'one_time'  => false,
        ],

        // --- 4. LUYỆN TẬP PHẢN XẠ PINYIN ---
        'pinyin_practice' => [
            'exp'       => 10,
            'daily_cap' => 30, // Tối đa 30 EXP (3 ván quiz) mỗi ngày
            'one_time'  => false,
        ],
    ],
];
