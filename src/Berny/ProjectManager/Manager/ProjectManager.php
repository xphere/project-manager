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
        }
    }

    public function removeProject($projectName)
    {
        if (@unlink($this->getProjectFilename($projectName)) === false) {
            throw new \RuntimeException("Can't remove project {$projectName}");
        }
    }

    public function enableProject($projectName)
    {
        if (@symlink($this->getEnabledProjectLink($projectName), $this->getEnabledProjectFilename($projectName)) === false) {
            throw new \RuntimeException("Can't enable project {$projectName}");
        }
    }

    public function disableProject($projectName)
    {
        if (@unlink($this->getEnabledProjectFilename($projectName)) === false) {
            throw new \RuntimeException("Can't disable project {$projectName}");
        }
    }

    public function getPath($projectName)
    {
        return readlink($this->getProjectFilename($projectName));
    }

    public function getProjects($callback = null)
    {
        /** @var $callback callable */
        $projects = array();
        /** @var $project \FilesystemIterator */
        foreach ($this->getProjectFiles() as $project) {
            if ($project->isLink()) {
                $projectName = $project->getBasename('.project');
                if (!$callback || $callback($projectName) !== false) {
                    $projects[] = $projectName;
                }
            }
        }
        return $projects;
    }

    public function getDisabledProjects()
    {
        return $this->getProjects(array($this, 'isProjectDisabled'));
    }

    public function getEnabledProjects()
    {
        return $this->getProjects(array($this, 'isProjectEnabled'));
    }

    public function isProjectEnabled($projectName)
    {
        return file_exists($this->getEnabledProjectFilename($projectName));
    }

    public function isProjectDisabled($projectName)
    {
        return !$this->isProjectEnabled($projectName);
    }

    protected function getProjectFilename($projectName)
    {
        return "{$this->path}/projects/{$projectName}.project";
    }

    protected function getEnabledProjectLink($projectName)
    {
        return "../projects/{$projectName}.project";
    }

    protected function getEnabledProjectFilename($projectName)
    {
        return "{$this->path}/public/{$projectName}.devel";
    }

    /**
     * @return \FilesystemIterator
     */
    protected function getProjectFiles()
    {
        return new \FilesystemIterator($this->path . '/projects');
    }
}
