<?php namespace Cysha\Modules\Auth\Commands;

use Cysha\Modules\Core\Commands\BaseCommand;

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
        $this->info('This still needs to be implemented..');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('username', InputArgument::REQUIRED, 'Username'),
            array('email', InputArgument::REQUIRED, 'Email Address'),
            array('password', InputArgument::REQUIRED, 'Password'),
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
