<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Core\Http\Controllers\BaseFrontendController;
use Former;

class BaseController extends BaseFrontendController
{
    public function boot()
    {
        parent::boot();

        $this->setLayout('1-column');
    }

    public function getUserDetails() {
        $user = \Auth::user();
        Former::populate($user);
        $this->theme->setTitle('Your Control Panel');
        $this->theme->breadcrumb()->add('Your Control Panel', route('pxcms.user.dashboard'));

        return compact('user');
    }

}
