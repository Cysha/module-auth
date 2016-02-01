<?php namespace Cms\Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Router as Route;

class EnforceUserActionsMiddleware {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * =
	 *
	 * @var Router
	 */
	protected $router;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth, Route $router)
	{
		$this->auth = $auth;
		$this->router = $router;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $actions = array_filter(session('actions', []));
        if (!count($actions)) {
            return $next($request);
        }

        // grab the first one and do it
        $current = session('actions');
        $key = key($current);
        $action = head($current);
        if (!empty($action)) {
            // check if we are already here
            if ($action !== null && route($action) !== $request->url()) {
                // reset the key
                \Session::forget(sprintf('actions.%', $key));
                return redirect()->route($action);
            }
        }

        $return = null;
        // check if we need to grab 2fa code
        if (session('actions.require_2fa', null) !== null) {
            $return = $this->enforce2fa();

        // check if we need to reset the password (expired/admin reset)
        } else if (session('actions.reset_pass', null) !== null) {
            $return = $this->enforcePassExpiry();
        }

        // if we got a return, return it?
        if ($return !== null) {
            return $return;
        }

        // if we get this far, great show the form
        return $next($request);
	}

	private function enforce2fa() {
		if (session('actions.require_2fa', null) !== null) {
            return;
		}

        // check to see if current user requires 2fa enabled
        if (!$this->auth->user()->require2fa) {
            return;
        }

        // if not, make sure we are on the 2fa or login route, if not log em out
        if (in_array(request()->getUri(), [
            route('pxcms.user.2fa'),
            route('pxcms.user.logout')
        ])) {
            $this->auth->logout();
            return redirect()->route('pxcms.user.login')
            	->withError(trans('auth::auth.user.2fa_bypass'));
        }
    }

	private function enforcePassExpiry() {
		if (session('actions.reset_pass', null) === null) {
            return;
		}

        // check if we are already on the pass_expired route, or trying to logout
        if (in_array(request()->getUri(), [
            route('pxcms.user.pass_expired'),
            route('pxcms.user.logout')
        ])) {
            return;
        }

        return redirect()->route('pxcms.user.pass_expired')
        	->withError(trans('auth::auth.user.pass_expired'));
	}

}
