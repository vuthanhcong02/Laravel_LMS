<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingService
{
    /**
     * Get all settings for the currently authenticated user as key-value pairs.
     *
     * @return array
     */
    public function getUserSettings()
    {
        return Setting::where('user_id', Auth::id())
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Update user settings from an array of key-value pairs.
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function updateSettings(array $data)
    {
        try {
            $userId = Auth::id();
            
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(
                    ['user_id' => $userId, 'key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : $value]
                );
            }
        } catch (\Exception $e) {
            Log::error('Có lỗi xảy ra khi cập nhật cài đặt: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $data
            ]);
            throw $e;
        }
    }
}
