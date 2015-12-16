<?php

namespace Cms\Modules\Auth\Http\Controllers\Api\V1;

use Auth;
use Cms\Modules\Core\Http\Controllers\BaseApiController;

class PagesController extends BaseApiController
{
    public function getUser()
    {
        if (!$this->auth->check()) {
            return $this->sendError('User is not currently authenticated', 500);
        }

        return $this->sendResponse('ok', 200, $this->auth->user()->transform());
    }
}
