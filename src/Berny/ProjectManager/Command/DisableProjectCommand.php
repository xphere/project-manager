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

use Berny\ProjectManager\Command\Helper\DialogHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableProjectCommand extends AbstractProjectCommand
{
    protected function configure()
    {
        $this
            ->setName('project:disable')
            ->setAliases(array(
                'disable',
            ))
            ->setDescription('Disable a managed project')
            ->addArgument(
                'project-name',
                InputArgument::REQUIRED,
                'Alias of the project to disable'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('project-name');

        if (!$input->isInteractive()) {
            $projectName = $this->validateProject($projectName);
        }

        $this->projectManager->disableProject($projectName);

        $output->writeln("Project <info>{$projectName}</info> disabled successfully.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var DialogHelper $dialog */
        $dialog = $this->getHelper('dialog');

        $projectName = $input->getArgument('project-name');
        if (empty($projectName)) {
            $possibleProjects = $this->getProjectManager()->getEnabledProjects();
            if (empty($possibleProjects)) {
                throw new \RuntimeException('There are no enabled projects');
            }
            $projectName = $dialog
                ->question('Please enter the alias of the project to disable')
                ->validateWith(array($this, 'validateProject'))
                ->autocomplete($possibleProjects)
                ->ask($output);
            $input->setArgument('project-name', $projectName);
        }
    }

    public function validateProject($projectName)
    {
        if ($projectName === '') {
            throw new \InvalidArgumentException("Project name can't be empty");
        }

        $projectManager = $this->getProjectManager();
        if (!$projectManager->hasProject($projectName)) {
            throw new \InvalidArgumentException("Project '{$projectName}' not known");
        }

        if ($projectManager->isProjectDisabled($projectName)) {
            throw new \InvalidArgumentException("Project '{$projectName}' is disabled already");
        }

        return $projectName;
    }
}
