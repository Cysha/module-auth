<?php namespace Cysha\Modules\Auth\Controllers\Admin\UserEdit;

use Cysha\Modules\Core\Controllers\BaseAdminController as BAC;
use Cysha\Modules\Auth as Auth;
use URL, Former;

class BaseUserEditController extends BAC
{
    public function __construct()
    {
        parent::__construct();

        $this->objTheme->setTitle('User Manager');
        $this->objTheme->breadcrumb()->add('User Manager', URL::route('admin.user.index'));
    }

    public function getUserDetails(Auth\Models\User $user)
    {
        Former::populate($user);

        return compact('user');
    }
}
