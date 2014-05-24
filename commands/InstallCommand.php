<?php namespace Cysha\Modules\Auth\Commands;

use Cysha\Modules\Core\Commands\BaseCommand;

class InstallCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms.modules.auth:install';

    /**
     * The Readable Module Name.
     *
     * @var string
     */
    protected $readableName = 'Auth Module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the Auth Module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $packages = array(
            'toddish/verify' => array(
                'name'      => 'Verify',
                'seedclass' => 'VerifyUserSeeder',
                'migrate'   => true,
                'seed'      => false,
                'config'    => true,
            ),
        );

        $this->install($packages);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
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
