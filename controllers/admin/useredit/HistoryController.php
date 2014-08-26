<?php namespace Cysha\Modules\Auth\Controllers\Admin\UserEdit;

use Cysha\Modules\Auth as Auth;
use Former, URL;

class HistoryController extends BaseUserEditController
{
    public function getHistory(Auth\Models\User $objUser)
    {
        $data = $this->getUserDetails($objUser);
        $this->objTheme->setTitle('User Manager <small>> '.$objUser->username.' > Edit</small>');

        return $this->setView('user.admin.history', $data, 'module');
    }


}
