<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Core\Http\Controllers\BaseFrontendController;

class BaseController extends BaseFrontendController
{
    public function boot()
    {
        parent::boot();

        $this->setLayout('1-column');
    }

    public function getUserDetails() {
        $data = [
            'user' => \Auth::user()->transform(),
        ];
        $this->theme->setTitle('Control Panel');
        $this->theme->breadcrumb()->add('Control Panel', route('pxcms.user.dashboard'));

        return $data;
    }

}
