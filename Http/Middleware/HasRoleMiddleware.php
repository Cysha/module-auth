<?php namespace Cms\Modules\Auth\Http\Middleware;

use Closure;
use Auth;

class HasRoleMiddleware
{
    /**
     * Check if user is in group/groups
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
        if (!array_key_exists('hasRole', $actions)) {
            return $next($request);
        }

        // make sure we have something to work with
        if (array_get($actions, 'hasRole', null) === null) {
            \Debug::console('There is a route with `hasRole` middleware attached, but no roles defined in the `hasRole` action.');
            return $next($request);
        }

        $roles = array_get($actions, 'hasRole');
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        // get the user
        $user = Auth::user();

        // roll over each role and see if the user has it
        foreach ($roles as $role) {
            // only needs to have 1 missing and access is denied
            if (!$user->hasRole($role)) {
                return redirect()->back()
                    ->with('error', trans('auth::auth.permissions.unauthorized'));
            }
        }

        return $next($request);
    }

}
