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

class AddProjectCommand extends Command
{
    protected $installedPath;

    public function __construct($installedPath)
    {
        $this->installedPath = $installedPath;
        parent::__construct();
    }

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

        if ($input->isInteractive()) {
            $dialog = $this->getHelper('dialog');
            if (!$dialog->askConfirmation($output, '<info>Do you confirm generation?</info> (Y/n) ', true)) {
                $output->writeln('<error>Command aborted</error>');
                return 1;
            }
        } else {
            if (!is_dir($projectPath)) {
                throw new \InvalidArgumentException("The project path '{$projectPath}' does not exist");
            }
            if (file_exists("{$this->installedPath}/projects/{$projectName}.project")) {
                throw new \InvalidArgumentException("A project named '{$projectName}' already exists");
            }
        }

        /** TODO: Add project to project-list */

        $output->writeln("Project <info>{$projectName}</info> added successfully.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('dialog');

        $projectPath = $input->getArgument('project-path');
        if (empty($projectPath)) {
            $defaultPath = getcwd();
            $projectPath = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<info>Please enter the path of the project public directory:</info> [' . $defaultPath . '] ',
                function ($answer) {
                    if (!is_dir($answer)) {
                        throw new \InvalidArgumentException('The directory does not exist');
                    }
                    return $answer;
                },
                false,
                $defaultPath
            );
            $input->setArgument('project-path', $projectPath);
        }

        $projectName = $input->getArgument('project-name');
        if (empty($projectName)) {
            $defaultName = basename($projectPath);
            $installedPath = $this->installedPath;
            $projectName = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<info>Please enter the alias of the project:</info> [' . $defaultName . '] ',
                function ($answer) use ($installedPath) {
                    if (file_exists($installedPath . '/projects/' . $answer . '.project')) {
                        throw new \InvalidArgumentException('A project named "' . $answer . '" already exists');
                    }
                    return $answer;
                },
                false,
                $defaultName
            );
            $input->setArgument('project-name', $projectName);
        }
    }
}
