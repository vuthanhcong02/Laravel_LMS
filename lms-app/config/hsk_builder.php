<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sections
    |--------------------------------------------------------------------------
    | List of main sections in an HSK mock exam.
    */
    'sections' => [
        'listening' => [
            'id' => 'listening',
            'name' => 'Nghe hiểu (Listening)',
            'icon' => 'headphones',
        ],
        'reading' => [
            'id' => 'reading',
            'name' => 'Đọc hiểu (Reading)',
            'icon' => 'menu_book',
        ],
        'writing' => [
            'id' => 'writing',
            'name' => 'Viết (Writing)',
            'icon' => 'edit',
        ],
        'speaking' => [
            'id' => 'speaking',
            'name' => 'Nói (Speaking - HSKK/HSK 3.0)',
            'icon' => 'mic',
        ],
        'translation' => [
            'id' => 'translation',
            'name' => 'Dịch (Translation - HSK 7-9)',
            'icon' => 'translate',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Question Types
    |--------------------------------------------------------------------------
    | Each type will have a corresponding Livewire component to handle 
    | the builder interface and store data properly.
    */
    'question_types' => [
        // ---------- LISTENING ----------
        'listening_true_false' => [
            'id' => 'listening_true_false',
            'section' => 'listening',
            'name' => 'Nghe - Phán đoán Đúng/Sai',
            'description' => 'Mỗi câu có 1 hình ảnh. Chọn đúng nếu âm thanh khớp với hình, sai nếu không khớp.',
            'has_audio' => true,
            'has_group_image' => false,
            'has_options' => false, // Hardcoded options (True/False) in code
            'component' => 'exam-builder.types.listening-true-false', // Livewire component name
            'is_implemented' => true
        ],
        'listening_image_choice' => [
            'id' => 'listening_image_choice',
            'section' => 'listening',
            'name' => 'Nghe - Trắc nghiệm chọn Ảnh (A, B, C)',
            'description' => 'Mỗi câu có 3 hoặc nhiều lựa chọn là hình ảnh.',
            'has_audio' => true,
            'has_group_image' => false,
            'has_options' => true,
            'component' => 'exam-builder.types.listening-image-choice',
            'is_implemented' => true
        ],
        'listening_matching_images' => [
            'id' => 'listening_matching_images',
            'section' => 'listening',
            'name' => 'Nghe - Nối âm thanh với Ảnh (A-E/F)',
            'description' => 'Nghe âm thanh và chọn 1 bức ảnh tương ứng (từ danh sách chung).',
            'has_audio' => true,
            'has_group_image' => true, // Upload common images (A-F) for the whole Part
            'has_options' => false,
            'component' => 'exam-builder.types.listening-matching-images',
            'is_implemented' => true
        ],
        'listening_dialogue_choice' => [
            'id' => 'listening_dialogue_choice',
            'section' => 'listening',
            'name' => 'Nghe - Hội thoại ngắn & Trắc nghiệm chữ',
            'description' => 'Nghe đoạn hội thoại (nam/nữ) và chọn đáp án đúng A, B, C, D.',
            'has_audio' => true,
            'has_group_image' => false,
            'has_options' => true,
            'component' => 'exam-builder.types.listening-dialogue-choice',
            'is_implemented' => true
        ],
        'listening_passage_choice' => [
            'id' => 'listening_passage_choice',
            'section' => 'listening',
            'name' => 'Nghe - Đoạn văn dài & Nhiều câu hỏi',
            'description' => 'Nghe một đoạn văn dài, sau đó trả lời 2-3 câu hỏi trắc nghiệm.',
            'has_audio' => true,
            'has_group_image' => false,
            'has_options' => true,
            'component' => 'exam-builder.types.listening-passage-choice',
            'is_implemented' => false
        ],

        // ---------- READING ----------
        'reading_true_false' => [
            'id' => 'reading_true_false',
            'section' => 'reading',
            'name' => 'Đọc - Phán đoán Đúng/Sai (Text vs Hình)',
            'description' => 'Đọc từ/cụm từ và xem có khớp với hình ảnh cho sẵn không.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false,
            'component' => 'exam-builder.types.reading-true-false',
            'is_implemented' => true
        ],
        'reading_matching_sentences' => [
            'id' => 'reading_matching_sentences',
            'section' => 'reading',
            'name' => 'Đọc - Nối câu (A-F)',
            'description' => 'Nối một câu/đoạn văn ngắn với câu tương ứng phù hợp.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false, // Tuỳ chọn A-F được lấy từ danh sách câu hỏi khác trong group
            'component' => 'exam-builder.types.reading-matching-sentences',
            'is_implemented' => true
        ],
        'reading_fill_in_blank' => [
            'id' => 'reading_fill_in_blank',
            'section' => 'reading',
            'name' => 'Đọc - Điền từ vào chỗ trống',
            'description' => 'Chọn 1 từ/cụm từ thích hợp điền vào đoạn hội thoại/đoạn văn.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => true, // Danh sách các lựa chọn text A-F dùng chung cho group
            'component' => 'exam-builder.types.reading-fill-in-blank',
            'is_implemented' => true
        ],
        'reading_passage_choice' => [
            'id' => 'reading_passage_choice',
            'section' => 'reading',
            'name' => 'Đọc - Đoạn văn & Câu hỏi trắc nghiệm',
            'description' => 'Đọc một đoạn văn và trả lời 1-4 câu hỏi (A, B, C, D).',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => true,
            'component' => 'exam-builder.types.reading-passage-choice',
            'is_implemented' => true
        ],
        'reading_sentence_ordering' => [
            'id' => 'reading_sentence_ordering',
            'section' => 'reading',
            'name' => 'Đọc - Sắp xếp câu (A, B, C)',
            'description' => 'Sắp xếp 3 câu cho sẵn thành một đoạn văn hoàn chỉnh.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false,
            'component' => 'exam-builder.types.reading-sentence-ordering',
            'is_implemented' => false
        ],

        // ---------- WRITING ----------
        'writing_sentence_building' => [
            'id' => 'writing_sentence_building',
            'section' => 'writing',
            'name' => 'Viết - Sắp xếp từ thành câu',
            'description' => 'Cho các từ xáo trộn, học viên cần sắp xếp lại thành câu đúng ngữ pháp.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false,
            'component' => 'exam-builder.types.writing-sentence-building',
            'is_implemented' => false
        ],
        'writing_character_writing' => [
            'id' => 'writing_character_writing',
            'section' => 'writing',
            'name' => 'Viết - Viết chữ Hán theo Pinyin',
            'description' => 'Cho câu có chứa pinyin, học viên viết chữ Hán tương ứng.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false,
            'component' => 'exam-builder.types.writing-character-writing',
            'is_implemented' => false
        ],
        'writing_picture_essay' => [
            'id' => 'writing_picture_essay',
            'section' => 'writing',
            'name' => 'Viết - Viết câu/đoạn văn theo Tranh',
            'description' => 'Viết dựa trên một bức tranh và từ gợi ý (HSK 4-5).',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false,
            'component' => 'exam-builder.types.writing-picture-essay',
            'is_implemented' => false
        ],
        'writing_passage_summary' => [
            'id' => 'writing_passage_summary',
            'section' => 'writing',
            'name' => 'Viết - Tóm tắt đoạn văn (HSK 6)',
            'description' => 'Đọc đoạn văn 1000 chữ trong 10 phút và viết lại tóm tắt 400 chữ.',
            'has_audio' => false,
            'has_group_image' => false,
            'has_options' => false,
            'component' => 'exam-builder.types.writing-passage-summary',
            'is_implemented' => false
        ],
    ],
];
