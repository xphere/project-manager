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

class Application extends BaseApplication
{
    const NAME    = 'Project Manager Application';
    const VERSION = '0.1';

    public function __construct(ProjectManager $projectManager)
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->getHelperSet()->set(new Command\Helper\DialogHelper());

        $this->addCommands(array(
            new Command\AddProjectCommand($projectManager),
            new Command\RemoveProjectCommand($projectManager),
            new Command\EnableProjectCommand($projectManager),
            new Command\DisableProjectCommand($projectManager),
        ));
    }
}
