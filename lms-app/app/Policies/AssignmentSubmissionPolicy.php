<?php

namespace App\Policies;

use App\Models\AssignmentSubmission;
use App\Models\User;

class AssignmentSubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER, User::ROLE_STUDENT]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AssignmentSubmission $assignmentSubmission): bool
    {
        if ($user->role === User::ROLE_ADMIN) return true;
        // Teacher can see submissions for their own assignment
        if ($user->role === User::ROLE_TEACHER && $user->id === $assignmentSubmission->assignment->teacher_id) {
            return true;
        }
        // Student can see their own submission
        return $user->id === $assignmentSubmission->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssignmentSubmission $assignmentSubmission): bool
    {
        // Student can update their own submission if it's pending/submitted (not graded)
        if ($user->role === User::ROLE_STUDENT && $user->id === $assignmentSubmission->user_id) {
            return $assignmentSubmission->status !== AssignmentSubmission::STATUS_GRADED;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssignmentSubmission $assignmentSubmission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssignmentSubmission $assignmentSubmission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssignmentSubmission $assignmentSubmission): bool
    {
        return false;
    }
}
