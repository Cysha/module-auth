<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Config;

use Cms\Modules\Admin\Http\Controllers\Backend\Config\BaseConfigController;

class AuthController extends BaseConfigController
{
    public function getIndex()
    {
        $this->theme->setTitle('Authentication Configuration');
        $this->theme->breadcrumb()->add('Authentication Configuration', route('admin.config.authentication'));

        return $this->setView('admin.config.authentication', [

        ], 'module');
    }
}
