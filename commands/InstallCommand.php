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
                'seed'      => true,
                'config'    => true,
            ),
        );

        foreach ($packages as $pkg => $settings) {
            if (array_get($settings, 'migrate', false) == true && !empty($pkg)) {
                $this->comment(sprintf('Migrating %s Package...', array_get($settings, 'name', '')));
                $this->call('migrate', array('--package' => $pkg));
            }

            if (array_get($settings, 'seed', false) == true && array_get($settings, 'seedclass', false) !== false) {
                $this->comment(sprintf('Seeding %s Package...', array_get($settings, 'name', '')));
                $this->call('db:seed', array('--class' => array_get($settings, 'seedclass')));
            }

            if (array_get($settings, 'config', false) == true && !empty($pkg)) {
                $this->comment(sprintf('Publishing %s Config...', array_get($settings, 'name', '')));
                $this->call('config:publish', array('package' => $pkg));
            }

        }
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
