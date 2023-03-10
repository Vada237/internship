<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    public function update(User $user, BoardTemplate $boardTemplate)
    {
        return ($user->hasRole(Role::list['ADMIN']) || $user->id == $boardTemplate->user_id);
    }

    public function delete(User $user, BoardTemplate $boardTemplate)
    {
        return ($user->hasRole(Role::list['ADMIN']) || $user->id == $boardTemplate->user_id);
    }
}
