<?php namespace Cysha\Modules\Users\Controllers\Admin\UserEdit;

use Cysha\Modules\Users as Users;
use Former, URL, Redirect;

class UserController extends BaseUserEditController {

    public function getEdit(Users\Models\User $objUser){
        $data = $this->getUserDetails($objUser);
        $this->theme->setTitle('User Manager <small>> '.$objUser->username.' > Edit</small>');

        return $this->setView('user.admin.users', $data, 'module');
    }

    public function postEdit(Users\Models\User $objUser){
        $objUser->hydrateFromInput();

        if( $objUser->save() === false ){
            return Redirect::back()->withErrors($objUser->getErrors());
        }

        return Redirect::route('admin.user.edit', $objUser->id)->withInfo('User Updated');
    }

    public function getDelete(Users\Models\User $objUser){

    }

    public function getPermissions(Users\Models\User $objUser){

    }

}