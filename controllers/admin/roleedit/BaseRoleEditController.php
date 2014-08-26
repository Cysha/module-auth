<?php namespace Cysha\Modules\Auth\Controllers\Admin\RoleEdit;

use Cysha\Modules\Core\Controllers\BaseAdminController as BAC;
use URL;

class BaseRoleEditController extends BAC
{
    public function __construct()
    {
        parent::__construct();

        $this->objTheme->setTitle('Role Manager');
        $this->objTheme->breadcrumb()->add('Role Manager', URL::route('admin.role.index'));
    }
}
