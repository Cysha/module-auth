<?php namespace Cms\Modules\Auth\Services;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepository;
use Cms\Modules\Auth\Models\UserProvider;

/**
 * Class Registrar
 */
class Registrar implements RegistrarContract
{
    /**
     * @var Socialite
     */
    private $socialite;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @param Socialite $socialite
     * @param Guard $auth
     * @param UserRepository $user
     */
    public function __construct(Socialite $socialite, Guard $auth, UserRepository $user)
    {
        $this->socialite = $socialite;
        $this->user = $user;
        $this->auth = $auth;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return \Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        return $this->user->create($data);
    }

    /**
     * @param $request
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginThirdParty($request, $provider, $redirectPath)
    {
        if (!$request) {
            return $this->getAuthorizationFirst($provider);
        }
        $user = $this->getOrCreateUser($provider);
        $this->auth->login($user, true);
        return redirect()->intended($redirectPath);
    }

    private function getOrCreateUser($provider)
    {
        $socialiteUser = $this->getSocialUser($provider);

        $user = $this->user->where('email', $socialiteUser->email)->first();
        if ($user === null) {
            $user = $this->user->create([
                'email' => $socialiteUser->email,
            ]);
        }

        if (!$user->hasProvider($provider)) {
            with(new UserProvider)->fill([
                'avatar'      => $socialiteUser->avatar,
                'user_id'     => $user->id,
                'provider'    => $provider,
                'provider_id' => $socialiteUser->id,
            ])->save();
        }

        return $user;
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getAuthorizationFirst($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * @param $provider
     * @return \Laravel\Socialite\Contracts\User
     */
    private function getSocialUser($provider)
    {
        return $this->socialite->driver($provider)->user();
    }
}
