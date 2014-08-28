<?php namespace Cysha\Modules\Auth\Controllers\Admin\UserEdit;

use Cysha\Modules\Auth as Auth;
use Former;
use URL;
use Redirect;
use Input;

class PasswordController extends BaseUserEditController
{
    public function getForm(Auth\Models\User $objUser)
    {
        $data = $this->getUserDetails($objUser);
        $this->objTheme->setTitle('User Manager <small>> '.$objUser->username.' > Edit</small>');

        return $this->setView('user.admin.password', $data, 'module');
    }

    public function postForm(Auth\Models\User $objUser)
    {
        $input = Input::only(['password', 'password_confirmation']);

        $objUser->hydrateFromInput($input);

        if ($objUser->save() === false) {
            return Redirect::back()->withErrors($objUser->getErrors());
        }

        return Redirect::route('admin.user.password', $objUser->id)->withInfo('Password Updated');
    }
}
