<?php namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Core\Http\Controllers\BaseAdminController;
use Cms\Modules\Admin\Traits\DataTableTrait;

class PermissionManagerController extends BaseAdminController
{
    use DataTableTrait;

    public function permissionManager()
    {
        return $this->renderDataTable('cms.auth.datatable.permission-manager');
    }

}
