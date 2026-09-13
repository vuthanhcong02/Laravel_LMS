<?php

namespace App\Listeners;

use App\Events\UserEarnedExp;
use App\Services\GamificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardExperiencePoints implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Initialize listener with GamificationService.
     */
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Handle user earned experience points event.
     */
    public function handle(UserEarnedExp $event): void
    {
        $this->gamificationService->awardExp(
            $event->user,
            $event->actionType,
            $event->referenceId
        );
    }
}
