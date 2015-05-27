<?php namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Core\Http\Controllers\BaseBackendController;
use Cms\Modules\Admin\Traits\DataTableTrait;

class UserManagerController extends BaseBackendController
{
    use DataTableTrait;

    public function userManager()
    {
        return $this->renderDataTable('cms.auth.datatable.user-manager');
    }

}
