<?php namespace Cms\Modules\Auth\Repositories\User;

/**
 * Interface UserRepository
 */
interface RepositoryInterface
{
    /**
     * Create a user and assign roles to it
     *
     * @param array $data
     * @param array $roles
     *
     * @param bool $activated
     */
    public function createWithRoles($data, $roles, $activated = false);

    /**
     * Update a user
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data);

    /**
     * Update a user and sync its roles
     *
     * @param  int   $userId
     * @param $data
     * @param $roles
     *
     * @return mixed
     */
    public function updateAndSyncRoles($userId, $data, $roles);

    /**
     * Find a user by its credentials
     *
     * @param  array $credentials
     *
     * @return mixed
     */
    public function findByCredentials(array $credentials);

}
