<?php

/*
 * This file is part of the Berny\Project-Manager package
 *
 * (c) Berny Cantos <be@rny.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Berny\ProjectManager\Command;

use Berny\ProjectManager\Manager\ProjectManager;
use Symfony\Component\Console\Command\Command;

abstract class AbstractProjectCommand extends Command
{
    protected $projectManager;

    public function __construct(ProjectManager $projectManager)
    {
        parent::__construct();
        $this->projectManager = $projectManager;
    }

    /**
     * @return ProjectManager
     */
    protected function getProjectManager()
    {
        return $this->projectManager;
    }
}