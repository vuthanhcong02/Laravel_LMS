<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Daily EXP Goal
    |--------------------------------------------------------------------------
    */
    'daily_goal_exp' => 50,

    /*
    |--------------------------------------------------------------------------
    | Level Progression Configuration
    |--------------------------------------------------------------------------
    |
    | 30 Levels on an Arithmetic Scale (+25 EXP per level increment).
    | Lv 1 -> Lv 2: 50 EXP (Cumulative: 50 EXP)
    | Lv 2 -> Lv 3: 75 EXP (Cumulative: 125 EXP)
    | ...
    | Max level is 30 at 11,600 cumulative EXP.
    |
    */
    'levels' => [
        'max_level' => 30,
        'thresholds' => [
            1  => 0,
            2  => 50,
            3  => 125,
            4  => 225,
            5  => 350,
            6  => 500,
            7  => 675,
            8  => 875,
            9  => 1100,
            10 => 1350,
            11 => 1625,
            12 => 1925,
            13 => 2250,
            14 => 2600,
            15 => 2975,
            16 => 3375,
            17 => 3800,
            18 => 4250,
            19 => 4725,
            20 => 5225,
            21 => 5750,
            22 => 6300,
            23 => 6875,
            24 => 7475,
            25 => 8100,
            26 => 8750,
            27 => 9425,
            28 => 10125,
            29 => 10850,
            30 => 11600,
        ],
    ],

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
            'exp'                 => 50,
            'min_score_percent'   => 30, // Điểm tối thiểu 30% mới được cộng EXP
            'daily_one_time_exam' => true, // Mỗi đề thi cụ thể chỉ nhận EXP 1 lần trong 1 ngày
            'daily_cap'           => null,
            'one_time'            => false,
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
            'exp'                 => 10,
            'exp_rates'           => [
                10 => 10,
                20 => 20,
                50 => 50,
            ],
            'min_correct_percent' => 40, // Làm đúng tối thiểu 40% số câu
            'diminishing_returns' => [
                ['max_sessions' => 2, 'rate' => 1.0],   // Ván 1 & 2 trong ngày: 100% EXP
                ['max_sessions' => 4, 'rate' => 0.5],   // Ván 3 & 4 trong ngày: 50% EXP
                ['max_sessions' => null, 'rate' => 0.25], // Ván 5 trở đi trong ngày: 25% EXP (tối thiểu 1 EXP)
            ],
            'daily_cap'           => null,
            'one_time'            => false,
        ],

        'sentence_practice' => [
            'exp'                 => 15,
            'exp_by_level'        => [
                'HSK1'   => 15,
                'HSK2'   => 15,
                'HSK3'   => 20,
                'HSK4'   => 20,
                'HSK5'   => 25,
                'HSK6'   => 25,
                'HSK7-9' => 30,
            ],
            'no_hint_bonus'       => 5,
            'min_correct_percent' => 50, // Minimum 50% correct to earn EXP
            'daily_cap'           => 150,
            'diminishing_returns' => null,
            'one_time'            => false,
        ],
    ],
];
