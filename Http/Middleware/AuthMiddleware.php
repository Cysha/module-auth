<?php namespace Cms\Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\Store as Session;
use Illuminate\Routing\Router as Route;

class AuthMiddleware {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * =
	 *
	 * @var Session
	 */
	protected $session;

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
	public function __construct(Guard $auth, Session $session, Route $router)
	{
		$this->auth = $auth;
		$this->session = $session;
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
		// if they arent logged in... redirect em out
		if ($this->auth->guest()) {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				return redirect()->guest(route('pxcms.user.login'));
			}
		} else {
			return $this->enforce2fa();
		}

		return $next($request);
	}


	private function enforce2fa() {
        // check to see if current user has 2fa enabled
        if (!$this->auth->user()->has2fa) {
            return $next($request);
        }

        // check if we have the session
        if ($this->session->has('verified_2fa', false) !== true) {
            // if not, make sure we are on the 2fa or login route, if not log em out
            if (!$this->router->is('pxcms.user.2fa') || $this->router->is('pxcms.user.login')) {
                $this->auth->logout();
                return redirect()->route('pxcms.pages.home')->withError(trans('auth::auth.user.2fa_bypass'));
            }
        }
	}

}
