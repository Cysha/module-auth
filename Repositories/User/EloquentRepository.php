<?php

namespace Cms\Modules\Auth\Repositories\User;

use Cms\Modules\Auth\Events\UserPasswordWasChanged;
use Cms\Modules\Auth\Http\Requests\ChangePasswordRequest;
use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepository;
use Cms\Modules\Core\Repositories\BaseEloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EloquentRepository extends BaseEloquentRepository implements UserRepository
{
    public function getModel()
    {
        return config('cms.auth.config.user_model');
    }

    /**
     * Create a user and assign roles to it.
     *
     * @param array $data
     * @param array $roles
     * @param bool  $verified
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

        if (!empty($roles) && !is_array($roles)) {
            $roles = [$roles];
        }

        if (!empty($roles) && is_array($roles)) {
            foreach ($roles as $roleId) {
                $user->roles()->attach(
                    $roleId,
                    ['caller_type' => 'auth_user']
                );
            }
        }

        return $user;
    }

    /**
     * Update a user.
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
     * Update a user and sync its roles.
     *
     * @param int $userId
     * @param $data
     * @param $roles
     *
     * @return mixed
     */
    public function updateAndSyncRoles($userId, $data, $roles)
    {
    }

    /**
     * Find a user by its credentials.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function findByCredentials(array $credentials)
    {
    }

    /**
     *
     */
    public function updatePassword($user, ChangePasswordRequest $input)
    {
        if ($user === null) {
            return [
                'old_password' => 'Cant find user?',
            ];
        }

        $oldPass = $input->get('old_password', null);
        $newPass = $input->get('new_password', null);
        $newPassConfirm = $input->get('new_password_confirmation', null);

        // check the new passwords match first
        if (md5($newPass) !== md5($newPassConfirm)) {
            return [
                'new_password' => 'Passwords do not match',
                'new_password_confirmation' => 'Passwords do not match',
            ];
        }

        // make sure the old & new passwords dont match
        if (md5($newPass) === md5($oldPass)) {
            return [
                'old_password' => 'Old & New Password can\'t match',
                'new_password' => 'Old & New Password can\'t match',
            ];
        }

        // make sure its valid against current users password
        if (!Hash::check($oldPass, $user->password)) {
            return [
                'old_password' => 'Old password doesnt match one on file.',
            ];
        }

        // if all checks out, change the users password to the new one
        // password auto gets run through bcrypt() via the model attributes
        $user->hydrateFromInput(['password' => $newPass]);

        // make sure we can save
        if ($user->save() === false) {
            return $user->getErrors();
        }

        event(new UserPasswordWasChanged($user));

        return true;
    }
}
