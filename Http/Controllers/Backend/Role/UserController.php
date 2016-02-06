<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Auth\Datatables\UserManager;
use Cms\Modules\Admin\Traits\DataTableTrait;

class UserController extends BaseRoleController
{
    use DataTableTrait;

    public function manager()
    {
        return $this->renderDataTable(with(new UserManager)->boot());
    }

    public function getDataTableHtml($data) {
        return $this->setView('admin.role._datatable', $data);
    }
}
