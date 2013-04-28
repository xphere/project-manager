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

class AddProjectCommand extends AbstractProjectCommand
{
    protected function configure()
    {
        $this
            ->setName('project:add')
            ->setAliases(array(
                'add',
            ))
            ->setDescription('Start managing a new project')
            ->addArgument(
                'project-name',
                InputArgument::REQUIRED,
                'New alias to refer to the project'
            )
            ->addArgument(
                'project-path',
                InputArgument::OPTIONAL,
                'Path to the project public directory'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('project-name');
        $projectPath = $input->getArgument('project-path');

        if (!$input->isInteractive()) {
            $projectName = $this->validateName($projectName);
            $projectPath = $this->validatePath($projectPath);
        }

        $this->getProjectManager()->createProject($projectName, $projectPath);
        $output->writeln("Project <info>{$projectName}</info> added successfully.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var $dialog DialogHelper */
        $dialog = $this->getHelper('dialog');

        $projectPath = $input->getArgument('project-path');
        if (empty($projectPath)) {
            $projectPath = $dialog
                ->question('Please enter the path of the project public directory')
                ->defaultsTo(getcwd())
                ->validateWith(array($this, 'validatePath'))
                ->ask($output);
            $input->setArgument('project-path', $projectPath);
        } else {
            $output->writeln("Project path is <info>{$projectPath}</info>");
        }

        $projectName = $input->getArgument('project-name');
        if (empty($projectName)) {
            $projectName = $dialog
                ->question('Please enter the alias of the project')
                ->defaultsTo(basename($projectPath))
                ->validateWith(array($this, 'validateName'))
                ->ask($output);
            $input->setArgument('project-name', $projectName);
        } else {
            $output->writeln("Project name is <info>{$projectName}</info>");
        }
    }

    public function validateName($projectName)
    {
        if ($this->getProjectManager()->hasProject($projectName)) {
            throw new \InvalidArgumentException("Project '{$projectName}' already exists");
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
