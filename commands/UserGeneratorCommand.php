<?php namespace Cysha\Modules\Auth\Commands;

use Cysha\Modules\Core\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UserGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms.user:generate';

    /**
     * The Readable Module Name.
     *
     * @var string
     */
    protected $readableName = 'User Generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new CMS user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $userInfo = [
            'username' => $this->argument('username'),
            'email'    => $this->argument('email'),
            'password' => $this->argument('password'),
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

        $event = \Event::fire('auth.user.register', array($userInfo));

        if ($event[0] instanceof \Cysha\Modules\Auth\Models\User) {
            $this->info('User registered successfully');
            return;
        }

        $this->warn('User was not registered');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('username',   InputArgument::OPTIONAL, 'Username to register this user with.'),
            array('email',      InputArgument::OPTIONAL, 'Users email address.'),
            array('password',   InputArgument::OPTIONAL, 'Password to set.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

}
