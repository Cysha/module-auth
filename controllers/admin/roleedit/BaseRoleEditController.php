<?php namespace Cysha\Modules\Users\Controllers\Admin\RoleEdit;

use Cysha\Modules\BaseAdminController as BAC;
use URL;

class BaseRoleEditController extends BAC {
    
    function __construct() {
        parent::__construct();

        $this->theme->setTitle('Role Manager');
        $this->theme->breadcrumb()->add('Role Manager', URL::route('admin.role.index'));
    }
}