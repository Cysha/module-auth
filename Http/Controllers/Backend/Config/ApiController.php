<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Config;

use Cms\Modules\Admin\Http\Controllers\Backend\Config\BaseConfigController;

class ApiController extends BaseConfigController
{
    public function getIndex()
    {
        $this->theme->setTitle('Api Configuration');
        $this->theme->breadcrumb()->add('Api Configuration', route('admin.config.api'));

        return $this->setView('admin.config.api', [

        ], 'module');
    }
}
