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

class EnableProjectCommand extends Command
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
            if (!file_exists("{$this->installedPath}/projects/{$projectName}.project")) {
                throw new \InvalidArgumentException("Project unknown: {$projectName}");
            }
        }

        symlink("{$this->installedPath}/public/{$projectName}.devel", "../projects/{$projectName}.project");

        $output->writeln("Project <info>{$projectName}</info> enabled successfully.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('dialog');

        $projectName = $input->getArgument('project-name');
        if (empty($projectName)) {
            $installedPath = $this->installedPath;
            $possibleProjects = array();
            $projectName = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<info>Please enter the alias of the project:</info> ',
                function ($answer) use ($installedPath) {
                    if (!file_exists("{$this->installedPath}/projects/{$answer}.project")) {
                        throw new \InvalidArgumentException("Project unknown: {$answer}");
                    }
                    return $answer;
                },
                false,
                null,
                $possibleProjects
            );
            $input->setArgument('project-name', $projectName);
        }
    }
}
