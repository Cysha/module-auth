<?php

namespace Cms\Modules\Auth\Composers;

use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepo;

class BackendSidebar
{
    /**
     * @var Cms\Modules\Auth\Repositories\User\RepositoryInterface
     */
    protected $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function userCount()
    {
        $userRepo = $this->userRepo;

        $counter = cache_remember('auth', 'sidebar.auth.user.count', 60, function () use ($userRepo) {

            return $userRepo->count();
        });

        return sprintf('<span class="label label-default pull-right">%d</span>', $counter);
    }

    public function apikeyCount()
    {
        $counter = \Cache::remember('sidebar.auth.apikey.count', 60, function () {
            return app('Cms\Modules\Auth\Models\ApiKey')
                ->count();
        });

        return sprintf('<span class="label label-default pull-right">%d</span>', $counter);
    }

    private function modelCount($model)
    {
        $counter = \Cache::remember('sidebar.auth.'.strtolower($model).'.count', 60, function () use ($model) {
            return app('Cms\Modules\Auth\Models\\'.ucwords($model))
                ->count();
        });

        return sprintf('<span class="label label-default pull-right">%d</span>', $counter);
    }

    public function __call($method, $args)
    {
        if (!preg_match('~(.*)Count$~', $method, $m)) {
            return false;
        }

        return $this->modelCount($m[1]);
    }
}
