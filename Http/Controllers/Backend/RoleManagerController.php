<?php namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Core\Http\Controllers\BaseAdminController;
use Cms\Modules\Admin\Traits\DataTableTrait;

class RoleManagerController extends BaseAdminController
{
    use DataTableTrait;

    public function roleManager()
    {
        return $this->renderDataTable('cms.auth.datatable.role-manager');
    }

}
