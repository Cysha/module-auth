<?php

namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Auth\Models\ApiKey;
use Dingo\Api\Auth\Provider\Provider;
use Dingo\Api\Routing\Route;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiAuthenticationProvider implements Provider
{
    protected $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function authenticate(Request $request, Route $route)
    {
        $apiKey = $this->getAuthToken($request);
        $keyRow = with(new ApiKey())->keyExists($apiKey);

        if (!($keyRow instanceof ApiKey)) {
            throw new UnauthorizedHttpException('AuthToken', 'Invalid authentication credentials.');
        }

        $driver = $this->auth->driver();

        return $driver->loginUsingId($keyRow->user_id);
    }

    protected function getAuthToken($request)
    {
        $token = $request->header('X-Auth-Token');

        if (empty($token)) {
            $token = $request->input('auth_token');
        }

        return $token;
    }

    public function getAuthorizationMethod()
    {
        return 'auth_token';
    }
}
