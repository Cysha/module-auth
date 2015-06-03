<?php namespace Cms\Modules\Auth\Http\Middleware;

use Closure;
use Lock;

class HasPermissionMiddleware
{
    /**
     * Check if user has permission for this route
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // grab the action
        $actions = $request->route()->getAction();

        // make sure we have something to work with
        if (array_get($actions, 'hasPermission', null) === null) {
            \Debug::console('There is a route with `hasPermission` middleware attached, but no perms defined in the `hasPermission` action.');
            return $next($request);
        }

        $permissions = array_get($actions, 'hasPermission');
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        // roll over each permission and see if the user has it
        foreach ($permissions as $perm) {
            list($perm, $resource) = explode('@', $perm);

            // check see if the string has an id on the end
            if (strpos($resource, ':') !== false) {
                list($resource, $id) = explode(':', $resource);
                $test = Lock::cannot($perm, $resource, $id);
            } else {
                $test = Lock::cannot($perm, $resource);
            }

            // if the permission test is false, redirect back with an error
            if ($test === false) {
                return redirect()->back()
                    ->with('error', trans('auth::auth.permissions.unauthorized', [
                        'permission' => $perm, 'resource' => $resource
                    ]));
            }
        }

        return $next($request);
    }

}
