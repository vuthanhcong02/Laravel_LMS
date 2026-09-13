<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 1;

    const ROLE_TEACHER = 2;

    const ROLE_STUDENT = 3;

    const ROLE_GUEST = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'avatar',
        'provider',
        'provider_id',
        'current_streak',
        'longest_streak',
        'last_learning_date',
        'exp_total',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'current_streak' => 'integer',
        'longest_streak' => 'integer',
        'last_learning_date' => 'date',
        'exp_total' => 'integer',
    ];

    /**
     * Custom send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    /**
     * Custom send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getAllRole()
    {
        return [
            self::ROLE_TEACHER => 'Teacher',
            self::ROLE_STUDENT => 'Student',
            self::ROLE_GUEST => 'Guest',
        ];
    }

    public function getFullNameAttribute(): string
    {
        $name = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        return $name ?: __('Học viên');
    }

    public function getAvatarUrlAttribute()
    {
        $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=FFFFFF&background=8fc0e0';

        if (!$this->avatar) {
            return $defaultAvatar;
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Many-to-many relationship with hsk_vocabularies table (learned vocabularies).
     */
    public function rememberedVocabularies()
    {
        return $this->belongsToMany(HskVocabulary::class, 'user_remembered_vocabularies', 'user_id', 'hsk_vocabulary_id')
            ->withTimestamps();
    }

    /**
     * List of learning logs by date.
     */
    public function learningLogs()
    {
        return $this->hasMany(UserLearningLog::class);
    }

    /**
     * History of all EXP transactions.
     */
    public function expTransactions()
    {
        return $this->hasMany(UserExpTransaction::class);
    }

    /**
     * Get EXP gained today.
     */
    public function getTodayExpAttribute(): int
    {
        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $today = now()->setTimezone($timezone)->toDateString();

        $log = $this->learningLogs()->where('learning_date', $today)->first();

        return $log ? (int) $log->exp_gained : 0;
    }

    /**
     * Daily EXP goal.
     */
    public function getDailyGoalExpAttribute(): int
    {
        return (int) config('gamification.daily_goal_exp', 50);
    }

    /**
     * Percentage of daily EXP goal completed (0 - 100%).
     */
    public function getDailyProgressPercentAttribute(): int
    {
        $goal = $this->daily_goal_exp;
        if ($goal <= 0) {
            return 100;
        }

        $percent = (int) round(($this->today_exp / $goal) * 100);

        return min(100, $percent);
    }
}
