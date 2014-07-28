<?php namespace Cysha\Modules\Users\Controllers\Admin\UserEdit;

use Cysha\Modules\BaseAdminController as BAC;
use Cysha\Modules\Users as Users;
use URL, Former;

class BaseUserEditController extends BAC {

    function __construct() {
        parent::__construct();

        $this->theme->setTitle('User Manager');
        $this->theme->breadcrumb()->add('User Manager', URL::route('admin.user.index'));
    }

    public function getUserDetails(Users\Models\User $user){

        Former::populate($user);

        return compact('user');
    }
}