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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveProjectCommand extends AbstractProjectCommand
{
    protected function configure()
    {
        $this
            ->setName('project:remove')
            ->setAliases(array(
                'remove',
            ))
            ->setDescription('Removes a project from the manager')
            ->addArgument(
                'project-name',
                InputArgument::REQUIRED,
                'Alias of the prohect to remove'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('project-name');

        if (!$input->isInteractive()) {
            $projectName = $this->validateName($projectName);

        } else if (!$this->getHelper('dialog')->askConfirmation($output, "<info>Do you confirm removal of {$projectName} project?</info> <comment>(Y/n)</comment> ")) {
            throw new \RuntimeException('Command aborted');
        }

        $this->getProjectManager()->removeProject($projectName);
        $output->writeln("Project <info>{$projectName}</info> removed successfully.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var $dialog DialogHelper */
        $dialog = $this->getHelper('dialog');

        $projectName = $input->getArgument('project-name');
        if (empty($projectName)) {
            $possibleProjects = $this->getProjectManager()->getProjects();
            if (empty($possibleProjects)) {
                throw new \RuntimeException('No projects managed yet');
            }
            $projectName = $dialog
                ->question('Please enter the alias of the project to remove')
                ->validateWith(array($this, 'validateName'))
                ->autocomplete($possibleProjects)
                ->ask($output);
            $input->setArgument('project-name', $projectName);
        }
    }

    public function validateName($projectName)
    {
        if (!$this->getProjectManager()->hasProject($projectName)) {
            throw new \InvalidArgumentException("Project '{$projectName}' does not exist");
        }
        return $projectName;
    }

    public function validatePath($projectPath)
    {
        if (!is_dir($projectPath)) {
            throw new \InvalidArgumentException("The path '{$projectPath}' does not exist");
        }
        return $projectPath;
    }
}
