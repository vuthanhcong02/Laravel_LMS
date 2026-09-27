<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HanziDecompositionController extends Controller
{
    /**
     * Hiển thị trang demo Chiết tự Chữ Hán & Bảng Bộ thủ
     */
    public function index(Request $request): View
    {
        $demoCharacters = $this->getDemoCharacters();
        $demoRadicals = $this->getDemoRadicals();

        return view('hanzi.index', compact('demoCharacters', 'demoRadicals'));
    }

    /**
     * API tra cứu chiết tự nhanh
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = trim($request->input('q', ''));
        $all = $this->getDemoCharacters();

        if (empty($keyword)) {
            return response()->json(array_values($all));
        }

        $filtered = array_filter($all, function ($item) use ($keyword) {
            return mb_stripos($item['character'], $keyword) !== false
                || mb_stripos($item['pinyin'], $keyword) !== false
                || mb_stripos($item['sino_vietnamese'], $keyword) !== false
                || mb_stripos($item['meaning'], $keyword) !== false;
        });

        return response()->json(array_values($filtered));
    }

    /**
     * Danh sách dữ liệu mẫu các chữ Hán chiết tự đặc sắc
     */
    private function getDemoCharacters(): array
    {
        return [
            '休' => [
                'character' => '休',
                'pinyin' => 'xiū',
                'sino_vietnamese' => 'Hưu',
                'meaning' => 'Nghỉ ngơi, ngừng lại',
                'hsk_level' => 1,
                'stroke_count' => 6,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Ghép từ bộ Nhân (Người) và bộ Mộc (Cây cối) để biểu thị ý nghĩa người đứng tựa gốc cây nghỉ mát.',
                'mnemonic_poem' => "Người đứng tựa bóng râm cây\nMỏi gối dừng chân nghỉ ngơi ngày dài.",
                'traditional_quote' => 'Nhân tựa gốc Mộc thành chữ Hưu.',
                'components' => [
                    [
                        'character' => '亻',
                        'name' => 'Bộ Nhân đứng (人)',
                        'sino_vietnamese' => 'Nhân',
                        'meaning' => 'Con người',
                        'position' => 'Trái',
                        'role' => 'Biểu ý (Semantic)',
                        'explanation' => 'Tượng trưng cho người lao động mệt mỏi cần nghỉ.',
                    ],
                    [
                        'character' => '木',
                        'name' => 'Bộ Mộc',
                        'sino_vietnamese' => 'Mộc',
                        'meaning' => 'Cây cối, gỗ',
                        'position' => 'Phải',
                        'role' => 'Biểu ý (Semantic)',
                        'explanation' => 'Tượng trưng cho bóng râm cây mát che chở.',
                    ]
                ],
                'stroke_rules' => 'Trái trước phải sau (亻 trước, 木 sau) • Trên trước dưới sau',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Nét phẩy bộ Nhân', 'pinyin' => 'piě'],
                    ['step' => 2, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Nét sổ thẳng bộ Nhân', 'pinyin' => 'shù'],
                    ['step' => 3, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Nét ngang bộ Mộc', 'pinyin' => 'héng'],
                    ['step' => 4, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Nét sổ giữa bộ Mộc', 'pinyin' => 'shù'],
                    ['step' => 5, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Nét phẩy trái bộ Mộc', 'pinyin' => 'piě'],
                    ['step' => 6, 'name' => 'Mác (捺 - nà)', 'highlight' => 'Nét mác phải bộ Mộc', 'pinyin' => 'nà'],
                ],
                'compounds' => [
                    ['word' => '休息', 'pinyin' => 'xiūxi', 'meaning' => 'Nghỉ ngơi'],
                    ['word' => '退休', 'pinyin' => 'tuìxiū', 'meaning' => 'Nghỉ hưu'],
                    ['word' => '休假', 'pinyin' => 'xiūjià', 'meaning' => 'Nghỉ phép'],
                ]
            ],
            '明' => [
                'character' => '明',
                'pinyin' => 'míng',
                'sino_vietnamese' => 'Minh',
                'meaning' => 'Sáng sủa, thấu hiểu, ngày mai',
                'hsk_level' => 1,
                'stroke_count' => 8,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Mặt trời (Nhật 日) và Mặt trăng (Nguyệt 月) cùng tỏa sáng rực rỡ.',
                'mnemonic_poem' => "Nhật là mặt trời rạng soi\nNguyệt vầng trăng sáng sáng soi đêm trường\nGhép đôi thành chữ Minh tường.",
                'traditional_quote' => 'Nhật Nguyệt song huy quang thiên hạ.',
                'components' => [
                    [
                        'character' => '日',
                        'name' => 'Bộ Nhật',
                        'sino_vietnamese' => 'Nhật',
                        'meaning' => 'Mặt trời, ban ngày',
                        'position' => 'Trái',
                        'role' => 'Biểu ý',
                        'explanation' => 'Nguồn sáng dương rạng rỡ ban ngày.',
                    ],
                    [
                        'character' => '月',
                        'name' => 'Bộ Nguyệt',
                        'sino_vietnamese' => 'Nguyệt',
                        'meaning' => 'Mặt trăng, ban đêm',
                        'position' => 'Phải',
                        'role' => 'Biểu ý',
                        'explanation' => 'Nguồn sáng âm dịu mát ban đêm.',
                    ]
                ],
                'stroke_rules' => 'Trái trước phải sau (日 trước, 月 sau) • Ngoài trước trong sau',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ trái chữ Nhật', 'pinyin' => 'shù'],
                    ['step' => 2, 'name' => 'Ngang gập (横折 - héngzhé)', 'highlight' => 'Ngang gập chữ Nhật', 'pinyin' => 'héngzhé'],
                    ['step' => 3, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang giữa chữ Nhật', 'pinyin' => 'héng'],
                    ['step' => 4, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang đóng đáy chữ Nhật', 'pinyin' => 'héng'],
                    ['step' => 5, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Phẩy sổ trái chữ Nguyệt', 'pinyin' => 'piě'],
                    ['step' => 6, 'name' => 'Ngang gập móc (横折钩 - héngzhégōu)', 'highlight' => 'Ngang gập móc chữ Nguyệt', 'pinyin' => 'héngzhégōu'],
                    ['step' => 7, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang trên chữ Nguyệt', 'pinyin' => 'héng'],
                    ['step' => 8, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang dưới chữ Nguyệt', 'pinyin' => 'héng'],
                ],
                'compounds' => [
                    ['word' => '明天', 'pinyin' => 'míngtiān', 'meaning' => 'Ngày mai'],
                    ['word' => '明白', 'pinyin' => 'míngbai', 'meaning' => 'Hiểu rõ'],
                    ['word' => '光明', 'pinyin' => 'guāngmíng', 'meaning' => 'Quang minh, tươi sáng'],
                ]
            ],
            '安' => [
                'character' => '安',
                'pinyin' => 'ān',
                'sino_vietnamese' => 'An',
                'meaning' => 'Bình an, yên ổn, an toàn',
                'hsk_level' => 1,
                'stroke_count' => 6,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Dưới mái nhà (Bộ Miên 宀) có người phụ nữ (Bộ Nữ 女) thì gia đình êm ấm, bình an vô sự.',
                'mnemonic_poem' => "Mái nhà che chở gió mưa\nCó nàng giữ bếp sớm trưa bình an.",
                'traditional_quote' => 'Mái gia trang có phụ nữ ắt an khang.',
                'components' => [
                    [
                        'character' => '宀',
                        'name' => 'Bộ Miên (Mái nhà)',
                        'sino_vietnamese' => 'Miên',
                        'meaning' => 'Mái che, ngôi nhà',
                        'position' => 'Trên',
                        'role' => 'Biểu ý',
                        'explanation' => 'Nơi che chở mưa nắng, chốn bình yên.',
                    ],
                    [
                        'character' => '女',
                        'name' => 'Bộ Nữ',
                        'sino_vietnamese' => 'Nữ',
                        'meaning' => 'Người phụ nữ, con gái',
                        'position' => 'Dưới',
                        'role' => 'Biểu ý',
                        'explanation' => 'Sự dịu dàng, gìn giữ tổ ấm.',
                    ]
                ],
                'stroke_rules' => 'Trên trước dưới sau (宀 trước, 女 sau)',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Chấm (点 - diǎn)', 'highlight' => 'Chấm đỉnh mái nhà', 'pinyin' => 'diǎn'],
                    ['step' => 2, 'name' => 'Chấm trái (点/撇点)', 'highlight' => 'Chấm trái mái nhà', 'pinyin' => 'diǎn'],
                    ['step' => 3, 'name' => 'Ngang móc (横钩 - hénggōu)', 'highlight' => 'Ngang móc mái nhà', 'pinyin' => 'hénggōu'],
                    ['step' => 4, 'name' => 'Phẩy chấm (撇点 - piědiǎn)', 'highlight' => 'Phẩy gập chấm chữ Nữ', 'pinyin' => 'piědiǎn'],
                    ['step' => 5, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Phẩy dài chữ Nữ', 'pinyin' => 'piě'],
                    ['step' => 6, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang dài chữ Nữ', 'pinyin' => 'héng'],
                ],
                'compounds' => [
                    ['word' => '安全', 'pinyin' => 'ānquán', 'meaning' => 'An toàn'],
                    ['word' => '安心', 'pinyin' => 'ānxīn', 'meaning' => 'Yên tâm'],
                    ['word' => '安排', 'pinyin' => 'ānpái', 'meaning' => 'Sắp xếp'],
                ]
            ],
            '德' => [
                'character' => '德',
                'pinyin' => 'dé',
                'sino_vietnamese' => 'Đức',
                'meaning' => 'Đạo đức, phẩm hạnh',
                'hsk_level' => 3,
                'stroke_count' => 15,
                'formation_type' => 'Hình thanh - Hội ý',
                'formation_desc' => 'Kết hợp giữa bước chân hành động (彳), sự ngay thẳng (thập tứ nhất) và một tấm lòng chân chính (心).',
                'mnemonic_poem' => "Chim chích mà đậu cành tre (彳)\nThập trên (十) tứ dưới (四) nhất đè (一) chữ tâm (心).",
                'traditional_quote' => 'Thơ chiết tự lưu truyền ngàn năm của văn hoá Nho học.',
                'components' => [
                    [
                        'character' => '彳',
                        'name' => 'Bộ Xích (Chim chích cành tre)',
                        'sino_vietnamese' => 'Xích',
                        'meaning' => 'Bước chân, hành vi đi lại',
                        'position' => 'Trái',
                        'role' => 'Biểu ý',
                        'explanation' => 'Đạo đức phải được thể hiện qua hành vi thực tế.',
                    ],
                    [
                        'character' => '十',
                        'name' => 'Bộ Thập',
                        'sino_vietnamese' => 'Thập',
                        'meaning' => 'Số 10, vẹn toàn',
                        'position' => 'Phải - Trên',
                        'role' => 'Biểu ý',
                        'explanation' => 'Sự trọn vẹn mười phân vẹn mười.',
                    ],
                    [
                        'character' => '四',
                        'name' => 'Bộ Tứ',
                        'sino_vietnamese' => 'Tứ',
                        'meaning' => 'Bốn phương, ngay thẳng',
                        'position' => 'Phải - Giữa',
                        'role' => 'Biểu ý',
                        'explanation' => 'Ngay thẳng nhìn về bốn phương trời.',
                    ],
                    [
                        'character' => '一',
                        'name' => 'Bộ Nhất',
                        'sino_vietnamese' => 'Nhất',
                        'meaning' => 'Một, trước sau như một',
                        'position' => 'Phải - Dưới Tứ',
                        'role' => 'Biểu ý',
                        'explanation' => 'Nhất quán, son sắt một lòng.',
                    ],
                    [
                        'character' => '心',
                        'name' => 'Bộ Tâm',
                        'sino_vietnamese' => 'Tâm',
                        'meaning' => 'Trái tim, tấm lòng',
                        'position' => 'Phải - Đáy',
                        'role' => 'Biểu ý cốt lõi',
                        'explanation' => 'Cội nguồn đạo đức là từ cái tâm.',
                    ]
                ],
                'stroke_rules' => 'Trái trước phải sau (彳 trước, phần phải sau) • Trên trước dưới sau',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Nét phẩy trên bộ 彳', 'pinyin' => 'piě'],
                    ['step' => 2, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Nét phẩy dưới bộ 彳', 'pinyin' => 'piě'],
                    ['step' => 3, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Nét sổ bộ 彳', 'pinyin' => 'shù'],
                    ['step' => 4, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang chữ 十', 'pinyin' => 'héng'],
                    ['step' => 5, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ chữ 十', 'pinyin' => 'shù'],
                    ['step' => 6, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ trái chữ 四', 'pinyin' => 'shù'],
                    ['step' => 7, 'name' => 'Ngang gập (横折 - héngzhé)', 'highlight' => 'Ngang gập chữ 四', 'pinyin' => 'héngzhé'],
                    ['step' => 8, 'name' => 'Phẩy ngắn (撇/竖)', 'highlight' => 'Nét trong trái chữ 四', 'pinyin' => 'piě'],
                    ['step' => 9, 'name' => 'Sổ gập (竖弯/竖)', 'highlight' => 'Nét trong phải chữ 四', 'pinyin' => 'shùwān'],
                    ['step' => 10, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang đóng đáy chữ 四', 'pinyin' => 'héng'],
                    ['step' => 11, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang chữ 一', 'pinyin' => 'héng'],
                    ['step' => 12, 'name' => 'Chấm (点 - diǎn)', 'highlight' => 'Chấm trái chữ 心', 'pinyin' => 'diǎn'],
                    ['step' => 13, 'name' => 'Nằm móc (卧钩 - wògōu)', 'highlight' => 'Ngoạ câu chữ 心', 'pinyin' => 'wògōu'],
                    ['step' => 14, 'name' => 'Chấm (点 - diǎn)', 'highlight' => 'Chấm giữa chữ 心', 'pinyin' => 'diǎn'],
                    ['step' => 15, 'name' => 'Chấm (点 - diǎn)', 'highlight' => 'Chấm phải chữ 心', 'pinyin' => 'diǎn'],
                ],
                'compounds' => [
                    ['word' => '道德', 'pinyin' => 'dàodé', 'meaning' => 'Đạo đức'],
                    ['word' => '品德', 'pinyin' => 'pǐndé', 'meaning' => 'Phẩm hạnh'],
                    ['word' => '德国', 'pinyin' => 'déguó', 'meaning' => 'Nước Đức'],
                ]
            ],
            '好' => [
                'character' => '好',
                'pinyin' => 'hǎo',
                'sino_vietnamese' => 'Hảo',
                'meaning' => 'Tốt, đẹp, vừa lòng',
                'hsk_level' => 1,
                'stroke_count' => 6,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Người mẹ (Nữ 女) ôm đứa con (Tử 子) thể hiện sự viên mãn và tốt đẹp nhất.',
                'mnemonic_poem' => "Mẹ Nữ bên cạnh con Tử\nVẹn toàn hạnh phúc tốt tươi muôn phần.",
                'traditional_quote' => 'Nữ Tử tương tùng, vạn sự giai hảo.',
                'components' => [
                    [
                        'character' => '女',
                        'name' => 'Bộ Nữ',
                        'sino_vietnamese' => 'Nữ',
                        'meaning' => 'Người mẹ, con gái',
                        'position' => 'Trái',
                        'role' => 'Biểu ý',
                        'explanation' => 'Tình mẫu tử, sự dịu dàng.',
                    ],
                    [
                        'character' => '子',
                        'name' => 'Bộ Tử',
                        'sino_vietnamese' => 'Tử',
                        'meaning' => 'Đứa con, con trai',
                        'position' => 'Phải',
                        'role' => 'Biểu ý',
                        'explanation' => 'Thế hệ tương lai, niềm vui sum vầy.',
                    ]
                ],
                'stroke_rules' => 'Trái trước phải sau (女 trước, 子 sau)',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Phẩy chấm (撇点 - piědiǎn)', 'highlight' => 'Nét phẩy chấm chữ 女', 'pinyin' => 'piědiǎn'],
                    ['step' => 2, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Nét phẩy dài chữ 女', 'pinyin' => 'piě'],
                    ['step' => 3, 'name' => 'Hất (提 - tí)', 'highlight' => 'Nét hất xiên ngang chữ 女', 'pinyin' => 'tí'],
                    ['step' => 4, 'name' => 'Ngang phẩy (横撇 - héngpiě)', 'highlight' => 'Ngang phẩy đỉnh chữ 子', 'pinyin' => 'héngpiě'],
                    ['step' => 5, 'name' => 'Uốn móc (弯钩 - wāngōu)', 'highlight' => 'Uốn móc thân chữ 子', 'pinyin' => 'wāngōu'],
                    ['step' => 6, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang dài chữ 子', 'pinyin' => 'héng'],
                ],
                'compounds' => [
                    ['word' => '你好', 'pinyin' => 'nǐ hǎo', 'meaning' => 'Xin chào'],
                    ['word' => '好看', 'pinyin' => 'hǎokàn', 'meaning' => 'Đẹp mắt'],
                    ['word' => '好久', 'pinyin' => 'hǎojiǔ', 'meaning' => 'Đã lâu'],
                ]
            ],
            '看' => [
                'character' => '看',
                'pinyin' => 'kàn',
                'sino_vietnamese' => 'Khán',
                'meaning' => 'Xem, nhìn, trông coi',
                'hsk_level' => 1,
                'stroke_count' => 9,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Bàn tay (Thủ ⺁) che lên trên mắt (Mục 目) để che bớt ánh nắng khi nhìn ra xa.',
                'mnemonic_poem' => "Đưa tay (Thủ) che mát mắt (Mục) trông\nNhìn xa ngắm cảnh mênh mông đất trời.",
                'traditional_quote' => 'Thủ thượng Mục hạ vi Khán.',
                'components' => [
                    [
                        'character' => '⺁ (手)',
                        'name' => 'Bộ Thủ (Biến thể tay)',
                        'sino_vietnamese' => 'Thủ',
                        'meaning' => 'Bàn tay',
                        'position' => 'Trên',
                        'role' => 'Biểu ý',
                        'explanation' => 'Động tác đưa bàn tay lên trán che nắng.',
                    ],
                    [
                        'character' => '目',
                        'name' => 'Bộ Mục',
                        'sino_vietnamese' => 'Mục',
                        'meaning' => 'Mắt, thị lực',
                        'position' => 'Dưới',
                        'role' => 'Biểu ý',
                        'explanation' => 'Cơ quan thị giác nhìn ngắm.',
                    ]
                ],
                'stroke_rules' => 'Trên trước dưới sau (⺁ trước, 目 sau) • Ngoài trước trong sau',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Phẩy nghiêng đỉnh bộ Thủ', 'pinyin' => 'piě'],
                    ['step' => 2, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang trên bộ Thủ', 'pinyin' => 'héng'],
                    ['step' => 3, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang giữa bộ Thủ', 'pinyin' => 'héng'],
                    ['step' => 4, 'name' => 'Phẩy dài (撇 - piě)', 'highlight' => 'Phẩy dài quét qua 3 nét', 'pinyin' => 'piě'],
                    ['step' => 5, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ trái chữ Mục', 'pinyin' => 'shù'],
                    ['step' => 6, 'name' => 'Ngang gập (横折 - héngzhé)', 'highlight' => 'Ngang gập ngoài chữ Mục', 'pinyin' => 'héngzhé'],
                    ['step' => 7, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang trong thứ nhất chữ Mục', 'pinyin' => 'héng'],
                    ['step' => 8, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang trong thứ hai chữ Mục', 'pinyin' => 'héng'],
                    ['step' => 9, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang đóng đáy chữ Mục', 'pinyin' => 'héng'],
                ],
                'compounds' => [
                    ['word' => '看见', 'pinyin' => 'kànjiàn', 'meaning' => 'Nhìn thấy'],
                    ['word' => '看书', 'pinyin' => 'kànshū', 'meaning' => 'Đọc sách'],
                    ['word' => '看病', 'pinyin' => 'kànbìng', 'meaning' => 'Khám bệnh'],
                ]
            ],
            '男' => [
                'character' => '男',
                'pinyin' => 'nán',
                'sino_vietnamese' => 'Nam',
                'meaning' => 'Đàn ông, con trai, phái nam',
                'hsk_level' => 1,
                'stroke_count' => 7,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Ruộng đồng (Điền 田) ở trên, sức lực (Lực 力) ở dưới; người đàn ông dùng sức vóc cày cấy.',
                'mnemonic_poem' => "Ruộng (Điền) trên Sức (Lực) ở bên dưới\nTrai tráng cày bừa đẫm giọt mồ hôi.",
                'traditional_quote' => 'Điền thượng Lực hạ thị Nam nhi.',
                'components' => [
                    [
                        'character' => '田',
                        'name' => 'Bộ Điền',
                        'sino_vietnamese' => 'Điền',
                        'meaning' => 'Ruộng đất',
                        'position' => 'Trên',
                        'role' => 'Biểu ý',
                        'explanation' => 'Nơi canh tác nông nghiệp.',
                    ],
                    [
                        'character' => '力',
                        'name' => 'Bộ Lực',
                        'sino_vietnamese' => 'Lực',
                        'meaning' => 'Sức mạnh, cơ bắp',
                        'position' => 'Dưới',
                        'role' => 'Biểu ý',
                        'explanation' => 'Sức vóc lao động của nam giới.',
                    ]
                ],
                'stroke_rules' => 'Trên trước dưới sau (田 trước, 力 sau) • Trong trước đóng sau',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ trái chữ Điền', 'pinyin' => 'shù'],
                    ['step' => 2, 'name' => 'Ngang gập (横折 - héngzhé)', 'highlight' => 'Ngang gập khung ngoài chữ Điền', 'pinyin' => 'héngzhé'],
                    ['step' => 3, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang giữa chữ Điền', 'pinyin' => 'héng'],
                    ['step' => 4, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ giữa chữ Điền', 'pinyin' => 'shù'],
                    ['step' => 5, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang đóng đáy chữ Điền', 'pinyin' => 'héng'],
                    ['step' => 6, 'name' => 'Ngang gập móc (横折钩 - héngzhégōu)', 'highlight' => 'Nét chính chữ Lực', 'pinyin' => 'héngzhégōu'],
                    ['step' => 7, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Phẩy chữ Lực', 'pinyin' => 'piě'],
                ],
                'compounds' => [
                    ['word' => '男人', 'pinyin' => 'nánrén', 'meaning' => 'Đàn ông'],
                    ['word' => '男生', 'pinyin' => 'nánshēng', 'meaning' => 'Nam sinh'],
                    ['word' => '男友', 'pinyin' => 'nányǒu', 'meaning' => 'Bạn trai'],
                ]
            ],
            '信' => [
                'character' => '信',
                'pinyin' => 'xìn',
                'sino_vietnamese' => 'Tín',
                'meaning' => 'Tin tưởng, uy tín, bức thư',
                'hsk_level' => 2,
                'stroke_count' => 9,
                'formation_type' => 'Hội ý (会意)',
                'formation_desc' => 'Con người (Nhân đứng 亻) và lời nói (Ngôn 言) đi liền nhau; lời người nói ra phải giữ chữ tín.',
                'mnemonic_poem' => "Người (Nhân) cùng Lời nói (Ngôn) song hành\nNói lời giữ lấy chữ Tín ngàn thu.",
                'traditional_quote' => 'Nhân ngôn vi Tín, thành thực bất khi.',
                'components' => [
                    [
                        'character' => '亻',
                        'name' => 'Bộ Nhân đứng',
                        'sino_vietnamese' => 'Nhân',
                        'meaning' => 'Con người',
                        'position' => 'Trái',
                        'role' => 'Biểu ý',
                        'explanation' => 'Chủ thể phát ngôn.',
                    ],
                    [
                        'character' => '言',
                        'name' => 'Bộ Ngôn',
                        'sino_vietnamese' => 'Ngôn',
                        'meaning' => 'Lời nói, ngôn ngữ',
                        'position' => 'Phải',
                        'role' => 'Biểu ý',
                        'explanation' => 'Lời ăn tiếng nói.',
                    ]
                ],
                'stroke_rules' => 'Trái trước phải sau (亻 trước, 言 sau) • Trên trước dưới sau',
                'stroke_steps' => [
                    ['step' => 1, 'name' => 'Phẩy (撇 - piě)', 'highlight' => 'Phẩy bộ Nhân', 'pinyin' => 'piě'],
                    ['step' => 2, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ bộ Nhân', 'pinyin' => 'shù'],
                    ['step' => 3, 'name' => 'Chấm (点 - diǎn)', 'highlight' => 'Chấm đỉnh chữ Ngôn', 'pinyin' => 'diǎn'],
                    ['step' => 4, 'name' => 'Ngang dài (横 - héng)', 'highlight' => 'Ngang dài thứ nhất chữ Ngôn', 'pinyin' => 'héng'],
                    ['step' => 5, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang ngắn thứ hai chữ Ngôn', 'pinyin' => 'héng'],
                    ['step' => 6, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang ngắn thứ ba chữ Ngôn', 'pinyin' => 'héng'],
                    ['step' => 7, 'name' => 'Sổ (竖 - shù)', 'highlight' => 'Sổ trái chữ Khẩu', 'pinyin' => 'shù'],
                    ['step' => 8, 'name' => 'Ngang gập (横折 - héngzhé)', 'highlight' => 'Ngang gập chữ Khẩu', 'pinyin' => 'héngzhé'],
                    ['step' => 9, 'name' => 'Ngang (横 - héng)', 'highlight' => 'Ngang đáy chữ Khẩu', 'pinyin' => 'héng'],
                ],
                'compounds' => [
                    ['word' => '相信', 'pinyin' => 'xiāngxìn', 'meaning' => 'Tin tưởng'],
                    ['word' => '信用卡', 'pinyin' => 'xìnyòngkǎ', 'meaning' => 'Thẻ tín dụng'],
                    ['word' => '写信', 'pinyin' => 'xiěxìn', 'meaning' => 'Viết thư'],
                ]
            ]
        ];
    }

    /**
     * Danh sách bộ thủ tiêu biểu mẫu
     */
    private function getDemoRadicals(): array
    {
        return [
            ['radical' => '一', 'variant' => '', 'stroke_count' => 1, 'pinyin' => 'yī', 'sino_vietnamese' => 'Nhất', 'meaning' => 'Số một, đồng nhất', 'category' => 'Số đếm'],
            ['radical' => '丨', 'variant' => '', 'stroke_count' => 1, 'pinyin' => 'gǔn', 'sino_vietnamese' => 'Cổn', 'meaning' => 'Nét sổ thẳng đứng', 'category' => 'Hình nét'],
            ['radical' => '丶', 'variant' => '', 'stroke_count' => 1, 'pinyin' => 'zhǔ', 'sino_vietnamese' => 'Chủ', 'meaning' => 'Điểm chấm, giọt nước', 'category' => 'Hình nét'],
            ['radical' => '丿', 'variant' => '', 'stroke_count' => 1, 'pinyin' => 'piě', 'sino_vietnamese' => 'Phiệt', 'meaning' => 'Nét phẩy chém xuống', 'category' => 'Hình nét'],
            ['radical' => '乙', 'variant' => '乛, 乚', 'stroke_count' => 1, 'pinyin' => 'yǐ', 'sino_vietnamese' => 'Ất', 'meaning' => 'Can Ất, uốn khúc', 'category' => 'Can chi'],
            ['radical' => '亅', 'variant' => '', 'stroke_count' => 1, 'pinyin' => 'jué', 'sino_vietnamese' => 'Quyết', 'meaning' => 'Nét móc lên', 'category' => 'Hình nét'],
            ['radical' => '人', 'variant' => '亻', 'stroke_count' => 2, 'pinyin' => 'rén', 'sino_vietnamese' => 'Nhân', 'meaning' => 'Con người, nhân loại', 'category' => 'Con người'],
            ['radical' => '刀', 'variant' => '刂', 'stroke_count' => 2, 'pinyin' => 'dāo', 'sino_vietnamese' => 'Đao', 'meaning' => 'Cây đao, con dao', 'category' => 'Vũ khí'],
            ['radical' => '力', 'variant' => '', 'stroke_count' => 2, 'pinyin' => 'lì', 'sino_vietnamese' => 'Lực', 'meaning' => 'Sức mạnh, cơ bắp', 'category' => 'Hành động'],
            ['radical' => '又', 'variant' => '', 'stroke_count' => 2, 'pinyin' => 'yòu', 'sino_vietnamese' => 'Hựu', 'meaning' => 'Lại nữa, bàn tay phải', 'category' => 'Hành động'],
            ['radical' => '口', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'kǒu', 'sino_vietnamese' => 'Khẩu', 'meaning' => 'Cái miệng, ăn uống', 'category' => 'Bộ phận'],
            ['radical' => '囗', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'wéi', 'sino_vietnamese' => 'Vi', 'meaning' => 'Bao quanh, bờ cõi', 'category' => 'Không gian'],
            ['radical' => '土', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'tǔ', 'sino_vietnamese' => 'Thổ', 'meaning' => 'Đất đai, thổ nhưỡng', 'category' => 'Thiên nhiên'],
            ['radical' => '女', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'nǚ', 'sino_vietnamese' => 'Nữ', 'meaning' => 'Phụ nữ, con gái', 'category' => 'Con người'],
            ['radical' => '子', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'zǐ', 'sino_vietnamese' => 'Tử', 'meaning' => 'Con cái, đứa trẻ', 'category' => 'Con người'],
            ['radical' => '宀', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'mián', 'sino_vietnamese' => 'Miên', 'meaning' => 'Mái nhà che phủ', 'category' => 'Kiến trúc'],
            ['radical' => '山', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'shān', 'sino_vietnamese' => 'Sơn', 'meaning' => 'Ngọn núi, non cao', 'category' => 'Thiên nhiên'],
            ['radical' => '巾', 'variant' => '', 'stroke_count' => 3, 'pinyin' => 'jīn', 'sino_vietnamese' => 'Cân', 'meaning' => 'Khăn vải, mũ nón', 'category' => 'Đồ vật'],
            ['radical' => '心', 'variant' => '忄, ⺗', 'stroke_count' => 4, 'pinyin' => 'xīn', 'sino_vietnamese' => 'Tâm', 'meaning' => 'Trái tim, tâm tư tình cảm', 'category' => 'Cảm xúc'],
            ['radical' => '手', 'variant' => '扌, ⺁', 'stroke_count' => 4, 'pinyin' => 'shǒu', 'sino_vietnamese' => 'Thủ', 'meaning' => 'Bàn tay, thao tác', 'category' => 'Bộ phận'],
            ['radical' => '日', 'variant' => '', 'stroke_count' => 4, 'pinyin' => 'rì', 'sino_vietnamese' => 'Nhật', 'meaning' => 'Mặt trời, ban ngày', 'category' => 'Vũ trụ'],
            ['radical' => '月', 'variant' => '⺼', 'stroke_count' => 4, 'pinyin' => 'yuè', 'sino_vietnamese' => 'Nguyệt / Nhục', 'meaning' => 'Mặt trăng / Thịt cơ thể', 'category' => 'Vũ trụ'],
            ['radical' => '木', 'variant' => '', 'stroke_count' => 4, 'pinyin' => 'mù', 'sino_vietnamese' => 'Mộc', 'meaning' => 'Cây cối, gỗ', 'category' => 'Cây cỏ'],
            ['radical' => '水', 'variant' => '氵, 氺', 'stroke_count' => 4, 'pinyin' => 'shuǐ', 'sino_vietnamese' => 'Thủy', 'meaning' => 'Nước, sông hồ', 'category' => 'Thiên nhiên'],
            ['radical' => '火', 'variant' => '灬', 'stroke_count' => 4, 'pinyin' => 'huǒ', 'sino_vietnamese' => 'Hỏa', 'meaning' => 'Lửa cháy, nhiệt độ', 'category' => 'Thiên nhiên'],
            ['radical' => '目', 'variant' => '', 'stroke_count' => 5, 'pinyin' => 'mù', 'sino_vietnamese' => 'Mục', 'meaning' => 'Con mắt, nhìn ngắm', 'category' => 'Bộ phận'],
            ['radical' => '示', 'variant' => '礻', 'stroke_count' => 5, 'pinyin' => 'shì', 'sino_vietnamese' => 'Thị', 'meaning' => 'Thần linh, tế tự, chỉ bảo', 'category' => 'Tâm linh'],
            ['radical' => '禾', 'variant' => '', 'stroke_count' => 5, 'pinyin' => 'hé', 'sino_vietnamese' => 'Hòa', 'meaning' => 'Cây lúa, mạ non', 'category' => 'Cây cỏ'],
            ['radical' => '言', 'variant' => '讠', 'stroke_count' => 7, 'pinyin' => 'yán', 'sino_vietnamese' => 'Ngôn', 'meaning' => 'Lời nói, phát ngôn', 'category' => 'Giao tiếp'],
            ['radical' => '辶', 'variant' => '辵', 'stroke_count' => 3, 'pinyin' => 'chuò', 'sino_vietnamese' => 'Sước', 'meaning' => 'Bước đi, dạo bước xa', 'category' => 'Hành động'],
        ];
    }
}
