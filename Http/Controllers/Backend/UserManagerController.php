<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Admin\Traits\DataTableTrait;
use Cms\Modules\Auth\Datatables\UserManager;
use Cms\Modules\Core\Http\Controllers\BaseBackendController;

class UserManagerController extends BaseBackendController
{
    use DataTableTrait;

    public function userManager()
    {
        return $this->renderDataTable(with(new UserManager())->boot());
    }
}
