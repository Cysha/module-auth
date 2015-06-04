<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as RoleRepo;
use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class RoleController extends BaseUserController
{

    public function getForm(Auth\Models\User $user, RoleRepo $roles)
    {
        $this->theme->asset()->add('multiselect-css', '/modules/auth/multiselect/css/multi-select.css', 'app.css');
        $this->theme->asset()->add('multiselect-js', '/modules/auth/multiselect/js/jquery.multi-select.js', 'all.js');

        $this->theme->asset()->container('footer')->writeScript('make-multi', "jQuery(function () { jQuery('#roles').multiSelect(); });", ['multiselect-js']);

        $data = $this->getUserDetails($user);

        // grab the roles
        $data['roles'] = $roles->all();

        // see which ones this user has
        $data['selected'] = [];
        foreach ($user->roles as $role) {
            $data['selected'][] = $role->id;
        }

        return $this->setView('admin.user.role', $data);
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->get('roles');

        $roles = [];
        foreach ($input as $role) {
            $roles[$role] = ['caller_type' => $user->getCallerType()];
        }

        $user->roles()->sync($roles);

        return redirect()->back()->withInfo('Roles Updated');
    }
}
