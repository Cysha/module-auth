<?php namespace Cms\Modules\Auth\Repositories\User;

use Illuminate\Database\Eloquent\Collection;
use Cms\Modules\Core\Repositories\BaseEloquentRepository;
use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepository;

class EloquentRepository extends BaseEloquentRepository implements UserRepository
{
    public function getModel()
    {
        return config('auth.model');
    }

    /**
     * Create a user and assign roles to it
     *
     * @param array $data
     * @param array $roles
     *
     * @param bool $verified
     */
    public function createWithRoles($data, $roles, $verified = false)
    {
        if ($verified === true) {
            $data['verified_at'] = \Carbon\Carbon::now();
        }

        $user = $this->create($data);
        if ($user === null) {
            return false;
        }

        if (!empty($roles) && is_array($roles)) {
            foreach ($roles as $roleId) {
                $user->roles()->attach(
                    $roleId,
                    ['caller_type' => 'auth_user']
                );
            }
        }
    }

    /**
     * Update a user
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data)
    {

    }

    /**
     * Update a user and sync its roles
     *
     * @param  int   $userId
     * @param $data
     * @param $roles
     *
     * @return mixed
     */
    public function updateAndSyncRoles($userId, $data, $roles)
    {

    }

    /**
     * Find a user by its credentials
     *
     * @param  array $credentials
     *
     * @return mixed
     */
    public function findByCredentials(array $credentials)
    {

    }

}
