<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupSetting extends Model
{
    protected $fillable = [
        'enabled',
        'frequency',
        'run_at',
        'day_of_week',
        'max_backups',
        'last_run_at',
    ];

    protected $casts = [
        'enabled'     => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public static function getCurrent(): self
    {
        return self::firstOrCreate([], [
            'enabled'     => false,
            'frequency'   => 'daily',
            'run_at'      => '02:00:00',
            'day_of_week' => 0,
            'max_backups' => 7,
        ]);
    }

    public function shouldRunNow(): bool
    {
        if (!$this->enabled) {
            return false;
        }

        $now = now();

        if ($this->last_run_at) {
            $alreadyRan = match ($this->frequency) {
                'hourly' => $this->last_run_at->isCurrentHour(),
                'daily'  => $this->last_run_at->isToday(),
                'weekly' => $this->last_run_at->isCurrentWeek(),
                default  => false,
            };

            if ($alreadyRan) {
                return false;
            }
        }

        return match ($this->frequency) {
            'hourly' => $now->minute === 0,
            'daily'  => $now->format('H:i') === substr($this->run_at, 0, 5),
            'weekly' => $now->dayOfWeek === (int) $this->day_of_week
                && $now->format('H:i') === substr($this->run_at, 0, 5),
            default  => false,
        };
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'hourly' => 'Mỗi giờ',
            'daily'  => 'Mỗi ngày',
            'weekly' => 'Mỗi tuần',
            default  => 'Không xác định',
        };
    }

    public function getDayOfWeekLabelAttribute(): string
    {
        $days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
        return $days[$this->day_of_week] ?? 'Chủ nhật';
    }
}
