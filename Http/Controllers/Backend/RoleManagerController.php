<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Admin\Traits\DataTableTrait;
use Cms\Modules\Auth\Datatables\RoleManager;
use Cms\Modules\Core\Http\Controllers\BaseBackendController;

class RoleManagerController extends BaseBackendController
{
    use DataTableTrait;

    public function roleManager()
    {
        return $this->renderDataTable(with(new RoleManager())->boot());
    }
}
