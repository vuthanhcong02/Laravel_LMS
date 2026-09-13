<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLearningLog extends Model
{
    use HasFactory;

    /**
     * Table to store daily learning logs.
     *
     * @var string
     */
    protected $table = 'user_learning_logs';

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'learning_date',
        'exp_gained',
    ];

    /**
     * Data type formatting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'learning_date' => 'date',
        'exp_gained' => 'integer',
    ];

    /**
     * Relationship to the owner of this learning log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
