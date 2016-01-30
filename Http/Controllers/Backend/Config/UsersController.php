<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Config;

use Cms\Modules\Admin\Http\Controllers\Backend\Config\BaseConfigController;

class UsersController extends BaseConfigController
{
    public function getIndex()
    {
        $this->theme->setTitle('Users Configuration');
        $this->theme->breadcrumb()->add('Users Configuration', route('admin.config.users'));

        return $this->setView('admin.config.users', [

        ], 'module');
    }
}
