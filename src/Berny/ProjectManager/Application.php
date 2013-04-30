<?php

/*
 * This file is part of the Berny\Project-Manager package
 *
 * (c) Berny Cantos <be@rny.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Berny\ProjectManager;

use Berny\ProjectManager\Manager\ProjectManager;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    const NAME    = 'Project Manager Application';
    const VERSION = '0.1';

    private $projectManager;

    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
        parent::__construct(static::NAME, static::VERSION);
    }

    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
              new Command\AddProjectCommand($this->projectManager),
              new Command\RemoveProjectCommand($this->projectManager),
              new Command\EnableProjectCommand($this->projectManager),
              new Command\DisableProjectCommand($this->projectManager),
              new Command\ListProjectCommand($this->projectManager),
            )
        );
    }

    protected function getDefaultHelperSet()
    {
        $helperSet = parent::getDefaultHelperSet();
        $helperSet->set(new Command\Helper\DialogHelper());
        return $helperSet;
    }
}
