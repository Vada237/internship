<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
    }

    public function view(User $user)
    {

    }

    public function update(User $user)
    {
    }

    public function delete(User $user, User $model)
    {
    }
}
