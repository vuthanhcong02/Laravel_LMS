<?php

return [
    /*
    |--------------------------------------------------------------------------
    | HSK 1 Structure
    |--------------------------------------------------------------------------
    */
    'hsk1' => [
        'listening' => [
            'name' => 'Nghe hiểu',
            'parts' => [
                ['group_type' => 'listening_true_false', 'questions' => 5],
                ['group_type' => 'listening_image_choice', 'questions' => 5],
                ['group_type' => 'listening_dialogue_choice', 'questions' => 5],
                ['group_type' => 'listening_dialogue_choice', 'questions' => 5],
            ]
        ],
        'reading' => [
            'name' => 'Đọc hiểu',
            'parts' => [
                ['group_type' => 'reading_true_false', 'questions' => 5],
                ['group_type' => 'reading_matching_sentences', 'questions' => 5],
                ['group_type' => 'reading_matching_sentences', 'questions' => 5],
                ['group_type' => 'reading_fill_in_blank', 'questions' => 5],
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | HSK 2 Structure
    |--------------------------------------------------------------------------
    */
    'hsk2' => [
        'listening' => [
            'name' => 'Nghe hiểu',
            'parts' => [
                ['group_type' => 'listening_true_false', 'questions' => 10],
                ['group_type' => 'listening_image_choice', 'questions' => 10],
                ['group_type' => 'listening_dialogue_choice', 'questions' => 10],
                ['group_type' => 'listening_dialogue_choice', 'questions' => 5],
            ]
        ],
        'reading' => [
            'name' => 'Đọc hiểu',
            'parts' => [
                ['group_type' => 'reading_true_false', 'questions' => 5],
                ['group_type' => 'reading_matching_sentences', 'questions' => 5],
                ['group_type' => 'reading_matching_sentences', 'questions' => 5],
                ['group_type' => 'reading_fill_in_blank', 'questions' => 10],
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | HSK 3 Structure
    |--------------------------------------------------------------------------
    */
    'hsk3' => [
        'listening' => [
            'name' => 'Nghe hiểu',
            'parts' => [
                ['group_type' => 'listening_image_choice', 'questions' => 10],
                ['group_type' => 'listening_true_false', 'questions' => 10],
                ['group_type' => 'listening_dialogue_choice', 'questions' => 10],
                ['group_type' => 'listening_dialogue_choice', 'questions' => 10],
            ]
        ],
        'reading' => [
            'name' => 'Đọc hiểu',
            'parts' => [
                ['group_type' => 'reading_matching_sentences', 'questions' => 10],
                ['group_type' => 'reading_fill_in_blank', 'questions' => 10],
                ['group_type' => 'reading_passage_choice', 'questions' => 10],
            ]
        ],
        'writing' => [
            'name' => 'Viết',
            'parts' => [
                ['group_type' => 'writing_sentence_building', 'questions' => 5],
                ['group_type' => 'writing_character_writing', 'questions' => 5],
            ]
        ]
    ],

    // Cấu trúc mặc định nếu chưa khai báo chi tiết cho HSK 4, 5, 6
    'default' => [
        'listening' => [
            'name' => 'Nghe hiểu',
            'parts' => [
                ['group_type' => 'listening_dialogue_choice', 'questions' => 5],
            ]
        ],
        'reading' => [
            'name' => 'Đọc hiểu',
            'parts' => [
                ['group_type' => 'reading_passage_choice', 'questions' => 5],
            ]
        ]
    ]
];
