<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Core\Http\Controllers\BaseFrontendController;

class BaseController extends BaseFrontendController
{
    public function boot()
    {
        parent::boot();

        $this->setLayout('1-column');
    }


}
