<?php namespace Cysha\Modules\Auth;

use Dingo\Api\Auth\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Manager;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Cysha\Modules\Auth\Models\ApiKey;

class ApiAuthenticationProvider extends Provider {

    protected $auth;

    public function __construct(Manager $auth)
    {
        $this->auth = $auth;
    }

    public function authenticate(Request $request, Route $route)
    {
        $apiKey = $this->getAuthToken($request);
        $keyRow = with(new ApiKey)->keyExists($apiKey);

        if (!($keyRow instanceof ApiKey)) {
            throw new UnauthorizedHttpException('AuthToken', 'Invalid authentication credentials.');
        }

        $driver = $this->auth->driver();
        return $driver->loginUsingId($keyRow->user_id);
    }

    protected function getAuthToken($request) {
        $token = $request->header('X-Auth-Token');

        if(empty($token)) {
            $token = $request->input('auth_token');
        }

        return $token;
    }

    public function getAuthorizationMethod()
    {
        return 'auth_token';
    }
}
