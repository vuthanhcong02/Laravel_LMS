<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\User;

class AssignmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Assignment $assignment): bool
    {
        if ($user->role === User::ROLE_ADMIN) return true;
        if ($user->role === User::ROLE_TEACHER && $user->id === $assignment->teacher_id) return true;
        
        // If student, check if enrolled in course
        if ($user->role === User::ROLE_STUDENT && $assignment->status === Assignment::STATUS_PUBLISHED) {
            return Enrollment::where('user_id', $user->id)
                ->where('course_id', $assignment->course_id)
                ->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === User::ROLE_TEACHER;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assignment $assignment): bool
    {
        return $user->role === User::ROLE_TEACHER && $user->id === $assignment->teacher_id;
    }

    /**
     * Determine whether the teacher can grade submissions for this assignment.
     */
    public function grade(User $user, Assignment $assignment): bool
    {
        return $user->role === User::ROLE_TEACHER && $user->id === $assignment->teacher_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->role === User::ROLE_TEACHER && $user->id === $assignment->teacher_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Assignment $assignment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Assignment $assignment): bool
    {
        return false;
    }
}
