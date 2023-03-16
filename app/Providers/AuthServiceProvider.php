<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\BoardTemplate;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Policies\BoardTemplatePolicy;
use App\Policies\InvitePolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TemplatePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Organization::class => OrganizationPolicy::class,
        Invite::class => InvitePolicy::class,
        Project::class => ProjectPolicy::class,
        BoardTemplate::class => TemplatePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
