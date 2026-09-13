<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PinyinQuizSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pinyin_quiz_submit_requires_login(): void
    {
        $response = $this->postJson(route('pinyin.quiz.submit'), [
            'quiz_length'   => 10,
            'correct_count' => 8,
            'score'         => 80,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'       => false,
            'require_login' => true,
        ]);
    }

    public function test_pinyin_quiz_submit_gives_exp_when_criteria_met(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['exp_total' => 0]);

        $response = $this->actingAs($user)->postJson(route('pinyin.quiz.submit'), [
            'quiz_length'   => 50,
            'correct_count' => 45,
            'score'         => 450,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonPath('gamification.exp_gained', 50);
        $this->assertEquals(50, $user->fresh()->exp_total);
    }

    public function test_pinyin_quiz_submit_gives_zero_exp_when_below_min_correct(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['exp_total' => 0]);

        // 50 questions with only 10 correct (< 40% threshold)
        $response = $this->actingAs($user)->postJson(route('pinyin.quiz.submit'), [
            'quiz_length'   => 50,
            'correct_count' => 10,
            'score'         => 100,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'      => true,
            'gamification' => null,
        ]);
        $this->assertEquals(0, $user->fresh()->exp_total);
    }
}
