<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEarnedExp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Initialize the event of user earning experience points.
     *
     * @param User $user
     * @param string $actionType
     * @param int|null $referenceId
     */
    public function __construct(
        public User $user,
        public string $actionType,
        public ?int $referenceId = null
    ) {}
}
