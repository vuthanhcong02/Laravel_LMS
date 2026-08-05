<?php

namespace Database\Seeders;

use App\Models\HskLevel;
use Illuminate\Database\Seeder;

class HskLevelStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hsk1 = HskLevel::where('level_code', 'hsk1')->first();
        if ($hsk1) {
            $hsk1->update([
                'exam_structure' => [
                    'note' => 'Đề thi HSK 1 sẽ có pinyin và mỗi câu hỏi được nghe 2 lần.',
                    'sections' => [
                        [
                            'title' => 'Nghe hiểu',
                            'total_questions' => 20,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi có một bức tranh và một câu miêu tả ngắn. Thí sinh nghe câu miêu tả và xác định xem nó có đúng với bức tranh không, chọn "đúng" hoặc "sai".'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi có một bức tranh và một đoạn hội thoại ngắn. Thí sinh nghe đoạn hội thoại và chọn bức tranh phù hợp với nội dung hội thoại.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn, trong đó có một câu hỏi. Thí sinh nghe đoạn hội thoại và chọn câu trả lời đúng từ ba lựa chọn.'
                                ],
                                [
                                    'name' => 'Phần 4',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn, trong đó có một câu hỏi. Thí sinh nghe đoạn hội thoại và chọn câu trả lời đúng từ ba lựa chọn.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Đọc hiểu',
                            'total_questions' => 20,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một câu miêu tả ngắn và một bức tranh. Thí sinh chọn bức tranh phù hợp với câu miêu tả.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một câu miêu tả ngắn và một hình ảnh. Thí sinh xác định xem câu miêu tả có đúng với hình ảnh không, chọn "đúng" hoặc "sai".'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một câu miêu tả ngắn và ba hình ảnh. Thí sinh chọn hình ảnh phù hợp với câu miêu tả.'
                                ],
                                [
                                    'name' => 'Phần 4',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một đoạn văn ngắn với một từ bị thiếu. Thí sinh chọn từ đúng để điền vào chỗ trống.'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $hsk2 = HskLevel::where('level_code', 'hsk2')->first();
        if ($hsk2) {
            $hsk2->update([
                'exam_structure' => [
                    'note' => 'Nắm rõ cấu trúc đề thi HSK 2 giúp bạn nắm được thể chủ động tự tin trong quá trình làm bài thi, tránh hiện tượng tâm lý dẫn đến kết quả bài thi không tốt. Bài thi HSK 2 gồm 3 phần thi cụ thể như sau:',
                    'sections' => [
                        [
                            'title' => 'Nghe hiểu',
                            'total_questions' => 35,
                            'total_score' => 100,
                            'duration' => 25,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi có một bức tranh và một đoạn hội thoại ngắn. Thí sinh nghe đoạn hội thoại và chọn phương án "đúng" hoặc "sai".'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi có các hình ảnh. Thí sinh nghe câu miêu tả và lựa chọn hình ảnh phù hợp nhất. Mỗi câu nghe 2 lần tương ứng một đoạn hội thoại.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn giữa 2 người với một câu hỏi từ người thứ ba. Thí sinh nghe đoạn hội thoại và chọn câu trả lời đúng từ ba lựa chọn. Mỗi câu nghe 2 lần.'
                                ],
                                [
                                    'name' => 'Phần 4',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn hơn (4-5 câu) giữa 2 người và câu hỏi kèm theo từ người thứ ba. Thí sinh nghe đoạn hội thoại và chọn câu trả lời đúng từ ba lựa chọn.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Đọc hiểu',
                            'total_questions' => 25,
                            'total_score' => 100,
                            'duration' => 20,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một câu miêu tả ngắn với ba bức tranh. Thí sinh chọn bức tranh phù hợp với câu miêu tả.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi là một câu đơn giản và trống các cụm từ. Thí sinh chọn từ hoặc cụm từ phù hợp để hoàn thành câu.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 5,
                                    'description' => 'Mỗi câu hỏi gồm 2 câu nói, thí sinh cần lựa chọn xem nội dung 2 câu đó có sự đồng nhất với nhau hay không.'
                                ],
                                [
                                    'name' => 'Phần 4',
                                    'questions' => 10,
                                    'description' => 'Phần 4 có 20 câu, thí sinh cần ghép 2 vế câu để tạo thành một câu hoàn chỉnh có nghĩa.'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $hsk3 = HskLevel::where('level_code', 'hsk3')->first();
        if ($hsk3) {
            $hsk3->update([
                'exam_structure' => [
                    'note' => 'Cấu trúc đề thi HSK 3 bao gồm 3 phần thi chính: Nghe hiểu, Đọc hiểu và Viết.',
                    'sections' => [
                        [
                            'title' => 'Nghe hiểu',
                            'total_questions' => 40,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi có một bức tranh và một đoạn hội thoại ngắn. Thí sinh nghe đoạn hội thoại và chọn bức tranh phù hợp với nội dung hội thoại.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn. Thí sinh nghe đoạn hội thoại và xác định xem nội dung đoạn hội thoại có đúng hay sai.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại giữa hai người. Thí sinh nghe đoạn hội thoại và chọn câu trả lời đúng từ ba lựa chọn.'
                                ],
                                [
                                    'name' => 'Phần 4',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn hơn giữa hai người. Thí sinh nghe đoạn hội thoại và chọn câu trả lời đúng từ ba lựa chọn.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Đọc hiểu',
                            'total_questions' => 30,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Thí sinh sẽ phải ghép các cặp câu với nhau thành các câu hoàn chỉnh và có nghĩa.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi là một đoạn văn ngắn/một câu với một chỗ trống. Thí sinh chọn từ đúng để điền vào chỗ trống.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 10,
                                    'description' => 'Mỗi câu hỏi là một đoạn văn ngắn hơn và câu hỏi kèm theo. Thí sinh đọc đoạn văn và chọn câu trả lời đúng từ ba lựa chọn.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Viết',
                            'total_questions' => 10,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 5,
                                    'description' => 'Đề bài sẽ cho các từ theo thứ tự ngẫu nhiên. Thí sinh cần sắp xếp lại các từ để tạo thành câu đúng.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 5,
                                    'description' => 'Thí sinh sẽ được cung cấp phiên âm pinyin, cần viết được chữ Hán dựa trên phiên âm pinyin.'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $hsk4 = HskLevel::where('level_code', 'hsk4')->first();
        if ($hsk4) {
            $hsk4->update([
                'exam_structure' => [
                    'note' => 'Cấu trúc đề thi HSK 4 bao gồm 3 phần thi chính: Nghe hiểu, Đọc hiểu và Viết.',
                    'sections' => [
                        [
                            'title' => 'Nghe hiểu',
                            'total_questions' => 45,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Đề bài bao gồm 1 cuộc hội thoại ngắn và các câu. Thí sinh nghe đoạn hội thoại và xét đúng/sai của các câu. Đúng thì tích sang bên cạnh câu, sai thì ghi X.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 15,
                                    'description' => 'Mỗi câu hỏi là một đoạn hội thoại ngắn giữa 2 người và câu hỏi từ người thứ ba. Thí sinh nghe đoạn hội thoại và lựa chọn đáp án đúng từ các đáp án được cung cấp.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 20,
                                    'description' => 'Thí sinh nghe một hội thoại dài và trả lời các câu hỏi liên quan bằng cách chọn đáp án đúng từ 4 đáp án được cung cấp.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Đọc hiểu',
                            'total_questions' => 40,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Thí sinh cần hiểu nghĩa và cấu trúc ngữ pháp của từ để điền từ thích hợp vào chỗ trống.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 10,
                                    'description' => 'Thí sinh cần sắp xếp các từ được cho trong đề để tạo thành một câu văn hoàn chỉnh có nghĩa.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 20,
                                    'description' => 'Mỗi câu hỏi là một đoạn văn ngắn hơn và câu hỏi kèm theo. Thí sinh cần đọc đoạn văn và chọn câu trả lời đúng nhất từ các lựa chọn.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Viết',
                            'total_questions' => 15,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Đề bài sẽ cho các từ theo thứ tự ngẫu nhiên. Thí sinh cần sắp xếp lại các từ để tạo thành câu đúng.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 5,
                                    'description' => 'Đề bài cung cấp một bức tranh/ hình ảnh. Thí sinh cần đặt câu dựa trên bức tranh đó.'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $hsk5 = HskLevel::where('level_code', 'hsk5')->first();
        if ($hsk5) {
            $hsk5->update([
                'exam_structure' => [
                    'note' => 'Cấu trúc đề thi HSK 5 bao gồm 3 phần thi chính: Nghe hiểu, Đọc hiểu và Viết.',
                    'sections' => [
                        [
                            'title' => 'Nghe hiểu',
                            'total_questions' => 45,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 20,
                                    'description' => 'Đề bài bao gồm 1 cuộc hội thoại ngắn và thí sinh cần dựa vào đó để đặt câu hỏi. Sau đó, dựa vào nội dung đoạn hội thoại để chọn đáp án đúng trong số bốn lựa chọn.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 25,
                                    'description' => 'Thí sinh được nghe các đoạn hội thoại hoặc đoạn văn ngắn. Sau đó, thí sinh cần lựa chọn đáp án đúng từ 4 đáp án (A,B,C,D) được cung cấp.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Đọc hiểu',
                            'total_questions' => 45,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 15,
                                    'description' => 'Thí sinh cần chọn đúng đáp án để điền từ thích hợp vào chỗ trống trong đoạn văn.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 10,
                                    'description' => 'Thí sinh cần chọn đáp án đúng/ phù hợp nhất với đoạn văn được cung cấp.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 20,
                                    'description' => 'Thí sinh được cung cấp 5 đoạn văn (mỗi đoạn 4 câu hỏi). Thí sinh cần chọn đáp án đúng nhất trong số 4 đáp án được cung cấp.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Viết',
                            'total_questions' => 10,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 8,
                                    'description' => 'Đề bài sẽ cho các từ theo thứ tự ngẫu nhiên. Thí sinh cần sắp xếp lại các từ để tạo thành câu đúng.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 2,
                                    'description' => 'Gồm 2 bài viết ngắn. Thí sinh viết các đoạn văn ngắn dựa trên các bức tranh hoặc từ cho trước (khoảng 80 chữ mỗi đoạn).'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $hsk6 = HskLevel::where('level_code', 'hsk6')->first();
        if ($hsk6) {
            $hsk6->update([
                'exam_structure' => [
                    'note' => 'Cấu trúc đề thi HSK 6 bao gồm 3 phần thi chính: Nghe hiểu, Đọc hiểu và Viết.',
                    'sections' => [
                        [
                            'title' => 'Nghe hiểu',
                            'total_questions' => 50,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 15,
                                    'description' => 'Đề bài bao gồm nhiều cuộc hội thoại ngắn và thí sinh cần dựa vào đó để chọn đáp án đúng trong số bốn lựa chọn. Lưu ý rằng audio sẽ được phát rất nhanh nên thí sinh cần tập trung chú ý để không bị miss thông tin.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 15,
                                    'description' => 'Thí sinh được nghe các đoạn phỏng vấn (5 câu hỏi mỗi đoạn). Sau đó, thí sinh cần lựa chọn đáp án đúng từ các đáp án được cung cấp.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 20,
                                    'description' => 'Thí sinh cần lựa chọn đáp án đúng dựa trên các đáp án được cung cấp. Mỗi đoạn audio đều là những đoạn văn ngắn khoảng 5-7 câu.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Đọc hiểu',
                            'total_questions' => 50,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 10,
                                    'description' => 'Thí sinh cần chọn đáp án có chứa lỗi sai trong số các đáp án được cung cấp.'
                                ],
                                [
                                    'name' => 'Phần 2',
                                    'questions' => 10,
                                    'description' => 'Thí sinh cần chọn đáp án đúng/ phù hợp nhất từ số các từ/ cụm từ cho trước vào đoạn văn được cung cấp.'
                                ],
                                [
                                    'name' => 'Phần 3',
                                    'questions' => 10,
                                    'description' => 'Thí sinh cần chọn đáp án đúng/ phù hợp nhất từ số các từ/ cụm từ cho trước vào đoạn văn được cung cấp.'
                                ],
                                [
                                    'name' => 'Phần 4',
                                    'questions' => 20,
                                    'description' => 'Thí sinh sẽ thấy các đoạn văn dài 500-700 từ và các câu hỏi liên quan. Cần chọn đáp án đúng phù hợp với nội dung câu hỏi.'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Viết',
                            'total_questions' => 1,
                            'total_score' => 100,
                            'parts' => [
                                [
                                    'name' => 'Phần 1',
                                    'questions' => 1,
                                    'description' => 'Thí sinh sẽ được cung cấp 1 đoạn văn khoảng 1000 từ để đọc trong 10 phút. Sau 10 phút, đoạn văn đó sẽ được thu lại và thí sinh cần viết một đoạn văn tóm tắt khoảng 400 từ về nội dung vừa đọc.'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }
}
