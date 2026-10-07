<?php

namespace Tests\Feature;

use App\Models\HanziCharacter;
use App\Models\HanziRadical;
use App\Services\HanziAdminService;
use App\Services\HanziStrokeStorageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HanziDecompositionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }
    /**
     * Kiểm tra trang Chiết tự Chữ Hán tải thành công với nhóm bộ thủ
     */
    public function test_hanzi_index_page_loads_successfully_with_radical_groups(): void
    {
        $response = $this->get(route('hanzi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('hanzi.index');
        $response->assertViewHas(['radicalGroups', 'demoRadicals', 'radicals214', 'hskGroups']);

        $groups = $response->viewData('radicalGroups');
        $this->assertArrayHasKey('ren', $groups);
        $this->assertArrayHasKey('shui', $groups);

        $radicals214 = $response->viewData('radicals214');
        $this->assertNotEmpty($radicals214);

        $hskGroups = $response->viewData('hskGroups');
        $this->assertNotEmpty($hskGroups);
        $this->assertArrayHasKey(1, $hskGroups);
        $this->assertArrayHasKey(6, $hskGroups);
    }

    /**
     * Kiểm tra API tra cứu chữ Hán trả về toàn bộ danh sách khi không truyền từ khóa
     */
    public function test_hanzi_search_api_returns_all_characters_when_query_empty(): void
    {
        $response = $this->getJson(route('hanzi.search'));

        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $this->assertNotEmpty($response->json());
    }

    /**
     * Kiểm tra API tra cứu chữ Hán theo ký tự chữ Hán chính xác
     */
    public function test_hanzi_search_api_filters_by_character(): void
    {
        $response = $this->getJson(route('hanzi.search', ['q' => '休']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);
        $this->assertSame('休', $data[0]['character']);
        $this->assertSame('ren', $data[0]['radical_id']);
    }

    /**
     * Kiểm tra API tra cứu chữ Hán theo âm Hán Việt
     */
    public function test_hanzi_search_api_filters_by_sino_vietnamese(): void
    {
        $response = $this->getJson(route('hanzi.search', ['q' => 'Hưu']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);
        $this->assertStringContainsString('Hưu', $data[0]['sino_vietnamese']);
    }

    /**
     * Kiểm tra API tra cứu chữ Hán theo Pinyin
     */
    public function test_hanzi_search_api_filters_by_pinyin(): void
    {
        $response = $this->getJson(route('hanzi.search', ['q' => 'xiū']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);
        $this->assertStringContainsString('xiū', $data[0]['pinyin']);
    }

    /**
     * Kiểm tra API tra cứu theo tên Bộ thủ
     */
    public function test_hanzi_search_api_filters_by_radical_name(): void
    {
        $response = $this->getJson(route('hanzi.search', ['q' => 'Nhân đứng']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);
        $this->assertSame('ren', $data[0]['radical_id']);
    }

    /**
     * Kiểm tra API lấy chi tiết chữ Hán kèm chiết tự, nét vẽ và từ ghép
     */
    public function test_hanzi_show_detail_api_returns_character_details(): void
    {
        $radical = HanziRadical::firstOrCreate(
            ['code' => 'ren'],
            [
                'radical' => '亻',
                'sino_vietnamese' => 'Nhân',
                'pinyin' => 'rén',
                'stroke_count' => 2,
                'meaning' => 'Con người',
            ]
        );

        $char = HanziCharacter::updateOrCreate(
            ['character' => '休'],
            [
                'radical_id' => $radical->id,
                'pinyin' => 'xiū',
                'sino_vietnamese' => 'Hưu',
                'meaning' => 'Nghỉ ngơi',
                'hsk_level' => 1,
                'stroke_count' => 6,
            ]
        );

        $response = $this->getJson(route('hanzi.show', ['character' => $char->character]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'character',
            'pinyin',
            'sino_vietnamese',
            'meaning',
            'hsk_level',
            'radical',
            'components',
            'compound_words',
            'examples',
        ]);
        $response->assertJsonPath('character', '休');
        $response->assertJsonPath('sino_vietnamese', 'Hưu');
    }

    /**
     * Kiểm tra HanziStrokeStorageService lưu trữ file vector nét vẽ phân cấp
     */
    public function test_hanzi_stroke_storage_service_saves_and_retrieves_hierarchical_vector_files(): void
    {
        $storageService = new HanziStrokeStorageService();
        $testChar = '木';
        $testData = [
            'strokes' => ['M 10 20 L 80 20', 'M 50 10 L 50 90'],
            'medians' => [[[10, 20], [80, 20]], [[50, 10], [50, 90]]],
        ];

        $savedPath = $storageService->saveStrokeData($testChar, $testData, 1, 'moc');
        $this->assertNotEmpty($savedPath);
        $this->assertStringContainsString('characters/hsk1/bo_moc', $savedPath);
        $this->assertTrue(Storage::exists($savedPath));

        $retrieved = $storageService->getStrokeData($testChar, $savedPath);
        $this->assertNotNull($retrieved);
        $this->assertSame('木', $retrieved['character']);
        $this->assertCount(2, $retrieved['strokes']);

        // Dọn dẹp
        $storageService->deleteStrokeData($savedPath);
        $this->assertFalse(Storage::exists($savedPath));
    }

    /**
     * Kiểm tra HanziAdminService thực hiện CRUD chữ Hán sẵn sàng cho Admin Panel
     */
    public function test_hanzi_admin_service_crud_character(): void
    {
        $storageService = new HanziStrokeStorageService();
        $adminService = new HanziAdminService($storageService);

        $radical = HanziRadical::firstOrCreate(
            ['code' => 'test_bo'],
            [
                'radical' => '木',
                'sino_vietnamese' => 'Mộc',
                'pinyin' => 'mù',
                'stroke_count' => 4,
                'meaning' => 'Cây cối',
            ]
        );

        $created = $adminService->createCharacter(
            [
                'radical_id' => $radical->id,
                'character' => '林',
                'pinyin' => 'lín',
                'sino_vietnamese' => 'Lâm',
                'meaning' => 'Rừng cây',
                'hsk_level' => 2,
                'stroke_count' => 8,
            ],
            [
                'strokes' => ['M 0 0 L 10 10'],
            ],
            [
                [
                    'component_char' => '木',
                    'component_name' => 'Bộ Mộc',
                    'role' => 'Hình bàng',
                    'meaning' => 'Cây',
                ]
            ]
        );

        $this->assertInstanceOf(HanziCharacter::class, $created);
        $this->assertSame('林', $created->character);
        $this->assertCount(1, $created->components);

        // Update
        $updated = $adminService->updateCharacter($created->id, ['meaning' => 'Rừng rậm tươi tốt']);
        $this->assertSame('Rừng rậm tươi tốt', $updated->meaning);

        // Delete
        $deleted = $adminService->deleteCharacter($created->id);
        $this->assertTrue($deleted);
    }
}
