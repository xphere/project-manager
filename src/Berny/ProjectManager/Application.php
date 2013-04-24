<?php

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
        $this->addCommands(array(
            new Command\AddProjectCommand($path),
        ));

        parent::__construct(static::NAME, static::VERSION);
    }
}
