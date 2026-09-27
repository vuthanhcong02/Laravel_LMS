<?php

namespace Tests\Feature;

use Tests\TestCase;

class HanziDecompositionTest extends TestCase
{
    /**
     * Kiểm tra trang Chiết tự Chữ Hán tải thành công với nhóm bộ thủ
     */
    public function test_hanzi_index_page_loads_successfully_with_radical_groups(): void
    {
        $response = $this->get(route('hanzi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('hanzi.index');
        $response->assertViewHas(['radicalGroups', 'demoRadicals']);

        $groups = $response->viewData('radicalGroups');
        $this->assertArrayHasKey('ren', $groups);
        $this->assertArrayHasKey('shui', $groups);
        $this->assertArrayHasKey('ri', $groups);
        $this->assertArrayHasKey('mu', $groups);
        $this->assertArrayHasKey('nv', $groups);
        $this->assertArrayHasKey('xin', $groups);

        // Kiểm tra các chữ liên quan nằm cùng bộ Nhân
        $this->assertArrayHasKey('休', $groups['ren']['characters']);
        $this->assertArrayHasKey('信', $groups['ren']['characters']);
        $this->assertArrayHasKey('你', $groups['ren']['characters']);

        // Kiểm tra các chữ liên quan nằm cùng bộ Thủy
        $this->assertArrayHasKey('江', $groups['shui']['characters']);
        $this->assertArrayHasKey('河', $groups['shui']['characters']);
        $this->assertArrayHasKey('海', $groups['shui']['characters']);
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
        $this->assertStringContainsString('Nhân đứng', $data[0]['radical_name']);
    }
}
