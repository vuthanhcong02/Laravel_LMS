<?php

namespace Tests\Feature;

use Tests\TestCase;

class TtsTest extends TestCase
{
    /**
     * Test TTS endpoint validation.
     */
    public function test_tts_requires_text_parameter(): void
    {
        $response = $this->getJson('/api/tts');
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['text']);
    }

    /**
     * Test TTS endpoint synthesizes speech successfully.
     */
    public function test_tts_synthesizes_audio_successfully(): void
    {
        $response = $this->get('/api/tts?text=你好&voice=zh-CN-XiaoxiaoNeural');
        $response->assertStatus(200);
        $this->assertEquals('audio/mpeg', $response->headers->get('content-type'));
    }
}
