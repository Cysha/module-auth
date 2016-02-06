<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Admin\Traits\DataTableTrait;
use Cms\Modules\Auth\Datatables\UserManager;
use Cms\Modules\Auth\Http\Requests\BackendAddUserToRoleRequest;
use Cms\Modules\Auth\Models\Role;
use Cms\Modules\Auth\Models\User;

class UserController extends BaseRoleController
{
    use DataTableTrait;

    public function manager()
    {
        return $this->renderDataTable(with(new UserManager)->boot());
    }

    public function getDataTableHtml($data) {
        return $this->setView('admin.role.users', $data);
    }

    public function postAddUser(Role $role, BackendAddUserToRoleRequest $input) {

        // find user
        $authModel = config('auth.model');
        $user = with(new $authModel)
            ->with(['roles'])
            ->where('username', $input->get('username'))
            ->first();

        if ($user === null) {
            return redirect()->back()
                ->withError('Could not find any user by that username');
        }

        // check that the user isnt already in there
        foreach ($user->roles as $row) {
            if ($row->id === $role->id) {
                return redirect()->back()
                    ->withError('User is already a member of this role.');
            }
        }

        // attach role to user
        $user->roles()->attach(
            $role->id,
            ['caller_type' => 'auth_user']
        );

        // save & redirect back

        return redirect()->back()
            ->withInfo('User was successfully added to this role!');
    }

    public function deleteRemoveUser(Role $role, User $user) {

        $user->roles()->detach($role->id);

        if (request()->ajax()) {
            return request()->json('success');
        } else {
            return redirect()->back()
                ->withInfo('User removed successfully.');
        }
    }
}
