<?php namespace Cms\Modules\Auth\Repositories\Role;

use Illuminate\Database\Eloquent\Collection;
use Cms\Modules\Core\Repositories\BaseEloquentRepository;
use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as UserRepository;

class EloquentRepository extends BaseEloquentRepository implements UserRepository
{
    public function getModel()
    {
        return 'Cms\Modules\Auth\Models\Role';
    }
}
