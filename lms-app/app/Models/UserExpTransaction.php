<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExpTransaction extends Model
{
    use HasFactory;

    /**
     * Table to store EXP transaction history.
     *
     * @var string
     */
    protected $table = 'user_exp_transactions';

    /**
     * Disable updated_at timestamp.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action_type',
        'exp_gained',
        'reference_id',
        'created_at',
    ];

    /**
     * Data type formatting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exp_gained' => 'integer',
        'reference_id' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship to the owner of this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
