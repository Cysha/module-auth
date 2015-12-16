<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Admin\Traits\DataTableTrait;
use Cms\Modules\Auth\Datatables\ApiKeyManager;
use Cms\Modules\Core\Http\Controllers\BaseBackendController;

class ApiManagerController extends BaseBackendController
{
    use DataTableTrait;

    public function manager()
    {
        return $this->renderDataTable(with(new ApiKeyManager())->boot());
    }
}
