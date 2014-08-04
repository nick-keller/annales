<?php

namespace nk\ExamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExamDatabaseUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('exam:database:update')
            ->setDescription('Met-à-jour la liste des examens')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('nk_exam.ade.explorer')->updateDatabase();

        $output->writeln('updated');
    }
}