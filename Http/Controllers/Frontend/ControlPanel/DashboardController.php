<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

class DashboardController extends BaseController
{

    public function getIndex()
    {
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
