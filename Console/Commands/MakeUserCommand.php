<?php namespace Cms\Modules\Auth\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Cms\Modules\Core\Console\Commands\BaseCommand;
use Carbon\Carbon;

class MakeUserCommand extends BaseCommand
{
    protected $name = 'make:user';
    protected $readableName = 'User Generator';
    protected $description = 'Create a new CMS user';

    public function fire()
    {
        $userInfo = [
            'username'   => $this->argument('username'),
            'email'      => $this->argument('email'),
            'password'   => $this->argument('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // we are missing some information, try and get it
        if (in_array(null, $userInfo) === true) {
            $this->info('You are missing some information to create this user.');

            if (!array_get($userInfo, 'username', false)) {
                $userInfo['username'] = $this->ask('Username? ');
            }

            if (!array_get($userInfo, 'email', false)) {
                $userInfo['email'] = $this->ask('Email? ');
            }

            if (!array_get($userInfo, 'password', false)) {
                $userInfo['password'] = $this->secret('Password? ');
            }
        }

        if (in_array(null, $userInfo) === true) {
            $this->info('The information required could not be gathered. Please try again.');
            return;
        }

        $authModel = config('auth.model');
        $user = with(new $authModel);
        $user->fill($userInfo);
        $save = $user->save();

        if ($save) {
            $this->info('User registered successfully');
            return;
        }

        $this->warn('User was not registered');
    }

    protected function getArguments()
    {
        return array(
            array('username',   InputArgument::OPTIONAL, 'Username to register this user with.'),
            array('email',      InputArgument::OPTIONAL, 'Users email address.'),
            array('password',   InputArgument::OPTIONAL, 'Password to set.'),
        );
    }

}
