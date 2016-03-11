<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\Api;

use BeatSwitch\Lock\Integrations\Laravel\Facades\Lock;
use Cms\Modules\Auth as Auth;
use Illuminate\Http\Request;

class RemoveController extends BaseApiController
{
    public function deleteApiKey(Auth\Models\ApiKey $key, Request $input)
    {
        if (Lock::cannot('apikey.delete', 'auth_user')) {
            return $this->sendMessage('You do not have permissions.', 401);
        }

        $delete = $key->delete();
        if ($delete === false) {
            return $this->sendMessage('API Key was not removed.', 200);
        }

        return $this->sendMessage('API Key was removed successfully.', 200);
    }
}
