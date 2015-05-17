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

            // only needs to have 1 missing and access is denied
            \Debug::console([
                'Checking permission:',
                [$perm, $resource],
                Lock::can($perm, $resource)
            ]);

            if (Lock::cannot($perm, $resource)) {
                return redirect()->back()
                    ->with('error', trans('auth::auth.permissions.unauthorized'));
            }
        }

        return $next($request);
    }

}
