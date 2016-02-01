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
		\Debug::console('attempting to enforce actions');
		if (session('actions', null) === null) {
            return $next($request);
		}

        // grab the first one and do it
        $action = head(session('actions'));
        if ($action !== null) {
            \Debug::console(['first one', $action]);

            // check if we are already here
            if (route($action) !== $request->url()) {
                return redirect()->route($action);
            }
        }
        if (session('actions.require_2fa', null) !== null) {
            $return = $this->enforce2fa();
            if ($return !== null) {
                return $return;
            }
        }
        if (session('actions.reset_pass', null) !== null) {
            $return = $this->enforcePassExpiry();
            if ($return !== null) {
                return $return;
            }
        }



		\Debug::console('no actions, continuing...');
        return $next($request);
	}

	private function enforce2fa() {
		// check if 2fa has already been verified
		if (session('actions.require_2fa', false) !== true) {
            return;
		}

        // check to see if current user requires 2fa enabled
        if (!$this->auth->user()->require2fa) {
            return;
        }

        // check if we have the session
        if (session('actions.require_2fa', false) === true) {
            // if not, make sure we are on the 2fa or login route, if not log em out
            if (!$this->router->is('pxcms.user.2fa') || $this->router->is('pxcms.user.login')) {
                $this->auth->logout();
                return redirect()->route('pxcms.pages.login')
                	->withError(trans('auth::auth.user.2fa_bypass'));
            }
        }
    }

	private function enforcePassExpiry() {
		if (session('actions.reset_pass', null) === null) {
            return;
		}

        if ($this->router->is('pxcms.user.pass_expired')) {
            return;
        }

        if (!$this->router->is('pxcms.user.pass_expired') || $this->router->is('pxcms.user.login')) {
            dd([$this->router->is('pxcms.user.pass_expired')]);
            return redirect()->route('pxcms.user.pass_expired')
            	->withError(trans('auth::auth.user.pass_expired'));
        }
	}

}
