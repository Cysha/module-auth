<?php namespace Cysha\Modules\Users\Controllers\Admin\UserEdit;

use Cysha\Modules\Users as Users;
use Former, URL;

class HistoryController extends BaseUserEditController {

    public function getHistory(Users\Models\User $objUser){
        $data = $this->getUserDetails($objUser);
        $this->theme->setTitle('User Manager <small>> '.$objUser->username.' > Edit</small>');

        return $this->setView('user.admin.history', $data, 'module');
    }


}