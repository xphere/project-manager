<?php

/*
 * This file is part of the Berny\Project-Manager package
 *
 * (c) Berny Cantos <be@rny.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Berny\ProjectManager\Manager;

class ProjectManager
{
    private $path;

    public function __construct($path)
    {
        $this->path = (string) $path;
    }

    public function hasProject($projectName)
    {
        return file_exists($this->getProjectFilename($projectName));
    }

    public function createProject($projectName, $projectPath)
    {
        if (@symlink($projectPath, $this->getProjectFilename($projectName)) === false) {
            throw new \RuntimeException("Can't create project {$projectName} to {$projectPath}");
        };
    }

    protected function getProjectFilename($projectName)
    {
        return "{$this->path}/projects/{$projectName}.project";
    }
}
