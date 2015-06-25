<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Datatables\ApiKeyManager;
use Cms\Modules\Admin\Traits\DataTableTrait;
use Cms\Modules\Auth;

class ApiKeyController extends BaseUserController
{
    use DataTableTrait;

    public function manager(Auth\Models\User $user)
    {
        $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Api Keys', route('admin.user.apikey', $user->id));


        return $this->renderDataTable(with(new ApiKeyManager)->boot());
    }

    private function getDataTableHtml($data)
    {
        return $this->setView('admin.user._datatable', $data);
    }
}
