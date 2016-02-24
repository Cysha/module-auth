<?php namespace Cms\Modules\Auth\Composers;

use Cms\Modules\Auth\Models;

class BackendSidebar {

    public function userCount() {
        $counter = \Cache::remember('auth.user.count', 60, function() {
            $authModel = config('auth.model');

            return app($authModel)->count();
        });

        return sprintf('<span class="label label-default pull-right">%d</span>', $counter);
    }

    private function modelCount($model) {
        $counter = \Cache::remember('auth.'.strtolower($model).'.count', 60, function() use($model) {
            return app('Cms\Modules\Auth\Models\\'.ucwords($model))
                ->count();
        });

        return sprintf('<span class="label label-default pull-right">%d</span>', $counter);
    }

    public function __call($method, $args) {
        if (!preg_match('~(.*)Count$~', $method, $m)) {
            return false;
        }

        return $this->modelCount($m[1]);
    }
}
