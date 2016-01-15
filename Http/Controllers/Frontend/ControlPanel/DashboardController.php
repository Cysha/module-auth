<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

class DashboardController extends BaseController
{

    public function getIndex()
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Dashboard', '#');

        return $this->setView('controlpanel.dashboard', [
            'user' => \Auth::user()->transform(),
        ]);
    }

    public function getProfile($user)
    {
        return $this->setView('controlpanel.user', [
            'user' => $user->transform(),
        ]);
    }

}
