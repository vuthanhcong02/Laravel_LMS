<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Media\MediaStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAvatarFixTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_fix_avatars_command_resets_corrupted_or_invalid_url(): void
    {
        // User with a broken/truncated URL
        $user = User::factory()->create([
            'avatar' => 'https://lh3.googleusercontent.com/truncated-invalid-link',
        ]);

        Http::fake([
            'https://lh3.googleusercontent.com/*' => Http::response('Not Found', 404),
        ]);

        $this->artisan('users:fix-avatars')
            ->assertSuccessful();

        $user->refresh();
        $this->assertNull($user->avatar);
    }

    public function test_users_fix_avatars_command_migrates_valid_external_image_to_b2(): void
    {
        Storage::fake('b2');

        $user = User::factory()->create([
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        Http::fake([
            'https://example.com/avatar.jpg' => Http::response('fake-binary-image-data', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $this->artisan('users:fix-avatars')
            ->assertSuccessful();

        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertStringStartsWith("avatars/user_{$user->id}/", $user->avatar);
        Storage::disk('b2')->assertExists($user->avatar);
    }
}
