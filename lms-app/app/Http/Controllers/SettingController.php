<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $this->settingService->getUserSettings();

        return view('portal.settings.index', compact('settings', 'user'));
    }

    /**
     * Update the user settings.
     */
    public function update(UpdateSettingRequest $request)
    {
        try {
            $data = $request->validated();

            $this->settingService->updateSettings($data);

            return redirect()->back()->with('success', 'Cài đặt đã được lưu thành công.');
        }
        catch (\Exception $e) {
            Log::error('Lỗi khi lưu cài đặt: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu cài đặt. Vui lòng thử lại sau.');
        }
    }
}
