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
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListProjectCommand extends AbstractProjectCommand
{
    protected function configure()
    {
        $this
            ->setName('project:list')
            ->setDescription('List managed projects')
            ->addOption(
                'type', 't',
                InputOption::VALUE_OPTIONAL,
                'Show all projects or only enabled or disabled ones',
                'all'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getProjectManager();
        $type = $input->getOption('type');
        $definition = $type;
        switch ($type) {
        case 'all':      $projects = $manager->getProjects(); $definition = 'managed'; break;
        case 'enabled':  $projects = $manager->getEnabledProjects(); break;
        case 'disabled': $projects = $manager->getDisabledProjects(); break;
        default:
            throw new \InvalidArgumentException("Type must be 'all', 'enabled' or 'disabled'.");
        }

        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');

        if (empty($projects)) {
            $output->writeln($formatter->formatBlock("There are no {$definition} projects", 'error', true));
            return 1;
        }

        $output->writeln($formatter->formatBlock("List of all {$definition} projects", 'bg=blue;fg=white', true));
        foreach ($projects as $project) {
            $output->writeln("<info>{$project}</info> {$manager->getPath($project)} <comment>(" . ($manager->isProjectDisabled($project) ? 'dis' : 'en') . 'abled)</comment>');
        }
    }
}
