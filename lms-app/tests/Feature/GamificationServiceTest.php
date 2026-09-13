<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserExpTransaction;
use App\Models\UserLearningLog;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GamificationServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected GamificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(GamificationService::class);
    }

    // =========================================================================
    // awardExp() — Tests
    // =========================================================================

    /**
     * Test cộng EXP thành công cho hành động hợp lệ.
     */
    public function test_award_exp_successfully_adds_points_and_logs(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'current_streak' => 0,
            'longest_streak' => 0,
            'exp_total'      => 0,
        ]);

        $result = $this->service->awardExp($user, 'hsk_mock_exam', 101);

        $this->assertNotNull($result);
        $this->assertEquals(50, $result['exp_gained']);
        $this->assertEquals(50, $result['today_exp']);
        $this->assertEquals(50, $result['exp_total']);
        $this->assertEquals(1, $result['current_streak']);
        $this->assertTrue($result['streak_increased']);

        $this->assertDatabaseHas('user_exp_transactions', [
            'user_id'      => $user->id,
            'action_type'  => 'hsk_mock_exam',
            'exp_gained'   => 50,
            'reference_id' => 101,
        ]);

        $this->assertDatabaseHas('user_learning_logs', [
            'user_id'    => $user->id,
            'exp_gained' => 50,
        ]);
    }

    /**
     * [NEW] Test trả về null khi action type không tồn tại trong config.
     */
    public function test_award_exp_returns_null_for_unknown_action_type(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->service->awardExp($user, 'action_khong_ton_tai');

        $this->assertNull($result);
        $this->assertDatabaseMissing('user_exp_transactions', ['user_id' => $user->id]);
    }

    /**
     * Test Daily Cap chặn đúng khi vượt quá giới hạn trong ngày.
     * Flashcard: daily_cap = 20 EXP, mỗi lần 1 EXP.
     */
    public function test_daily_cap_enforces_maximum_exp_per_day(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        for ($i = 1; $i <= 20; $i++) {
            $res = $this->service->awardExp($user, 'flashcard_remember', $i);
            $this->assertNotNull($res);
            $this->assertEquals(1, $res['exp_gained']);
        }

        // Lần thứ 21 phải bị chặn
        $res21 = $this->service->awardExp($user, 'flashcard_remember', 21);
        $this->assertNull($res21);

        $total = UserExpTransaction::where('user_id', $user->id)
            ->where('action_type', 'flashcard_remember')
            ->sum('exp_gained');
        $this->assertEquals(20, $total);
    }

    /**
     * [NEW] Test Daily Cap cộng EXP từng phần khi sắp chạm trần (partial cap).
     * pinyin_practice: daily_cap = 30, mỗi lần 10 EXP.
     * Sau 2 ván (20 EXP), ván thứ 3 nhận đủ 10 EXP. Ván thứ 4 bị chặn hoàn toàn.
     */
    public function test_daily_cap_gives_partial_exp_when_nearing_limit(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['exp_total' => 0]);

        $res1 = $this->service->awardExp($user, 'pinyin_practice');
        $this->assertEquals(10, $res1['exp_gained']);

        $res2 = $this->service->awardExp($user, 'pinyin_practice');
        $this->assertEquals(10, $res2['exp_gained']);

        $res3 = $this->service->awardExp($user, 'pinyin_practice');
        $this->assertEquals(10, $res3['exp_gained']);

        // Ván 4: đã đủ 30 EXP/ngày → bị chặn
        $res4 = $this->service->awardExp($user, 'pinyin_practice');
        $this->assertNull($res4);

        $total = UserExpTransaction::where('user_id', $user->id)
            ->where('action_type', 'pinyin_practice')
            ->sum('exp_gained');
        $this->assertEquals(30, $total);
    }

    /**
     * Test chống trùng lặp với hành động one-time.
     */
    public function test_one_time_action_prevents_duplicate_exp_for_same_reference_id(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $lessonId = 55;

        $first = $this->service->awardExp($user, 'course_vocab', $lessonId);
        $this->assertNotNull($first);
        $this->assertEquals(10, $first['exp_gained']);

        // Cùng lesson → bị từ chối
        $second = $this->service->awardExp($user, 'course_vocab', $lessonId);
        $this->assertNull($second);

        // Lesson khác → vẫn được nhận
        $differentLesson = $this->service->awardExp($user, 'course_vocab', 56);
        $this->assertNotNull($differentLesson);
        $this->assertEquals(10, $differentLesson['exp_gained']);
    }

    /**
     * Streak increments as soon as the learner crosses streak_min_exp (10 EXP),
     * NOT the daily goal (50 EXP).
     */
    public function test_streak_increments_when_reaching_daily_goal_exp(): void
    {
        $timezone  = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $yesterday = Carbon::yesterday($timezone)->toDateString();

        /** @var User $user */
        $user = User::factory()->create([
            'current_streak'     => 5,
            'longest_streak'     => 5,
            'last_learning_date' => $yesterday,
        ]);

        // The first pinyin_practice = 10 EXP = streak_min_exp, so streak must increase immediately.
        $result = $this->service->awardExp($user, 'pinyin_practice');

        $this->assertNotNull($result);
        $this->assertTrue($result['streak_increased']);

        $user->refresh();
        $this->assertEquals(6, $user->current_streak);
        $this->assertEquals(6, $user->longest_streak);
        $this->assertEquals(Carbon::today($timezone)->toDateString(), $user->last_learning_date->toDateString());

        // Earning more EXP in the same day must NOT increase streak again.
        $second = $this->service->awardExp($user, 'pinyin_practice');
        $this->assertNotNull($second);
        $this->assertFalse($second['streak_increased']);

        $user->refresh();
        $this->assertEquals(6, $user->current_streak); // Unchanged.
    }

    /**
     * reached_goal in the return payload becomes true only at 50 EXP (daily_goal_exp),
     * even though the streak is already counted at 10 EXP.
     */
    public function test_reached_goal_is_true_only_at_daily_goal_threshold(): void
    {
        $timezone  = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $yesterday = Carbon::yesterday($timezone)->toDateString();

        /** @var User $user */
        $user = User::factory()->create([
            'last_learning_date' => $yesterday,
        ]);

        // 10 EXP — streak triggered, but not yet goal.
        $r1 = $this->service->awardExp($user, 'pinyin_practice');
        $this->assertFalse($r1['reached_goal']);
        $this->assertTrue($r1['streak_increased']);

        // +20 EXP (ref 1): total 30 EXP, still below daily goal (50).
        $r_mid = $this->service->awardExp($user, 'course_practice', 1);
        $this->assertFalse($r_mid['reached_goal']);

        // +20 EXP (ref 2): total 50 EXP, exactly at daily goal threshold.
        $r2 = $this->service->awardExp($user, 'course_practice', 2);
        $this->assertTrue($r2['reached_goal']);

        // Refresh to verify cumulative exp stored in the database.
        $user->refresh();
        $this->assertGreaterThanOrEqual(50, $user->exp_total);
    }

    /**
     * [NEW] Test Streak KHÔNG tăng lần 2 nếu đã cán mốc trong cùng ngày hôm nay.
     */
    public function test_streak_does_not_increment_twice_on_same_day(): void
    {
        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $today    = Carbon::today($timezone)->toDateString();

        /** @var User $user */
        $user = User::factory()->create([
            'current_streak'    => 3,
            'longest_streak'    => 5,
            'last_learning_date'=> $today, // Đã tính streak hôm nay rồi
            'exp_total'         => 0,
        ]);

        $result = $this->service->awardExp($user, 'hsk_mock_exam', 777);

        $user->refresh();
        $this->assertFalse($result['streak_increased']);
        $this->assertEquals(3, $user->current_streak); // Không tăng
    }

    /**
     * [NEW] Test Streak bắt đầu từ 1 khi học viên chưa từng học (last_learning_date = null).
     */
    public function test_streak_starts_at_1_for_brand_new_learner(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'current_streak'    => 0,
            'longest_streak'    => 0,
            'last_learning_date'=> null,
            'exp_total'         => 0,
        ]);

        $result = $this->service->awardExp($user, 'hsk_mock_exam', 999);

        $this->assertNotNull($result);
        $this->assertTrue($result['streak_increased']);
        $this->assertEquals(1, $result['current_streak']);
        $this->assertEquals(1, $result['longest_streak']);
    }

    /**
     * [NEW] Test longest_streak được cập nhật đúng khi vượt kỷ lục cũ.
     */
    public function test_longest_streak_updates_when_current_exceeds_record(): void
    {
        $timezone  = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $yesterday = Carbon::yesterday($timezone)->toDateString();

        /** @var User $user */
        $user = User::factory()->create([
            'current_streak'    => 7,
            'longest_streak'    => 7, // Kỷ lục hiện tại = 7
            'last_learning_date'=> $yesterday,
            'exp_total'         => 0,
        ]);

        // Học hôm nay → streak 7 → 8, vượt kỷ lục
        $this->service->awardExp($user, 'hsk_mock_exam', 888);

        $user->refresh();
        $this->assertEquals(8, $user->current_streak);
        $this->assertEquals(8, $user->longest_streak); // Kỷ lục mới = 8
    }

    /**
     * Test Streak reset về 1 nếu học viên bỏ lỡ quá 1 ngày.
     */
    public function test_streak_resets_to_1_if_missed_more_than_one_day(): void
    {
        $timezone     = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $threeDaysAgo = Carbon::today($timezone)->subDays(3)->toDateString();

        /** @var User $user */
        $user = User::factory()->create([
            'current_streak'    => 10,
            'longest_streak'    => 10,
            'last_learning_date'=> $threeDaysAgo,
        ]);

        $this->service->awardExp($user, 'hsk_mock_exam', 99);

        $user->refresh();
        $this->assertEquals(1, $user->current_streak);  // Reset về 1
        $this->assertEquals(10, $user->longest_streak); // Kỷ lục vẫn được bảo tồn
    }

    // =========================================================================
    // getHeatmapData() — Tests
    // =========================================================================

    /**
     * Test lấy dữ liệu Heatmap 91 ngày chính xác.
     */
    public function test_get_heatmap_data_returns_91_days(): void
    {
        /** @var User $user */
        $user    = User::factory()->create();
        $heatmap = $this->service->getHeatmapData($user->id);

        // 13 tuần (91 ngày): từ CN của 12 tuần trước → T7 của tuần hiện tại
        $this->assertCount(91, $heatmap);

        // Hôm nay phải xuất hiện (không nhất thiết ở cuối vì end = T7 của tuần)
        $todayItem = collect($heatmap)->firstWhere('is_today', true);
        $this->assertNotNull($todayItem, 'Heatmap phải chứa ngày hôm nay');
        $this->assertTrue($todayItem['is_today']);
    }

    /**
     * [NEW] Test Heatmap hoạt động với userId = null (khách vãng lai chưa đăng nhập).
     */
    public function test_get_heatmap_data_works_for_guest_with_null_user_id(): void
    {
        $heatmap = $this->service->getHeatmapData(null);

        $this->assertCount(91, $heatmap);

        // Tất cả các ngày quá khứ phải có exp = 0, level = 0
        $pastDays = collect($heatmap)->filter(fn($d) => !$d['is_future']);
        foreach ($pastDays as $day) {
            $this->assertEquals(0, $day['exp']);
            $this->assertEquals(0, $day['level']);
            $this->assertFalse($day['is_checked_in']);
            $this->assertEmpty($day['activities']);
        }
    }

    /**
     * [NEW] Test Heatmap hiển thị đúng level và is_checked_in sau khi học viên có EXP.
     * Bảng màu: level 3 = 50 <= exp < 80.
     */
    public function test_get_heatmap_data_reflects_exp_level_correctly(): void
    {
        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $today    = Carbon::today($timezone)->toDateString();

        /** @var User $user */
        $user = User::factory()->create();

        // Tạo learning log hôm nay với 60 EXP → level 3
        UserLearningLog::create([
            'user_id'      => $user->id,
            'learning_date'=> $today,
            'exp_gained'   => 60,
        ]);

        $heatmap  = $this->service->getHeatmapData($user->id);
        $todayDay = collect($heatmap)->firstWhere('is_today', true);

        $this->assertNotNull($todayDay);
        $this->assertEquals(60, $todayDay['exp']);
        $this->assertEquals(3, $todayDay['level']);
        $this->assertTrue($todayDay['is_checked_in']);
    }

    /**
     * [NEW] Test Heatmap: các ngày tương lai phải có is_future = true, level = 0, exp = 0.
     */
    public function test_get_heatmap_data_marks_future_days_correctly(): void
    {
        /** @var User $user */
        $user       = User::factory()->create();
        $heatmap    = $this->service->getHeatmapData($user->id);
        $futureDays = collect($heatmap)->filter(fn($d) => $d['is_future']);

        foreach ($futureDays as $day) {
            $this->assertTrue($day['is_future']);
            $this->assertEquals(0, $day['exp']);
            $this->assertEquals(0, $day['level']);
            $this->assertFalse($day['is_checked_in']);
        }
    }

    // =========================================================================
    // getGamificationLeaderboard() — Tests
    // =========================================================================

    /**
     * [NEW] Test leaderboard all_time sắp xếp đúng theo exp_total giảm dần.
     */
    public function test_leaderboard_all_time_orders_by_exp_total_descending(): void
    {
        /** @var User $userA */
        $userA = User::factory()->create(['exp_total' => 500, 'role' => User::ROLE_STUDENT]);
        /** @var User $userB */
        $userB = User::factory()->create(['exp_total' => 1000, 'role' => User::ROLE_STUDENT]);
        /** @var User $userC */
        $userC = User::factory()->create(['exp_total' => 200, 'role' => User::ROLE_STUDENT]);

        $result      = $this->service->getGamificationLeaderboard('all_time', 3);
        $leaderboard = $result['leaderboard'];

        $this->assertCount(3, $leaderboard);

        $ids = array_column($leaderboard, 'user_id');
        $this->assertEquals($userB->id, $ids[0]); // 1000 EXP → hạng 1
        $this->assertEquals($userA->id, $ids[1]); // 500 EXP → hạng 2
        $this->assertEquals($userC->id, $ids[2]); // 200 EXP → hạng 3

        $this->assertEquals(1, $leaderboard[0]['rank']);
        $this->assertEquals(2, $leaderboard[1]['rank']);
        $this->assertEquals(3, $leaderboard[2]['rank']);
    }

    /**
     * [NEW] Test Admin KHÔNG xuất hiện trong bảng xếp hạng dù có exp_total cao nhất.
     */
    public function test_leaderboard_excludes_admin_users(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['exp_total' => 99999, 'role' => User::ROLE_ADMIN]);
        /** @var User $student */
        $student = User::factory()->create(['exp_total' => 100, 'role' => User::ROLE_STUDENT]);

        $result  = $this->service->getGamificationLeaderboard('all_time', 10);
        $userIds = array_column($result['leaderboard'], 'user_id');

        $this->assertNotContains($admin->id, $userIds, 'Admin không được xuất hiện trong leaderboard');
        $this->assertContains($student->id, $userIds);
    }

    /**
     * [NEW] Test $limit hoạt động đúng — chỉ trả về đúng số lượng user theo giới hạn.
     */
    public function test_leaderboard_respects_limit_parameter(): void
    {
        User::factory()->count(5)->create(['role' => User::ROLE_STUDENT, 'exp_total' => 100]);

        $result = $this->service->getGamificationLeaderboard('all_time', 2);

        $this->assertCount(2, $result['leaderboard']);
    }

    /**
     * [NEW] Test leaderboard theo tuần sắp xếp theo EXP kiếm được trong tuần, không theo exp_total.
     */
    public function test_leaderboard_week_orders_by_exp_earned_this_week(): void
    {
        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $now      = Carbon::now($timezone);

        /** @var User $userHighWeekly */
        $userHighWeekly = User::factory()->create(['exp_total' => 50, 'role' => User::ROLE_STUDENT]);
        /** @var User $userLowWeekly */
        $userLowWeekly  = User::factory()->create(['exp_total' => 200, 'role' => User::ROLE_STUDENT]);

        // userHighWeekly: 100 EXP trong tuần này
        UserExpTransaction::create([
            'user_id'     => $userHighWeekly->id,
            'action_type' => 'hsk_mock_exam',
            'exp_gained'  => 100,
            'created_at'  => $now,
        ]);

        // userLowWeekly: chỉ 10 EXP trong tuần này (dù exp_total cao hơn)
        UserExpTransaction::create([
            'user_id'     => $userLowWeekly->id,
            'action_type' => 'hsk_mock_exam',
            'exp_gained'  => 10,
            'created_at'  => $now,
        ]);

        $result = $this->service->getGamificationLeaderboard('week', 10);
        $ids    = array_column($result['leaderboard'], 'user_id');

        $posHigh = array_search($userHighWeekly->id, $ids);
        $posLow  = array_search($userLowWeekly->id, $ids);

        $this->assertNotFalse($posHigh);
        $this->assertNotFalse($posLow);
        $this->assertLessThan($posLow, $posHigh, 'User có EXP tuần cao hơn phải xếp trên');
    }

    /**
     * [NEW] Test leaderboard trả về đúng cấu trúc dữ liệu và badge cho từng item.
     */
    public function test_leaderboard_returns_correct_data_structure_and_badge(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['exp_total' => 600, 'role' => User::ROLE_STUDENT]);

        $result      = $this->service->getGamificationLeaderboard('all_time', 1);
        $leaderboard = $result['leaderboard'];

        $item = collect($leaderboard)->firstWhere('user_id', $user->id);
        $this->assertNotNull($item);

        // Kiểm tra các key bắt buộc
        foreach (['rank', 'user_id', 'name', 'avatar', 'exp', 'raw_exp', 'streak', 'longest_streak', 'badge'] as $key) {
            $this->assertArrayHasKey($key, $item);
        }

        // Badge: 600 EXP >= 500 → 'Học giả'
        $this->assertEquals('Học giả', $item['badge']);
        // Badge: raw_exp phải đúng
        $this->assertEquals(600, $item['raw_exp']);
    }
}
