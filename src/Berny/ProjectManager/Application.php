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

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    const NAME    = 'Project Manager Application';
    const VERSION = '0.1';

    public function __construct($path)
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->addCommands(array(
            new Command\AddProjectCommand($path),
        ));
    }
}
