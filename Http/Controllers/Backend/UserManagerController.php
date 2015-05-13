<?php namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Core\Http\Controllers\BaseAdminController;
use Cms\Modules\Admin\Traits\DataTableTrait;

class UserManagerController extends BaseAdminController
{
    use DataTableTrait;

    public function userManager()
    {
        return $this->renderDataTable('cms.auth.datatable.user-manager');
    }

}
