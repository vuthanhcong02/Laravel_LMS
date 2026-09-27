<?php

namespace Tests\Feature;

use Tests\TestCase;

class HanziDecompositionTest extends TestCase
{
    /**
     * Kiểm tra trang Chiết tự Chữ Hán tải thành công với mã phản hồi 200
     */
    public function test_hanzi_index_page_loads_successfully(): void
    {
        $response = $this->get(route('hanzi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('hanzi.index');
        $response->assertViewHas(['demoCharacters', 'demoRadicals']);
    }

    /**
     * Kiểm tra API tra cứu chữ Hán trả về toàn bộ dữ liệu mẫu khi không truyền từ khóa
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
}
