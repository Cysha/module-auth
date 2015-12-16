<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\Api;

use Cms\Modules\Auth as Auth;
use Cms\Modules\Core\Http\Controllers\BaseBackendController;
use Former;

class BaseApiController extends BaseBackendController
{
    public function boot()
    {
        parent::boot();

        $this->theme->setTitle('ApiKey Manager');
        $this->theme->breadcrumb()->add('ApiKey Manager', route('admin.apikey.manager'));
    }

    public function getApiKeyDetails(Auth\Models\ApiKey $ApiKey)
    {
        Former::populate($ApiKey);
        $this->theme->setTitle('ApiKey Manager');

        return compact('ApiKey');
    }
}
