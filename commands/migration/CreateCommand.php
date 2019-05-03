<?php

namespace Commands\Migration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected $commandName = 'migration:create';
    protected $commandDescription = "Create database migration file";

    protected $commandArgumentName = "migration_name";
    protected $commandArgumentDescription = "Name of your migration file";

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);

        $commandResult = shell_exec("php vendor/bin/phinx create $name -c db/config.php");

        $output->writeln($commandResult);
    }
}