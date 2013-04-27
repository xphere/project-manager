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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableProjectCommand extends AbstractProjectCommand
{
    protected function configure()
    {
        $this
            ->setName('project:enable')
            ->setAliases(array(
                'enable',
            ))
            ->setDescription('Enable a managed project')
            ->addArgument(
                'project-name',
                InputArgument::REQUIRED,
                'Alias of the project to enable'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('project-name');

        if (!$input->isInteractive()) {
            $projectName = $this->validateProject($projectName);
        }

        $this->projectManager->enableProject($projectName);

        $output->writeln("Project <info>{$projectName}</info> enabled successfully.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('dialog');

        $projectName = $input->getArgument('project-name');
        if (empty($projectName)) {
            $possibleProjects = $this->getProjectManager()->getDisabledProjects();
            if (empty($possibleProjects)) {
                throw new \RuntimeException('No projects are ready to be enabled');
            }
            $projectName = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<info>Please enter the alias of the project to enable:</info> ',
                array($this, 'validateProject'),
                false,
                null,
                $possibleProjects
            );
            $input->setArgument('project-name', $projectName);
        }
    }

    public function validateProject($projectName)
    {
        $projectManager = $this->getProjectManager();
        if (!$projectManager->hasProject($projectName)) {
            throw new \InvalidArgumentException("Project not known: {$projectName}");
        }

        if ($projectManager->isProjectEnabled($projectName)) {
            throw new \InvalidArgumentException("Project {$projectName} is enabled already");
        }

        return $projectName;
    }
}
