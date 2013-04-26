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
    public function getProject($name)
    {
        if ($this->hasProject($name)) {
            return new MaterializedProject($name);
        }
        return new TemporaryProject($name);
    }

    public function hasProject()
    {
    }

    protected function getPath($name)
    {
        return
    }
}
