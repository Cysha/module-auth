<?php namespace Cysha\Modules\Auth\Controllers\Admin\UserEdit;

use Cysha\Modules\Auth as Auth;
use Former, URL, Redirect;

class UserController extends BaseUserEditController
{
    public function getEdit(Auth\Models\User $objUser)
    {
        $data = $this->getUserDetails($objUser);
        $this->objTheme->setTitle('User Manager <small>> '.$objUser->username.' > Edit</small>');

        return $this->setView('user.admin.users', $data, 'module');
    }

    public function postEdit(Auth\Models\User $objUser)
    {
        $objUser->hydrateFromInput();

        if( $objUser->save() === false ){
            return Redirect::back()->withErrors($objUser->getErrors());
        }

        return Redirect::route('admin.user.edit', $objUser->id)->withInfo('User Updated');
    }

    public function getDelete(Auth\Models\User $objUser)
    {
    }

    public function getPermissions(Auth\Models\User $objUser)
    {
    }

}
