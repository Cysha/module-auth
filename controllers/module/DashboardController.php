<?php namespace Cysha\Modules\Auth\Controllers\Module;

use Cysha\Modules\Auth\Controllers\AuthBaseController;
use Cysha\Modules\Auth as PXAuth;
use Redirect;

class DashboardController extends AuthBaseController
{

    public function getDashboard()
    {

        return Redirect::to('/');
    }

}
